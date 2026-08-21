#!/usr/bin/env bash
set -euo pipefail

port="${STUDY_TEST_PORT:-8765}"
origin="https://study.example.edu"
base="http://127.0.0.1:${port}/api/study/task-events.php"

STUDY_ALLOWED_ORIGIN="$origin" php -S "127.0.0.1:${port}" -t deployment/public >/tmp/study-php-server.log 2>&1 &
server_pid=$!
trap 'kill "$server_pid" 2>/dev/null || true' EXIT

for _ in {1..20}; do
  if curl --silent --output /dev/null "http://127.0.0.1:${port}/"; then break; fi
  sleep 0.2
done

assert_status() {
  local expected="$1"
  shift
  local actual
  actual="$(curl --silent --output /dev/null --write-out '%{http_code}' "$@")"
  if [[ "$actual" != "$expected" ]]; then
    echo "Expected HTTP ${expected}, received ${actual}: curl $*" >&2
    exit 1
  fi
}

assert_status 204 -X OPTIONS -H "Origin: ${origin}" "$base"
assert_status 405 -X GET -H "Origin: ${origin}" "$base"
assert_status 403 -X POST -H 'Origin: https://unapproved.example' -H 'Content-Type: application/json' --data '{}' "$base"
assert_status 415 -X POST -H "Origin: ${origin}" -H 'Content-Type: text/plain' --data '{}' "$base"
assert_status 400 -X POST -H "Origin: ${origin}" -H 'Content-Type: application/json' --data '{' "$base"
assert_status 422 -X POST -H "Origin: ${origin}" -H 'Content-Type: application/json' --data '{"pid":"invalid"}' "$base"

echo "API guard tests passed."
