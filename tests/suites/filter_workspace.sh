#!/usr/bin/env bash
# Test: Workspace filter
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "filter_workspace"

write_config '{"localTimeZone":"UTC","selectedWorkspaces":["MyApp.xcworkspace"],"selectedProjects":[]}'

write_csv "2024-01-15T10:00:00+00:00,100,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,success,Other.xcworkspace,Other.xcodeproj,15.0,15A240
2024-01-16T10:00:00+00:00,30,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
# Only MyApp rows counted: 2 builds, 130s total
assert_output_contains "Builds 2, 0 failed" "filtered build count (only MyApp)"
assert_output_contains "Build time: 2m 10s" "filtered build time (130s)"
assert_output_contains "MyApp.xcworkspace" "filter name in output"

cleanup_test_env
end_suite
