import { readFileSync } from "node:fs";

const html = readFileSync(new URL("../deployment/public/index.html", import.meta.url), "utf8");
const scripts = [...html.matchAll(/<script(?:\s[^>]*)?>([\s\S]*?)<\/script>/gi)]
  .map((match) => match[1])
  .filter((script) => script.trim() !== "");

if (scripts.length === 0) {
  throw new Error("No inline JavaScript found in deployment/public/index.html");
}

for (const script of scripts) {
  // Parse without running browser-dependent study code.
  new Function(script);
}

console.log(`Parsed ${scripts.length} inline script block(s).`);
