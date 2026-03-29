#!/usr/bin/env bash
# Test: reset command deletes CSV file
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "reset"

write_csv "2024-01-15T10:00:00+00:00,45,success,App.xcworkspace,App.xcodeproj,15.0,15A240"
assert_file_exists "$DATA_DIR/buildTimes.csv" "CSV exists before reset"

run_bin reset
assert_exit_code 0 "reset exits successfully"
assert_file_not_exists "$DATA_DIR/buildTimes.csv" "CSV file deleted after reset"

cleanup_test_env
end_suite
