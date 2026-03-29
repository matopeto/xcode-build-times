#!/usr/bin/env bash
# Test: Timezone from config affects date grouping
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "config_timezone"

# UTC 04:30 Jan 15 = Jan 14 23:30 in New York (EST = UTC-5)
write_config '{"localTimeZone":"America/New_York","selectedWorkspaces":[],"selectedProjects":[]}'

write_csv "2024-01-15T04:30:00+00:00,60,success,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
# The row should be grouped under Jan 14 in New York timezone
assert_output_contains "Total, since: 2024-01-14" "date grouped by New York timezone (Jan 14, not Jan 15)"

cleanup_test_env
end_suite
