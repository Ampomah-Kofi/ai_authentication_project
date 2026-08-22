import { readFileSync, readdirSync } from "node:fs";
import { basename, join, relative } from "node:path";
import { fileURLToPath } from "node:url";

const read = (path) => readFileSync(new URL(`../${path}`, import.meta.url), "utf8");
const failures = [];

function requireMatch(name, text, pattern) {
  if (!pattern.test(text)) failures.push(`${name}: missing ${pattern}`);
}

function rejectMatch(name, text, pattern) {
  if (pattern.test(text)) failures.push(`${name}: prohibited ${pattern}`);
}

const html = read("deployment/public/index.html");
const config = read("deployment/public/study-config.js");
const bootstrap = read("deployment/src/bootstrap.php");
const events = read("deployment/public/api/study/task-events.php");
const withdrawals = read("deployment/public/api/study/task-withdrawals.php");
const envExample = read("deployment/.env.example");

const publicRoot = fileURLToPath(new URL("../deployment/public/", import.meta.url));
function listFiles(directory) {
  return readdirSync(directory, { withFileTypes: true }).flatMap((entry) => {
    const fullPath = join(directory, entry.name);
    return entry.isDirectory() ? listFiles(fullPath) : [relative(publicRoot, fullPath)];
  });
}
const publicFiles = listFiles(publicRoot);

// Browser-side safeguards and production-safe defaults.
rejectMatch("deployment/public/index.html", html, /\.innerHTML\s*=|\.outerHTML\s*=|document\.write\s*\(|\beval\s*\(/);
requireMatch("deployment/public/index.html", html, /new Uint8Array\(10\)/);
requireMatch("deployment/public/index.html", html, /crypto\.getRandomValues\(bytes\)/);
requireMatch("deployment/public/study-config.js", config, /apiBaseUrl:\s*""/);
requireMatch("deployment/public/study-config.js", config, /enableAssignmentOverrides:\s*false/);
requireMatch("deployment/public/study-config.js", config, /enableDebugLogging:\s*false/);
rejectMatch("deployment/public/study-config.js", config, /https?:\/\/(?!REPLACE-WITH-YOUR-QUALTRICS-LINK)/i);

// Server-side validation and database safeguards.
requireMatch("deployment/src/bootstrap.php", bootstrap, /REQUEST_METHOD/);
requireMatch("deployment/src/bootstrap.php", bootstrap, /STUDY_ALLOWED_ORIGIN/);
requireMatch("deployment/src/bootstrap.php", bootstrap, /STUDY_MAX_BODY_BYTES/);
requireMatch("deployment/src/bootstrap.php", bootstrap, /\^P-\[A-F0-9\]\{20\}\$/);
requireMatch("deployment/src/bootstrap.php", bootstrap, /PDO::ATTR_EMULATE_PREPARES\s*=>\s*false/);
requireMatch("deployment/src/bootstrap.php", bootstrap, /hash_hmac\('sha256'/);
requireMatch("deployment/public/api/study/task-events.php", events, /INSERT INTO task_events/);
requireMatch("deployment/public/api/study/task-withdrawals.php", withdrawals, /INSERT INTO task_withdrawals/);
requireMatch("deployment/public/api/study/task-events.php", events, /dirname\(__DIR__,\s*3\)\s*\.\s*'\/src\/bootstrap\.php'/);
requireMatch("deployment/public/api/study/task-withdrawals.php", withdrawals, /dirname\(__DIR__,\s*3\)\s*\.\s*'\/src\/bootstrap\.php'/);
rejectMatch("deployment/public/api/study/task-events.php", events, /\b(?:SELECT|UPDATE|DELETE)\b\s+(?:FROM|task_events)/i);
rejectMatch("deployment/public/api/study/task-withdrawals.php", withdrawals, /\b(?:SELECT|UPDATE|DELETE)\b\s+(?:FROM|task_withdrawals)/i);

for (const file of publicFiles) {
  if (/^(?:\.env|.*\.(?:sql|log|md|gz))$/i.test(basename(file))) {
    failures.push(`deployment/public contains prohibited file: ${file}`);
  }
}

// The example may name secrets but must contain placeholders, not plausible values.
requireMatch("deployment/.env.example", envExample, /STUDY_DB_PASSWORD=replace-with-/);
requireMatch("deployment/.env.example", envExample, /STUDY_RATE_LIMIT_SECRET=replace-with-/);
rejectMatch("deployment/.env.example", envExample, /ghp_[A-Za-z0-9]{20,}|AKIA[0-9A-Z]{16}|BEGIN (?:RSA |OPENSSH )?PRIVATE KEY/);

if (failures.length > 0) {
  for (const failure of failures) console.error(failure);
  process.exit(1);
}

console.log("Static security invariants passed.");
