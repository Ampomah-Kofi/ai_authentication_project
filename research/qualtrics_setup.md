# Qualtrics Setup Instructions

1. Open the Qualtrics survey and go to **Survey Flow**.
2. At the very top of the flow, before the first block, add an **Embedded Data** element.
3. Add three embedded data fields named exactly `pid`, `condition`, and `placement`.
4. Set each field to capture the URL parameter of the same name. The task sends participants to the survey with this format: `?pid=P-XXXXXXXXXXXXXXXXXXXX&condition=A_fresh&placement=top`.
5. Keep this Embedded Data element above every survey block so the participant code and assigned cell are attached before any questions are answered.
6. Publish the survey, then paste the published survey link into `surveyBaseUrl` in `deployment/public/study-config.js`.
7. Run one end-to-end test participant by opening the task link, completing the task, and writing down the generated code on the task completion screen.
8. Click the task's survey handoff button and confirm the browser lands on the Qualtrics URL with the same `pid`, `condition`, and `placement` parameters attached.
9. Submit the test survey response.
10. In Qualtrics Data & Analysis, confirm the response includes the same `pid`, `condition`, and `placement` shown in the task handoff URL.
11. In the university-hosted study database, confirm the matching `pid` appears in `task_events`.
12. If the same `pid` appears in both places, with matching `condition` and `placement`, the behavioral task and survey are joinable.
