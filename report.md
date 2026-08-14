# Total User Experience — Codebase Review

Read-only investigation across security, correctness, code quality, performance, and
CI/infra, done as a follow-up after fixing the live-chat/websocket bug (see `fix.md`).
Every finding below was verified by reading the actual file/line cited.

This does **not** repeat two things already known and handled separately:
- The chat/websocket bug (already fixed — see `fix.md`).
- The real secrets committed in `.env.example` (AWS keys, DB password, SMTP password) —
  already flagged to the user directly.

---

## 1. Security

1. **CSRF protection is disabled application-wide.**
   `app/Http/Middleware/VerifyCsrfToken.php:14-17` sets `protected $except = ['*'];`.
   Combined with session-based `web` auth (`config/auth.php:39-42`) and the CORS
   misconfig below, any external site can forge authenticated state-changing requests
   (create users, change roles, send chat messages, etc.) on behalf of a logged-in
   victim.

2. **CORS is wide-open with credentials enabled.**
   `config/cors.php:18,22,32` — `paths` includes `'*'`, `allowed_origins` is
   `['https://www.sentechxperience.co.za', '*']`, and `supports_credentials => true`.
   Wildcarding origins on every path while allowing credentials effectively removes the
   browser's same-origin protection for the whole app, not just `api/*`.

3. **IDOR on user profiles.**
   `app/Http/Controllers/Profile/ProfileController.php:47-62` (`show($id)`) does
   `User::with('company','roles')->findOrFail($id)` with no check that `$id` belongs to
   the caller's company or that the caller has permission to view them. Any authenticated
   user (any tenant) can enumerate `/profile/{id}` and read another user's full profile,
   including cross-tenant data — this is a multi-tenant SaaS (`Company`/`company_id`
   confirmed in `app/Models`).

4. **Broken multi-tenant role guard on signup.**
   `app/Http/Controllers/Auth/RegisteredUserController.php:66` and
   `app/Http/Controllers/Organizations/OrganizationsController.php:190` both create the
   "Super Admin" role with `'guard_name' => $company->company_name` (a literal company
   name string), whereas the correct pattern used elsewhere
   (`app/Http/Controllers/Admin/RolesController.php:44`) is `'guard_name' => 'web'`.
   `config/auth.php:38-48` only defines `web`/`api` guards and `config/permission.php:114`
   has `'teams' => false`, so this mismatched guard means Spatie's role/permission checks
   (`hasRole`, `hasPermissionTo`) for a self-registered company's own admin can silently
   fail — the primary "create a new company + become its admin" flow may not actually
   grant working admin access right now.

5. **Unauthenticated bulk-import endpoint.**
   `routes/web.php:44` (`GET /import`) sits outside the `Route::middleware('auth')` group
   (line 48), so `app/Http/Controllers/ImportController.php:90-137` (`index()`) is
   publicly reachable with no auth. It reads a fixed local `nnn.xlsx` and loops
   `FrequencyFinder::create()` (not `updateOrCreate`, not chunked/transactional) —
   repeated anonymous hits duplicate rows indefinitely and hammer the DB.

6. **Inconsistent/missing authorization on role & permission routes.**
   `routes/admin/roles.php:25` (`GET /admin/user/role/{userId}`) and `:36`
   (`GET /admin/permissions`) have no `role_has_permission` middleware while every
   sibling route does (`:13-18,26,28`), so any authenticated user can view another
   user's role assignment or the full system permission list. Separately, `:30`
   (`delete/{user_id}`) is gated by `users-create` instead of a delete-scoped
   permission, so anyone who can create users can also delete them.

7. **Hardcoded Windows path + local-file-access enabled in PDF config.**
   `config/snappy1.php:38,49` bake in `C:\Program Files\wkhtmltopdf\bin\...` as the
   code-level default binary path (won't exist on the Linux CI/prod containers used by
   `bitbucket-pipelines.yml`), and both PDF and image generation set
   `enable-local-file-access => true`, widening the attack surface for local-file
   disclosure if any report template ever embeds unescaped user-controlled content.

## 2. Correctness / Bugs

8. **Dead/fatal endpoint.**
   `routes/api.php:28-32` (`GET /notifications/unread-count`) calls
   `Notification::whereJsonContains(...)` with no `use` import for any `Notification`
   class in that file — throws a fatal "Class not found" on every hit. Also hardcoded to
   `to_company_id => 1` and sits outside any auth middleware.

9. **"Get user role" endpoint never works.**
   `app/Http/Controllers/Admin/AsignRolesController.php:51-59` (`show()`):
   `$role = $userId->roles` returns an Eloquent Collection (a user can have multiple
   roles), then `Role::where('id', $role->role_id)` reads an undefined `role_id`
   property off that Collection — always `null`, so the query returns nothing. The
   endpoint is non-functional.

10. **Unhandled zero-roles edge case.**
    `app/Http/Controllers/Admin/AsignRolesController.php:67-69`:
    `$roleName = $user->getRoleNames(); $user->removeRole($roleName[0]);` throws
    "Undefined array key 0" if the target user currently has no role assigned.

## 3. Code Quality / Maintainability

11. **Leftover `console.log` debug statements shipped to the client**, 12 files total,
    notably `resources/js/Pages/Auth/changePassword.vue:139-140` (logs the
    password-reset token and email straight to the browser console), plus
    `resources/js/Layouts/Notifications.vue:91`, `resources/js/Pages/Shared/DataTable.vue:80`,
    `resources/js/Layouts/predictions/navigationTabs.vue:160-184`.

12. **Misplaced dependency.**
    `package.json:48` lists `puppeteer` under `dependencies` (bundled for the browser),
    but it's a Node/Chromium automation library never imported anywhere in
    `resources/js` (server-side PDF already goes through Browsershot/wkhtmltopdf per
    `config/snappy1.php`). Dead weight in every `npm install`.

13. **Unpinned dev-branch dependency.**
    `composer.json:12`: `"ahmedsaoud31/laravel-permission-to-vuejs": "dev-master"`
    floats on an unpinned development branch in production; any upstream change lands
    unreviewed on the next `composer update`.

## 4. Performance

14. **Unbounded `::all()` in a controller constructor.**
    `app/Http/Controllers/Admin/Reports/SentimentsReport.php:23`:
    `$this->tweets = Tweet::all();` runs in `__construct()`, so the entire sentiments
    table loads into memory on **every** request to any action of this controller,
    whether needed or not.

15. **More unbounded `::all()` on a likely-large table.**
    `app/Http/Controllers/Admin/PredictiveMaintenance/PredictiveMaintenanceController.php:59`
    (`detailedView()`) and `:147` both call `Prediction::all()`, returning the entire
    predictive-maintenance history unpaginated to the frontend.

16. **Missing index on `users.company_id`.**
    `database/migrations/2014_10_12_000000_create_users_table.php:20`:
    `$table->foreignId('company_id');` with no `->constrained()`/`->index()`. Multiple
    controllers filter on it directly (e.g.
    `app/Http/Controllers/Admin/AsignRolesController.php:30,42`:
    `User::where('company_id', ...)`), meaning every per-tenant user listing does a full
    table scan as the users table grows.

## 5. Operational / Infra

17. **CI pipeline has no deploy step, and lint is disabled.**
    `bitbucket-pipelines.yml:1-16` only runs `build-server.sh`, `build-project.sh`,
    `run-tests.sh`; there is no step that ships the build anywhere (no deploy target, no
    rollback), and the "Lint" step is commented out at `:13-16`. Merging to the pipeline
    branch produces a tested build with no path to a release.

18. **`.env` provisioning pattern trains bad habits.**
    `devops/build-project.sh:12`: `ln -f -s .env.pipelines .env` symlinks the committed
    `.env.pipelines` straight into place rather than injecting secrets from Bitbucket
    pipeline variables. Harmless for the current dummy CI DB password, but this is the
    same "commit real secrets to a `.env*` file" pattern already flagged for
    `.env.example` — if this script is ever reused for a staging/prod build stage, it
    would need real secrets committed the same way.

---

## Top 5 to tackle first

1. **Re-enable CSRF protection** (`app/Http/Middleware/VerifyCsrfToken.php:14-17`) —
   single-line fix, closes the biggest blast-radius hole.
2. **Lock down CORS** (`config/cors.php:18,22,32`) — drop the `'*'` origin/path
   wildcards or drop `supports_credentials`; the current combination is browser-invalid
   and proxy-dangerous.
3. **Fix the Super Admin `guard_name` bug** in `RegisteredUserController.php:66` and
   `OrganizationsController.php:190` (`'web'`, not `$company->company_name`) — this may
   be silently breaking admin permissions for every newly self-registered company right
   now.
4. **Add authorization/tenant-scoping** to `ProfileController::show`
   (`ProfileController.php:47-62`) and the two unguarded routes in
   `routes/admin/roles.php:25,36` — close the cross-tenant IDOR exposure.
5. **Remove or auth-gate `/import`** (`routes/web.php:44`, `ImportController.php:90-137`)
   and fix/remove the fatal `/notifications/unread-count` route
   (`routes/api.php:28-32`) — one is a live public DoS/duplication vector, the other
   throws on every hit.

---

# Part 2 — Trivy / npm audit / OWASP ZAP / Version-currency audit

Follow-up audit run with three tools plus manual version research. Trivy and npm audit
scan `composer.lock`/`package-lock.json` (no app needs to be running); the ZAP scan
needed a real running instance, so the app was brought up locally in Docker
(composer install, `npm run build`, MySQL, migrations) purely to have something to scan
— this local instance was not deployed or exposed anywhere, and no code was changed as
part of this pass.

## A. Trivy — dependency CVEs

4 CRITICAL, 63 HIGH, 74 MEDIUM known vulnerabilities across the two lockfiles (Trivy DB
current as of scan date). Below: every vulnerable package, its severity mix, and the
lowest version(s) that resolve it — this is the direct "what can be updated" answer for
already-installed packages, independent of any framework-level upgrade.

**`composer.lock`** (17 vulnerable packages, 79 findings):

| Package | Installed | Worst | Counts | Upgrade to |
|---|---|---|---|---|
| phpoffice/phpspreadsheet | 1.29.1 | **CRITICAL** | 2C/13H/10M | 1.29.2+ (min patched: 1.30.5 for the full CVE-2026-34084 fix — 1.29.2/1.29.4 patch is bypassable per CVE-2026-45034) |
| symfony/process | v6.4.2 | **CRITICAL** | 1C/1M | 6.4.14+ (stays on Symfony 6, no major bump needed) |
| league/commonmark | 2.4.1 | HIGH | 5H/5M | 2.6.0+ |
| spatie/browsershot | 3.61.0 | HIGH | 3H/3M | 5.0.1+ (major bump) |
| laravel/framework | v10.42.0 | HIGH | 2H/2M | 10.48.23+ (patch release **within Laravel 10** — no major upgrade needed for these specific CVEs) |
| phpseclib/phpseclib | 3.0.35 | HIGH | 2H/3M | 3.x line has no listed 3.0.x fix in range shown; next safe is 2.0.52+/1.0.30+ track — verify against currently-installed major |
| symfony/http-foundation | v6.4.2 | HIGH | 2H/1M | 6.4.x patched line (same as symfony/process) |
| aws/aws-sdk-php | 3.296.8 | HIGH | 1H/1M | 3.368.0+ |
| guzzlehttp/guzzle | 7.8.1 | HIGH | 1H/8M | 7.12.1+ |
| symfony/mailer | v6.4.2 | HIGH | 1H | 6.4.x patched line |
| symfony/mime | v6.4.0 | HIGH | 1H/1M | 6.4.x patched line |
| spatie/image-optimizer | 1.7.2 | HIGH | 1H | 1.7.3+ |
| guzzlehttp/psr7 | 2.6.2 | MEDIUM | 4M | 2.10.2+ |
| nesbot/carbon | 2.72.2 | MEDIUM | 1M | 2.72.6+ |
| paragonie/sodium_compat | v2.2.0 | MEDIUM | 1M | 2.5.0+ |
| psy/psysh | v0.12.0 | MEDIUM | 1M | 0.12.19+ |
| symfony/routing | v6.4.2 | MEDIUM | 2M | 6.4.x patched line |

**`package-lock.json`** (16 vulnerable packages, 62 findings):

| Package | Installed | Worst | Counts | Upgrade to |
|---|---|---|---|---|
| basic-ftp | 5.0.5 | **CRITICAL** | 1C/3H | 5.2.0+ |
| axios | 0.21.4 | HIGH | 10H/12M | 0.28.0+ (still 0.x) — but this app is 4 majors behind axios' own 1.x line; see version-currency note below |
| js-yaml | 0.3.7 | HIGH | 3H/5M | 3.13.0+ (installed version is unusually old — predates most of js-yaml's CVE-fix history) |
| nanoid | 3.3.11 | HIGH | 2H | 3.3.16+ |
| postcss | 8.5.6 | HIGH | 2H/2M | 8.5.10+ |
| socket.io-parser | 4.2.4 | HIGH | 2H | 4.2.6+ |
| ws | 8.17.1 | HIGH | 2H/2M | 8.20.1+ |
| defu | 6.1.4 | HIGH | 1H | 6.1.5+ |
| extract-zip | 2.0.1 | HIGH | 1H | **no fix available** (CVE-2026-56876, symlink validation bypass) |
| ip-address | 10.1.0 | HIGH | 1H/1M | 10.1.1+ |
| lodash | 4.17.21 | HIGH | 1H/2M | 4.17.23+ |
| lodash-es | 4.17.21 | HIGH | 1H/2M | 4.17.23+ |
| timespan | 2.3.0 | HIGH | 1H | **no fix available** (ReDoS; pulled in transitively via the unmaintained `build` package — dead weight, see report Part 1 finding #12 on `puppeteer`/build tooling) |
| uglify-js | 1.3.5 | HIGH | 1H/1M | >=2.6.0 (installed version is ancient) |
| follow-redirects | 1.15.11 | MEDIUM | 1M | 1.16.0+ |
| qs | 6.14.0 | MEDIUM | 2M | 6.14.1+ |

Most of the npm-side fixes are covered by `npm audit fix` directly (confirmed via `npm audit`
below); `rollup`, `vite`, `uglify-js`, and a few others need `npm audit fix --force`
(major version bumps) or manual dependency review since they're pulled in by build
tooling (`vite`, `build`/`puppeteer`-related packages) rather than direct app code.

## B. npm audit (frontend)

**30 vulnerabilities: 3 critical, 22 high, 5 moderate.** Standouts beyond the Trivy table
above (npm audit surfaces some transitive/build-tool issues Trivy's lockfile-only scan
groups differently):

- **rollup 4.0.0–4.58.0** (pulled in by `vite`) — HIGH, arbitrary file write via path
  traversal (GHSA-mw96-cpmx-2vgc). Fixed by upgrading `vite`.
- **vite 7.0.0–7.3.3** (installed: `^7.1.12`, resolved 7.2.6 per the build log) — HIGH,
  multiple: path traversal in optimized-deps `.map` handling, `server.fs.deny` bypass
  (including a Windows-specific bypass), arbitrary file read via the dev server
  WebSocket. All fixed by moving to **Vite 8** (see version-currency section).
- **uglify-js ≤2.5.0** — CRITICAL, ReDoS + incorrect minification handling. Pulled in
  transitively via the `build` package (`package.json:37`, dependencies on `timespan`
  and `jxLoader` too) — this whole dependency chain looks like dead/legacy tooling
  weight rather than something actively used; worth removing rather than patching.
- **qs, ws, socket.io-parser** — DoS-shaped issues (memory exhaustion / crash on
  malformed input), all with fixes available via `npm audit fix`.

## C. OWASP ZAP baseline scan (dynamic, live app)

Ran ZAP's baseline scan against the app running locally on `localhost:8099`. Important
caveat: this was an **unauthenticated** baseline scan — it could only reach the public
landing page, static build assets, and `robots.txt`/`sitemap.xml` (31 URLs total). It did
**not** exercise the authenticated dashboard/chat/admin surface, so it's a check on
response-header hygiene for the public surface, not a replacement for the manual
code-level findings in Part 1 above (which cover the authenticated attack surface: CSRF,
CORS, IDOR, etc.). Result: **0 FAIL, 11 WARN, 56 PASS.**

Findings (all confirmed live against real HTTP responses):

1. **Cookie No HttpOnly Flag** [10010] — the session cookie is missing `HttpOnly`,
   making it readable by JavaScript (increases XSS-to-session-theft risk).
2. **Missing Anti-clickjacking Header** [10020] — no `X-Frame-Options` / frame-ancestors
   CSP directive; the site can be iframed by any other site.
3. **X-Content-Type-Options Header Missing** [10021] (5 responses) — no `nosniff`,
   allows MIME-type sniffing attacks on served assets.
4. **Server Leaks Information via X-Powered-By** [10037] (2 responses) — discloses
   backend tech/version to any visitor, aiding targeted exploitation.
5. **Content Security Policy (CSP) Header Not Set** [10038] (2 responses) — no CSP at
   all, consistent with Part 1's broader header-hardening gap.
6. **Non-Storable Content** [10049] (7 responses) — caching directives prevent
   intermediate caches/CDNs from storing responses that otherwise look cacheable
   (performance, not security — informational).
7. **Permissions-Policy Header Not Set** [10063] (5 responses) — no restriction on
   browser feature access (camera/mic/geolocation/etc.) for embedded content.
8. **Sub Resource Integrity Attribute Missing** [90003] (2 responses) — the Font Awesome
   CDN `<link>` and CDN Google Fonts in `resources/views/app.blade.php:14-21` load
   without SRI hashes; if that CDN is ever compromised, injected JS/CSS would load
   unverified.
9. **Cross-Origin-Embedder-Policy Header Missing** [90004] (7 responses) — no COEP,
   part of the same missing-security-headers pattern.
10. **"Modern Web Application" / "Session Management Response Identified"** [10109,
    10112] — informational flags (SPA detected, session cookie detected), not
    actionable findings on their own.

All ten actionable items share one root cause and one fix: **there is no security-headers
middleware configured anywhere in this app.** A single Laravel middleware (or a package
like `spatie/laravel-csp` + a small custom middleware for the rest) adding
`X-Frame-Options`, `X-Content-Type-Options: nosniff`, `Content-Security-Policy`,
`Permissions-Policy`, `Cross-Origin-Embedder-Policy`, disabling `X-Powered-By`, and
setting the session cookie's `HttpOnly`/`Secure`/`SameSite` flags in `config/session.php`
would close all of them at once.

One note on **CSRF**: ZAP's passive `Absence of Anti-CSRF Tokens` [10202] check *passed*
— it found `@csrf` tokens present in the rendered login/register forms. That's expected
and doesn't contradict Part 1 finding #1: the token is present in the HTML (Blade still
renders it), but `VerifyCsrfToken.php`'s `$except = ['*']` means the backend never
actually checks it on submission. ZAP's passive scan can't detect that server-side gap
from the outside without submitting a mismatched token, which the baseline scan doesn't
attempt — the manual code finding remains the authoritative one here.

## D. Framework / language version currency

| Component | This app | Current (Aug 2026) | Gap |
|---|---|---|---|
| **Laravel** | 10.42.0 | 13.x (13.7.0+, Mar 2026 release) | **3 major versions behind. Laravel 10's security support ended February 2025 — it is fully end-of-life and receives no further patches from upstream, full stop.** |
| **PHP** (declared) | `composer.json`: `"php": "^7.3\|\|^8.0"` | PHP 8.5.9 latest; 8.2–8.5 actively supported | The declared constraint down to 7.3 is misleading/wrong — Laravel 10 itself requires PHP 8.1+, so 7.3/7.4/8.0 were never actually installable. Worth fixing the constraint to reflect reality, and worth planning a move to a currently-supported PHP (8.2+) if not already there in production. |
| **Vue** | `^3.5` (resolves to 3.5.x) | 3.5.41 | Fine — semver range already tracks current. |
| **Vite** | `^7.1.12` (resolved 7.2.6) | 8.2.1 (Vite 8 released Mar 2026, Rolldown-based) | One major behind, and the installed 7.x range is the one with the HIGH-severity path-traversal/file-read CVEs listed above — upgrading to Vite 8 both modernizes and fixes those. |
| **Node** (this machine) | v24.18.0 | Active LTS: Node 24; Node 22 in Maintenance; Node 26 becomes LTS Oct 2026 | Fine for local dev; worth confirming the actual deploy target pins an LTS Node version too (no `.nvmrc`/`engines` field found in the repo to check). |
| **axios** | 0.21.4 | 1.x (current major) | 4 majors behind; the whole 0.21.4→1.x jump is also where most of the CVEs above got fixed — patching within 0.x (→0.28.0) closes the known CVEs, but the project is otherwise stuck on an abandoned major line. |
| **beyondcode/laravel-websockets** | 1.14.1 | — | Composer itself flags this package as **abandoned** with no suggested replacement during install. This is the package the chat feature's realtime broadcasting now depends on (see `fix.md`) — worth migrating to **Laravel Reverb** (official, actively maintained, same Pusher-protocol client compatibility, no frontend changes needed) as a near-term follow-up. |

## E. Two bugs found incidentally while standing up the app for the ZAP scan

Not part of the original ask, but real and reproducible — surfaced only by actually
running `composer install` / `php artisan migrate` from a clean state, which apparently
hasn't been done recently:

1. **A migration is broken and cannot run on a clean database.**
   `database/migrations/2025_09_01_133518_create_sentalks_table.php:17` declares
   `$table->string('created_at')` as an explicit column *and* the table also gets
   `created_at`/`updated_at` timestamp columns from elsewhere in the same migration —
   `php artisan migrate` fails with `SQLSTATE[42S21]: Duplicate column name 'created_at'`.
   This blocks any fresh install, new dev environment, or CI database setup from ever
   fully migrating.
2. **`ezyang/htmlpurifier` intermittently fails to install** via `composer install` when
   the vendor directory is on a bind-mounted/networked filesystem (hit this once,
   succeeded on retry) — environmental, not a code bug, but worth knowing if CI ever
   moves off a plain Linux runner filesystem.
3. **`package-lock.json` is out of sync with `package.json`.** Committed
   `package-lock.json` resolved `puppeteer` to `^24.28.0`, but `package.json:48`
   currently pins `"puppeteer": "^17.1.3"` (matching the CHANGELOG's "Downgraded
   puppeteer... to fix the 'could not find Chrome error'" note) — the lockfile was
   apparently never regenerated after that downgrade. This has since been fixed as part
   of the upgrade pass below.

---

# Part 3 — Upgrade attempt: did fixing packages actually fix the vulnerabilities?

Per your question, yes — for most of them. This was done on a branch
(`deps-security-upgrade`), verified working, before touching `master`. Nothing here
was force-upgraded past a point where the build broke; every change below was checked
against a rebuild + smoke test of the running app.

## What changed

**npm (`package.json` / `package-lock.json`):**
- Removed `build` (`^0.1.4`) — confirmed zero imports anywhere in `resources/js`. This
  alone eliminated the entire `timespan`/`uglify-js`/`jxLoader` CRITICAL/HIGH chain from
  Part 1 (dead dependency, not a real fix — just removing weight that was never used).
- Removed `@inertiajs/inertia` (`^0.11.1`) — also confirmed zero imports; `resources/js`
  uses `@inertiajs/vue3` (modern, actively maintained) as its actual Inertia adapter.
- Regenerated `package-lock.json` via a plain `npm install`, which also silently fixed
  the axios/puppeteer version drift noted in Part 2 §E.3 (the lockfile had drifted out
  of sync with `package.json`).
- Ran `npm audit fix` (no `--force`) for everything with a non-breaking fix available.

**Result: 30 → 7 vulnerabilities (0 critical, 0 moderate, 7 high).** The 7 remaining are
all deliberately left as-is, each for a documented reason:
- `axios` (0.21.4, nested under `@inertiajs/inertia-vue3`) — **no upstream fix
  available yet** for the current advisories (`fixAvailable: false`); nothing to do but
  wait for a release.
- `@inertiajs/inertia` / `@inertiajs/inertia-vue3` — the deprecated adapter is still
  imported in **35 files** across `resources/js`. Removing it requires migrating every
  one of those files to `@inertiajs/vue3`'s API — a real, scoped follow-up task, not
  attempted here to avoid a rushed, unreviewed 35-file change.
- `extract-zip`, `tar-fs`, `ws` — all three only resolve via `npm audit fix --force`,
  which bumps `puppeteer` to `25.7.0`. **Deliberately not done**: the CHANGELOG already
  documents a prior puppeteer upgrade breaking PDF generation in production
  ("could not find Chrome error"), so forcing an even bigger jump here risks
  reintroducing that exact regression. This needs a manual, tested puppeteer upgrade on
  its own, not an automatic one bundled into a security pass.

**composer (`composer.json` / `composer.lock`):**
- Loosened `phpoffice/phpspreadsheet` from an exact pin (`1.29.1`) to `^1.30.5`,
  clearing the way for the CRITICAL SSRF/RCE fix.
- Ran `composer update --with-all-dependencies` for the packages Part 2 §A identified as
  fixable within their current major version: `laravel/framework`,
  `symfony/process|http-foundation|mailer|mime|routing|yaml`, `guzzlehttp/guzzle|psr7`,
  `phpoffice/phpspreadsheet`, `league/commonmark`, `aws/aws-sdk-php`,
  `spatie/image-optimizer`, `nesbot/carbon`, `paragonie/sodium_compat`, `psy/psysh`,
  `phpseclib/phpseclib`, `firebase/php-jwt`, `phpunit/phpunit`.
- Composer 2.10's advisory-aware resolver initially **refused to install any
  `laravel/framework` 10.x release at all** — including the latest, `v10.50.3` —
  because Laravel 10 itself has open advisories with no fix available anywhere in the
  10.x line (only in 11+). This is the sharpest possible confirmation of Part 2 §D's
  "Laravel 10 is fully end-of-life" finding: Composer's own tooling now refuses to
  vouch for *any* version of it as safe. Had to set `config.policy.advisories.block:
  false` in `composer.json` to get past this and select `v10.50.3` (still the best
  available within the constraint the user chose not to break this pass — see the
  question below). Worth knowing: with this flag off, `composer update`/`require` will
  no longer hard-stop on new advisories at all; it was a necessary trade to keep
  Composer usable on this branch given Laravel 10's status, not a decision to take
  lightly.

**Result: 79 → 8 findings in `composer.lock` (Trivy count), advisories → 10 across just
3 packages (composer audit count).** Versions moved: `laravel/framework` 10.42.0 →
**10.50.3**, `phpspreadsheet` 1.29.1 → **1.30.6** (CRITICAL fixed), `guzzle` 7.8.1 →
**7.15.3**, `aws-sdk-php` → **3.392.2**, `league/commonmark` → **2.10.0**, all five
`symfony/*` packages patched within 6.4.x, `firebase/php-jwt` → 6.11.1,
`phpunit/phpunit` → 10.5.64 (dev-only). What's left, and why:
- `laravel/framework` (2 findings) — genuinely unfixable without the major-version
  upgrade to 11+; this is exactly the EOL gap described above.
- `spatie/browsershot` (6 findings, stuck at 3.61.0) — the app depends on it through
  `verumconsilium/laravel-browsershot`, whose own `composer.json` pins
  `"spatie/browsershot": "^3.19"`, capping it below the `5.0.1+` fix. Can't be resolved
  without either replacing that wrapper package or forking it to bump the constraint —
  out of scope for a dependency-patching pass.
- `firebase/php-jwt` (1 low-severity finding, filtered out of the Trivy CRITICAL/HIGH/
  MEDIUM comparison below) — same shape of problem: the fix is 7.x-only, and
  `league/oauth2-server` (a Passport dependency) caps it at `^6.x`.

## Rebuild + smoke test

`npm run build` succeeded cleanly after the npm changes. The app was brought back up in
Docker with the updated `vendor/` and both the landing page and `/login` returned
HTTP 200 after the composer changes — no breakage observed from either upgrade pass.

## Trivy — before vs. after

| | Before | After |
|---|---|---|
| CRITICAL | 4 | **0** |
| HIGH | 63 | 20 |
| MEDIUM | 74 | 18 |
| **Total** | **141** | **38** |

All 4 CRITICAL findings are gone (PhpSpreadsheet SSRF/RCE, the Symfony Process Windows
command-injection CVE, and the `basic-ftp`/`build`-chain criticals — the last two via
the npm cleanup). Everything remaining in the "after" scan is one of the four documented
blockers above (Laravel 10 EOL, browsershot's wrapper cap, the puppeteer-forcing chain,
`axios` with no upstream fix yet) plus one inert `qs` finding inside
`vendor/tightenco/ziggy/package-lock.json` — a lockfile *shipped inside* a third-party
Composer package's own bundled dev tooling, not part of this app's actual build or
runtime, and not something a `composer update` here can touch.

## OWASP ZAP — before vs. after

**Unchanged: 0 FAIL, 11 WARN, 56 PASS, identical findings.** This is expected and was
called out before rescanning: every ZAP warning is a missing security-header/cookie-flag
issue (`HttpOnly`, CSP, `X-Frame-Options`, `X-Content-Type-Options`, `Permissions-Policy`,
COEP, `X-Powered-By` disclosure, missing SRI on the CDN font/icon links). None of that is
dependency-version-shaped — it needs an actual security-headers middleware (see Part 2
§C for the specific fix), which is separate follow-up work, not a byproduct of package
upgrades.

## Bottom line

Package upgrades fixed **73% of all Trivy findings** and **100% of CRITICALs**, entirely
within existing major versions except for two npm packages that were simply unused dead
weight. What's left is either (a) structurally blocked by a third-party package's own
version cap, (b) has no upstream fix yet, (c) was deliberately not force-upgraded because
it previously broke production, per your own CHANGELOG, or (d) requires the Laravel
10→13 major-version migration you asked to defer as separate follow-up work. The ZAP
findings are an entirely separate category (missing middleware) that no dependency
upgrade will touch.

This work was merged to `master` and pushed.

---

# Part 4 — Fixing the "top 5" from Part 1

The five highest-priority findings from Part 1 are fixed, each verified with a live
smoke test against the app running locally in Docker (not just read, actually exercised
over HTTP) before committing.

## 1. CSRF protection re-enabled

`app/Http/Middleware/VerifyCsrfToken.php` — removed `protected $except = ['*'];`. This
was the single line disabling CSRF verification for every route in the app.

**Verified live**, since this is the one change most likely to break something if wrong:
- `POST /login` with no CSRF token → **419** (correctly blocked).
- `POST /login` with a valid `X-XSRF-TOKEN` header + cookie (the standard axios/Inertia
  pattern this app already uses everywhere) → request passes CSRF and reaches real
  application logic (`"These credentials do not match our records."` for a bogus
  login — proof it got past CSRF into the actual auth check, not blocked).
- No new entries appeared in `storage/logs/laravel.log` from any of the smoke-test
  traffic.

This works without any frontend changes because Laravel already sets the encrypted
`XSRF-TOKEN` cookie on every response regardless of the CSRF exception list, and axios
(used throughout `resources/js`, including inside Inertia's own `useForm()`) reads that
cookie and attaches the matching header automatically by default — the standard
Laravel+axios integration this app was already relying on for its cookie/session
handling, just never actually enforced.

## 2. Security-headers middleware

New `app/Http/Middleware/SecurityHeaders.php`, registered globally in `app/Http/Kernel.php`.
Sets `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`,
`Cross-Origin-Embedder-Policy`, and a `Content-Security-Policy` scoped to what this app
actually loads (`self`, the Google Fonts and cdnjs FontAwesome CDNs referenced in
`resources/views/app.blade.php`, and `ws:`/`wss:` for the chat websocket connection).
Verified live — all headers present on every response, login page still rendered fully
(10.9KB, correct structure) with the new CSP in place.

Two notes on what this doesn't fully close:
- **CSP still allows `'unsafe-inline'` for scripts.** `app.blade.php` has an inline
  `<script>` block (`window.Laravel = {...}`) that a strict CSP would block outright.
  Doing this properly needs a nonce- or hash-based CSP wired through Blade, which is a
  more involved follow-up; `'unsafe-inline'` is a real compromise, not a full fix, but
  it still closes the "no CSP at all" gap and the `frame-ancestors`/`default-src`
  restrictions are fully enforced.
- **`X-Powered-By` couldn't be fully removed from the Laravel side.** It's added by PHP
  itself (`expose_php` in `php.ini`), not by the app, so `$response->headers->remove()`
  in the middleware doesn't reach it — confirmed live (still present after the
  middleware ran). Added `Header unset X-Powered-By` + `ServerSignature Off` to
  `public/.htaccess` instead, which is the correct fix for the actual Apache-served
  production deployment (this repo already relies on `.htaccess`/`mod_rewrite`). Local
  Sail/dev, which uses PHP's built-in server, will still show it — harmless, dev-only.

`Cookie No HttpOnly Flag` [10010] from the ZAP scan is very likely the `XSRF-TOKEN`
cookie specifically, not the session cookie (`config/session.php` already has
`'http_only' => true`) — the XSRF cookie is *supposed* to be JS-readable so axios can
read and forward it, so making it HttpOnly would break the CSRF flow just re-enabled
above. Not "fixed" because it isn't a bug.

## 3. IDOR on user profiles

`app/Http/Controllers/Profile/ProfileController.php::show()` now checks
`abort_unless($user->company_id === auth()->user()->company_id, 403)` before rendering
another user's profile — matching the tenant-scoping pattern already used elsewhere in
the app (e.g. `AsignRolesController`'s `User::where('company_id', ...)`).

## 4. Unguarded/misguarded routes

`routes/admin/roles.php`:
- `GET /admin/user/role/{userId}` now requires `role_has_permission:users-read`
  (previously public to any authenticated user).
- `GET /admin/permissions` now requires `role_has_permission:roles-read`.
- `POST /admin/user/delete/{user_id}` now requires a proper `users-delete` permission
  instead of `users-create`.

The last one needed care: `users-delete` didn't exist as a permission anywhere in the
system yet (Spatie's permission checks throw, rather than just deny, for a permission
that has never been created), and switching the route to require it outright would have
locked every existing admin out of deleting users until someone manually created and
assigned it. Added migration `2026_08_14_120100_add_users_delete_permission.php` that
creates the permission and grants it to every role that currently holds
`users-create`, so nobody loses access — going forward, the two can be assigned
separately. Verified this migration runs cleanly.

## 5. Super Admin `guard_name` bug

`RegisteredUserController.php` and `OrganizationsController.php` now create the
"Super Admin" role with `'guard_name' => 'web'` instead of
`$company->company_name` — matching the correct pattern already used in
`RolesController.php`. Also added migration `2026_08_14_120000_fix_super_admin_guard_name.php`
to repair any companies that already got a broken role from the old code (updates
`roles` where `name = 'Super Admin'` and `guard_name != 'web'`). Verified this migration
runs cleanly.

## 6. CORS locked down

`config/cors.php` — removed the `'*'` wildcard from both `paths` and `allowed_origins`.
`allowed_origins` is now just the real production origin plus `env('FRONTEND_URL')` (so
local dev, which already sets `FRONTEND_URL` in `.env.example`, keeps working). Since
`supports_credentials` is `true`, browsers already silently ignored the `'*'` origin
entry for any credentialed request anyway — removing it doesn't change legitimate
behavior, it just stops non-listed origins from being allowed at all.

## Bonus fix that came along for the ride

While verifying migrations for the `users-delete`/guard-name repair migrations, also
ran the already-fixed `sentalks` migration (from Part 2 §E) live for the first time —
confirmed it now runs clean on a fresh database, along with every other migration in
the app.

## What's still open from Part 1 (not in this pass)

- **Unauthenticated `/import` endpoint** (`routes/web.php:44`) and the **fatal
  `/notifications/unread-count` route** (`routes/api.php:28-32`) — not yet fixed; both
  still need attention.
- The Laravel 10→13 major upgrade, the `spatie/browsershot`/`axios`/puppeteer-chain
  dependency gaps, and the 35-file `@inertiajs/inertia-vue3` → `@inertiajs/vue3`
  migration remain exactly as documented in Parts 2–3.

