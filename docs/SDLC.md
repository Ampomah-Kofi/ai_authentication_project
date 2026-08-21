# Secure Software Development Lifecycle

## Purpose and scope

This document defines the lifecycle for the participant-facing study task, its two PHP submission endpoints, and the MariaDB schema. It does not govern Qualtrics or Prolific internally; those services are governed through their institutional agreements and the final IRB plan.

The process is informed by NIST SP 800-218 Secure Software Development Framework (SSDF) 1.1 and uses WCAG 2.2 as the accessibility verification target. This is a project process statement, not a claim of NIST certification or WCAG conformance.

## Roles

| Role | Responsibility | Status |
|---|---|---|
| Faculty PI | Research approval, IRB alignment, data-use decisions, risk acceptance | Must be formally confirmed |
| Technical owner | Application changes, tests, remediation, releases, application support | Kofi Ampomah proposed |
| University IT | Hosting, DNS, TLS, administrative SSO/MFA, infrastructure logging, backup, monitoring, patch coordination | Pending assignment |
| Approved research team | Authorized analysis, withdrawal processing, retention and deletion | Pending final access list |
| Information security/accessibility reviewers | Independent production findings and approval | Pending University routing |

No developer may approve unresolved research, privacy, or infrastructure risk on behalf of the PI or University control owner.

## Lifecycle evidence and status

| Phase | Required evidence | Repository evidence | Current status |
|---|---|---|---|
| Plan | Scope, owners, users, data, regulatory constraints | `README.md`, `irb_protocol_draft.md`, `docs/UNIVERSITY_HOSTING_OVERVIEW.md` | Partial: owners, sample cap, and approvals pending |
| Requirements | Functional, security, privacy, accessibility, operational requirements | `docs/REQUIREMENTS_TRACEABILITY.md` | Review-ready; external decisions pending |
| Design | Architecture, trust boundaries, database design, threat analysis | `docs/ARCHITECTURE.md`, `docs/THREAT_MODEL.md`, `database/mariadb_schema.sql` | Review-ready |
| Build | Reviewed source, safe defaults, no committed secrets, supported versions | `auth_task.html`, `study-config.js`, `api/`, `.env.example`, `SECURITY.md` | Implemented |
| Verify | Automated checks, integration tests, accessibility testing, vulnerability testing, approval evidence | `.github/workflows/quality.yml`, `scripts/`, `docs/TEST_PLAN.md` | Partial: local static checks pass; live tests pending |
| Release | Approved change, immutable commit, configuration review, rollback plan | `CONTRIBUTING.md`, `CHANGELOG.md`, `deployment_checklist.md`, `docs/OPERATIONS_RUNBOOK.md` | Not approved for production |
| Operate | Monitoring, incident response, backup/restore, access review, patching | `SECURITY.md`, `docs/OPERATIONS_RUNBOOK.md` | Documented; infrastructure validation pending |
| Retire | Recruitment closure, export, retention, deletion, account/secret removal | `docs/OPERATIONS_RUNBOOK.md`, IRB retention fields | Pending IRB retention decision |

## Change workflow

1. Open a scoped branch and describe the requirement or defect.
2. Assess effects on participant wording, data collection, randomization, IRB documents, security, accessibility, and operations.
3. Update code and every affected document in the same change.
4. Run automated checks and record any manual verification required by `docs/TEST_PLAN.md`.
5. Obtain technical review. Obtain PI/IRB review when participant experience, collected information, consent, debrief, withdrawal, recruitment, or analysis logic changes.
6. Obtain University control-owner approval when hosting, network, authentication, logging, backup, retention, or data-access controls change.
7. Merge only after required checks and approvals pass. Tag or otherwise record the exact deployed commit.
8. Deploy using `docs/OPERATIONS_RUNBOOK.md`, complete smoke tests, and retain release evidence.
9. Roll back or suspend recruitment if acceptance criteria fail.

## Definition of review-ready

A revision is ready for University review when the source is committed without secrets or participant data; syntax and static security checks pass; architecture, data, dependencies, threats, and unresolved decisions are documented; and reviewers can trace each hosting request to evidence.

## Definition of production-ready

Production readiness requires all review-ready criteria plus final IRB authorization, approved data classification and retention, completed PHP/MariaDB integration tests, vulnerability findings remediated or formally accepted, WCAG/accessibility review completed, administrative SSO/MFA verified, backup restoration demonstrated, monitoring and incident contacts tested, production configuration reviewed, and the faculty PI/technical owner/access list confirmed.

The repository currently meets the review-ready definition. It does not yet meet the production-ready definition.
