# AI Authentication Study

University-hosting review implementation for a 2 x 2 behavioral experiment on authentication-chain effects and review of AI-assistant permissions.

## Review status

The repository contains a functional participant-facing prototype plus a PHP 8.1+/MariaDB 10.6+ insert-only study API. It is ready for University IT architecture and security feedback, but it is not authorized to collect participant data until the IRB, hosting, data-governance, and production-security reviews are complete.

The application does not implement real authentication. Every sign-in, SSO, MFA, account, and payment-related screen is simulated with study-provided values. Participants do not create accounts or submit credentials.

## Supported hosting stack

- Static HTML, CSS, and vanilla JavaScript
- PHP 8.1 or newer with PDO MySQL
- MariaDB 10.6 or newer
- HTTPS on an approved University hostname
- No containers, Node.js service, framework, package manager, scheduled email, or file upload

## Application structure

- `auth_task.html` - participant task, instrumentation, debrief, and Qualtrics handoff
- `study-config.js` - public deployment configuration; contains no secrets
- `api/study/task-events.php` - validated insert-only endpoint for completed and abandoned task records
- `api/study/task-withdrawals.php` - validated insert-only endpoint for withdrawal requests
- `api/study/bootstrap.php` - shared request validation, database connection, CORS, security headers, and rate limiting
- `database/mariadb_schema.sql` - production-target MariaDB tables and least-privilege grant examples
- `docs/ARCHITECTURE.md` - design, data flow, and trust boundaries
- `docs/SDLC.md` - lifecycle, roles, evidence, and production exit criteria
- `docs/REQUIREMENTS_TRACEABILITY.md` - University requirement-to-evidence matrix
- `docs/THREAT_MODEL.md` - assets, threats, controls, and residual risks
- `docs/TEST_PLAN.md` - automated, staging, accessibility, security, and operational tests
- `docs/DATA_DICTIONARY.md` - database, task payload, and Qualtrics information inventory
- `docs/OPERATIONS_RUNBOOK.md` - deployment, monitoring, incidents, rollback, withdrawal, and retirement
- `SECURITY.md` - implemented controls and infrastructure responsibilities
- `docs/THIRD_PARTY_SERVICES.md` - Qualtrics and Prolific inventory
- `deployment_checklist.md` - approval and production verification checklist
- `.github/workflows/quality.yml` - automated PHP and JavaScript syntax checks
- `CONTRIBUTING.md` and `CHANGELOG.md` - change control and material change history
- `irb_protocol_draft.md` - working protocol with unresolved governance fields
- `supabase_setup.sql` - deprecated prototype artifact; never deploy for participant data

## API contract

The browser sends JSON only to:

- `POST /api/study/task-events.php`
- `POST /api/study/task-withdrawals.php`

The API has no public read, update, delete, reporting, or administrative route. Researcher access is expected to occur through University-managed database and administrative tools protected by approved SSO and MFA.

Both endpoints require an exact allowed browser origin, validate the participant code and study assignment, confirm that envelope and payload identifiers match, enforce a request-size limit, use parameterized SQL, and apply rate limiting without storing the raw network address.

## Deployment outline

1. Complete the unresolved IRB and data-governance decisions.
2. Create the database with `database/mariadb_schema.sql`.
3. Create a least-privilege PHP database account using the grant pattern in that file.
4. Configure the server variables listed in `.env.example` through the hosting platform's protected environment settings. Do not place a `.env` file in the public document root.
5. Set the approved HTTPS `apiBaseUrl`, replace the Qualtrics placeholder in `study-config.js`, and keep assignment overrides disabled.
6. Deploy the repository to the approved HTTPS hostname.
7. Complete every item in `deployment_checklist.md`, including vulnerability and accessibility review, before recruitment.

## Local review

Opening `auth_task.html` directly keeps the study API disabled. Browser-console event logging is also disabled by default; it may be enabled temporarily for local fake-data testing by setting `enableDebugLogging` to `true`, but it must remain `false` in production. A full API test requires PHP, MariaDB, the schema, server environment variables, and an `Origin` header that exactly matches `STUDY_ALLOWED_ORIGIN`.

No production credentials, participant records, Qualtrics exports, or Prolific exports belong in this repository.

## Review statement

The current branch is prepared for University technical review. It is not production-approved. The exact completed and pending lifecycle evidence is recorded in `docs/SDLC.md` and `docs/REQUIREMENTS_TRACEABILITY.md`; reviewers should not interpret repository completeness as IRB, security, accessibility, or infrastructure approval.
