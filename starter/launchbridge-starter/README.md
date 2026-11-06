# LaunchBridge — Backend Demo Overlay

A small **Laravel REST API** connecting startups with graduates through projects. This ZIP contains **application files to copy into a newly created Laravel project**, not the complete Laravel framework or `vendor/` dependencies.

## Setup (Windows PowerShell, PHP + Composer installed)

```powershell
composer create-project laravel/laravel launchbridge
cd launchbridge
php artisan install:api
```

Copy the contents of this ZIP's `app/`, `database/`, `routes/`, `tests/` directories into the corresponding directories of your new `launchbridge` project. Merge rather than replace entire directories. **Apply `USER_MODEL_CHANGES.txt` manually.**

For the fastest first run, use SQLite (included in current Laravel defaults):

```powershell
php artisan migrate
php artisan test
php artisan serve
```

If you prefer MySQL, create a `launchbridge` database, then set `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=launchbridge`, `DB_USERNAME=...`, `DB_PASSWORD=...` in `.env` before `php artisan migrate`. Never commit `.env` or credentials.

## API flow

Use `Accept: application/json` for all requests. For protected routes send `Authorization: Bearer YOUR_TOKEN`.

1. `POST /api/register` with `{ "name":"Startup Demo", "email":"startup@example.com", "password":"password123", "password_confirmation":"password123", "role":"startup" }`
2. `POST /api/register` with a different email, `role: graduate`.
3. `POST /api/projects` as startup with `{ "title":"Build a startup dashboard", "description":"Create an internal dashboard for our small business.", "skills":["Laravel","MySQL"], "compensation":"BDT 3000 fixed" }`
4. `GET /api/projects` to browse.
5. `POST /api/projects/1/apply` as graduate with `{ "message":"I have experience with Laravel and MySQL." }`.
6. `GET /api/projects/1/applications` as owning startup.
7. `PATCH /api/applications/1/status` as owning startup with `{ "status":"accepted" }`.

Other routes: `POST /api/login`, `GET /api/me`, `POST /api/logout`, `GET /api/projects/{project}`.

**Expected failures:** duplicate application = 409, invalid input = 422, missing authentication = 401, wrong role/owner = 403.

## Swagger UI

`docs/openapi.yaml` defines the API. You can preview it using Swagger Editor or serve it locally with Swagger UI. For recruitment, ensure the final API docs are reachable and requests execute against your server. A local-only server is not a publicly accessible live demo.

## Limitations

This code has passed PHP syntax linting but could not run Laravel integration tests in the creation environment (Composer and dependencies were unavailable). Run `php artisan test` after installing Laravel and resolve any version-specific differences. This demo intentionally omits production-scale requirements such as rate-limiting policies, email verification and full audit trails.
