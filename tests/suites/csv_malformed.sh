#!/usr/bin/env bash
# Test: Malformed CSV rows are skipped with warning
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# Test: row with too few columns
setup_test_env "csv_malformed_short"
write_csv "bad,data
2024-01-15T10:00:00+00:00,30,success,App.xcworkspace,App.xcodeproj,15.0,15A240"
run_bin
assert_output_contains "problem with data" "warning for short row"
assert_output_contains "Builds 1, 0 failed" "valid row still counted"
cleanup_test_env

# Test: non-numeric duration
setup_test_env "csv_malformed_duration"
write_csv "2024-01-15T10:00:00+00:00,abc,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,30,success,App.xcworkspace,App.xcodeproj,15.0,15A240"
run_bin
assert_output_contains "problem with data" "warning for non-numeric duration"
assert_output_contains "Builds 1, 0 failed" "valid row still counted"
cleanup_test_env

# Test: invalid type (not success/fail)
setup_test_env "csv_malformed_type"
write_csv "2024-01-15T10:00:00+00:00,45,unknown,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,30,success,App.xcworkspace,App.xcodeproj,15.0,15A240"
run_bin
assert_output_contains "problem with data" "warning for invalid type"
assert_output_contains "Builds 1, 0 failed" "valid row still counted"
cleanup_test_env

end_suite
