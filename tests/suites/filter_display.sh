#!/usr/bin/env bash
# Test: Filter name displayed in section headers
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "filter_display"

write_config '{"localTimeZone":"UTC","selectedWorkspaces":["MyApp.xcworkspace"],"selectedProjects":[]}'

write_csv "2024-01-15T10:00:00+00:00,100,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,fail,Other.xcworkspace,Other.xcodeproj,15.0,15A240"

run_bin
assert_output_contains "Today (MyApp.xcworkspace)" "today header shows filter name"
assert_output_contains "Total, since: 2024-01-15 (MyApp.xcworkspace)" "total header shows filter name"

# Check filter menu shows checkmark on selected workspace
assert_output_contains "✔ MyApp.xcworkspace" "checkmark on selected workspace"
# Show all should NOT have checkmark when filter is active
assert_output_not_contains "✔ Show all" "no checkmark on Show all when filter active"

cleanup_test_env
end_suite
