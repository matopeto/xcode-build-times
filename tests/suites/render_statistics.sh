#!/usr/bin/env bash
# Test: Statistics calculation (counts, times, averages)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "statistics"

write_csv "$(today_ts 08 00 00),100,success,,,,
$(today_ts 09 00 00),50,success,,,,
$(today_ts 10 00 00),30,fail,,,,"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_contains "Builds 3, 1 failed" "build counts: 3 total, 1 failed"
assert_output_contains "Build time: 3m 0s" "total build time (180s)"
assert_output_contains "Success: 2m 30s" "success build time (150s)"
assert_output_contains "Fail: 30s" "fail build time (30s)"
assert_output_contains "Average build time: 1m 0s" "average (180/3=60s)"

cleanup_test_env
end_suite
