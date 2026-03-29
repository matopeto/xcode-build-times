#!/usr/bin/env bash
# Test: Multiple in-progress builds — header sums all durations
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "multi_in_progress"

# Today has 60s of completed builds
today1=$(today_ts 09 00 00)
write_csv "${today1},60,success,,,,,"

# Two in-progress builds: ~120s and ~60s
start_ts1=$(($(date +%s) - 120))
start_ts2=$(($(date +%s) - 60))
write_start_file "build1" "$start_ts1"
write_start_file "build2" "$start_ts2"

run_bin
assert_exit_code 0 "exits successfully"

# Header should show today (60s) + in-progress (~120s + ~60s) = ~240s = ~4m
assert_output_line_matches 1 "4m" "header sums today + all in-progress (~4m)"

# Both in-progress builds should be listed
# Note: output order may vary, so just count occurrences
progress_count=$(echo "$NORMALIZED_OUTPUT" | grep -c "Build in progress:" || true)
if [ "$progress_count" -eq 2 ]; then
    pass "two in-progress builds listed"
else
    fail "two in-progress builds listed" "Expected 2, got $progress_count"
fi

cleanup_test_env
end_suite
