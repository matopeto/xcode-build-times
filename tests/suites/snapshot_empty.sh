#!/usr/bin/env bash
# Golden test: Full output comparison — empty data (no CSV file)
set -uo pipefail
SCRIPT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
source "$SCRIPT_DIR/lib/test_framework.sh"
begin_suite

setup_test_env "snapshot_empty"

# No CSV file
run_bin
assert_snapshot_match "$SCRIPT_DIR/expected/snapshot_empty.txt" "full output matches snapshot (empty data)"

cleanup_test_env
end_suite
