#!/usr/bin/env bash
# Test framework for xcode-build-times
# Provides setup/teardown, assertions, and helpers for isolated testing.

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
NC='\033[0m'

# Counters (per suite)
SUITE_PASS=0
SUITE_FAIL=0

# Test state
TEST_DIR=""
DATA_DIR=""
TEST_BINARY=""
STDOUT=""
STDERR=""
EXIT_CODE=0
NORMALIZED_OUTPUT=""

# ============================================================================
# Setup / Teardown
# ============================================================================

setup_test_env() {
    local test_name="$1"
    TEST_DIR="$TMPDIR_BASE/$test_name"
    DATA_DIR="$TEST_DIR/sources/.xcodeBuildTimes"
    mkdir -p "$DATA_DIR"

    # Copy binary into test dir so path resolution finds data relative to it
    cp "$BINARY_PATH" "$TEST_DIR/sources/$(basename "$BINARY_PATH")"
    chmod +x "$TEST_DIR/sources/$(basename "$BINARY_PATH")"
    TEST_BINARY="$TEST_DIR/sources/$(basename "$BINARY_PATH")"

    # Default UTC config
    write_config '{"localTimeZone":"UTC","selectedWorkspaces":[],"selectedProjects":[]}'
}

cleanup_test_env() {
    if [ -n "$TEST_DIR" ] && [ -d "$TEST_DIR" ]; then
        rm -rf "$TEST_DIR"
    fi
    TEST_DIR=""
    DATA_DIR=""
    TEST_BINARY=""
}

# ============================================================================
# Data Helpers
# ============================================================================

write_config() {
    local json="$1"
    echo "$json" > "$DATA_DIR/config.json"
}

write_csv() {
    local content="$1"
    echo "$content" > "$DATA_DIR/buildTimes.csv"
}

write_start_file() {
    local hash="$1"
    local timestamp="$2"
    # Use printf (no trailing newline) — matches how both PHP and Swift write the file
    printf "%s" "$timestamp" > "$DATA_DIR/buildStartTime.$hash"
}

# ============================================================================
# Date Helpers (all UTC for determinism)
# ============================================================================

# Returns ISO 8601 UTC timestamp for today at given hour:minute:second
# Usage: today_ts [HH] [MM] [SS]
today_ts() {
    local hour="${1:-10}"
    local min="${2:-00}"
    local sec="${3:-00}"
    printf "%sT%s:%s:%s+00:00" "$(date -u +%Y-%m-%d)" "$hour" "$min" "$sec"
}

# Returns ISO 8601 UTC timestamp for N days ago at given hour
# Usage: days_ago_ts DAYS [HH] [MM] [SS]
days_ago_ts() {
    local days="$1"
    local hour="${2:-10}"
    local min="${3:-00}"
    local sec="${4:-00}"
    local date_str
    # macOS date
    date_str=$(date -u -v-${days}d +%Y-%m-%d 2>/dev/null) || \
    # Linux date
    date_str=$(date -u -d "$days days ago" +%Y-%m-%d 2>/dev/null)
    printf "%sT%s:%s:%s+00:00" "$date_str" "$hour" "$min" "$sec"
}

# Returns today's date in YYYY-MM-DD format (UTC)
today_date() {
    date -u +%Y-%m-%d
}

# Wait for a file to appear, polling every 200ms, up to N seconds.
# Returns immediately if file already exists. Used for async operations
# like showAlert which runs osascript in background (exec with &).
# Usage: wait_for_file "/path/to/file" TIMEOUT_SECONDS
wait_for_file() {
    local path="$1"
    local timeout="${2:-2}"
    local deadline=$(($(date +%s) + timeout))
    while [ ! -f "$path" ] && [ "$(date +%s)" -lt "$deadline" ]; do
        perl -e 'select(undef,undef,undef,0.2)' 2>/dev/null || sleep 1
    done
}

# ============================================================================
# Running the Binary
# ============================================================================

run_bin() {
    local tmp_stdout tmp_stderr
    tmp_stdout=$(mktemp)
    tmp_stderr=$(mktemp)

    set +e
    "$TEST_BINARY" "$@" > "$tmp_stdout" 2> "$tmp_stderr"
    EXIT_CODE=$?
    set -e

    STDOUT=$(cat "$tmp_stdout")
    STDERR=$(cat "$tmp_stderr")
    rm -f "$tmp_stdout" "$tmp_stderr"

    normalize_output
}

run_bin_env() {
    local env_vars="$1"
    shift

    local tmp_stdout tmp_stderr
    tmp_stdout=$(mktemp)
    tmp_stderr=$(mktemp)

    set +e
    env $env_vars "$TEST_BINARY" "$@" > "$tmp_stdout" 2> "$tmp_stderr"
    EXIT_CODE=$?
    set -e

    STDOUT=$(cat "$tmp_stdout")
    STDERR=$(cat "$tmp_stderr")
    rm -f "$tmp_stdout" "$tmp_stderr"

    normalize_output
}

normalize_output() {
    # Replace both /private prefixed and non-prefixed paths with SELF
    local private_path="/private$TEST_DIR/sources/$(basename "$BINARY_PATH")"
    local normal_path="$TEST_DIR/sources/$(basename "$BINARY_PATH")"
    NORMALIZED_OUTPUT=$(echo "$STDOUT" | \
        sed -e "s|$private_path|SELF|g" \
            -e "s|$normal_path|SELF|g")
}

# Normalize output for golden file comparison:
# - Replace script path with SELF
# - Replace base64 icon with ICON placeholder
normalize_for_golden() {
    echo "$NORMALIZED_OUTPUT" | \
        sed -E 's/templateImage=[A-Za-z0-9+/=]+/templateImage=ICON/g'
}

# ============================================================================
# Assertions
# ============================================================================

pass() {
    SUITE_PASS=$((SUITE_PASS + 1))
    echo -e "  ${GREEN}PASS${NC}: $1"
}

fail() {
    SUITE_FAIL=$((SUITE_FAIL + 1))
    echo -e "  ${RED}FAIL${NC}: $1"
    if [ -n "${2:-}" ]; then
        echo -e "    ${YELLOW}$2${NC}"
    fi
}

assert_output_contains() {
    local pattern="$1"
    local label="${2:-output contains '$pattern'}"
    if echo "$NORMALIZED_OUTPUT" | grep -qF -- "$pattern"; then
        pass "$label"
    else
        fail "$label" "Pattern not found in output"
    fi
}

assert_output_matches() {
    local regex="$1"
    local label="${2:-output matches '$regex'}"
    if echo "$NORMALIZED_OUTPUT" | grep -qE "$regex"; then
        pass "$label"
    else
        fail "$label" "Regex not matched in output"
    fi
}

assert_output_not_contains() {
    local pattern="$1"
    local label="${2:-output does not contain '$pattern'}"
    if echo "$NORMALIZED_OUTPUT" | grep -qF -- "$pattern"; then
        fail "$label" "Pattern unexpectedly found in output"
    else
        pass "$label"
    fi
}

assert_exit_code() {
    local expected="$1"
    local label="${2:-exit code is $expected}"
    if [ "$EXIT_CODE" -eq "$expected" ]; then
        pass "$label"
    else
        fail "$label" "Expected $expected, got $EXIT_CODE"
    fi
}

assert_file_exists() {
    local path="$1"
    local label="${2:-file exists: $(basename "$path")}"
    if [ -e "$path" ]; then
        pass "$label"
    else
        fail "$label" "File does not exist: $path"
    fi
}

assert_file_not_exists() {
    local path="$1"
    local label="${2:-file does not exist: $(basename "$path")}"
    if [ ! -e "$path" ]; then
        pass "$label"
    else
        fail "$label" "File unexpectedly exists: $path"
    fi
}

assert_file_contains() {
    local path="$1"
    local pattern="$2"
    local label="${3:-file $(basename "$path") contains '$pattern'}"
    if [ -f "$path" ] && grep -qF -- "$pattern" "$path"; then
        pass "$label"
    else
        fail "$label" "Pattern not found in $(basename "$path")"
    fi
}

assert_file_not_contains() {
    local path="$1"
    local pattern="$2"
    local label="${3:-file $(basename "$path") does not contain '$pattern'}"
    if [ -f "$path" ] && grep -qF -- "$pattern" "$path"; then
        fail "$label" "Pattern unexpectedly found in $(basename "$path")"
    else
        pass "$label"
    fi
}

assert_file_line_count() {
    local path="$1"
    local expected="$2"
    local label="${3:-file $(basename "$path") has $expected lines}"
    if [ -f "$path" ]; then
        local actual
        actual=$(wc -l < "$path" | tr -d ' ')
        if [ "$actual" -eq "$expected" ]; then
            pass "$label"
        else
            fail "$label" "Expected $expected lines, got $actual"
        fi
    else
        fail "$label" "File does not exist: $path"
    fi
}

assert_file_glob_count() {
    local pattern="$1"
    local expected="$2"
    local label="${3:-glob '$pattern' matches $expected files}"
    local actual
    actual=$(ls -1 $pattern 2>/dev/null | wc -l | tr -d ' ')
    if [ "$actual" -eq "$expected" ]; then
        pass "$label"
    else
        fail "$label" "Expected $expected files, got $actual"
    fi
}

# Get a specific line from normalized output (1-indexed)
get_output_line() {
    local n="$1"
    echo "$NORMALIZED_OUTPUT" | sed -n "${n}p"
}

assert_output_line_contains() {
    local n="$1"
    local pattern="$2"
    local label="${3:-line $n contains '$pattern'}"
    local line
    line=$(get_output_line "$n")
    if echo "$line" | grep -qF -- "$pattern"; then
        pass "$label"
    else
        fail "$label" "Line $n: '$line'"
    fi
}

assert_output_line_matches() {
    local n="$1"
    local regex="$2"
    local label="${3:-line $n matches '$regex'}"
    local line
    line=$(get_output_line "$n")
    if echo "$line" | grep -qE "$regex"; then
        pass "$label"
    else
        fail "$label" "Line $n: '$line'"
    fi
}

# Compare normalized output against a snapshot file.
# The snapshot file may contain TODAY_DATE placeholder which is replaced with the current UTC date.
assert_snapshot_match() {
    local snapshot_file="$1"
    local label="${2:-output matches snapshot $(basename "$snapshot_file")}"

    local expected
    expected=$(sed "s|TODAY_DATE|$(today_date)|g" "$snapshot_file")

    local actual
    actual=$(normalize_for_golden)

    # Trim trailing blank lines for comparison using awk
    expected=$(printf '%s' "$expected" | awk 'NF{p=1} p')
    actual=$(printf '%s' "$actual" | awk 'NF{p=1} p')

    if [ "$actual" = "$expected" ]; then
        pass "$label"
    else
        fail "$label" "Diff:\n$(diff <(echo "$expected") <(echo "$actual") | head -30)"
    fi
}

# ============================================================================
# Suite lifecycle
# ============================================================================

begin_suite() {
    SUITE_PASS=0
    SUITE_FAIL=0
}

end_suite() {
    if [ "$SUITE_FAIL" -gt 0 ]; then
        echo -e "  ${GREEN}${SUITE_PASS} passed${NC}, ${RED}${SUITE_FAIL} failed${NC}"
        return 1
    else
        echo -e "  ${GREEN}${SUITE_PASS} passed${NC}, ${RED}${SUITE_FAIL} failed${NC}"
        return 0
    fi
}
