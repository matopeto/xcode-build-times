#!/usr/bin/env bash
# Test: In-progress builds shown in render
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# --- Test A: in-progress only (no today data) ---
setup_test_env "in_progress_only"

start_ts=$(($(date +%s) - 120))
write_start_file "abc123" "$start_ts"

# Only past data — no today builds
write_csv "2024-01-15T10:00:00+00:00,45,success,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_matches "Build in progress: [12]m" "shows in-progress build duration (~2m)"
assert_output_line_matches 1 "[12]m" "header shows in-progress time when no today data"

cleanup_test_env

# --- Test B: today data + in-progress combined in header ---
setup_test_env "in_progress_plus_today"

# Today has 60s of completed builds
today1=$(today_ts 09 00 00)
write_csv "2024-01-15T10:00:00+00:00,45,success,,,,,
${today1},60,success,,,,,"

# In-progress build from ~120 seconds ago
start_ts=$(($(date +%s) - 120))
write_start_file "def456" "$start_ts"

run_bin
assert_exit_code 0 "exits successfully with today + in-progress"

# Header should show today (60s) + in-progress (~120s) = ~180s = ~3m
assert_output_line_matches 1 "3m" "header shows today + in-progress combined (~3m)"
assert_output_matches "Build in progress: [12]m" "in-progress section shows ~2m"
assert_output_contains "Build time: 1m 0s" "today build time is just completed builds (60s)"

cleanup_test_env

end_suite
