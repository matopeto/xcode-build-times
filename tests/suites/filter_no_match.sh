#!/usr/bin/env bash
# Test: Filter with no matching rows → "no builds yet"
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "filter_no_match"

write_config '{"localTimeZone":"UTC","selectedWorkspaces":["NonExistent.xcworkspace"],"selectedProjects":[]}'

write_csv "2024-01-15T10:00:00+00:00,100,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,fail,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_contains "No data" "no data warning when filter matches nothing"
assert_output_contains "Builds: no builds yet" "no builds message"
assert_output_contains "NonExistent.xcworkspace" "filter name still shown"
# The filter menu should still list all workspaces from data + the selected one
assert_output_contains "App.xcworkspace" "existing workspace in filter menu"

cleanup_test_env
end_suite
