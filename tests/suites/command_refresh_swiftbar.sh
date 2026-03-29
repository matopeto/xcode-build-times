#!/usr/bin/env bash
# Test: refreshSwiftBar outputs swiftbar:// URL via MOCK_COMMANDS
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# --- Test A: refresh after start ---
setup_test_env "refresh_start"

run_bin_env "MOCK_COMMANDS=1" start

assert_output_contains "OPEN: swiftbar://refreshplugin" "swiftbar refresh after start"

cleanup_test_env

# --- Test B: refresh after success ---
setup_test_env "refresh_success"

start_ts=$(($(date +%s) - 5))
hash=$(echo -n "" | md5 2>/dev/null || echo -n "" | md5sum | cut -d' ' -f1)
write_start_file "$hash" "$start_ts"

run_bin_env "MOCK_COMMANDS=1" success

assert_output_contains "OPEN: swiftbar://refreshplugin" "swiftbar refresh after success"

cleanup_test_env

# --- Test C: refresh after fail ---
setup_test_env "refresh_fail"

start_ts=$(($(date +%s) - 5))
hash=$(echo -n "" | md5 2>/dev/null || echo -n "" | md5sum | cut -d' ' -f1)
write_start_file "$hash" "$start_ts"

run_bin_env "MOCK_COMMANDS=1" fail

assert_output_contains "OPEN: swiftbar://refreshplugin" "swiftbar refresh after fail"

cleanup_test_env

end_suite
