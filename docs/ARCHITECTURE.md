# Architecture and Data Flow

## Purpose

This application supports a short usable-security research task. It presents simulated authentication and permission screens, records behavioral interaction data under a random participant code, and transfers the participant to a University Qualtrics survey.

## Components

1. **Participant browser** - serves `auth_task.html`, CSS, JavaScript, and non-secret `study-config.js` from the approved HTTPS origin.
2. **PHP study API** - accepts validated JSON insert requests. It exposes no public read, update, delete, reporting, or administrative interface.
3. **MariaDB** - stores task-event and withdrawal-request records. The PHP account has least-privilege database grants.
4. **University Qualtrics** - stores follow-up survey responses and receives `pid`, `condition`, and `placement` as URL parameters.
5. **Prolific** - supports recruitment, screening, compensation, and completion routing. Any required Prolific linkage is handled separately under the final IRB plan.

## Data flow

```text
Prolific
   |
   v
University HTTPS task --> POST-only PHP API --> University MariaDB
   |
   v
University Qualtrics --> Prolific completion
```

The browser never receives a database credential. The API returns only success status and a generated record UUID. Task data cannot be retrieved through a browser API.

## Research records

`task_events` contains a UUID, random participant code, assigned condition, permission placement, completed/abandoned classification, JSON behavioral payload, and database creation time.

`task_withdrawals` contains a UUID, participant code, assigned condition, permission placement, participant request time, JSON request payload, and database creation time. A withdrawal row is a request for the approved study team to process; the public endpoint does not directly delete research records.

The task payload includes screen sequence and timing, button choices, simulated MFA attempts, scrolling and permission exposure, the allow/cancel decision, recall responses, browser user-agent, general display dimensions, timestamps, and completion/abandonment state.

The task does not request real names, personal email addresses, passwords, account credentials, payment details, health information, uploaded files, or University student identifiers. Survey responses may contain student-status, degree, field-of-study, optional gender, knowledge/experience, and optional open-text research data and must be governed through the approved Qualtrics/IRB plan.

## Trust boundaries

- The participant browser and all submitted fields are untrusted.
- The PHP API validates the request independently of browser validation.
- Only an exact configured origin receives CORS permission; this is defense in depth, not participant authentication.
- Rate limiting uses a daily HMAC of `REMOTE_ADDR` and never stores the raw address in the application database.
- Server/proxy access logs may independently record network information and require an approved retention and access policy.
- Researcher administration occurs outside this public application through University-managed systems protected by approved SSO/MFA.

## Availability and recovery

Completed submissions wait for an API response. Abandonment capture is best effort because browsers cannot guarantee that a request finishes during page closure. Backup, restoration, monitoring, retention, deletion, and incident response are hosting-environment responsibilities that must be finalized with University IT and documented in the IRB protocol.
