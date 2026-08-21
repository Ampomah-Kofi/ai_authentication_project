# Contributing and Change Control

This is a human-subject research application. Code correctness alone is not sufficient approval for a change.

## Before changing the application

- Use a branch; do not push an unreviewed large change directly to the default branch.
- Describe the requirement, affected files/data, security and accessibility impact, and rollback approach.
- Determine whether participant wording, consent, debrief, collected fields, randomization, withdrawal, recruitment, or analysis changes require faculty PI or IRB review.
- Determine whether hosting, authentication, network, logging, backup, retention, or access changes require University control-owner review.

## Implementation requirements

- Keep secrets and participant records out of the repository, logs, tests, screenshots, commits, and issues.
- Treat all browser input as untrusted and validate again on the server.
- Preserve insert-only public API behavior and least-privilege database access unless a separately reviewed requirement changes it.
- Avoid new runtime dependencies. Document the purpose, support status, license, maintenance owner, and security review before adding one.
- Update architecture, data dictionary, threat model, test plan, operations, checklist, survey, and IRB drafts whenever affected.
- Add comments that explain security or research rationale; avoid comments that merely restate syntax.

## Required checks

Run locally where available:

```text
node --check study-config.js
node scripts/check-inline-js.mjs
node scripts/static-security-check.mjs
git diff --check
```

GitHub CI must also pass PHP lint, API guard tests, and private-file checks. Complete the applicable manual and staging cases in `docs/TEST_PLAN.md`.

## Review and release

At least one reviewer other than the author should review security-relevant or production changes when University staffing permits. Record the deployed commit and approvals. Do not describe a release as production-ready until the exit criteria in `docs/SDLC.md` and `deployment_checklist.md` are met.

Report vulnerabilities privately to the designated technical owner and University information-security contact, not through a public issue.
