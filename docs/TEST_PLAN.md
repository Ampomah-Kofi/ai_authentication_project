# Verification and Test Plan

## Test environments

- **Local/static:** fake data only; no approved participant collection
- **University staging:** production-equivalent PHP/MariaDB configuration using test records
- **Production smoke test:** minimum fake records after approval and before recruitment

Test data must use generated study codes and fictional values. Never place participant data in source control, CI output, screenshots, or public issue trackers.

## Automated checks

| Check | Command/evidence | Current status |
|---|---|---|
| External configuration JavaScript syntax | `node --check study-config.js` | Passed locally |
| Inline application JavaScript parse | `node scripts/check-inline-js.mjs` | Passed locally |
| Security invariants and secret patterns | `node scripts/static-security-check.mjs` | Passed locally |
| Workflow YAML parse | Local YAML parser | Passed locally |
| PHP syntax | CI `php -l` | Pending remote push/CI; PHP unavailable locally |
| API rejection guards | `bash scripts/test-api-guards.sh` in CI | Script syntax passed locally; execution pending PHP CI |
| Private-file rejection | GitHub workflow | Pending remote push/CI |

## Staging API cases

Verify both endpoints with response bodies that contain no stack trace, SQL, credential, or internal path.

| Case | Expected result |
|---|---|
| Valid task record | `201`, generated record UUID, one matching MariaDB row |
| Valid withdrawal request | `201`, generated record UUID, one matching request row |
| GET/PUT/PATCH/DELETE | `405` |
| Missing or unapproved Origin | `403` |
| Non-JSON content type | `415` |
| Empty/malformed JSON | `400` |
| Oversized body | `413` |
| Invalid code, condition, or placement | `422` |
| Envelope/payload identifier mismatch | `422` |
| Invalid withdrawal timestamp/request flag | `422` |
| Rate threshold exceeded | `429` with `Retry-After` |
| Database unavailable | Generic `503`; protected operational log entry |
| Public SELECT/update/delete attempt | No route; database account denied |

## End-to-end and research-integrity cases

- Complete each of the four experimental cells using controlled test overrides in a non-production configuration.
- Confirm overrides are disabled in production.
- Verify event ordering, screen timing, scrolling, planted-permission position, recall values, completion, and abandonment classification.
- Verify a completed submission is confirmed before the Qualtrics button becomes available.
- Verify task `pid`, `condition`, and `placement` join exactly to Qualtrics embedded fields.
- Verify no Prolific identifier enters the behavioral payload unless the final approved design explicitly requires it.
- Test duplicate, refresh, back-button, interrupted connection, API failure, and survey-link failure behavior.
- Test the approved withdrawal verification and deletion workflow, including database and Qualtrics records.

## Accessibility and compatibility

- Keyboard-only completion with logical focus order and visible focus
- Screen-reader review of headings, labels, fieldsets, live regions, and error/status messages
- 200% and 400% zoom/reflow; desktop and narrow mobile viewports
- Contrast review and non-color status cues
- Reduced-motion preference
- Current University-supported browsers and mobile platforms
- WCAG 2.2 AA evaluation using automated tools plus human review

No conformance claim may be made until the applicable University review is complete.

## Security, load, and operations

- University vulnerability scan and manual application-security review
- TLS, headers, HSTS, CSP, directory/file exposure, and error-page review
- Verify SSO/MFA and least privilege for administration, logs, backups, database, and exports
- Expected and burst traffic tests using the final recruitment cap
- Reverse-proxy/NAT rate-limit test
- Backup restoration test and documented recovery time/data-loss result
- Monitoring/alert delivery test and incident contact exercise
- Secret rotation and rollback rehearsal

## Exit criteria

Production release requires all applicable cases to pass, findings to be remediated or formally accepted, test records removed, exact deployed commit recorded, configuration independently reviewed, and the approvals listed in `docs/SDLC.md` completed.
