#!/usr/bin/env bash
# Test: Menu structure ordering and required sections
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "menu_structure"

today1=$(today_ts 09 00 00)

write_csv "2024-01-15T10:00:00+00:00,45,success,App.xcworkspace,App.xcodeproj,15.0,15A240
2024-01-15T11:00:00+00:00,30,fail,App.xcworkspace,App.xcodeproj,15.0,15A240
${today1},60,success,App.xcworkspace,App.xcodeproj,15.0,15A240"

run_bin

# Check that key sections appear in the correct order
# Find line numbers for key markers
line_header=$(echo "$NORMALIZED_OUTPUT" | grep -n "templateImage=" | head -1 | cut -d: -f1)
line_today=$(echo "$NORMALIZED_OUTPUT" | grep -n "^Today" | head -1 | cut -d: -f1)
line_last_build=$(echo "$NORMALIZED_OUTPUT" | grep -n "^Last build:" | head -1 | cut -d: -f1)
line_refresh=$(echo "$NORMALIZED_OUTPUT" | grep -n "^Refresh" | head -1 | cut -d: -f1)
line_share=$(echo "$NORMALIZED_OUTPUT" | grep -n "^Share" | head -1 | cut -d: -f1)
line_settings=$(echo "$NORMALIZED_OUTPUT" | grep -n "^Settings" | head -1 | cut -d: -f1)
line_filter=$(echo "$NORMALIZED_OUTPUT" | grep -n "Filter" | head -1 | cut -d: -f1)
line_about=$(echo "$NORMALIZED_OUTPUT" | grep -n "^About" | head -1 | cut -d: -f1)

# Verify ordering
if [ "$line_header" -lt "$line_today" ] && \
   [ "$line_today" -lt "$line_last_build" ] && \
   [ "$line_last_build" -lt "$line_refresh" ] && \
   [ "$line_refresh" -lt "$line_share" ] && \
   [ "$line_share" -lt "$line_settings" ] && \
   [ "$line_settings" -lt "$line_filter" ] && \
   [ "$line_filter" -lt "$line_about" ]; then
    pass "menu sections in correct order"
else
    fail "menu sections in correct order" "header=$line_header today=$line_today last=$line_last_build refresh=$line_refresh share=$line_share settings=$line_settings filter=$line_filter about=$line_about"
fi

# Line 1 is header, line 2 is ---
assert_output_line_contains 2 "---" "separator after header"

# Check required sections exist
assert_output_contains "Daily average" "daily average section present"
assert_output_contains "Data" "data submenu present"
assert_output_contains "Export" "export option present"
assert_output_contains "Import" "import option present"
assert_output_contains "Open Data Location" "open data location present"
assert_output_contains "Reset" "reset option present"
assert_output_contains "Really?" "confirmation prompt present"
assert_output_contains "Source Code & Info" "source code link present"
assert_output_contains "Icon by Icons8" "icon attribution present"
assert_output_contains "Update" "update option present"

cleanup_test_env
end_suite
