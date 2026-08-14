# Total User Experience

Sentech's sentiment-analysis and predictive-maintenance reporting platform: a
multi-tenant Laravel + Vue admin dashboard with live chat, notifications, PDF/Excel
report generation, and a companion mobile app.

## Tech stack

- **Backend:** Laravel 13 (PHP 8.4), MySQL 8
- **Frontend:** Vue 3 + Inertia.js, Vite, Tailwind CSS, Element Plus
- **Realtime:** Laravel Reverb (WebSockets) for live chat
- **Auth:** Laravel Jetstream (web sessions) + Laravel Sanctum (bearer tokens for the
  mobile app) + Laravel Passport (OAuth2)
- **Permissions:** Spatie `laravel-permission`, company-scoped roles
- **Reporting:** PhpSpreadsheet / Maatwebsite Excel (spreadsheets), Spatie Browsershot
  and Snappy/wkhtmltopdf (PDF generation)
- **Dev environment:** Docker via Laravel Sail

## Key features

- **Sentiment dashboards** ("senTalk") — social sentiment analysis, trends, and
  predictive maintenance reporting for network infrastructure
- **Live chat** between users, broadcast over WebSockets (Reverb)
- **Notifications** with per-company read/unread tracking
- **Company/user management** — multi-tenant company registration, role & permission
  administration, activity logs
- **Report export** — PDF and CSV/Excel exports for sentiment and predictive
  maintenance data
- **Mobile companion app** — [tx-platform-mobile](https://github.com/Sentech-Research-and-Innovation/tx-platform-mobile)
  (Ionic/Vue + Capacitor) authenticates against this backend via Sanctum bearer tokens

## Local development

Requires Docker Desktop.

```bash
cp .env.example .env
composer install
php artisan key:generate
docker compose up -d
docker compose exec laravel.test php artisan migrate
npm install
npm run build   # or `npm run dev` for the Vite dev server
```

The app serves on the port set by `APP_PORT` (default `80`); the Reverb WebSocket
server runs as its own `reverb` service (`REVERB_SERVER_PORT`, default `6001`) via
`php artisan reverb:start`.

### Key environment variables

| Variable | Purpose |
|---|---|
| `DB_*` | MySQL connection |
| `BROADCAST_DRIVER=reverb`, `REVERB_*` / `VITE_REVERB_*` | Live chat WebSocket config |
| `FRONTEND_URL`, `SANCTUM_STATEFUL_DOMAINS` | CORS/CSRF origin allowlist for the web SPA |
| `MAIL_*` | Outbound email (password resets, notifications) |

See `.env.example` for the full list.

## Testing

```bash
php artisan test
```

CI (Bitbucket Pipelines) runs `devops/build-server.sh`, `devops/build-project.sh`, and
`devops/run-tests.sh` on every push — build/test only, no deploy step.

## Project history & audit trail

This is a backup mirror of the team's Bitbucket repository. Recent work done against
this backup is documented in full, with before/after evidence, rather than just
summarized here:

- **[`fix.md`](fix.md)** — root-cause fix for the live-chat feature (WebSocket
  broadcasting was fully disabled)
- **[`report.md`](report.md)** — a multi-part security/dependency audit and fix
  campaign: a full code review (CSRF, IDOR, auth gaps, and more), Trivy/npm
  audit/OWASP ZAP scans, dependency vulnerability patching, migration off two
  abandoned packages (`laravel-websockets` → Reverb, `inertia-vue3` → `vue3`), and the
  Laravel 10 → 13 major-version upgrade (including a mobile-app compatibility fix
  verified against the real `tx-platform-mobile` client)

## License

Proprietary — Sentech Research and Innovation. Not licensed for external use or
redistribution.
