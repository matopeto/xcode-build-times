#!/usr/bin/env bash
# Test: Alternate (Option key) display shows total stats instead of today's
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "alternate"

today1=$(today_ts 09 00 00)
today2=$(today_ts 10 00 00)

# Today: 2 builds (1 success 45s, 1 fail 30s) = 75s total
# Past:  2 builds (1 success 100s, 1 fail 50s) = 150s total
# Grand total: 4 builds, 2 failed, 225s
write_csv "2024-01-15T10:00:00+00:00,100,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,fail,App.xcworkspace,App.xcodeproj,15.0,15A240
${today1},45,success,App.xcworkspace,App.xcodeproj,15.0,15A240
${today2},30,fail,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin

# Header always shows today's time
assert_output_line_contains 1 "1m 15s" "header shows today build time"

# Today/Total section headers
assert_output_contains "Today" "normal section header"
assert_output_contains "Total, since: 2024-01-15| alternate=true" "alternate section header with date"

# Counts: today vs total
assert_output_contains "Builds 2, 1 failed" "today counts"
assert_output_contains "Builds 4, 2 failed| alternate=true" "alternate total counts"

# Build time: today vs total
assert_output_contains "Build time: 1m 15s" "today build time"
assert_output_contains "Build time: 3m 45s| alternate=true" "alternate total build time"

# Average: today vs total
assert_output_contains "Average build time: 37s" "today average"
assert_output_contains "Average build time: 56s| alternate=true" "alternate total average"

# Success/fail sub-menu alternates
assert_output_contains "-- Success: 45s" "today success time"
assert_output_contains "-- Success: 2m 25s| alternate=true" "alternate total success time"
assert_output_contains "-- Fail: 30s" "today fail time"
assert_output_contains "-- Fail: 1m 20s| alternate=true" "alternate total fail time"

cleanup_test_env
end_suite
