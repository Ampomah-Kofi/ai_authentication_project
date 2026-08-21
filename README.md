# AI Authentication Study

University-hosting review implementation for a 2 x 2 behavioral experiment on authentication-chain effects and review of AI-assistant permissions.

## Repository organization

The folders separate what University IT needs to deploy from review evidence and research-only material.

### `deployment/` - required to run the application

- `deployment/public/index.html` - participant task, instrumentation, debrief, and Qualtrics handoff
- `deployment/public/study-config.js` - public configuration with safe, disabled defaults
- `deployment/public/api/study/` - the two public insert-only PHP endpoints
- `deployment/src/bootstrap.php` - server-only validation, database, CORS, headers, and rate-limit code
- `deployment/database/mariadb_schema.sql` - MariaDB tables and least-privilege grant examples
- `deployment/.env.example` - names and placeholder values for protected server configuration

Configure the approved web server document root as `deployment/public`. This keeps the database schema, environment example, and shared server code outside the publicly served directory.

### `review/` - needed for University technical and compliance review

- Architecture, SDLC, requirements traceability, threat model, test plan, and data dictionary
- Security, deployment, accessibility, operational, incident, rollback, and retirement evidence
- Third-party service and licensing inventory
- University hosting technical overview and production checklist

Start with `review/UNIVERSITY_HOSTING_OVERVIEW.md`, then use `review/REQUIREMENTS_TRACEABILITY.md` to locate supporting evidence.

### `research/` - research materials, not needed to host the site

- IRB protocol draft
- Qualtrics survey and setup notes
- Advisor update and study-flow explanation
- Recruitment draft, bibliography, and research-flow diagram

These files may be needed by the PI, IRB, or research team but should not be included in a production web deployment.

### `archive/` - obsolete or historical; never deploy

- Deprecated Supabase/PostgreSQL prototype schema
- Initial hosting-request email draft

These files remain only for project history and are clearly named to prevent accidental use.

### Root and automation files

- `SECURITY.md`, `CONTRIBUTING.md`, and `CHANGELOG.md` - repository-level security and change control
- `.github/` - code ownership and automated checks
- `scripts/` - local/CI syntax, security-invariant, and API guard checks

## Review status

The repository contains a functional participant-facing prototype plus a PHP 8.1+/MariaDB 10.6+ insert-only API. It is ready for University IT architecture and security feedback, but it is not authorized to collect participant data until the IRB, hosting, data-governance, accessibility, and production-security reviews are complete.

The application does not implement real authentication. Every sign-in, SSO, MFA, account, and payment-related screen is simulated with study-provided values. Participants do not create accounts or submit credentials.

## Supported hosting stack

- Static HTML, CSS, and vanilla JavaScript
- PHP 8.1 or newer with PDO MySQL
- MariaDB 10.6 or newer
- HTTPS on an approved University hostname
- No containers, Node.js runtime service, application framework, package manager, scheduled email, or file upload

## Public API

- `POST /api/study/task-events.php`
- `POST /api/study/task-withdrawals.php`

The API has no public read, update, delete, reporting, or administrative route. Researcher access must occur through University-managed tools protected by approved SSO/MFA.

Both endpoints require an exact allowed browser origin, validate the participant code and study assignment, confirm that envelope and payload identifiers match, enforce a request-size limit, use native parameterized SQL, and apply rate limiting without storing the raw network address in the application database.

## Deployment outline

1. Complete the unresolved IRB and University decisions listed in `review/DEPLOYMENT_CHECKLIST.md`.
2. Create the database with `deployment/database/mariadb_schema.sql`.
3. Create a least-privilege PHP database account using the grant pattern in that schema.
4. Configure the variables listed in `deployment/.env.example` through protected hosting settings. Never place a real `.env` file in `deployment/public`.
5. Set the approved HTTPS API and Qualtrics values in `deployment/public/study-config.js`; keep debug logging and assignment overrides disabled.
6. Configure `deployment/public` as the HTTPS document root.
7. Complete the full test and release gates before recruitment.

## Local review

Opening `deployment/public/index.html` directly keeps the API disabled. Console event logging is disabled by default and may be enabled only for local fictional-data testing. Full API testing requires PHP, MariaDB, the schema, server environment values, and an Origin matching `STUDY_ALLOWED_ORIGIN`.

No production credential, participant record, Qualtrics/Prolific export, database dump, or application log belongs in this repository.
