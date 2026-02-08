# Swift Rewrite Summary

## What is done

### Full Swift rewrite (`sources/xcodeBuildTimes.1m.swift`)
- Single-file Swift script mirroring all PHP functionality
- Shares the same data files as PHP: `buildTimes.csv`, `config.json`, `buildStartTime.*`
- Compiled binary at `sources/xcodeBuildTimes.1m`

### Implemented features
- **Render** — menu bar output with all sections (today, total, daily averages, last build, in-progress, warnings, filters, settings, about)
- **Build lifecycle** — `start`, `success`, `fail` commands with MD5-hashed start time files for parallel build support
- **Config** — `config filter_toggle` for workspace/project/all with set/add modes
- **Reset** — deletes CSV data file
- **Update** — downloads from GitHub, compares SHA256 (excluding shebang), atomic replace with shebang preservation
- **Share** — copies today/total stats to clipboard via `pbcopy`, shows osascript alert
- **Configure** — no-op for Swift (no shebang needed)
- **SwiftBar version warning** — checks `SWIFTBAR_VERSION < 1.4.3`
- **CSV parsing** — supports both old (`YYYY-MM-DD HH:MM:SS`) and ISO8601 date formats
- **Timezone detection** — uses `TimeZone.current` (standard Swift API)

### PHP fix
- Fixed timezone detection bug in `getLocalTimeZone()` on macOS 15+ (Sequoia)
- `realpath(/etc/localtime)` resolves to `/usr/share/zoneinfo.default/...` which breaks the `zoneinfo/` regex
- Added `readlink()` fallback which returns `/var/db/timezone/zoneinfo/...` and works correctly

### Test script (`tests/compare_outputs.sh`)
- 20 tests comparing PHP and Swift outputs

## What is tested

| Test | Description |
|------|-------------|
| Empty data | No CSV file, render with warnings |
| Basic render | Past data, multiple workspaces/projects |
| Today data | Mix of past and today's data |
| Filter workspace | Workspace filter active |
| Filter project | Project filter active |
| Build start (PHP) | Start time file created |
| Build success (PHP) | Start file removed, CSV row appended |
| Build start (Swift) | Start time file created |
| Build success (Swift) | Start file removed, CSV row appended |
| Config filter_toggle workspace | JSON config matches after toggle |
| Config filter_toggle all | JSON config matches after select all |
| Old date format | Mixed old/new date formats in CSV |
| Time formatting | All ranges: seconds, minutes, hours, days |
| Reset | CSV file deletion |
| SwiftBar version warning | Warning for old SwiftBar versions |
| Share | Skipped (requires clipboard/GUI) |
| Malformed CSV | Invalid rows skipped, valid rows rendered |
| Daily averages | Multi-day data with correct averaging |

## What is NOT tested

- **Share command** — requires clipboard (`pbcopy`) and GUI (`osascript`) interaction
- **Update command** — requires network access to GitHub, file self-replacement
- **In-progress builds** — would need a running start time file with a recent timestamp
- **Xcode environment variables** — `IDEAlertMessage`, `XcodeWorkspace`, `XcodeProject`, `XcodeWorkspacePath`, `XcodeProjectPath`, `XcodeDeveloperDirectory`
- **Xcode version.plist parsing** — Swift uses `PropertyListSerialization`, PHP uses `simplexml_load_file`
- **Concurrent/parallel builds** — multiple start time files with different hashes
- **Config with `localTimeZone` set** — explicit timezone override in config.json
- **Very large CSV files** — performance comparison
- **CSV fields with special characters** — commas, quotes, newlines in workspace/project names

## Known differences

### JSON formatting
PHP's `json_encode(JSON_PRETTY_PRINT)` and Swift's `JSONSerialization(.prettyPrinted)` produce different whitespace:
- PHP: 4-space indent, `"key": value`
- Swift: 2-space indent, `"key" : value`

Both are valid JSON. The test normalizes via `python3 -c "import json; ..."` before comparing. This only affects `config.json` — the rendered menu bar output is identical.

### Self-referencing paths (`bash=` parameter)
PHP uses `__FILE__` (absolute path via `realpath`), Swift uses `CommandLine.arguments[0]` (as invoked). Tests normalize both to `SELF` before comparing.

### Update URL
PHP downloads from `xcodeBuildTimes.1m.php`, Swift from `xcodeBuildTimes.1m.swift` (different `Config.UPDATE_URL`).

## Potential issues

### CSV escape character
PHP uses `fgetcsv($h, 1000, ",", "\"", "")` with empty escape char (5th parameter). Swift uses a custom CSV parser that handles double-quote escaping (`""`) but not backslash escaping. This matches PHP's behavior with empty escape char, but if any external tool writes CSV with backslash escapes, Swift would parse it differently.

### Old date format timezone
Old format dates (`YYYY-MM-DD HH:MM:SS`) have no timezone info. PHP's `DateTime::createFromFormat` uses the system default timezone. Swift's `DateFormatter` also uses system default when no timezone is set. These should match, but could diverge if PHP's `date.timezone` ini setting differs from the system timezone.

### Sort stability
When multiple CSV rows have the exact same timestamp, the sort order after `allRows.sort` is not guaranteed. PHP's `usort` and Swift's `sort` may order equal elements differently. This could affect which row appears as `lastBuild`. In practice, builds recorded at the exact same second are rare.

### Integer division truncation
Both PHP's `intval()` and Swift's `/` truncate toward zero for positive numbers. For negative numbers, PHP's `intval()` also truncates toward zero, while Swift's `/` truncates toward zero as well. No difference expected, but worth noting if durations could ever be negative (they shouldn't be — negative durations are rejected).

## How to compile and test

```bash
# Compile
swiftc sources/xcodeBuildTimes.1m.swift -o sources/xcodeBuildTimes.1m -framework Foundation

# Run tests
bash tests/compare_outputs.sh

# Compare against real data
diff <(php sources/xcodeBuildTimes.1m.php 2>/dev/null) <(sources/xcodeBuildTimes.1m 2>/dev/null)
```
