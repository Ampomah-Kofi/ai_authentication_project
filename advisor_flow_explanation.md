# AI Authentication Study: Behavioral Task Flow

Behavioral task for a usable security and privacy study on authentication and
account permissions in the AI era (UA SPECTRAL Lab, University of Alabama).

## Design

2x2 between-subjects. Each page load randomly assigns one cell:

| Factor | Levels | Manipulates |
|---|---|---|
| Chain | Fresh (consent screen shown cold) vs. Chained (sign-in choice, provider handoff, MFA verification, then consent) | Cascade attention decay (RQ3) |
| Placement | Planted permission rendered first (top) vs. last (bottom) in the list | Position effects on noticing and recall (RQ2) |

The permission set is identical across arms; only the position of the planted
row varies. The scrollbox height is constant, so scroll demand is held equal.

## Flow

1. **Research consent page.** Participant reads study information and agrees to begin.
2. **Authentication chain** (chained arm only). Sign-in method choice, provider
   handoff, and a number-matching MFA prompt. Realistic back-navigation
   ("Use another account," "Deny") returns to the sign-in screen and is logged.
3. **AI consent screen** (all arms). TaskFlow AI Assistant requests six
   permissions. The planted over-broad item is access to saved payment methods
   to make purchases on the participant's behalf. Participant allows or cancels.
4. **Recall check.** All six real permissions plus two foils (post to social
   media; permanently delete emails), shuffled. Also includes an assistant-name
   attention check and a perceived-breadth question.
5. **Debrief and handoff.** Debrief text discloses the simulated screens, the
   reason for delayed disclosure, and the planted permission, then
   a random participant code carries into Qualtrics via URL parameters
   (pid, condition, placement), with a data-withdrawal option.

## Data captured

- Participant code, condition, placement, and cell assignment
- Selected sign-in method and full sequence of chain clicks, including
  back-navigation loops and MFA attempts (selected code, match outcome)
- Time on each screen and screen visit sequence
- Consent screen: time on screen, whether the list was scrolled, maximum
  scroll depth, whether the planted row was visible before any scrolling,
  whether and when it entered the viewport
- Decision (allow or cancel), decision latency, and combined decision
  scenarios (decision x scrolled x planted seen)
- Recall: per-item hits across the six real permissions, planted-payment
  recall, foil false alarms, assistant-name attention check, felt-breadth
  response
- Withdrawal requests and mid-task abandonment where feasible

No real credentials or account information are collected. The participant code
joins task records to survey responses.

The pre-task information and task screens do not label the experience as a
simulation. Participants are instructed not to enter personal credentials and
receive the full simulation disclosure immediately after the recall questions,
before continuing to Qualtrics. This delayed disclosure is contingent on the
final IRB-approved protocol and consent/debrief language.
