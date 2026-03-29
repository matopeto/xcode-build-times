#!/usr/bin/env bash
# Test: Mixed old and new CSV date formats
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "csv_mixed"

write_csv "2024-01-15 10:00:00,45,success,App.xcworkspace,App.xcodeproj,14.0,14A309
2024-01-15T11:00:00+00:00,30,success,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_contains "Builds 2, 0 failed" "both formats parsed"
assert_output_contains "Build time: 1m 15s" "build time correct (75s)"
assert_output_not_contains "problem with data" "no malformed data warning"

cleanup_test_env
end_suite
