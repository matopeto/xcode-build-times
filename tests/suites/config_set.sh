#!/usr/bin/env bash
# Test: config filter_toggle set mode (replaces selection)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "config_set"

write_config '{"selectedWorkspaces":["Old.xcworkspace"],"selectedProjects":["Old.xcodeproj"]}'

run_bin config filter_toggle workspace "New.xcworkspace" set
assert_exit_code 0 "config set exits successfully"
assert_file_contains "$DATA_DIR/config.json" "New.xcworkspace" "new workspace in config"
assert_file_not_contains "$DATA_DIR/config.json" "Old.xcworkspace" "old workspace replaced"
# Set mode for workspace clears projects
assert_file_not_contains "$DATA_DIR/config.json" "Old.xcodeproj" "projects cleared by workspace set"

cleanup_test_env
end_suite
