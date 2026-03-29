#!/usr/bin/env bash
# Test: Render with no CSV file (empty data)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "render_empty"

# No CSV file — only config exists
run_bin
assert_exit_code 0 "exits successfully with no data"
assert_output_line_contains 1 "0s" "header shows 0s"
assert_output_contains "Unable to read data file" "warning about missing data file"
assert_output_line_contains 1 "⚠️" "warning indicator in header"
assert_output_contains "Today" "today section present"
assert_output_contains "Builds: no builds yet" "no builds message"
assert_output_contains "Refresh" "refresh row present"
assert_output_contains "Settings" "settings section present"
assert_output_contains "About" "about section present"

cleanup_test_env
end_suite
