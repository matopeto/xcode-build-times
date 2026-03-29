#!/usr/bin/env bash
# Test: Names with pipes or newlines are sanitized to underscores in display
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "sanitize_name"

# CSV with pipe in workspace name
write_csv "2024-01-15T10:00:00+00:00,45,success,My|App.xcworkspace,My|App.xcodeproj,15.0,15A240"

run_bin
assert_exit_code 0 "exits successfully"
# Pipes should be replaced with underscores in display
assert_output_contains "My_App.xcworkspace" "pipe sanitized to underscore in workspace"
assert_output_contains "My_App.xcodeproj" "pipe sanitized to underscore in project"
# Note: the original name with pipe still appears in bash action params (param4="My|App...")
# but display names (menu labels) should be sanitized
# Check that the display label lines (starting with ----) use the sanitized name
sanitized_labels=$(echo "$NORMALIZED_OUTPUT" | grep "^---- " | grep -c "My_App" || true)
if [ "$sanitized_labels" -gt 0 ]; then
    pass "display labels use sanitized name"
else
    fail "display labels use sanitized name"
fi

cleanup_test_env
end_suite
