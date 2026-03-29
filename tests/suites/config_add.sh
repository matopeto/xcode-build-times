#!/usr/bin/env bash
# Test: config filter_toggle add mode (toggles in selection)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "config_add"

write_config '{"selectedWorkspaces":["A.xcworkspace"],"selectedProjects":[]}'

# Add B to existing selection
run_bin config filter_toggle workspace "B.xcworkspace" add
assert_exit_code 0 "add exits successfully"
assert_file_contains "$DATA_DIR/config.json" "A.xcworkspace" "A still in config"
assert_file_contains "$DATA_DIR/config.json" "B.xcworkspace" "B added to config"

# Toggle A off (it's already selected, add mode removes it)
run_bin config filter_toggle workspace "A.xcworkspace" add
assert_file_not_contains "$DATA_DIR/config.json" "A.xcworkspace" "A toggled off"
assert_file_contains "$DATA_DIR/config.json" "B.xcworkspace" "B still in config"

cleanup_test_env
end_suite
