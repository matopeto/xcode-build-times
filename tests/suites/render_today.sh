#!/usr/bin/env bash
# Test: Render with today's data
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "render_today"

today1=$(today_ts 09 00 00)
today2=$(today_ts 10 00 00)

write_csv "2024-01-15T10:00:00+00:00,100,success,App.xcworkspace,App.xcodeproj,15.0,15A240
${today1},45,success,App.xcworkspace,App.xcodeproj,15.0,15A240
${today2},30,fail,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_line_contains 1 "1m 15s" "header shows today's build time (75s)"
assert_output_contains "Builds 2, 1 failed" "today's build counts"
assert_output_contains "Build time: 1m 15s" "today's build time"
assert_output_contains "Average build time: 37s" "today's average (75/2=37)"

cleanup_test_env
end_suite
