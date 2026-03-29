#!/usr/bin/env bash
# Golden test: Full output comparison — past data only, no filter
set -uo pipefail
SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
source "$SCRIPT_DIR/lib/test_framework.sh"
begin_suite

setup_test_env "snapshot_simple"

write_csv "2024-01-15T10:00:00+00:00,45,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T10:30:00+00:00,60,fail,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,30,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-16T09:00:00+00:00,120,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-16T10:00:00+00:00,90,fail,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-17T08:00:00+00:00,15,success,OtherApp.xcworkspace,OtherApp.xcodeproj,15.0,15A240"

run_bin
assert_snapshot_match "$SCRIPT_DIR/expected/snapshot_simple.txt" "full output matches snapshot (simple, past data)"

cleanup_test_env
end_suite
