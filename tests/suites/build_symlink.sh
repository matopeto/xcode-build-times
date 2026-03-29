#!/usr/bin/env bash
# Test: Binary run via symlink resolves data from real location
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "symlink"

write_csv "2024-01-15T10:00:00+00:00,45,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-15T10:30:00+00:00,60,fail,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240
2024-01-16T09:00:00+00:00,30,success,MyApp.xcworkspace,MyApp.xcodeproj,15.0,15A240"

# Create a separate directory with a symlink to the binary
SYMLINK_DIR="$TMPDIR_BASE/symlink_dir"
mkdir -p "$SYMLINK_DIR"
ln -s "$TEST_BINARY" "$SYMLINK_DIR/$(basename "$TEST_BINARY")"

# Run via the symlink — should resolve and find data in original dir
tmp_stdout=$(mktemp)
tmp_stderr=$(mktemp)

set +e
"$SYMLINK_DIR/$(basename "$TEST_BINARY")" > "$tmp_stdout" 2> "$tmp_stderr"
EXIT_CODE=$?
set -e

STDOUT=$(cat "$tmp_stdout")
STDERR=$(cat "$tmp_stderr")
rm -f "$tmp_stdout" "$tmp_stderr"
normalize_output

assert_exit_code 0 "symlink binary exits successfully"
assert_output_contains "Builds 3, 1 failed" "data found via symlink resolution"
assert_output_contains "Build time: 2m 15s" "correct build time via symlink"

rm -rf "$SYMLINK_DIR"
cleanup_test_env
end_suite
