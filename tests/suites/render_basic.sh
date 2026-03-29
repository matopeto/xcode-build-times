#!/usr/bin/env bash
# Test: Render with only past data (nothing today)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "render_basic"

write_csv "2024-01-15T10:00:00+00:00,45,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,30,fail,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-16T10:00:00+00:00,60,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_line_contains 1 "0s" "header shows 0s (no today data)"
assert_output_not_contains "⚠️" "no warning indicator"
assert_output_contains "Today" "today section present"
assert_output_contains "Total, since: 2024-01-15" "total section with date range"
assert_output_contains "Builds: no builds yet" "no builds today"
assert_output_contains "Builds 3, 1 failed" "total build counts"
assert_output_contains "Build time: 2m 15s" "total build time (135s)"
assert_output_contains "Last build: success, 1m 0s" "last build info"
assert_output_contains "Daily average" "daily averages section present"

cleanup_test_env
end_suite
