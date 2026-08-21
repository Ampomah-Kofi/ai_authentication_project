# Changelog

Material application, data, security, and operational changes are documented here. Git history remains the authoritative source for exact diffs.

## Unreleased / University review branch

### Added

- PHP 8.1+ insert-only task-event and withdrawal endpoints
- MariaDB 10.6+ schema and least-privilege grant examples
- Exact-origin, bounded-JSON, allowlist, cross-field, timestamp, and rate-limit validation
- Architecture, security, third-party, SDLC, requirements, threat, testing, data, and operations documentation
- CI syntax checks, secret/private-file checks, static security invariants, and API rejection-guard tests

### Changed

- Participant codes strengthened to 80 bits of cryptographic randomness
- Production browser-console logging and assignment overrides disabled by default
- Dynamic HTML insertion removed in favor of safe DOM operations
- Supabase/PostgreSQL prototype marked deprecated for participant data

### Pending before production

- Final IRB, data classification, owner/access list, sample cap, retention/deletion, and Prolific-linkage decisions
- University hosting, DNS, TLS, administrative SSO/MFA, logging, backup/restore, monitoring, patching, and incident-response validation
- PHP/MariaDB staging integration, vulnerability, accessibility, load, browser, and end-to-end testing
- Production Qualtrics and API configuration
