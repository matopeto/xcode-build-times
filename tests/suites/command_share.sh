#!/usr/bin/env bash
# Test: Share command outputs text via MOCK_COMMANDS
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# --- Test A: Share today ---
setup_test_env "share_today"

today1=$(today_ts 09 00 00)
today2=$(today_ts 10 00 00)

write_csv "2024-01-15T10:00:00+00:00,100,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,fail,App.xcworkspace,App.xcodeproj,15.0,15A240
${today1},45,success,App.xcworkspace,App.xcodeproj,15.0,15A240
${today2},30,fail,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin_env "MOCK_COMMANDS=1" share today

assert_exit_code 0 "share today exits successfully"

# Check PBCOPY lines (the share text)
assert_output_contains "PBCOPY: Xcode Build Times - Today" "share header present"
assert_output_contains "PBCOPY: Builds 2, 1 failed" "today build counts in share"
assert_output_contains "PBCOPY: Build time: 1m 15s" "today build time in share (75s)"
assert_output_contains "PBCOPY:   Success: 45s" "today success time in share"
assert_output_contains "PBCOPY:   Fail: 30s" "today fail time in share"
assert_output_contains "PBCOPY: Average build time: 37s" "today average in share"
assert_output_contains "PBCOPY: Daily average" "daily average in share"
assert_output_contains "PBCOPY: Tracked with Xcode Build Times" "footer in share"

# Check ALERT lines
assert_output_contains "ALERT: Copied to clipboard:" "alert mentions clipboard"

cleanup_test_env

# --- Test B: Share total ---
setup_test_env "share_total"

write_csv "2024-01-15T10:00:00+00:00,100,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,fail,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-16T09:00:00+00:00,30,success,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin_env "MOCK_COMMANDS=1" share total

assert_exit_code 0 "share total exits successfully"
assert_output_contains "PBCOPY: Xcode Build Times - Total, since: 2024-01-15" "total header with date"
assert_output_contains "PBCOPY: Builds 3, 1 failed" "total build counts in share"
assert_output_contains "PBCOPY: Build time: 3m 0s" "total build time in share (180s)"

cleanup_test_env

# --- Test C: Share today with filter ---
setup_test_env "share_filter"

write_config '{"localTimeZone":"UTC","selectedWorkspaces":["MyApp.xcworkspace"],"selectedProjects":[]}'

today1=$(today_ts 09 00 00)

write_csv "${today1},60,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
${today1},40,success,Other.xcworkspace,Other.xcodeproj,15.0,15A240"

run_bin_env "MOCK_COMMANDS=1" share today

assert_output_contains "PBCOPY: Xcode Build Times - Today (MyApp.xcworkspace)" "filter name in share header"
assert_output_contains "PBCOPY: Builds 1, 0 failed" "filtered counts in share"

cleanup_test_env

end_suite
