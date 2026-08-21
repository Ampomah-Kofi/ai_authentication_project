# Data Dictionary

## Identifiers and classification

`pid` is a random `P-` plus 20 uppercase hexadecimal characters (80 bits). It is a study join code, not a name, University identifier, or authentication credential. Research records are coded/pseudonymous rather than guaranteed anonymous because browser metadata, open text, Qualtrics data, and a separately maintained Prolific linkage may affect identifiability.

Final data classification, retention, access, and deletion are subject to the IRB and University data-governance decision.

## MariaDB tables

### `task_events`

| Column | Type | Purpose |
|---|---|---|
| `id` | `CHAR(36)` | Server-generated UUID primary key |
| `pid` | `VARCHAR(64)` | Random task/survey join code |
| `study_condition` | enum | `A_fresh` or `B_chained` |
| `placement` | enum | Planted permission at `top` or `bottom` |
| `record_type` | enum | `completed` or `abandoned` |
| `payload` | JSON | Validated behavioral record described below |
| `created_at` | `DATETIME(3)` | Database receipt time |

### `task_withdrawals`

| Column | Type | Purpose |
|---|---|---|
| `id` | `CHAR(36)` | Server-generated UUID primary key |
| `pid` | `VARCHAR(64)` | Code locating associated research records |
| `study_condition` | enum | Assigned condition |
| `placement` | enum | Assigned placement |
| `requested_at` | `DATETIME(3)` | Browser request time normalized to UTC |
| `payload` | JSON | Request flag, code, assignment, and relative time |
| `created_at` | `DATETIME(3)` | Database receipt time |

### `study_rate_limits`

| Column | Type | Purpose/retention |
|---|---|---|
| `client_hash` | `CHAR(64)` | Daily HMAC pseudonym of direct peer address; no raw address |
| `window_bucket` | integer | Fixed rate-limit time bucket |
| `request_count` | integer | Requests observed in the bucket |
| `updated_at` | `DATETIME(3)` | Cleanup timestamp; application removes rows older than two days opportunistically |

## Behavioral JSON payload

| Category/field | Purpose |
|---|---|
| `pid`, `condition`, `placement`, `cell` | Join and experimental assignment |
| `started_at`, `consented_at`, `finished_at`, `timestamps` | Study chronology |
| `user_agent` | General browser/platform compatibility context |
| `environment` | Viewport, screen dimensions, and device-pixel ratio |
| `screen_times_ms`, `screen_visits` | Screen exposure and sequence |
| `signin_choice`, `chain_clicks` | Simulated sign-in behavior |
| `mfa` | Generated challenge, selected choice, attempts, and correctness in the simulated prompt |
| `consent` | Permission placement, visibility, scrolling, timing, allow/cancel decision, and derived scenario fields |
| `recall` | Remembered items, item hits, false alarms, planted-permission recall, assistant recognition, and broad-access response |
| `events` | Ordered interaction-event objects with type, timestamp, relative time, screen, and event-specific details |
| `abandoned`, `abandonment` | Best-effort interrupted-session state |
| `withdrawal` | Participant withdrawal-request state when selected |

The user-agent and display measurements are research data and may contribute to indirect identifiability. They must not be described as harmless merely because they are not names.

## Qualtrics information

Qualtrics receives `pid`, `condition`, and `placement`, eligibility confirmation (adult and current student), task reflection, security/permission knowledge and experience, major/field, degree level, optional gender, prior cybersecurity coursework/training, and optional open-text responses. The complete instrument is in `research/full_qualtrics_survey.md`.

## Explicitly excluded from the task

The task must not request or store real names, personal email addresses, passwords, authentication secrets, payment details, financial account data, health information, uploaded files, or University student identifiers. Displayed account and payment-related values are simulated study content.

Any Prolific identifier required for compensation or duplicate handling must be minimized and stored separately under the final approved procedure; it is not part of the current task payload.
