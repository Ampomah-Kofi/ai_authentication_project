# Requirements Traceability Matrix

Status values are **Implemented**, **Documented/pending validation**, or **Pending external decision**. “Implemented” describes source code, not production approval.

| ID | Requirement | Evidence | Status |
|---|---|---|---|
| APP-01 | Use the supported PHP/MariaDB hosting model | `README.md`, `api/`, `database/mariadb_schema.sql` | Implemented |
| APP-02 | Avoid containers and specialized services | `README.md`, `docs/THIRD_PARTY_SERVICES.md` | Implemented |
| APP-03 | Provide bounded participant traffic and usage estimate | `docs/UNIVERSITY_HOSTING_OVERVIEW.md` | Pending external decision: final sample cap |
| DATA-01 | Document all collected research and student-status information | `docs/DATA_DICTIONARY.md`, `docs/ARCHITECTURE.md` | Documented/pending IRB validation |
| DATA-02 | Do not collect real credentials, financial, health, or University identifier data | `auth_task.html`, `docs/DATA_DICTIONARY.md` | Implemented; requires participant-flow validation |
| DATA-03 | Keep any Prolific linkage separate and minimized | `irb_protocol_draft.md`, `docs/ARCHITECTURE.md` | Pending external decision |
| DATA-04 | Define retention, backup, and secure deletion | `deployment_checklist.md`, `docs/OPERATIONS_RUNBOOK.md` | Pending external decision |
| AUTH-01 | Explain participant authentication | `docs/UNIVERSITY_HOSTING_OVERVIEW.md` | Implemented: no participant account; simulated screens only |
| AUTH-02 | Protect administrative access with approved SSO/MFA | `SECURITY.md`, `deployment_checklist.md` | Pending University infrastructure |
| SEC-01 | Keep credentials and secrets server-side | `.env.example`, `study-config.js`, `api/study/bootstrap.php` | Implemented |
| SEC-02 | Validate untrusted input and use prepared SQL | `api/study/bootstrap.php`, endpoint files | Implemented |
| SEC-03 | Limit public API capability | Endpoint files, MariaDB grant examples | Implemented; live grants pending validation |
| SEC-04 | Encrypt in transit and at rest | `deployment_checklist.md` | Pending University infrastructure |
| SEC-05 | Apply supported versions, patching, and remediation | `README.md`, `SECURITY.md`, `docs/OPERATIONS_RUNBOOK.md` | Documented/pending validation |
| SEC-06 | Complete vulnerability testing | `docs/TEST_PLAN.md`, `deployment_checklist.md` | Pending University review |
| SEC-07 | Provide incident response, logging, and monitoring | `SECURITY.md`, `docs/OPERATIONS_RUNBOOK.md` | Documented/pending infrastructure validation |
| PRIV-01 | Provide withdrawal-request capability | `auth_task.html`, `api/study/task-withdrawals.php` | Implemented; researcher processing procedure pending |
| PRIV-02 | Prevent raw IP storage in the application database | `api/study/bootstrap.php`, schema | Implemented; web-server log policy pending |
| ACC-01 | Support keyboard, reduced motion, responsive layout, and semantic controls | `auth_task.html` | Implemented; formal evaluation pending |
| ACC-02 | Complete applicable University/WCAG accessibility review | `docs/TEST_PLAN.md`, `deployment_checklist.md` | Pending University review |
| THIRD-01 | Document external services and runtime dependencies | `docs/THIRD_PARTY_SERVICES.md` | Documented/pending institutional validation |
| THIRD-02 | Confirm project ownership/license and licenses for tools/services | `docs/THIRD_PARTY_SERVICES.md` | Pending faculty/University decision |
| OWNER-01 | Identify technical and research owners | `docs/SDLC.md`, `docs/UNIVERSITY_HOSTING_OVERVIEW.md` | Pending formal confirmation |
| OPS-01 | Document DNS, TLS, email, upload, and firewall needs | `docs/UNIVERSITY_HOSTING_OVERVIEW.md` | Documented/pending University validation |
| OPS-02 | Define deployment, rollback, backup, and retirement procedures | `docs/OPERATIONS_RUNBOOK.md` | Documented/pending infrastructure validation |
| TEST-01 | Automate syntax, secret-file, security-invariant, and API guard checks | `.github/workflows/quality.yml`, `scripts/` | Implemented; remote CI pending push |
| TEST-02 | Complete end-to-end, load, backup, accessibility, and security tests | `docs/TEST_PLAN.md` | Pending approved environment |
