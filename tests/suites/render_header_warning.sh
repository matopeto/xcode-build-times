#!/usr/bin/env bash
# Test: Warning indicator (⚠️) in header line
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# With valid data — no warning
setup_test_env "header_no_warn"
write_csv "2024-01-15T10:00:00+00:00,45,success,App.xcworkspace,App.xcodeproj,15.0,15A240"
run_bin
assert_output_line_matches 1 "^0s " "header shows 0s"
# Line 1 should NOT contain warning emoji
line1=$(get_output_line 1)
if echo "$line1" | grep -qF "⚠️"; then
    fail "no warning in header with valid data" "Header: $line1"
else
    pass "no warning in header with valid data"
fi
cleanup_test_env

# With no CSV — warnings present
setup_test_env "header_warn"
run_bin
line1=$(get_output_line 1)
if echo "$line1" | grep -qF "⚠️"; then
    pass "warning in header when data is missing"
else
    fail "warning in header when data is missing" "Header: $line1"
fi
cleanup_test_env

end_suite
