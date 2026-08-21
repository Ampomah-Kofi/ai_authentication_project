import { readFileSync } from "node:fs";

const read = (path) => readFileSync(new URL(`../${path}`, import.meta.url), "utf8");
const failures = [];

function requireMatch(name, text, pattern) {
  if (!pattern.test(text)) failures.push(`${name}: missing ${pattern}`);
}

function rejectMatch(name, text, pattern) {
  if (pattern.test(text)) failures.push(`${name}: prohibited ${pattern}`);
}

const html = read("auth_task.html");
const config = read("study-config.js");
const bootstrap = read("api/study/bootstrap.php");
const events = read("api/study/task-events.php");
const withdrawals = read("api/study/task-withdrawals.php");
const envExample = read(".env.example");

// Browser-side safeguards and production-safe defaults.
rejectMatch("auth_task.html", html, /\.innerHTML\s*=|\.outerHTML\s*=|document\.write\s*\(|\beval\s*\(/);
requireMatch("auth_task.html", html, /new Uint8Array\(10\)/);
requireMatch("auth_task.html", html, /crypto\.getRandomValues\(bytes\)/);
requireMatch("study-config.js", config, /apiBaseUrl:\s*""/);
requireMatch("study-config.js", config, /enableAssignmentOverrides:\s*false/);
requireMatch("study-config.js", config, /enableDebugLogging:\s*false/);
rejectMatch("study-config.js", config, /https?:\/\/(?!REPLACE-WITH-YOUR-QUALTRICS-LINK)/i);

// Server-side validation and database safeguards.
requireMatch("bootstrap.php", bootstrap, /REQUEST_METHOD/);
requireMatch("bootstrap.php", bootstrap, /STUDY_ALLOWED_ORIGIN/);
requireMatch("bootstrap.php", bootstrap, /STUDY_MAX_BODY_BYTES/);
requireMatch("bootstrap.php", bootstrap, /\^P-\[A-F0-9\]\{20\}\$/);
requireMatch("bootstrap.php", bootstrap, /PDO::ATTR_EMULATE_PREPARES\s*=>\s*false/);
requireMatch("bootstrap.php", bootstrap, /hash_hmac\('sha256'/);
requireMatch("task-events.php", events, /INSERT INTO task_events/);
requireMatch("task-withdrawals.php", withdrawals, /INSERT INTO task_withdrawals/);
rejectMatch("task-events.php", events, /\b(?:SELECT|UPDATE|DELETE)\b\s+(?:FROM|task_events)/i);
rejectMatch("task-withdrawals.php", withdrawals, /\b(?:SELECT|UPDATE|DELETE)\b\s+(?:FROM|task_withdrawals)/i);

// The example may name secrets but must contain placeholders, not plausible values.
requireMatch(".env.example", envExample, /STUDY_DB_PASSWORD=replace-with-/);
requireMatch(".env.example", envExample, /STUDY_RATE_LIMIT_SECRET=replace-with-/);
rejectMatch(".env.example", envExample, /ghp_[A-Za-z0-9]{20,}|AKIA[0-9A-Z]{16}|BEGIN (?:RSA |OPENSSH )?PRIVATE KEY/);

if (failures.length > 0) {
  for (const failure of failures) console.error(failure);
  process.exit(1);
}

console.log("Static security invariants passed.");
