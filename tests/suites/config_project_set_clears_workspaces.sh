#!/usr/bin/env bash
# Test: Project set mode clears workspaces (and vice versa)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# Project set clears workspaces
setup_test_env "proj_set_clears_ws"
write_config '{"selectedWorkspaces":["A.xcworkspace"],"selectedProjects":[]}'
run_bin config filter_toggle project "B.xcodeproj" set
assert_file_contains "$DATA_DIR/config.json" "B.xcodeproj" "project set in config"
assert_file_not_contains "$DATA_DIR/config.json" "A.xcworkspace" "workspaces cleared by project set"
cleanup_test_env

# Workspace set clears projects (already tested in 15, but explicit here)
setup_test_env "ws_set_clears_proj"
write_config '{"selectedWorkspaces":[],"selectedProjects":["B.xcodeproj"]}'
run_bin config filter_toggle workspace "A.xcworkspace" set
assert_file_contains "$DATA_DIR/config.json" "A.xcworkspace" "workspace set in config"
assert_file_not_contains "$DATA_DIR/config.json" "B.xcodeproj" "projects cleared by workspace set"
cleanup_test_env

end_suite
