# University Hosting Technical Overview

## Application

The proposed site is a short web-based usable-security research study supervised by Dr. Le Nhat Tu. Adult university students recruited through Prolific complete a simulated authentication and AI-permission task and then continue to a University Qualtrics survey.

The deployable implementation uses static HTML, CSS, and vanilla JavaScript; PHP 8.1+; and MariaDB 10.6+. It requires no application framework, container, Node.js process, Composer dependency, background worker, scheduled email, or specialized server component. The source is maintained at `https://github.com/Ampomah-Kofi/ai_authentication_project`.

## Functionality and endpoints

The public application displays the task, generates a random participant code and study assignment, collects behavioral events, and sends JSON to two insert-only PHP endpoints:

- `POST /api/study/task-events.php` for completed and best-effort abandoned task records
- `POST /api/study/task-withdrawals.php` for participant withdrawal requests

There is no public read, update, delete, reporting, account-management, or administrative API. The browser contains no database credentials or other secrets.

## Authentication

Participants do not create an account or authenticate to the application. The sign-in, SSO, MFA, identity-provider, account, and payment-related screens are simulations using study-provided values and do not connect to Okta, Duo, Google, Microsoft, a payment service, or any real account.

Researcher access to the hosting environment, database, logs, backups, and exports should use University-managed accounts, approved SSO/MFA, and least privilege. The application itself does not provide a researcher administration interface.

## Information collected

The University-hosted task records a random participant code, assigned study cell, screen sequence and timing, button choices, simulated MFA attempts, scrolling and permission exposure, allow/cancel decision, immediate recall responses, browser user-agent, general display dimensions, timestamps, and completion/abandonment state. A participant may also submit a withdrawal request keyed by the random code.

The task does not request real names, personal email addresses, passwords, account credentials, payment details, health information, uploaded files, or University student identifiers. The associated Qualtrics survey includes eligibility confirmations, knowledge and experience items, field of study, degree level, optional gender, and optional open text. Because these are human-subject research records and include student-status information, the final classification, access, retention, deletion, and IRB controls remain subject to University review.

Any Prolific identifier needed for recruitment, compensation, duplicate handling, or matching will be minimized and stored separately from the behavioral task records under the final IRB-approved procedure.

## Database

MariaDB stores three tables:

- `task_events` for completed and abandoned behavioral payloads
- `task_withdrawals` for removal requests
- `study_rate_limits` for short-lived rate-limit counters using a daily HMAC pseudonym rather than a raw network address

The proposed PHP account receives INSERT only on the two research tables and narrowly scoped permissions on the rate-limit table. Researcher read or deletion access is not granted to the public application account.

## Security controls in the code

The submitted implementation provides exact-origin enforcement, JSON-only POST handling, bounded request bodies, allowlisted field validation, cross-field identity checks, parameterized SQL, generic client errors, server-side operational logging, insert-only public routes, and rate limiting. Assignment overrides are disabled by default, and all database credentials and rate-limit secrets are server-side environment values.

University infrastructure must still provide or approve HTTPS/TLS, DNS, certificate management, SSO/MFA for administrative access, backups, restoration testing, server-log policy, monitoring, patching, vulnerability testing, incident response, and final security headers. Findings from University security or accessibility testing will be corrected before participant recruitment.

## Third parties and network requirements

The external services are University Qualtrics for survey collection and Prolific for recruitment, compensation, and completion routing. The application uses no external JavaScript libraries, CDN assets, analytics, advertising, identity-provider API, payment API, email service, or file-upload service.

The site requires ordinary inbound HTTPS and participant browser navigation to the approved Qualtrics and Prolific pages. It sends no email, accepts no files, and currently requires no special inbound firewall exception beyond HTTPS.

## Use, ownership, and remaining decisions

Each participant completes one short session. The final enrollment cap is pending power analysis and IRB approval; expected use is bounded research-study traffic rather than continuous enterprise traffic.

Kofi Ampomah is the proposed application technical owner and will coordinate application maintenance, testing, remediation of findings, and application-level troubleshooting with the faculty supervisor and University infrastructure team. Before production, the study team will confirm the final faculty PI, approved access list, enrollment cap, retention/deletion schedule, Qualtrics data controls, Prolific linkage procedure, hostname, and operational contacts.
