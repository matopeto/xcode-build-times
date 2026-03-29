#!/usr/bin/env bash
# Test: Combined workspace OR project filter
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "filter_combined"

write_config '{"localTimeZone":"UTC","selectedWorkspaces":["A.xcworkspace"],"selectedProjects":["B.xcodeproj"]}'

write_csv "2024-01-15T10:00:00+00:00,100,success,A.xcworkspace,A.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,success,B.xcworkspace,B.xcodeproj,15.0,15A240
2024-01-15T12:00:00+00:00,30,fail,C.xcworkspace,C.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
# Rows 1 (workspace=A) and 2 (project=B) match, row 3 excluded
assert_output_contains "Builds 2, 0 failed" "combined filter: 2 matching builds"
assert_output_contains "Build time: 2m 30s" "combined filter build time (150s)"
assert_output_contains "A.xcworkspace, B.xcodeproj" "both filter names in output"

cleanup_test_env
end_suite
