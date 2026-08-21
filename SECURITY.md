# Security and Maintenance

## Implemented application controls

- Browser code contains no database credential or server secret.
- The API permits only `POST` and CORS preflight `OPTIONS`; other methods return `405`.
- Requests require JSON, an exact configured Origin, a bounded body size, a valid random participant-code format, allowlisted assignment values, and matching identifiers inside the payload.
- SQL writes use native parameterized PDO statements with emulated prepares disabled.
- Public routes insert records only and provide no read, update, delete, reporting, login, or administrative operation.
- Rate limiting uses a daily HMAC of the direct connection address and stores no raw address in the application database.
- API errors return generic codes; operational details go only to protected server error logs.
- Assignment overrides are disabled in the committed production configuration.

## Required hosting controls

University IT and the application owner must establish HTTPS, TLS policy, HSTS, web-server security headers, patching, backups, restoration testing, monitoring, log protection, vulnerability scanning, and incident response. The hosting platform must prevent public access to environment configuration, database files, logs, backups, directory listings, and Git metadata.

Database, hosting-console, log, backup, and research-data access must be limited to approved personnel through University-managed accounts, SSO/MFA, and least privilege. The public participant API intentionally does not require a University account because eligible participants are recruited externally through Prolific.

## Data and research governance

Do not collect participant data until the final IRB protocol, hosting architecture, data classification, retention schedule, deletion workflow, Qualtrics configuration, Prolific linkage procedure, and access list are approved. A withdrawal endpoint records a request; only the authorized study team may verify and perform deletion under the approved protocol.

Do not commit secrets, participant data, Qualtrics exports, Prolific exports, database dumps, application logs, or production configuration to this repository.

## Maintenance ownership

The designated technical owner is responsible for testing application changes, maintaining supported PHP/MariaDB compatibility, responding to security findings, coordinating patch deployment with infrastructure staff, and retesting the full participant flow before each recruitment period. The faculty PI and final technical/support contacts must be confirmed before production approval.

Potential security issues should be reported privately to the designated technical owner and University information-security contact rather than opened as a public GitHub issue.
