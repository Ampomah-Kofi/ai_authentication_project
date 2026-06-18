# Deployment Checklist

## Configuration

- [ ] Set `CONFIG.supabaseUrl` to the production Supabase project URL.
- [ ] Set `CONFIG.supabaseAnonKey` to the production Supabase anon key.
- [ ] Set `CONFIG.surveyBaseUrl` to the published Qualtrics survey link.

## Supabase

- [ ] Run `supabase_setup.sql` in the Supabase SQL editor.
- [ ] Confirm `task_events` and `task_withdrawals` exist.
- [ ] Confirm RLS is enabled on both tables.
- [ ] Confirm anon INSERT policies appear in the verification query.
- [ ] Verify anon INSERT works with a `curl` test.
- [ ] Verify anon SELECT is blocked: `curl "$SUPABASE_URL/rest/v1/task_events?select=*" -H "apikey: $SUPABASE_ANON_KEY" -H "Authorization: Bearer $SUPABASE_ANON_KEY"` should return a permission-denied response.
- [ ] Verify anon UPDATE and DELETE are blocked.

## Qualtrics

- [ ] Add embedded data field `pid` at the top of Survey Flow.
- [ ] Confirm `pid` captures the URL parameter `?pid=P-XXXXXXXXXX`.
- [ ] Run one test participant through task and survey.
- [ ] Confirm the test `pid` joins between Supabase and Qualtrics.

## Spot Checks

- [ ] Complete one desktop walkthrough end-to-end.
- [ ] Complete one mobile walkthrough end-to-end.
- [ ] Complete one keyboard-only walkthrough end-to-end.
- [ ] Confirm demo-mode banner appears when Supabase is unconfigured.
- [ ] Confirm demo-mode banner is hidden when Supabase is configured.
