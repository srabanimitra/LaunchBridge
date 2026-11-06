# LaunchBridge API

**A project-based opportunity platform connecting fresh graduates with startups.**

LaunchBridge is a backend-focused proof of concept built for the **Credosis Internship Program — Phase 02 (From Idea to Demo)**. It demonstrates one end-to-end workflow: a startup publishes a real-world project, a graduate applies, and the startup reviews the application.

## The problem

Fresh graduates often face an experience gap: employers expect practical experience, yet entry-level opportunities can be limited. LaunchBridge explores a way for graduates to demonstrate skills through real startup projects rather than relying only on previous employment.

## Features

- **Role-based accounts:** register and log in as a `startup` or `graduate`.
- **Project posting:** startups create projects with a description, required skills, and compensation information.
- **Project discovery:** view open projects and individual project details.
- **Applications:** graduates submit an application message to an open project.
- **Application review:** the startup that owns a project can list applications and accept or reject pending candidates.
- **API protections:** Sanctum bearer-token authentication, request validation, role/ownership checks, and duplicate application prevention.
- **Interactive API documentation:** Swagger UI driven by an OpenAPI 3 specification.

### Core workflow

1. Register or log in as a startup.
2. Create a project using `POST /api/projects`.
3. Register or log in as a graduate.
4. Browse available projects and apply using `POST /api/projects/{project}/apply`.
5. Log in as the project-owning startup to view applications and change a pending application to `accepted` or `rejected`.

## Tech stack

- **PHP / Laravel** — REST API
- **Laravel Sanctum** — bearer-token authentication
- **SQLite** — development database
- **Eloquent ORM** — database relations
- **Swagger UI / OpenAPI 3** — API exploration and documentation
- **PHPUnit / Laravel test runner** — automated API tests

## API endpoints

| Method | Endpoint | Access | Purpose |
|---|---|---|---|
| POST | `/api/register` | Public | Create a startup or graduate account |
| POST | `/api/login` | Public | Authenticate and obtain a token |
| POST | `/api/logout` | Authenticated | Revoke current token |
| GET | `/api/me` | Authenticated | Current account information |
| GET | `/api/projects` | Public | List open projects |
| GET | `/api/projects/{project}` | Public | View a project |
| POST | `/api/projects` | Startup | Create a project |
| POST | `/api/projects/{project}/apply` | Graduate | Apply to a project |
| GET | `/api/projects/{project}/applications` | Project owner | Review applications |
| PATCH | `/api/applications/{application}/status` | Project owner | Accept or reject an application |

Protected routes require `Authorization: Bearer <token>`.

## Database design

| Table | Relevant fields |
|---|---|
| `users` | `id`, `name`, `email`, `password`, `role` |
| `projects` | `id`, `startup_id`, `title`, `description`, `skills`, `compensation`, `status` |
| `applications` | `id`, `project_id`, `graduate_id`, `message`, `status` |
| `personal_access_tokens` | Sanctum authentication tokens |

A unique constraint on `(project_id, graduate_id)` prevents multiple applications by the same graduate to the same project, including concurrent duplicate requests.

## Local setup

Requirements: PHP and Composer, with the SQLite PHP extension enabled.

```bash
git clone -b launch https://github.com/srabanimitra/LaunchBridge.git
cd LaunchBridge
composer install
cp .env.example .env
php artisan key:generate
mkdir -p database
touch database/database.sqlite
php artisan migrate
php artisan serve --host=0.0.0.0 --port=8000
```

The default `.env` should use `DB_CONNECTION=sqlite`. For a fresh application using the SQLite database, confirm the database path and migration state in your environment.

Visit:

- Laravel server: `http://localhost:8000`
- Swagger UI: `http://localhost:8000/swagger/`
- OpenAPI specification: `http://localhost:8000/openapi.yaml`

If developing inside GitHub Codespaces, open forwarded port **8000** from its **PORTS** tab. Codespaces port URLs are temporary and may be unavailable when the Codespace stops.

## Example requests

Register a startup:

```http
POST /api/register
Content-Type: application/json

{
  "name": "Example Startup",
  "email": "startup@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "startup"
}
```

Create a project with a startup token:

```http
POST /api/projects
Authorization: Bearer <startup-token>
Content-Type: application/json

{
  "title": "Student Events Dashboard",
  "description": "Build a simple dashboard to manage events and registrations for students.",
  "skills": ["Laravel", "MySQL"],
  "compensation": "Paid project — amount negotiable"
}
```

Apply to a project with a graduate token:

```http
POST /api/projects/1/apply
Authorization: Bearer <graduate-token>
Content-Type: application/json

{
  "message": "I have built Laravel projects and would like to contribute to this dashboard."
}
```

## Testing and error handling

```bash
php artisan test
php artisan route:list --path=api
```

During manual verification in Swagger UI, the following responses were observed:

| Scenario | HTTP status |
|---|---|
| Successful registration or project creation | `201 Created` |
| Unauthenticated project creation | `401 Unauthorized` |
| Graduate attempts to create a project | `403 Forbidden` |
| Startup attempts to apply to a project | `403 Forbidden` |
| Duplicate application | `409 Conflict` |
| Invalid registration email | `422 Unprocessable Content` |

The latest test run reported **4 passing tests and 7 assertions**. These tests cover the core application flow and unauthenticated project creation; additional error scenarios above were manually tested.

## Limitations and next steps

This is a **recruitment demo, not a production marketplace**. It does not yet include email verification, project completion workflows, payment processing, application editing, or a frontend dashboard. Real-world deployment would also need production-grade rate limiting, monitoring, user verification, and a persistent hosting environment.

## Security notes

Never commit `.env`, the SQLite database, API tokens, or real user credentials. The authentication tokens used in development are test credentials only. Keep `APP_DEBUG=false` for any publicly accessible demo.

## Author

**Srabani Mitra**  
GitHub: [@srabanimitra](https://github.com/srabanimitra)
