#!/usr/bin/env bash
# Test: openDataLocation outputs data directory path via MOCK_COMMANDS
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "open_data_location"

run_bin_env "MOCK_COMMANDS=1" openDataLocation

assert_exit_code 0 "openDataLocation exits successfully"
assert_output_contains "OPEN:" "OPEN command output present"

# Verify the path points to the data directory
assert_output_contains ".xcodeBuildTimes" "path contains data directory name"

cleanup_test_env
end_suite
