#!/usr/bin/env bash
# Test: Daily average calculations (past days only, excludes today)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "daily_averages"

# Day 1 (2 days ago): bt=150, sbt=100, fbt=50, bc=2, sbc=1, fbc=1
# Day 2 (1 day ago):  bt=300, sbt=260, fbt=40, bc=3, sbc=2, fbc=1
# Totals: bt=450, sbt=360, fbt=90, bc=5, sbc=3, fbc=2
# Daily avg (2 days): avgBT=225(3m 45s), avgSBT=180(3m 0s), avgFBT=45(45s)
#                     avgBC=2, avgSBC=1, avgFBC=1

d2=$(days_ago_ts 2 10)
d2b=$(days_ago_ts 2 11)
d1=$(days_ago_ts 1 10)
d1b=$(days_ago_ts 1 11)
d1c=$(days_ago_ts 1 12)

write_csv "${d2},100,success,,,,
${d2b},50,fail,,,,
${d1},200,success,,,,
${d1b},60,success,,,,
${d1c},40,fail,,,,"

run_bin
assert_exit_code 0 "exits successfully"
assert_output_contains "Daily average: 3m 45s, 2 builds" "daily average build time and count"
assert_output_contains "Success: 3m 0s, 1 builds" "daily average success"
assert_output_contains "Fail: 45s, 1 builds" "daily average fail"

cleanup_test_env
end_suite
