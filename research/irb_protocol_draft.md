# Authentication Study IRB Protocol Draft

> Working draft. Replace bracketed items and reconcile every section with the final recruitment notice, consent page, Qualtrics instrument, hosting decision, and institutional IRB form before submission.

## Study title and personnel

**Title:** Attention and Mental Models Across Authentication and AI Authorization Flows
**Principal investigator:** [Faculty PI name and title]
**Student investigator:** Kofi Ampomah
**Institution:** The University of Alabama, Department of Computer Science / UA SPECTRAL Lab

## Purpose

This study examines how adult university students understand and respond to combined sign-in, single sign-on, multifactor authentication, and AI-assistant authorization flows. It tests whether a preceding authentication sequence and the position of an over-broad permission affect review behavior, authorization decisions, and immediate recall. A follow-up survey measures mental models, familiarity, perceived attention, revocation knowledge, and related experiences.

## Participants and recruitment

- Population: adults age 18 or older currently enrolled at a U.S. college or university.
- Recruitment: Prolific using an approved education/student prescreener; Qualtrics confirms age and current enrollment.
- Target sample: [insert power-analysis result and allowance for exclusions].
- Compensation: [amount, estimated duration, hourly-equivalent rate, partial-payment policy].
- Exclusions: minors, people not currently enrolled, duplicate participation under a prespecified rule, incomplete/unmatched records, technically invalid sessions, and quality exclusions stated in the preregistration. Experimental recall outcomes will not themselves be used as attention-check exclusions.

## Procedures

1. Participants open the university-hosted study page and review the IRB-approved information/consent language.
2. After agreement, random assignment places each participant in one cell of a 2 x 2 design: fresh versus chained authentication, and over-broad permission at the top versus bottom of the list.
3. Chained participants complete study-provided sign-in, provider-handoff, and number-matching MFA screens. Fresh participants proceed directly to the AI-assistant permission screen.
4. All participants decide whether to allow or cancel a six-item permission request. One item intentionally requests access to saved payment methods to make purchases.
5. Participants complete immediate recall and recognition questions.
6. A debrief explains that the account and permission screens were simulated, no real access was granted, disclosure was delayed to reduce bias, and one permission was intentionally over-broad. Participants may request removal of their task record.
7. Participants continue to Qualtrics using a randomly generated participant code and assigned condition parameters, then return to Prolific for completion.

No interview component is planned in the current protocol.

## Incomplete disclosure and debriefing

Before and during the interactive task, participants are not told that the account interfaces are simulated because that knowledge could materially change their attention and decisions. The consent page accurately describes the activity, data collected, foreseeable discomfort, voluntariness, and prohibition on entering personal credentials. The full simulation disclosure occurs immediately after the behavioral task and recall questions, before the survey handoff.

The IRB should determine whether this design qualifies for authorized deception/incomplete disclosure and whether participants must be offered an explicit post-debrief option to withdraw all data. The deployed wording and withdrawal workflow will follow that determination.

## Data collected

- Random participant code and randomized condition/placement.
- Screen sequence and duration, button choices, MFA attempts, scroll behavior, permission-list viewport exposure, authorization decision, recall responses, browser user-agent, and general screen/viewport dimensions.
- Qualtrics eligibility, knowledge, attitudes, experience, and demographic responses listed in the attached survey instrument.
- Prolific identifiers only to the minimum extent needed for recruitment, compensation, duplicate prevention, and task/survey matching: [describe exact fields, location, and separation].

The task instructs participants not to enter personal emails, passwords, payment details, or account credentials. The interface uses study-provided values and does not connect to a real identity provider or payment system.

## Risks and safeguards

Expected risk is no greater than minimal risk. Possible harms include mild discomfort about security/privacy decisions, concern after learning about the over-broad permission, and confidentiality risk if research records are improperly accessed. Safeguards include voluntary participation, the ability to close the page, study-provided credentials, immediate debriefing, a withdrawal-request mechanism, participant codes rather than names in task records, least-privilege researcher access, encrypted transport, university-managed storage, and a documented retention/deletion plan.

## Data flow, hosting, and access

Planned flow: Prolific -> university-hosted task/API -> university-managed database -> Qualtrics -> Prolific completion.

The browser sends validated POST requests to two narrow API routes for task events and withdrawal requests. Database credentials remain server-side. The public client has no route for reading, updating, or deleting research rows. Complete the following after the university service owner responds:

- Service and Azure subscription/tenant: [insert].
- Application/API hostname and region: [insert].
- Database service and region: [insert].
- Encryption at rest and in transit: [insert verified controls].
- Authorized research-team roles: [insert names/roles and least-privilege access].
- Logging, monitoring, backups, and incident response: [insert].
- Retention period and secure deletion method: [insert].
- Qualtrics tenant/data-location and export procedure: [insert].
- Code linking Prolific to study records, storage separation, and destruction date: [insert].

## Confidentiality and withdrawal

Task records are keyed by a random participant code. Any Prolific linkage will be stored separately with access limited to approved personnel. Publications will report aggregate results and de-identified quotations, if open-text responses are quoted. The protocol must specify the deadline through which a participant can request deletion, how the code is authenticated or verified, who processes requests, and when data become irreversibly de-identified or aggregated.

## Consent, debrief, and attachments checklist

- [ ] Recruitment message and Prolific study description.
- [ ] Pre-task information/consent page.
- [ ] Screenshots or flow diagram of all four experimental cells.
- [ ] Post-task debrief and withdrawal wording.
- [ ] Full Qualtrics instrument.
- [ ] Data dictionary and API/database architecture.
- [ ] University hosting confirmation and applicable security documentation.
- [ ] Power analysis and statistical analysis plan.
- [ ] Compensation and partial-completion policy.
- [ ] Data-retention and deletion schedule.
- [ ] Incomplete-disclosure/deception justification and waiver/alteration request, if required by the IRB.
