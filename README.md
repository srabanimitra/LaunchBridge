
# LaunchBridge API

**Connecting Fresh Graduates with Startups Through Real-World Projects**

LaunchBridge is a backend-focused proof of concept developed for the **Credosis Internship Program — Phase 02 (From Idea to Demo)**.

The project addresses a common challenge faced by fresh graduates: companies often expect practical experience, while graduates struggle to find opportunities to gain that experience.

LaunchBridge provides a simple platform where startups can publish real-world projects, graduates can apply based on their skills, and startups can review applications and select suitable candidates.

This project focuses on backend functionality, database design, RESTful APIs, authentication, authorization, validation, and error handling.

---

## Live Demo

| Resource | Link |
|---|---|
| Live Backend | https://launchbridge.onrender.com |
| Swagger UI | https://launchbridge.onrender.com/swagger/ |
| OpenAPI Specification | https://launchbridge.onrender.com/openapi.yaml |
| GitHub Repository | https://github.com/srabanimitra/LaunchBridge |

**Deployment Status:** Live on Render

**Hosting:** Render (Docker + PostgreSQL)

**Note:** The free Render instance may enter sleep mode after inactivity. The first request may take approximately 50 seconds or longer.

---

## Problem Statement

Fresh graduates in Bangladesh often face difficulties entering the professional job market.

Many employers prefer candidates with previous work experience, while recent graduates have limited opportunities to gain that experience.

At the same time, advances in AI and automation are changing the nature of entry-level work.

This creates a challenging situation:

- Graduates need experience to get jobs.
- Companies often expect experience before hiring.
- Startups may need assistance with projects but lack the resources for traditional recruitment.

LaunchBridge explores a project-based approach to bridging this gap.

Instead of depending entirely on previous employment or academic results, graduates can demonstrate their abilities by participating in real startup projects.

---

## Proposed Solution

LaunchBridge connects two types of users:

**Startups**

Startups can register, publish projects, review applications, and accept or reject candidates.

**Graduates**

Graduates can register, explore available projects, and submit applications to opportunities matching their interests and skills.

The primary objective is to make it easier for fresh graduates to gain practical experience while helping startups discover potential talent.

---

## Implemented Features

### 1. Authentication and User Roles

- User registration
- User login
- User logout
- Laravel Sanctum token authentication
- Two account roles: `startup` and `graduate`
- Role-based access control

### 2. Project Management

- Startups can create projects.
- Users can browse available projects.
- Users can view individual project details.
- Projects contain descriptions, required skills, and compensation information.

### 3. Graduate Applications

- Graduates can apply to available projects.
- Applications include a message from the graduate.
- Duplicate applications are prevented.
- Application records are linked to projects and graduates.

### 4. Application Review

- Project owners can view applications.
- Startups can accept or reject applicants.
- Application statuses are stored in the database.
- Users cannot manage applications belonging to other startup accounts.

### 5. API Validation and Security

- Request validation
- Email format validation
- Authentication checks
- Role-based authorization
- Resource ownership checks
- Duplicate application prevention
- JSON error responses
- Appropriate HTTP status codes

### 6. Interactive API Documentation

Swagger UI provides interactive API documentation using an OpenAPI 3 specification.

Users can explore endpoints, submit requests, test authentication, and inspect server responses.

---

## Core Workflow

The implemented demonstration follows this process:

1. A startup creates an account.
2. The startup logs in and receives an authentication token.
3. The startup publishes a project.
4. A graduate creates an account.
5. The graduate browses available projects.
6. The graduate applies to a project.
7. The startup views the submitted application.
8. The startup accepts or rejects the application.

This workflow represents the primary functionality of the LaunchBridge proof of concept.

---

## Technology Stack

| Technology | Purpose |
|---|---|
| PHP 8.4 | Backend programming language |
| Laravel 13 | REST API framework |
| Laravel Sanctum | Token-based authentication |
| PostgreSQL | Cloud database |
| SQLite | Local development database |
| Eloquent ORM | Database relationships |
| Swagger UI | Interactive API documentation |
| OpenAPI 3.0 | API specification |
| PHPUnit / Laravel Test Runner | Automated testing |
| Docker | Application containerization |
| Render | Cloud deployment |
| Git and GitHub | Version control |

---

## System Architecture

LaunchBridge follows a REST API architecture.

```text
             API Client / Swagger UI
                       |
                       v
                Laravel Routes
                       |
                       v
                 Controllers
                       |
                       v
           Authentication & Validation
                       |
                       v
                Eloquent Models
                       |
                       v
                PostgreSQL Database
```

The backend uses controllers to handle incoming HTTP requests, Laravel Sanctum for authentication, and Eloquent models for database operations.

For cloud deployment, the application runs inside a Docker container on Render and connects to a PostgreSQL database.

---

## Database Design

The application uses the following main tables.

### Users

Stores graduate and startup account information.

| Field | Purpose |
|---|---|
| id | Unique user identifier |
| name | User or startup name |
| email | Account email |
| password | Hashed password |
| role | startup or graduate |

### Projects

Stores projects published by startups.

| Field | Purpose |
|---|---|
| id | Project identifier |
| startup_id | Startup that owns the project |
| title | Project title |
| description | Project details |
| skills | Required skills |
| compensation | Compensation information |
| status | Project status |

### Applications

Stores applications submitted by graduates.

| Field | Purpose |
|---|---|
| id | Application identifier |
| project_id | Associated project |
| graduate_id | Graduate who applied |
| message | Application message |
| status | pending, accepted, or rejected |

### Personal Access Tokens

Laravel Sanctum manages API authentication tokens using the `personal_access_tokens` table.

### Database Relationships

- One startup can create multiple projects.
- One project can receive multiple applications.
- One graduate can apply to multiple projects.
- Each application belongs to one graduate and one project.

A unique database constraint on `(project_id, graduate_id)` prevents the same graduate from applying to the same project multiple times.

---

## API Endpoints

**Base URL:**

https://launchbridge.onrender.com/api

| Method | Endpoint | Access | Description |
|---|---|---|---|
| POST | `/register` | Public | Register a user |
| POST | `/login` | Public | Authenticate user |
| POST | `/logout` | Authenticated | Revoke authentication token |
| GET | `/me` | Authenticated | Get current user |
| GET | `/projects` | Public | List projects |
| GET | `/projects/{project}` | Public | View project details |
| POST | `/projects` | Startup | Create project |
| POST | `/projects/{project}/apply` | Graduate | Apply to project |
| GET | `/projects/{project}/applications` | Project owner | Review applications |
| PATCH | `/applications/{application}/status` | Project owner | Update application status |

### Authentication

Protected endpoints require a valid Laravel Sanctum bearer token.

```http
Authorization: Bearer YOUR_TOKEN
```

Authentication tokens are returned after successful registration or login.

---

## Example API Requests

### 1. Register a Startup

**POST `/api/register`**

```json
{
  "name": "TechNova Startup",
  "email": "technova@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "startup"
}
```

Expected result: `201 Created`

The response contains the registered user information and an authentication token.

### 2. Create a Project

**POST `/api/projects`**

Requires startup authentication.

```json
{
  "title": "University Event Management Dashboard",
  "description": "Build a dashboard to manage university events and student registrations.",
  "skills": [
    "Laravel",
    "PHP",
    "MySQL"
  ],
  "compensation": "Paid project - negotiable"
}
```

Expected result: `201 Created`

### 3. Register a Graduate

**POST `/api/register`**

```json
{
  "name": "Demo Graduate",
  "email": "graduate@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "graduate"
}
```

### 4. Apply to a Project

**POST `/api/projects/1/apply`**

Requires graduate authentication.

```json
{
  "message": "I have experience with Laravel and MySQL and would like to contribute to this project."
}
```

The application is stored with an initial status of `pending`.

### 5. Review Applications

**GET `/api/projects/1/applications`**

Requires authentication as the startup that owns the project.

The response includes application details and related graduate information.

### 6. Accept an Application

**PATCH `/api/applications/1/status`**

```json
{
  "status": "accepted"
}
```

The application status is updated in the database.

---

## Error Handling and Validation

The backend uses Laravel validation, authentication middleware, and authorization checks.

The following responses were verified during testing:

| Scenario | HTTP Status |
|---|---|
| Successful registration | `201 Created` |
| Successful project creation | `201 Created` |
| Request without authentication | `401 Unauthorized` |
| Graduate attempts to create project | `403 Forbidden` |
| Startup attempts to apply | `403 Forbidden` |
| Duplicate application | `409 Conflict` |
| Invalid registration email | `422 Unprocessable Content` |

### Example: Duplicate Application

```json
{
  "message": "Already applied to this project."
}
```

HTTP Status: `409 Conflict`

### Example: Unauthenticated Request

```json
{
  "message": "Unauthenticated."
}
```

HTTP Status: `401 Unauthorized`

These responses help API clients understand errors without exposing internal application details.

---

## Local Installation

### Requirements

- PHP compatible with the project
- Composer
- SQLite PHP extension
- Git

### 1. Clone the Repository

```bash
git clone -b main https://github.com/srabanimitra/LaunchBridge.git
cd LaunchBridge
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Ensure `.env` contains:

```env
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
```

### 4. Prepare the Database

```bash
mkdir -p database
touch database/database.sqlite
php artisan migrate
```

### 5. Start Laravel

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 6. Open API Documentation

Swagger UI:

http://localhost:8000/swagger/

OpenAPI Specification:

http://localhost:8000/openapi.yaml

---

## Automated Testing

Run the tests using:

```bash
php artisan test
```

To view registered API routes:

```bash
php artisan route:list --path=api
```

The latest verified test execution reported:

**4 passing tests and 7 assertions.**

The automated test suite covers the core project application workflow and unauthenticated project creation.

Additional role-based authorization, validation, and duplicate application scenarios were manually verified using Swagger UI.

---

## Cloud Deployment

LaunchBridge is deployed using Docker and Render.

### Deployment Configuration

| Component | Configuration |
|---|---|
| Hosting Platform | Render |
| Deployment Runtime | Docker |
| Git Branch | main |
| Application Environment | production |
| Database | PostgreSQL 18 |
| Database Region | Singapore |
| Application Port | 10000 |
| Documentation | Swagger UI |

### Deployment Process

1. Render connects to the GitHub repository.
2. Render builds the Docker image.
3. Composer installs Laravel dependencies.
4. The application connects to PostgreSQL.
5. Laravel database migrations are executed.
6. The web service starts.
7. Render provides an HTTPS endpoint.

### Important Environment Variables

```env
APP_NAME=LaunchBridge
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql
DB_PORT=5432
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
PORT=10000
```

Database credentials, `APP_KEY`, and the production `APP_URL` are configured securely through Render environment settings.

Sensitive credentials are not included in the repository.

### Hosting Limitations

The application uses Render's Free web service.

Free instances can sleep after inactivity, resulting in delayed responses when they restart.

The deployment is intended for demonstration and evaluation rather than production-scale traffic.

---

## Challenges and Solutions

### Authentication and Authorization

**Challenge:** Different user roles require different access permissions.

**Solution:** Laravel Sanctum and role-based authorization ensure that startup and graduate accounts can access only their permitted actions.

### Duplicate Applications

**Challenge:** A graduate might attempt to apply multiple times to the same project.

**Solution:** Application-level checks and a database unique constraint prevent duplicate submissions.

### API Error Responses

**Challenge:** Unauthenticated API requests initially caused an internal server error.

**Solution:** Laravel's exception configuration was updated so API authentication failures return JSON responses with `401 Unauthorized`.

### Cloud Proxy Configuration

**Challenge:** Pagination links initially referenced localhost instead of the public HTTPS address.

**Solution:** Laravel's trusted proxy configuration was updated to correctly handle forwarded requests in the hosting environment.

### Time Constraints

**Challenge:** Developing a complete marketplace would require more time than the assessment allows.

**Solution:** Development was limited to the core project application workflow and essential supporting features.

---

## Current Limitations

LaunchBridge is a backend proof of concept, not a complete production marketplace.

The current version does not include:

- A dedicated frontend dashboard
- Email verification
- Project completion tracking
- Payment processing
- Real-time notifications
- Advanced skill matching
- Production-grade monitoring and rate limiting

These would be considered for future development.

---

## Future Improvements

Potential improvements include:

1. Frontend dashboards for graduates and startups.
2. Graduate profiles and project portfolios.
3. Project completion and feedback workflows.
4. Startup and project verification.
5. Improved project discovery and filtering.
6. Notifications for application updates.
7. More comprehensive automated tests.
8. Improved deployment and monitoring infrastructure.

---

## Security Considerations

- Passwords are stored using Laravel's password hashing.
- Authentication uses Laravel Sanctum.
- Protected routes require bearer tokens.
- Role and ownership checks restrict sensitive operations.
- Request validation prevents invalid inputs.
- Duplicate applications are rejected.
- Production debugging is disabled.
- `.env` files, database credentials, and API tokens must never be committed.

This proof of concept requires additional security hardening before use as a production platform.

---

## Credosis Internship — Phase 02

This project was developed as a practical demonstration of backend engineering skills.

The focus is on:

- REST API development
- Database design and relationships
- Authentication and authorization
- Request validation
- Error handling
- API documentation
- Testing
- Cloud deployment

The goal is to demonstrate a functional core idea rather than a complete production-ready application.

---

## Author

**Srabani Mitra**

Computer Science and Engineering  
Ahsanullah University of Science and Technology

GitHub: https://github.com/srabanimitra
