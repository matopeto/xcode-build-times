#!/usr/bin/env bash
# Test: Import command replaces CSV from file path argument
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# --- Test A: Successful import ---
setup_test_env "import_ok"

write_csv "2024-01-15T10:00:00+00:00,45,success,Old.xcworkspace,,,"

IMPORT_SRC="$TEST_DIR/import_data.csv"
cat > "$IMPORT_SRC" << 'EOF'
2024-02-20T10:00:00+00:00,100,success,New.xcworkspace,New.xcodeproj,15.0,15A240
2024-02-20T11:00:00+00:00,60,fail,New.xcworkspace,New.xcodeproj,15.0,15A240
EOF

run_bin_env "MOCK_COMMANDS=1" import "$IMPORT_SRC"

assert_exit_code 0 "import exits successfully"
assert_file_contains "$DATA_DIR/buildTimes.csv" "New.xcworkspace" "imported data present"
assert_file_not_contains "$DATA_DIR/buildTimes.csv" "Old.xcworkspace" "old data replaced"
assert_file_line_count "$DATA_DIR/buildTimes.csv" 2 "imported CSV has 2 rows"
assert_output_contains "ALERT: Data imported successfully" "success alert shown"

cleanup_test_env

# --- Test B: Import invalid CSV ---
setup_test_env "import_invalid"

write_csv "2024-01-15T10:00:00+00:00,45,success,Keep.xcworkspace,,,"

INVALID_SRC="$TEST_DIR/invalid.csv"
cat > "$INVALID_SRC" << 'EOF'
this,is,not,valid,csv,data
more,garbage
EOF

run_bin_env "MOCK_COMMANDS=1" import "$INVALID_SRC"

assert_exit_code 0 "import invalid exits successfully"
assert_file_contains "$DATA_DIR/buildTimes.csv" "Keep.xcworkspace" "original data preserved after invalid import"
assert_output_contains "ALERT: Selected file is not a valid build times CSV" "invalid file alert shown"

cleanup_test_env

# --- Test C: Import non-existent file ---
setup_test_env "import_nofile"

write_csv "2024-01-15T10:00:00+00:00,45,success,Keep.xcworkspace,,,"

run_bin_env "MOCK_COMMANDS=1" import "/tmp/nonexistent_file.csv"

assert_exit_code 0 "import nonexistent exits successfully"
assert_file_contains "$DATA_DIR/buildTimes.csv" "Keep.xcworkspace" "original data preserved"

cleanup_test_env

end_suite
