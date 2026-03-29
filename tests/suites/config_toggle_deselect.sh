#!/usr/bin/env bash
# Test: Config toggle deselect — set on already-selected item deselects it
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# Workspace: set on already-selected → deselects
setup_test_env "toggle_deselect_ws"
write_config '{"selectedWorkspaces":["A.xcworkspace"],"selectedProjects":[]}'
run_bin config filter_toggle workspace "A.xcworkspace" set
assert_file_not_contains "$DATA_DIR/config.json" "A.xcworkspace" "workspace deselected when set on already-selected"
cleanup_test_env

# Project: set on already-selected → deselects
setup_test_env "toggle_deselect_proj"
write_config '{"selectedWorkspaces":[],"selectedProjects":["B.xcodeproj"]}'
run_bin config filter_toggle project "B.xcodeproj" set
assert_file_not_contains "$DATA_DIR/config.json" "B.xcodeproj" "project deselected when set on already-selected"
cleanup_test_env

end_suite
