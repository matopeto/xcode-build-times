#!/usr/bin/env bash
# Golden test: Full output comparison — today + past, workspace filter, multiple workspaces
set -uo pipefail
SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
source "$SCRIPT_DIR/lib/test_framework.sh"
begin_suite

setup_test_env "snapshot_complex"

write_config '{"localTimeZone":"UTC","selectedWorkspaces":["MyApp.xcworkspace"],"selectedProjects":[]}'

TODAY=$(today_date)

write_csv "2024-01-15T10:00:00+00:00,100,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,50,fail,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T12:00:00+00:00,200,success,Other.xcworkspace,Other.xcodeproj,15.0,15A240
2024-01-16T09:00:00+00:00,80,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-16T10:00:00+00:00,120,fail,Other.xcworkspace,Other.xcodeproj,15.0,15A240
${TODAY}T09:00:00+00:00,45,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
${TODAY}T10:00:00+00:00,30,fail,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
${TODAY}T11:00:00+00:00,60,success,Other.xcworkspace,Other.xcodeproj,15.0,15A240"

run_bin
assert_snapshot_match "$SCRIPT_DIR/expected/snapshot_complex.txt" "full output matches snapshot (complex, filter + today)"

cleanup_test_env
end_suite
