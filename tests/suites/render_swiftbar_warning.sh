#!/usr/bin/env bash
# Test: SWIFTBAR_VERSION env var triggers warning for old versions
set -uo pipefail
source "$(dirname "$0")/../lib/test_framework.sh"
begin_suite

# Test: old version shows warning
setup_test_env "swiftbar_old"
write_csv "2024-01-15T10:00:00+00:00,45,success,,,,"
run_bin_env "SWIFTBAR_VERSION=1.4.2"
assert_output_contains "Please use the latest SwiftBar" "warning shown for old SwiftBar"
assert_output_contains "⚠️" "warning indicator present"
cleanup_test_env

# Test: current version — no warning
setup_test_env "swiftbar_ok"
write_csv "2024-01-15T10:00:00+00:00,45,success,,,,"
run_bin_env "SWIFTBAR_VERSION=1.4.3"
assert_output_not_contains "Please use the latest SwiftBar" "no warning for current SwiftBar"
cleanup_test_env

# Test: newer version — no warning
setup_test_env "swiftbar_new"
write_csv "2024-01-15T10:00:00+00:00,45,success,,,,"
run_bin_env "SWIFTBAR_VERSION=2.0.0"
assert_output_not_contains "Please use the latest SwiftBar" "no warning for newer SwiftBar"
cleanup_test_env

end_suite
