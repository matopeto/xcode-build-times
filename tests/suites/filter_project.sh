#!/usr/bin/env bash
# Test: Project filter
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "filter_project"

write_config '{"localTimeZone":"UTC","selectedWorkspaces":[],"selectedProjects":["Other.xcodeproj"]}'

write_csv "2024-01-15T10:00:00+00:00,100,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,success,Other.xcworkspace,Other.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_contains "Builds 1, 0 failed" "filtered to Other only"
assert_output_contains "Build time: 50s" "filtered build time"
assert_output_contains "Other.xcodeproj" "filter name in output"

cleanup_test_env
end_suite
