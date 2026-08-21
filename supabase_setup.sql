-- DEPRECATED: retained only as a record of the earlier Supabase prototype.
-- Do not deploy this schema for participant data. The approved study deployment
-- must use university-managed infrastructure and the final IRB-approved controls.

-- Store completed task payloads and event logs.
CREATE TABLE IF NOT EXISTS task_events (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  pid text,
  condition text,
  placement text,
  payload jsonb,
  created_at timestamptz DEFAULT now()
);

-- Store participant withdrawal/removal requests.
CREATE TABLE IF NOT EXISTS task_withdrawals (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  pid text,
  condition text,
  placement text,
  requested_at timestamptz,
  payload jsonb,
  created_at timestamptz DEFAULT now()
);

-- Bring existing tables up to date if this script is rerun after a schema change.
ALTER TABLE task_events ADD COLUMN IF NOT EXISTS id uuid DEFAULT gen_random_uuid();
ALTER TABLE task_events ADD COLUMN IF NOT EXISTS placement text;
ALTER TABLE task_withdrawals ADD COLUMN IF NOT EXISTS id uuid DEFAULT gen_random_uuid();
ALTER TABLE task_withdrawals ADD COLUMN IF NOT EXISTS condition text;
ALTER TABLE task_withdrawals ADD COLUMN IF NOT EXISTS placement text;
ALTER TABLE task_withdrawals ADD COLUMN IF NOT EXISTS requested_at timestamptz;

UPDATE task_events SET id = gen_random_uuid() WHERE id IS NULL;
UPDATE task_withdrawals SET id = gen_random_uuid() WHERE id IS NULL;

ALTER TABLE task_events ALTER COLUMN id SET NOT NULL;
ALTER TABLE task_withdrawals ALTER COLUMN id SET NOT NULL;

DO $$
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM pg_constraint
    WHERE conname = 'task_events_pkey'
      AND conrelid = 'task_events'::regclass
  ) THEN
    ALTER TABLE task_events ADD CONSTRAINT task_events_pkey PRIMARY KEY (id);
  END IF;

  IF NOT EXISTS (
    SELECT 1 FROM pg_constraint
    WHERE conname = 'task_withdrawals_pkey'
      AND conrelid = 'task_withdrawals'::regclass
  ) THEN
    ALTER TABLE task_withdrawals ADD CONSTRAINT task_withdrawals_pkey PRIMARY KEY (id);
  END IF;
END $$;

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
WITH CHECK (
  pid IS NOT NULL
  AND char_length(pid) BETWEEN 3 AND 64
  AND condition IN ('A_fresh', 'B_chained')
  AND placement IN ('top', 'bottom')
  AND payload IS NOT NULL
  AND jsonb_typeof(payload) = 'object'
);

-- Allow anonymous browser clients to insert withdrawal requests only.
CREATE POLICY "anon_insert_task_withdrawals"
ON task_withdrawals
FOR INSERT
TO anon
WITH CHECK (
  pid IS NOT NULL
  AND char_length(pid) BETWEEN 3 AND 64
  AND condition IN ('A_fresh', 'B_chained')
  AND placement IN ('top', 'bottom')
  AND requested_at IS NOT NULL
  AND payload IS NOT NULL
  AND jsonb_typeof(payload) = 'object'
);

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
