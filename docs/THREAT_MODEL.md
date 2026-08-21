# Threat Model

## Scope and security objectives

The scope is the public study page, PHP write API, MariaDB research tables, and handoff to Qualtrics. Prolific and Qualtrics internals are external trust zones.

Security objectives are to preserve participant confidentiality, prevent unauthorized reading or modification of stored research records, limit fabricated or abusive submissions, maintain study integrity, keep secrets out of the browser, provide reliable completed submissions, and support approved withdrawal/deletion procedures.

## Assets

- Behavioral research payloads and withdrawal requests
- Qualtrics survey responses and any separate Prolific linkage
- Database and hosting credentials
- Rate-limit HMAC secret
- Research integrity: random assignment, event ordering, and data joins
- Operational logs, backups, configuration, and deployment history

## Trust boundaries

1. Untrusted participant browser to public PHP API
2. PHP application to MariaDB using a least-privilege service account
3. Participant browser navigation to University Qualtrics and Prolific
4. University administrators/researchers to hosting, database, logs, backups, and exports

## Threats, controls, and residual risk

| Threat | Existing controls | Residual risk / required action |
|---|---|---|
| SQL injection | Allowlisted fields, bounded JSON, native prepared PDO statements | Verify PDO MySQL configuration in production and include injection cases in testing |
| Cross-site scripting | No untrusted HTML rendering, no dynamic `innerHTML`, no external scripts | Configure and test a production Content Security Policy at the web server |
| Cross-origin browser submission | Exact HTTPS Origin validation and restrictive CORS response | Origin is not authentication and non-browser clients can forge it |
| Fabricated study rows | 80-bit random participant codes, cross-field validation, rate limiting | Public recruitment means no strong participant authentication; consider signed launch tokens if IT requires stronger integrity |
| Participant-code guessing | Cryptographic 80-bit codes and rate limiting | Codes must not be treated as identity proof outside the approved withdrawal procedure |
| Unauthorized database reading | No public read route; application grants are insert-only for research tables | Verify live grants; protect researcher access with University SSO/MFA |
| Unauthorized deletion/modification | No public update/delete route; withdrawal only records a request | Authorized deletion workflow and audit evidence remain pending |
| Secret disclosure | Environment-only server secrets, ignored private files, static secret checks | Hosting must prevent access to environment, logs, backups, and Git metadata |
| Sensitive browser-console disclosure | Debug logging disabled by default | Verify production configuration and browser console during acceptance testing |
| Denial of service or spam | Request-size limit, rate limiting, bounded parsing | Add infrastructure limits/monitoring; review behavior behind reverse proxies or shared NAT |
| Rate-limit privacy | Daily HMAC pseudonym; raw address absent from application database | Web/proxy access logs may store raw IP and need an approved policy |
| Data interception | HTTPS-only approved origin requirement | University must configure TLS, HSTS, certificate renewal, and encryption at rest |
| Dependency/supply-chain compromise | No runtime packages, CDN, Composer, or container dependency | GitHub Actions uses a pinned major checkout action; monitor workflow changes and supported runtime versions |
| Assignment manipulation | URL overrides disabled by default | Verify the deployed config; consider server/Qualtrics assignment for stronger study-integrity controls |
| Duplicate/replayed submissions | Random participant code and recorded timestamps | Database currently permits duplicates for retry/abandonment semantics; analysis plan must define duplicate handling |
| Withdrawal abuse | Request is logged rather than automatically executed | Researcher must verify and process under the IRB-approved procedure |
| Backup or export exposure | No export code in the public application | University must define encryption, access, restoration, retention, and destruction controls |
| Service failure/data loss | Completed submission waits for API response | Abandonment remains best effort; backup/restore and monitoring require live validation |

## Risk acceptance

Unresolved risks must be owned by the faculty PI, University IT, information security, accessibility, or data-governance authority as appropriate. A repository commit or developer statement is not risk acceptance. Critical confidentiality or integrity findings require suspension of recruitment until remediated or formally accepted through University procedures.
