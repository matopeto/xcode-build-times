# Tests

Language-independent test suite for xcode-build-times. Works with any implementation (PHP, Swift, Go, etc.).

## Running

```bash
./tests/run_tests.sh <path_to_binary_or_script>
```

Example with PHP:
```bash
./tests/run_tests.sh sources/xcodeBuildTimes.1m.php
```

## Structure

```
tests/
  run_tests.sh              # Test runner
  lib/test_framework.sh     # Assertions, setup/teardown, helpers
  expected/                 # Snapshot files for full output comparison
  suites/                   # Test files grouped by feature area
    build_*                 # Build lifecycle (start, success, fail, in-progress)
    command_*               # CLI commands (reset, export, import, share, etc.)
    config_*                # Config management (set, add, toggle, defaults, timezone)
    csv_*                   # CSV parsing (old format, mixed, malformed)
    filter_*                # Workspace/project filtering
    render_*                # Menu bar rendering (stats, daily averages, warnings, etc.)
    snapshot_*              # Full output comparison against saved snapshots
```

## How it works

Each test creates an isolated temp directory, copies the binary there, sets up test data (CSV, config), runs the binary, and asserts on output or file state. No real data files are touched.

All tests use `localTimeZone: UTC` for deterministic results.

## MOCK_COMMANDS

Set `MOCK_COMMANDS=1` to replace external commands with stdout output:

| Normal | With MOCK_COMMANDS=1 |
|--------|---------------------|
| `pbcopy` receives text | `PBCOPY: text` on stdout |
| `osascript` shows alert | `ALERT: message` on stdout |
| `open path` opens Finder/URL | `OPEN: path` on stdout |

Export and import also accept an optional file path argument to skip the GUI dialog:
```bash
./binary export /tmp/dest.csv
./binary import /tmp/source.csv
```
