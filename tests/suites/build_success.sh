#!/usr/bin/env bash
# Test: success command appends CSV row and removes start file
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "build_success"

# Pre-write a start file with timestamp from ~5 seconds ago
start_ts=$(($(date +%s) - 5))
# Use a known hash (md5 of empty string — no env vars set)
hash=$(echo -n "" | md5 2>/dev/null || echo -n "" | md5sum | cut -d' ' -f1)
write_start_file "$hash" "$start_ts"

run_bin_env "XcodeWorkspace=MyApp.xcworkspace XcodeProject=MyApp.xcodeproj" success
assert_exit_code 0 "success exits successfully"
assert_file_not_exists "$DATA_DIR/buildStartTime.$hash" "start file removed"
assert_file_exists "$DATA_DIR/buildTimes.csv" "CSV file created"
assert_file_line_count "$DATA_DIR/buildTimes.csv" 1 "CSV has 1 row"
assert_file_contains "$DATA_DIR/buildTimes.csv" "success" "CSV row contains success type"
assert_file_contains "$DATA_DIR/buildTimes.csv" "MyApp.xcworkspace" "CSV row contains workspace"
assert_file_contains "$DATA_DIR/buildTimes.csv" "MyApp.xcodeproj" "CSV row contains project"

cleanup_test_env
end_suite
