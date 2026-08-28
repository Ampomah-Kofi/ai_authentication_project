/*
 * Public, non-secret deployment configuration.
 *
 * Set the API and Qualtrics URLs only after the approved HTTPS host is ready.
 * Never put database passwords, API keys, or other secrets in this
 * browser-readable file.
 */
window.STUDY_CONFIG = Object.freeze({
  surveyBaseUrl: "https://REPLACE-WITH-YOUR-QUALTRICS-LINK",
  surveyPidParam: "pid",
  apiBaseUrl: "",
  assignmentPath: "/api/study/assignment.php",
  eventsPath: "/api/study/task-events.php",
  withdrawalsPath: "/api/study/task-withdrawals.php",
  abandonmentPath: "/api/study/task-events.php",
  enableAssignmentOverrides: false,
  enableDebugLogging: false
});
