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
