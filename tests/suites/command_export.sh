#!/usr/bin/env bash
# Test: Export command copies CSV to chosen location
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# --- Test A: Successful export (path as argument) ---
setup_test_env "export_ok"

EXPORT_DEST="$TEST_DIR/exported.csv"

write_csv "2024-01-15T10:00:00+00:00,45,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,30,fail,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin_env "MOCK_COMMANDS=1" export "$EXPORT_DEST"

assert_exit_code 0 "export exits successfully"
assert_file_exists "$EXPORT_DEST" "exported file created"

# Verify content matches original
if diff -q "$DATA_DIR/buildTimes.csv" "$EXPORT_DEST" > /dev/null 2>&1; then
    pass "exported file matches original CSV"
else
    fail "exported file matches original CSV"
fi

# Alert should confirm success
assert_output_contains "ALERT: Data exported successfully" "success alert shown"

cleanup_test_env

# --- Test B: Export with no data file ---
setup_test_env "export_no_data"

run_bin_env "MOCK_COMMANDS=1" export "/tmp/doesntmatter.csv"

assert_exit_code 0 "export with no data exits successfully"
assert_output_contains "ALERT: No data file to export" "no data alert shown"

cleanup_test_env

end_suite
