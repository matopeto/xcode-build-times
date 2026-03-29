#!/usr/bin/env bash
# Test: Old date format (YYYY-MM-DD HH:MM:SS) parsing
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "csv_old_format"

write_csv "2024-01-15 10:00:00,45,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15 11:00:00,30,fail,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_contains "Builds 2, 1 failed" "old format rows parsed"
assert_output_contains "Build time: 1m 15s" "build time correct (75s)"
assert_output_not_contains "problem with data" "no malformed data warning"

cleanup_test_env
end_suite
