# Third-Party Services

## University Qualtrics

- **Purpose:** eligibility confirmation and follow-up research survey
- **Information exchanged:** random participant code, assigned condition, and permission placement in URL parameters; survey responses are collected in Qualtrics
- **Authentication:** researcher access through the University's approved Qualtrics account controls
- **Production requirement:** confirm approved tenant, licensing, data location, access list, export process, retention, deletion, and IRB language

## Prolific

- **Purpose:** participant recruitment, prescreening, compensation, duplicate handling, and study-completion routing
- **Information exchanged:** only the minimum fields approved by the IRB; any link between a Prolific identifier and the random participant code must be stored separately from task behavioral records
- **Authentication:** researcher access through the approved Prolific account
- **Production requirement:** document exact fields, lawful/institutional approval, access list, separation, compensation procedure, and destruction date

## Software libraries and external APIs

The participant application uses no generative-AI or machine-learning model, AI API, third-party JavaScript library, plugin, font CDN, analytics service, advertising service, real identity provider, payment API, email service, or file-upload service. “AI assistant” appears only as participant-facing study content. The PHP API uses only PHP core functionality and PDO MySQL. No Composer package or container image is required.

Service terms, privacy documentation, support status, and institutional approvals should be reviewed again before recruitment and whenever either external service materially changes.
