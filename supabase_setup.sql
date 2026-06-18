-- Supabase setup for the AI Authentication Study behavioral task.
-- Run this in the Supabase SQL editor for the project used by auth_task.html.

-- Store completed task payloads and event logs.
CREATE TABLE IF NOT EXISTS task_events (
  pid text,
  condition text,
  payload jsonb,
  created_at timestamptz DEFAULT now()
);

-- Store participant withdrawal/removal requests.
CREATE TABLE IF NOT EXISTS task_withdrawals (
  pid text,
  condition text,
  payload jsonb,
  created_at timestamptz DEFAULT now()
);

-- Enable Row Level Security so browser clients only get the access granted below.
ALTER TABLE task_events ENABLE ROW LEVEL SECURITY;
ALTER TABLE task_withdrawals ENABLE ROW LEVEL SECURITY;

-- Keep anon access insert-only at the database privilege layer.
REVOKE ALL ON task_events FROM anon;
REVOKE ALL ON task_withdrawals FROM anon;
GRANT INSERT ON task_events TO anon;
GRANT INSERT ON task_withdrawals TO anon;

-- Recreate policies so the script can be rerun during setup.
DROP POLICY IF EXISTS "anon_insert_task_events" ON task_events;
DROP POLICY IF EXISTS "anon_insert_task_withdrawals" ON task_withdrawals;

-- Allow anonymous browser clients to insert completed task payloads only.
CREATE POLICY "anon_insert_task_events"
ON task_events
FOR INSERT
TO anon
WITH CHECK (true);

-- Allow anonymous browser clients to insert withdrawal requests only.
CREATE POLICY "anon_insert_task_withdrawals"
ON task_withdrawals
FOR INSERT
TO anon
WITH CHECK (true);

-- Verification: confirm both tables have only the intended INSERT policies.
SELECT
  schemaname,
  tablename,
  policyname,
  permissive,
  roles,
  cmd,
  qual,
  with_check
FROM pg_policies
WHERE schemaname = 'public'
  AND tablename IN ('task_events', 'task_withdrawals')
ORDER BY tablename, policyname;
