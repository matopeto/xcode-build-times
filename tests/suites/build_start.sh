#!/usr/bin/env bash
# Test: start command creates buildStartTime file
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "build_start"

run_bin start
assert_exit_code 0 "start exits successfully"
assert_file_glob_count "$DATA_DIR/buildStartTime.*" 1 "exactly one start file created"

# Verify the file contains a unix timestamp (all digits)
start_file=$(ls "$DATA_DIR"/buildStartTime.* 2>/dev/null | head -1)
if [ -n "$start_file" ]; then
    content=$(cat "$start_file")
    if [[ "$content" =~ ^[0-9]+$ ]]; then
        pass "start file contains unix timestamp"
    else
        fail "start file contains unix timestamp" "Content: $content"
    fi
fi

cleanup_test_env
end_suite
