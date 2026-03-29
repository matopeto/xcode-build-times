#!/usr/bin/env bash
# Test: When all data is from today, no Daily average section should appear
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "today_only"

today1=$(today_ts 09 00 00)
today2=$(today_ts 10 00 00)

write_csv "${today1},100,success,,,,
${today2},50,fail,,,,"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_contains "Builds 2, 1 failed" "today counts correct"
assert_output_not_contains "Daily average" "no daily average section when only today data"

cleanup_test_env
end_suite
