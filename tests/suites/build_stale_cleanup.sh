#!/usr/bin/env bash
# Test: Stale start files (>24h) are cleaned up
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "stale_cleanup"

# Create a start file from 100000 seconds ago (>86400)
start_ts=$(($(date +%s) - 100000))
write_start_file "stale123" "$start_ts"

write_csv "2024-01-15T10:00:00+00:00,45,success,,,,"

run_bin
assert_exit_code 0 "exits successfully"
assert_file_not_exists "$DATA_DIR/buildStartTime.stale123" "stale start file deleted"
assert_output_not_contains "Build in progress" "no in-progress shown for stale build"

cleanup_test_env
end_suite
