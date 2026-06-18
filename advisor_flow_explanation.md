# AI Authentication Study Flow

This behavioral task is designed for a usable security and privacy study about authentication and account permissions in the AI era.

Participants first see an informed research consent page. After they agree to begin, the page randomly assigns them to one of two conditions:

- **Group A / Fresh:** the participant goes directly to the AI permission screen.
- **Group B / Chained:** the participant completes a simulated authentication chain first: sign-in choice, provider handoff, and MFA verification. The participant can also use realistic back-navigation choices such as "Use another account" or "Deny," which return them to the sign-in choice screen.

Both groups then see the same permission screen for **TaskFlow AI Assistant**. The key behavioral stimulus is a broad permission placed below the visible fold: permission to read, send, and permanently delete emails. This lets the study compare whether participants notice over-broad access and whether attention changes after a chained authentication flow.

After the consent decision, participants complete a recall check, receive a debrief explaining the purpose of the task and the broad permission, and then receive an anonymous participant code for the Qualtrics survey.

## Data Captured

The HTML task records:

- participant ID and assigned condition
- selected sign-in method
- time spent on each screen
- sequence of authentication clicks and any back-navigation loops
- consent-screen time
- whether the permission area was scrolled
- maximum scroll depth
- whether the planted broad permission was visible
- allow or cancel decision
- recall answers and false-alarm selections
- mid-task abandonment where feasible

The participant code links the behavioral task to the Qualtrics survey without collecting real login credentials or real account information.
