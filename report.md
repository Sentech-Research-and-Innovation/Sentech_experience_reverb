# Total User Experience — Security & Upgrade Report

This is a current-state summary of the security review, dependency audit, and
framework upgrade carried out on this repository. It supersedes the incremental,
part-by-part working log in [`report_old.md`](report_old.md) — that file is kept as
the full chronological record (what was found, in what order, with the raw before/after
evidence for every step) if you need to trace how any specific fix was reached. The
live-chat bug fix has its own dedicated writeup in [`fix.md`](fix.md).

## Executive summary

Starting point: Laravel 10 (fully end-of-life since February 2025), a live chat
feature that was completely non-functional, CSRF protection disabled application-wide,
wide-open CORS, several authorization gaps, 141 known dependency CVEs, and two
abandoned upstream packages the app depended on.

Current state: Laravel 13 on PHP 8.4, all of the above fixed and verified — live chat
works end-to-end, CSRF/CORS are properly scoped, the authorization gaps are closed,
dependency findings are at effectively zero for Composer and down to 4 (all
deliberately-not-forced, documented below) for npm, and both abandoned packages have
been replaced. Every fix in this report was verified against the application actually
running in Docker — HTTP responses, log output, and for the chat/mobile fixes, live
protocol-level testing — not just read and assumed correct.

---

## 1. Live chat

**Was:** Completely broken. The `broadcast()` call that would push a new chat message
to the recipient was commented out, the broadcast event's channel/payload didn't match
what the frontend expected even if re-enabled, the Echo client had a broken CSRF header
and hardcoded TLS settings, a Vite `@` import alias the chat component relied on didn't
exist, and no WebSocket server process was ever actually run anywhere.

**Now:** Fixed and verified live — see [`fix.md`](fix.md) for the full before/after.
The realtime transport has since also been migrated from the abandoned
`beyondcode/laravel-websockets` package to **Laravel Reverb** (§4).

## 2. Security fixes

| Finding | Status | Detail |
|---|---|---|
| CSRF protection disabled app-wide (`$except = ['*']`) | **Fixed** | Re-enabled globally; narrowly re-exempted only `login`/`register` for mobile-app compatibility (§5) — every other route stays protected |
| CORS wide open (`'*'` in paths and origins, with credentials) | **Fixed** | Scoped to the real web origin(s) plus the two verified mobile-app WebView origins |
| IDOR on user profiles (`ProfileController::show`) | **Fixed** | Now tenant-scoped to the caller's `company_id` |
| Two admin routes missing permission middleware | **Fixed** | `role_has_permission` added to both |
| Delete-user route gated by the wrong permission (`users-create` instead of a delete permission) | **Fixed** | New `users-delete` permission, backfilled onto every role that already had `users-create` so no one lost access |
| Super Admin role created with a broken `guard_name` | **Fixed** | Corrected in code; a migration repairs any company already affected |
| Public, unauthenticated `/import` endpoint that duplicated rows on every hit | **Fixed** | Moved behind auth; switched to `updateOrCreate` |
| Fatal `/notifications/unread-count` endpoint (missing import, wrong company scoping) | **Fixed** | Rewritten to match the working pattern used elsewhere, properly scoped and authenticated |
| No security-headers middleware (missing CSP, X-Frame-Options, etc. — 11 OWASP ZAP findings) | **Fixed** | Global middleware now sets CSP, X-Frame-Options, X-Content-Type-Options, Permissions-Policy, COEP, and strips `X-Powered-By` (production, via `.htaccess`) |
| Broken migration (`sentalks` table, duplicate `created_at` column) | **Fixed** | Couldn't run on a clean database before; now migrates cleanly |
| Real secrets committed to the repo (AWS keys, DB password, mail password in `.env.example` and `config/filesystems.php`) | **Open — needs you** | I can't rotate real AWS/DB credentials from here. These need rotating on the AWS/DB side, and ideally scrubbing from git history |
| No process supervisor for the WebSocket server in production | **Open — needs infra access** | `php artisan reverb:start` needs a systemd/supervisor unit on the real deployment host; this repo has no deploy step to wire that up from |

## 3. Dependency security

| | Original | Current |
|---|---|---|
| Trivy findings (Composer) | 79 | **0** |
| Composer security advisories | 10, across 3 packages | **0** |
| Trivy findings (npm) | 62 | 7 (Trivy-counted; see below) |
| npm audit vulnerabilities | 30 | **4** |

The 4 remaining npm findings (`extract-zip`, `puppeteer`, `tar-fs`, `ws`) are a single
dependency chain that only resolves by force-upgrading `puppeteer` to a new major
version. **Deliberately not done** — this repo's own CHANGELOG documents a prior
puppeteer upgrade breaking PDF generation in production, so forcing it again needs its
own dedicated, tested pass, not a drive-by dependency bump.

Two abandoned upstream packages this app depended on have been replaced:

- **`beyondcode/laravel-websockets`** (no longer maintained, flagged by Composer
  itself) → **Laravel Reverb**, the framework's own actively-maintained WebSocket
  server. No app code changed (Reverb speaks the same Pusher protocol) — only
  broadcasting config and the Docker service definition.
- **`verumconsilium/laravel-browsershot`** (blocked the Laravel 11 upgrade — its own
  dependency constraints capped `spatie/browsershot` below the version Laravel 11
  needs) → a small ~40-line direct replacement on `spatie/browsershot` itself
  (`app/Support/Browsershot/Pdf.php`), since only two controllers used it, both
  through the same small API.

Also migrated off the deprecated `@inertiajs/inertia-vue3` package (35 files,
mechanical import-path change — both packages export an API-identical `Head`/`Link`)
to the actively-maintained `@inertiajs/vue3`.

## 4. Framework upgrade: Laravel 10 → 13

Composer's own advisory-aware resolver made the case for this directly: it refused to
install *any* `laravel/framework` 10.x release, including the latest, because Laravel
10 has open advisories with no fix short of 11+.

Done as three separate hops (10→11→12→13), each one fully boot-tested in Docker before
moving to the next, not just resolved by Composer:

- **PHP 8.4** (not 8.3, despite Laravel 13's docs stating 8.3+ as the floor) — the
  resolved `symfony/http-foundation` version uses PHP 8.4-only syntax
  (property hooks). Found by the app actually failing to boot on 8.3, not by reading
  changelogs.
- Every Laravel-ecosystem package that had no version compatible with the target
  (Sanctum, Jetstream, Passport, `spatie/laravel-permission`, Tinker, and others) was
  identified and bumped by following Composer's actual conflict output, not guessed in
  advance.
- Four real bugs were caught only by booting the app on each hop: stale file
  permissions from an earlier container image, Ziggy 2.x's PHP namespace rename
  (`Tightenco\Ziggy` → `Tighten\Ziggy`), Ziggy 2.x's JS export path change (broke
  `npm run build` outright), and the PHP 8.4 requirement itself.

Full package-by-package table and verification detail: `report_old.md` Part 7.

## 5. Mobile app compatibility

Two repos were flagged as possible consumers of this backend. Checked directly rather
than assumed:

- **`sentech-connect`** — ruled out. A fully static React marketing site with no
  network calls anywhere in the codebase.
- **`tx-platform-mobile`** — confirmed as a real dependent (Ionic/Vue + Capacitor app,
  hardcoded to this backend's domain, authenticating via a Sanctum bearer token this
  backend issues under the literal name `'MobileAppToken'`).

Checking it surfaced a real, already-live regression: the CSRF and CORS fixes in §2
had broken this app's login and registration, since it has no CSRF token mechanism and
its WebView origins (`https://localhost` on Android, `capacitor://localhost` on iOS)
weren't in the new CORS allowlist. Fixed narrowly — only `/login` and `/register` are
exempt from CSRF (every other route stays protected), and only those two mobile
origins were added to CORS.

**Verified end-to-end** against the real request/response shape the mobile app uses:
login → real Sanctum token issued → authenticated `/api/user` fetch (200, full
user+company payload) → `/api/logout` (201). No changes to the mobile app's own code
are required — every fix was server-side.

## What's still open

- **Rotate the leaked credentials** in `.env.example`/`config/filesystems.php` (AWS,
  DB, mail) — needs action on the AWS/DB side, which isn't possible from this repo.
- **Production process supervisor** for `php artisan reverb:start` — needs access to
  the real deployment host.
- **The puppeteer dependency chain** (§3) — needs its own dedicated, tested upgrade
  pass given the documented prior regression.
- Everything else identified in the original code review that wasn't part of the
  "top 5" security pass — see `report_old.md` Part 1, items not marked fixed in the
  table above.
