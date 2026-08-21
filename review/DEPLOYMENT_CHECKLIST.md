# Deployment Checklist

This checklist is a release gate used with `review/SDLC.md`, `review/TEST_PLAN.md`, and `review/OPERATIONS_RUNBOOK.md`. An unchecked required item means the application is not production-ready.

## Approval and ownership

- [ ] Obtain final IRB approval, including incomplete-disclosure and withdrawal wording.
- [ ] Confirm the faculty PI, technical owner, approved research team, and application-maintenance responsibility.
- [ ] Confirm source/research-material ownership, repository visibility, and the intended project license.
- [ ] Confirm data classification, hosting region, access controls, backups, retention, secure deletion, incident response, and server-log handling.
- [ ] Record the final sample-size cap and expected recruitment window.
- [ ] Document how any Prolific identifier is minimized, separated, accessed, and destroyed.

## Configuration

- [ ] Obtain an approved HTTPS hostname, DNS record, and TLS certificate.
- [ ] Set every server variable listed in `deployment/.env.example` using protected hosting configuration.
- [ ] Use a generated database password and an independent rate-limit secret of at least 32 random characters.
- [ ] Set `STUDY_ALLOWED_ORIGIN` to the exact production origin without a trailing slash.
- [ ] Set `apiBaseUrl` in `deployment/public/study-config.js` to the same approved HTTPS origin.
- [ ] Replace the Qualtrics placeholder in `deployment/public/study-config.js`.
- [ ] Confirm `enableAssignmentOverrides` remains `false` in production.
- [ ] Confirm `enableDebugLogging` remains `false` in production and participant records do not appear in the browser console.

## Application and database

- [ ] Confirm PHP 8.1+ includes PDO MySQL and MariaDB is 10.6+.
- [ ] Apply `deployment/database/mariadb_schema.sql` to the University-managed database.
- [ ] Confirm `task_events`, `task_withdrawals`, and `study_rate_limits` exist.
- [ ] Grant the PHP account only INSERT on the two research tables and the documented limited permissions on `study_rate_limits`.
- [ ] Confirm `POST /api/study/task-events.php` and `POST /api/study/task-withdrawals.php` return `201` for valid test records.
- [ ] Confirm GET, PUT, PATCH, and DELETE return `405` and an unapproved or missing Origin returns `403`.
- [ ] Confirm malformed JSON, oversized bodies, invalid participant codes, invalid assignments, and mismatched payload identifiers are rejected.
- [ ] Confirm database errors are logged server-side without returning credentials, SQL, or stack traces to the browser.
- [ ] Confirm the server does not expose `.env`, database dumps, logs, directory listings, or repository metadata.

## Infrastructure security

- [ ] Require HTTPS and enable HSTS after the hostname is validated.
- [ ] Configure supported TLS versions, security headers, request logging, monitoring, and alerting with University IT.
- [ ] Review whether server access logs contain IP addresses and apply the approved retention/access policy.
- [ ] Protect researcher, database, hosting-panel, backup, and log access with University-approved SSO/MFA and least privilege.
- [ ] Establish patching, dependency/version monitoring, vulnerability-remediation, and incident-response ownership.
- [ ] Complete University vulnerability testing and correct findings before production.

## Qualtrics and Prolific

- [ ] Add embedded fields `pid`, `condition`, and `placement` at the top of the Qualtrics Survey Flow.
- [ ] Confirm the fields capture the matching URL parameters.
- [ ] Confirm the approved Qualtrics tenant, data location, access list, export procedure, and retention settings.
- [ ] Configure only the minimum Prolific fields needed for recruitment, compensation, duplicate handling, and matching.
- [ ] Run one test participant through the task, Qualtrics, and Prolific completion route.
- [ ] Confirm the participant code and assignment join correctly without placing a Prolific identifier in the task payload.

## Accessibility and study validation

- [ ] Complete desktop, mobile, keyboard-only, screen-reader, zoom, contrast, and reduced-motion checks.
- [ ] Correct issues identified by the University accessibility review.
- [ ] Confirm the data-saving warning appears when the API is disabled and disappears on the configured HTTPS host.
- [ ] Confirm the frontend waits for a successful task save before enabling the survey handoff.
- [ ] Confirm the withdrawal endpoint records a test request and the approved research workflow can locate and delete the associated records.
- [ ] Confirm no simulation disclosure appears before or during the task and the full debrief appears before the survey handoff, as approved by the IRB.
- [ ] Remove all test records before recruitment.
