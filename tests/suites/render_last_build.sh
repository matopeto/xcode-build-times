#!/usr/bin/env bash
# Test: Last build info display (type and duration from last CSV row)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# Last build is fail
setup_test_env "last_build_fail"
write_csv "2024-01-15T10:00:00+00:00,45,success,,,,,
2024-01-15T11:00:00+00:00,120,fail,,,,,"
run_bin
assert_output_contains "Last build: fail, 2m 0s" "last build shows fail with correct duration"
cleanup_test_env

# Last build is success
setup_test_env "last_build_success"
write_csv "2024-01-15T10:00:00+00:00,120,fail,,,,,
2024-01-15T11:00:00+00:00,45,success,,,,,"
run_bin
assert_output_contains "Last build: success, 45s" "last build shows success with correct duration"
cleanup_test_env

end_suite
