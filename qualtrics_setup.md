# Qualtrics Setup Instructions

1. Open the Qualtrics survey and go to **Survey Flow**.
2. At the very top of the flow, before the first block, add an **Embedded Data** element.
3. Add one embedded data field named exactly `pid`.
4. Set `pid` to capture the URL parameter of the same name. The task sends participants to the survey with this format: `?pid=P-XXXXXXXXXX`.
5. Keep this Embedded Data element above every survey block so the participant code is attached before any questions are answered.
6. Publish the survey, then paste the published survey link into `CONFIG.surveyBaseUrl` in `auth_task.html`.
7. Run one end-to-end test participant by opening the task link, completing the task, and writing down the generated code on the task completion screen.
8. Click the task's survey handoff button and confirm the browser lands on the Qualtrics URL with the same `pid` parameter attached.
9. Submit the test survey response.
10. In Qualtrics Data & Analysis, confirm the response includes the same `pid` shown on the task completion screen.
11. In Supabase, confirm the matching `pid` appears in `task_events`.
12. If the same `pid` appears in both places, the behavioral task and survey are joinable.
