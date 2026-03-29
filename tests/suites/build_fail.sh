#!/usr/bin/env bash
# Test: fail command appends CSV row with fail type
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "build_fail"

start_ts=$(($(date +%s) - 5))
hash=$(echo -n "" | md5 2>/dev/null || echo -n "" | md5sum | cut -d' ' -f1)
write_start_file "$hash" "$start_ts"

run_bin_env "XcodeWorkspace=MyApp.xcworkspace XcodeProject=MyApp.xcodeproj" fail
assert_exit_code 0 "fail exits successfully"
assert_file_not_exists "$DATA_DIR/buildStartTime.$hash" "start file removed"
assert_file_exists "$DATA_DIR/buildTimes.csv" "CSV file created"
assert_file_line_count "$DATA_DIR/buildTimes.csv" 1 "CSV has 1 row"
assert_file_contains "$DATA_DIR/buildTimes.csv" "fail" "CSV row contains fail type"
assert_file_contains "$DATA_DIR/buildTimes.csv" "MyApp.xcworkspace" "CSV row contains workspace"

cleanup_test_env
end_suite
