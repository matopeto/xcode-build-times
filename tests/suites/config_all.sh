#!/usr/bin/env bash
# Test: config filter_toggle all (clears selections)
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

setup_test_env "config_all"

write_config '{"selectedWorkspaces":["A.xcworkspace"],"selectedProjects":["B.xcodeproj"]}'

run_bin config filter_toggle all - set
assert_exit_code 0 "config all exits successfully"

# Both should be empty arrays now
# Check that neither A.xcworkspace nor B.xcodeproj are in config
assert_file_not_contains "$DATA_DIR/config.json" "A.xcworkspace" "workspace selection cleared"
assert_file_not_contains "$DATA_DIR/config.json" "B.xcodeproj" "project selection cleared"

cleanup_test_env
end_suite
