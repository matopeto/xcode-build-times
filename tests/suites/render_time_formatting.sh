#!/usr/bin/env bash
# Test: Time formatting (seconds, minutes, hours, days)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# Test seconds (<60)
setup_test_env "time_fmt_seconds"
write_csv "$(today_ts 10 00 00),5,success,,,,"
run_bin
assert_output_line_contains 1 "5s" "formats 5 seconds as 5s"
cleanup_test_env

# Test minutes (<3600)
setup_test_env "time_fmt_minutes"
write_csv "$(today_ts 10 00 00),150,success,,,,"
run_bin
assert_output_line_contains 1 "2m 30s" "formats 150 seconds as 2m 30s"
cleanup_test_env

# Test hours (<86400)
setup_test_env "time_fmt_hours"
write_csv "$(today_ts 10 00 00),6300,success,,,,"
run_bin
assert_output_line_contains 1 "1h 45m" "formats 6300 seconds as 1h 45m"
cleanup_test_env

# Test days (>=86400)
setup_test_env "time_fmt_days"
write_csv "$(today_ts 10 00 00),183600,success,,,,"
run_bin
assert_output_line_contains 1 "2d 3h" "formats 183600 seconds as 2d 3h"
cleanup_test_env

end_suite
