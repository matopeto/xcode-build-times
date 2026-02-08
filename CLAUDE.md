# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Xcode Build Times is a macOS menu bar plugin (for SwiftBar/xbar) that tracks cumulative Xcode build times. It stores build data in CSV format and renders metrics in the menu bar. There are two implementations: PHP and Swift. Both share the same data files and produce identical output.

## Running and Testing

### PHP

```bash
# Render menu bar output (normal operation)
php sources/xcodeBuildTimes.1m.php

# Simulate build lifecycle
php sources/xcodeBuildTimes.1m.php start
php sources/xcodeBuildTimes.1m.php success
php sources/xcodeBuildTimes.1m.php fail

# Other commands
php sources/xcodeBuildTimes.1m.php reset          # Clear all data
php sources/xcodeBuildTimes.1m.php configure       # Auto-detect PHP path and set shebang
php sources/xcodeBuildTimes.1m.php update          # Self-update from GitHub
php sources/xcodeBuildTimes.1m.php share [today|total]  # Copy stats to clipboard
php sources/xcodeBuildTimes.1m.php config filter_toggle [all|workspace|project] [name] [set|add]
```

### Swift

```bash
# Compile
swiftc sources/xcodeBuildTimes.1m.swift -o sources/xcodeBuildTimes.1m -framework Foundation

# All the same commands work with the compiled binary
sources/xcodeBuildTimes.1m                # Render menu bar output
sources/xcodeBuildTimes.1m start          # Mark build start
sources/xcodeBuildTimes.1m success        # Mark build success
sources/xcodeBuildTimes.1m fail           # Mark build failure
sources/xcodeBuildTimes.1m reset          # Clear all data
sources/xcodeBuildTimes.1m update         # Self-update from GitHub
sources/xcodeBuildTimes.1m share [today|total]
sources/xcodeBuildTimes.1m config filter_toggle [all|workspace|project] [name] [set|add]

# Set SwiftBar metadata on compiled binary (binary can't embed // comments)
xattr -w "com.ameba.SwiftBar" "$(cat sources/swiftbar-metadata.txt | base64)" sources/xcodeBuildTimes.1m

# Symlink into SwiftBar plugins directory
ln -s "$(pwd)/sources/xcodeBuildTimes.1m" ~/Library/Application\ Support/SwiftBar/Plugins/xcodeBuildTimes.1m
```

### Tests

```bash
# Run comparison tests (PHP vs Swift, 20 tests)
bash tests/compare_outputs.sh
```

The `1m` in the filename is an xbar/SwiftBar convention meaning "refresh every 1 minute."

## Architecture

Both PHP and Swift implementations share the same architecture and data files:

- **Entry point** dispatches based on environment variables (set by Xcode Build Behaviors) or CLI arguments
- **Build tracking**: start time is stored in a temp file keyed by MD5 hash of workspace+project path (supports parallel builds); on success/fail, duration is calculated and appended to a CSV file
- **Data storage**: `sources/.xcodeBuildTimes/buildTimes.csv` — columns: timestamp, duration, status, workspace, project, xcode_version, xcode_build
- **Config**: `sources/.xcodeBuildTimes/config.json` — workspace/project filter preferences
- **Rendering**: `BitBarRenderer` outputs in xbar/SwiftBar plugin format (pipe-separated key-value pairs)

Key classes in PHP (`xcodeBuildTimes.1m.php`):
- `Config` / `Strings` — constants
- `BuildTimesFileParser` — CSV parsing, date grouping, metric aggregation
- `BuildTimesConfig` — JSON config read/write
- `BitBarRenderer` — menu bar output formatting

The Swift version (`xcodeBuildTimes.1m.swift`) mirrors this structure with the same class/struct names.

## Key Details

- Timezone detection: PHP uses `realpath`/`readlink` on `/etc/localtime` symlink; Swift uses `TimeZone.current`
- CSV format has backward compatibility: supports both `YYYY-MM-DD HH:MM:SS` and ISO8601 date formats
- Self-update mechanism downloads from GitHub and compares file hashes (excluding shebang line)
- No external dependencies — PHP uses only builtins; Swift uses Foundation + CommonCrypto
- macOS Monterey+ requires PHP installed via Homebrew
- See `SWIFT_REWRITE.md` for detailed notes on differences, test coverage, and potential issues
