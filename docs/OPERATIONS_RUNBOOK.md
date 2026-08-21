# Operations and Release Runbook

## Preconditions

- Production-ready criteria in `docs/SDLC.md` are satisfied.
- Exact release commit and approvers are recorded.
- Production configuration is stored outside the web root and source repository.
- Database, hosting, log, backup, and research access lists are approved.
- Maintenance window, rollback owner, incident contacts, and participant-recruitment status are known.

## Deployment

1. Back up the current approved application/configuration and verify rollback access.
2. Apply reviewed schema changes using a database administrator account; do not grant the application broad privileges.
3. Configure the server variables from `.env.example` in protected hosting settings.
4. Set the approved HTTPS API/Qualtrics values in `study-config.js`; keep debug logging and assignment overrides disabled.
5. Deploy only the reviewed commit. Do not deploy `.git`, local environment files, logs, exports, database dumps, or draft secrets.
6. Configure TLS, certificate renewal, HSTS, CSP/security headers, directory restrictions, supported PHP settings, logging, monitoring, and backups with University IT.
7. Run production smoke tests using fictional records, confirm Qualtrics joining, inspect the browser console/network responses, then remove test records.
8. Obtain release sign-off before opening recruitment.

## Monitoring and routine maintenance

- Monitor availability, PHP errors, database errors, rate-limit anomalies, certificate expiration, backup results, and vulnerability findings without exposing research payloads in alerts.
- Review administrative access and service accounts at the interval required by University policy and after personnel changes.
- Track supported PHP/MariaDB/browser versions and apply security patches through a tested change.
- Re-run the full participant flow before each recruitment period and after any participant-facing, API, database, Qualtrics, or infrastructure change.

## Incident response

1. Preserve safety: suspend recruitment or disable submissions when confidentiality, integrity, consent, or data-loss risk may be material.
2. Contact the technical owner, faculty PI, University IT/information security, and research-compliance contacts according to University policy.
3. Preserve protected logs and deployment evidence without copying participant data into public issues or chat.
4. Determine scope, affected records, exposure window, root cause, and required notification.
5. Remediate in a reviewed branch; test and obtain required approvals before restoration.
6. Document lessons learned and update requirements, threat model, tests, and controls.

The repository does not define breach-notification timing; University policy and applicable IRB/legal requirements control.

## Withdrawal processing

1. An authorized researcher reviews a `task_withdrawals` row.
2. Verify the request using the IRB-approved participant-code procedure; do not treat possession of a code as identity proof unless the protocol explicitly authorizes it.
3. Locate matching task, Qualtrics, and separately stored linkage records.
4. Delete or exclude records according to the approved deadline and retention rules.
5. Record completion in the protected study administration record without exposing participant data publicly.

## Backup and recovery

University IT must define backup frequency, encryption, storage region, access, retention, restoration ownership, recovery time objective, and recovery point objective. Before recruitment, restore a backup into an isolated test environment and verify record counts, JSON validity, access controls, and application operation.

## Rollback

- Stop recruitment if the release affects consent, data integrity, confidentiality, accessibility, or successful submission.
- Restore the last approved application commit and compatible configuration.
- Do not reverse a database migration until the DBA confirms data preservation and schema compatibility.
- Run smoke tests, reconcile records received during the incident window, and obtain sign-off before resuming.

## Secret rotation and personnel changes

Rotate database and rate-limit secrets after suspected exposure and according to University policy. Remove departed personnel from hosting, database, Qualtrics, Prolific, logs, backups, and repository access; review recent activity and transfer ownership.

## Study closure and retirement

1. Close recruitment and disable public submissions when no longer needed.
2. Export only approved analysis data to approved storage and verify access.
3. Process outstanding withdrawal requests.
4. Apply the IRB retention/de-identification/destruction schedule to MariaDB, Qualtrics, Prolific linkage, logs, and backups.
5. Revoke service accounts/secrets, remove DNS/certificates when appropriate, archive required code/evidence, and record closure approval.
