#!/usr/bin/env bash
# Test: Missing/empty/invalid config.json defaults gracefully
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

CSV_DATA="2024-01-15T10:00:00+00:00,45,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,30,fail,App.xcworkspace,App.xcodeproj,15.0,15A240"

# Test: Missing config file
setup_test_env "config_missing"
rm -f "$DATA_DIR/config.json"
write_csv "$CSV_DATA"
run_bin
assert_exit_code 0 "no crash with missing config"
assert_output_contains "Builds 2, 1 failed" "all data shown (no filter)"
cleanup_test_env

# Test: Empty config file
setup_test_env "config_empty"
echo -n "" > "$DATA_DIR/config.json"
write_csv "$CSV_DATA"
run_bin
assert_exit_code 0 "no crash with empty config"
assert_output_contains "Builds 2, 1 failed" "all data shown (empty config)"
cleanup_test_env

# Test: Invalid JSON
setup_test_env "config_invalid"
echo "{invalid json" > "$DATA_DIR/config.json"
write_csv "$CSV_DATA"
run_bin
assert_exit_code 0 "no crash with invalid JSON config"
assert_output_contains "Builds 2, 1 failed" "all data shown (invalid config)"
cleanup_test_env

end_suite
