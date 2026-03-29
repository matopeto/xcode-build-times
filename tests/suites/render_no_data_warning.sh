#!/usr/bin/env bash
# Test: "No data" warning when CSV exists but has zero valid rows
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# CSV exists but all rows are invalid
setup_test_env "no_data_warn"
write_csv "bad,data
also,invalid"
run_bin
assert_exit_code 0 "exits successfully"
assert_output_contains "No data" "no data warning when CSV has no valid rows"
assert_output_contains "problem with data" "problem warning for invalid rows"
assert_output_line_contains 1 "⚠️" "warning indicator in header"
cleanup_test_env

# CSV exists but is empty
setup_test_env "no_data_empty_csv"
printf "" > "$DATA_DIR/buildTimes.csv"
run_bin
assert_exit_code 0 "exits with empty CSV"
assert_output_contains "No data" "no data warning for empty CSV"
cleanup_test_env

end_suite
