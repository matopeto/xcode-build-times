#!/usr/bin/env bash
# Language-independent test runner for xcode-build-times
# Usage: bash tests/run_tests.sh <path_to_binary_or_script>
#
# The binary/script must be a working implementation of xcode-build-times.
# Tests are fully isolated — no real data files are modified.

set -uo pipefail

if [ $# -lt 1 ]; then
    echo "Usage: $0 <binary_path>"
    echo "  e.g.: $0 sources/xcodeBuildTimes.1m.php"
    exit 1
fi

# Resolve binary to absolute path
BINARY_INPUT="$1"
if [[ "$BINARY_INPUT" != /* ]]; then
    BINARY_INPUT="$(pwd)/$BINARY_INPUT"
fi
export BINARY_PATH="$BINARY_INPUT"

if [ ! -f "$BINARY_PATH" ]; then
    echo "Error: Binary not found: $BINARY_PATH"
    exit 1
fi

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Create global temp base and ensure cleanup
export TMPDIR_BASE
TMPDIR_BASE=$(mktemp -d)
trap "rm -rf $TMPDIR_BASE" EXIT

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

TOTAL_PASS=0
TOTAL_FAIL=0
FAILED_SUITES=""

echo "========================================="
echo "xcode-build-times Test Suite"
echo "Binary: $BINARY_PATH"
echo "========================================="
echo ""

for suite in "$SCRIPT_DIR/suites/"*.sh; do
    suite_name="$(basename "$suite" .sh)"
    echo "--- $suite_name ---"

    set +e
    output=$(bash "$suite" 2>&1)
    status=$?
    set -e

    echo "$output"

    # Parse pass/fail counts from output (strip ANSI codes first)
    stripped=$(echo "$output" | sed 's/\x1b\[[0-9;]*m//g')
    suite_passes=$(echo "$stripped" | grep -c "PASS:" || true)
    suite_fails=$(echo "$stripped" | grep -c "FAIL:" || true)

    TOTAL_PASS=$((TOTAL_PASS + suite_passes))
    TOTAL_FAIL=$((TOTAL_FAIL + suite_fails))

    if [ "$suite_fails" -gt 0 ]; then
        FAILED_SUITES="${FAILED_SUITES}\n  ${suite_name}"
    fi
done

echo ""
echo "========================================="
echo -e "Results: ${GREEN}${TOTAL_PASS} passed${NC}, ${RED}${TOTAL_FAIL} failed${NC}"
if [ -n "$FAILED_SUITES" ]; then
    echo -e "Failed suites:${FAILED_SUITES}"
fi
echo "========================================="

if [ "$TOTAL_FAIL" -gt 0 ]; then
    exit 1
fi
