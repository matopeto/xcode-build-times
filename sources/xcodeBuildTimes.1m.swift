#!/usr/bin/env swift

// <bitbar.title>Xcode build times</bitbar.title>
// <bitbar.version>v1.0</bitbar.version>
// <bitbar.author>Mato Peto</bitbar.author>
// <bitbar.author.github>matopeto</bitbar.author.github>
// <bitbar.desc>Shows today's and total time you spend waiting to Xcode finish building.</bitbar.desc>
// <bitbar.image>https://raw.githubusercontent.com/matopeto/xcode-build-times/master/screenshots/menubar-extended.png</bitbar.image>
// <bitbar.dependencies>swift,xcode</bitbar.dependencies>
// <bitbar.abouturl>https://github.com/matopeto/xcode-build-timese</bitbar.abouturl>
// <swiftbar.hideAbout>true</swiftbar.hideAbout>
// <swiftbar.hideRunInTerminal>true</swiftbar.hideRunInTerminal>
// <swiftbar.hideLastUpdated>true</swiftbar.hideLastUpdated>
// <swiftbar.hideDisablePlugin>true</swiftbar.hideDisablePlugin>
// <swiftbar.hideSwiftBar>true</swiftbar.hideSwiftBar>
// <swiftbar.refreshOnOpen>true</swiftbar.refreshOnOpen>

import Foundation
import CommonCrypto

// MARK: - Config

enum Config {
    static let icon = "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DEwlCw2cQ+CYleoN5kOCSNk1iorrkOzTCcXrh6/9K/wc/g/iTy7+CmVuZHN0cmVhbQplbmRvYmoKNCAwIG9iagogICAyNjY1CmVuZG9iagoyIDAgb2JqCjw8CiAgIC9FeHRHU3RhdGUgPDwKICAgICAgL2EwIDw8IC9DQSAxIC9jYSAxID4+CiAgID4+Cj4+CmVuZG9iago1IDAgb2JqCjw8IC9UeXBlIC9QYWdlCiAgIC9QYXJlbnQgMSAwIFIKICAgL01lZGlhQm94IFsgMCAwIDE3IDE3IF0KICAgL0NvbnRlbnRzIDMgMCBSCiAgIC9Hcm91cCA8PAogICAgICAvVHlwZSAvR3JvdXAKICAgICAgL1MgL1RyYW5zcGFyZW5jeQogICAgICAvSSB0cnVlCiAgICAgIC9DUyAvRGV2aWNlUkdCCiAgID4+CiAgIC9SZXNvdXJjZXMgMiAwIFIKPj4KZW5kb2JqCjEgMCBvYmoKPDwgL1R5cGUgL1BhZ2VzCiAgIC9LaWRzIFsgNSAwIFIgXQogICAvQ291bnQgMQo+PgplbmRvYmoKNiAwIG9iago8PCAvQ3JlYXRvciAoY2Fpcm8gMS4xNC44IChodHRwOi8vY2Fpcm9ncmFwaGljcy5vcmcpKQogICAvUHJvZHVjZXIgKGNhaXJvIDEuMTQuOCAoaHR0cDovL2NhaXJvZ3JhcGhpY3Mub3JnKSkKPj4KZW5kb2JqCjcgMCBvYmoKPDwgL1R5cGUgL0NhdGFsb2cKICAgL1BhZ2VzIDEgMCBSCj4+CmVuZG9iagp4cmVmCjAgOAowMDAwMDAwMDAwIDY1NTM1IGYgCjAwMDAwMDMwNjQgMDAwMDAgbiAKMDAwMDAwMjc4MCAwMDAwMCBuIAowMDAwMDAwMDE1IDAwMDAwIG4gCjAwMDAwMDI3NTcgMDAwMDAgbiAKMDAwMDAwMjg1MiAwMDAwMCBuIAowMDAwMDAzMTI5IDAwMDAwIG4gCjAwMDAwMDMyNTYgMDAwMDAgbiAKdHJhaWxlcgo8PCAvU2l6ZSA4CiAgIC9Sb290IDcgMCBSCiAgIC9JbmZvIDYgMCBSCj4+CnN0YXJ0eHJlZgozMzA4CiUlRU9GCg=="
    static let iconURL = "https://icons8.com"

    static let aboutURL = "https://github.com/matopeto/xcode-build-times"
    static let swiftBarURL = "https://github.com/swiftbar/SwiftBar"

    static let updateBaseURL = "https://raw.githubusercontent.com/matopeto/xcode-build-times/master/sources/"
    static let updateUserAgent = "matopeto/xcode-build-times"

    static let dateFormat = "yyyy-MM-dd"

    static let dataFileDir = ".xcodeBuildTimes"
    static let dataFileName = "buildTimes.csv"
    static let startTimeFileName = "buildStartTime"
    static let updateTmpFileName = ".newVersion"
    static let configFileName = "config.json"
}

// MARK: - Strings

enum Strings {
    static let warningUnableToReadDataFile = "Unable to read data file. that's ok as long as no build has been done yet"
    static let warningProblemWithDataFile = "There is some problem with data"
    static let warningNoData = "No data"
    static let warningOldSwiftBarVersion = "Please use the latest SwiftBar for proper functionality"
    static let rowWarning = "⚠️ %@"

    static let rowHeaderToday = "Today"
    static let rowHeaderTodayFilter = "Today (%@)"
    static let rowHeaderTotal = "Total"
    static let rowHeaderTotalFilter = "Total (%@)"
    static let rowHeaderTotalSince = "Total, since: %@"
    static let rowHeaderTotalSinceFilter = "Total, since: %@ (%@)"

    static let rowBuildCounts = "Builds %d, %d failed"
    static let rowBuildCountsNoBuilds = "Builds: no builds yet"
    static let rowBuildTime = "Build time: %@"
    static let rowAverageBuildTime = "Average build time: %@"

    static let rowSuccessBuildTime = "Success: %@"
    static let rowFailBuildTime = "Fail: %@"

    static let rowDailyAverageBuildTime = "Daily average: %@, %@ builds"
    static let rowDailySuccessBuildTime = "Success: %@, %@ builds"
    static let rowDailyFailBuildTime = "Fail: %@, %@ builds"

    static let rowLastBuild = "Last build: %@, %@"
    static let rowBuildInProgress = "Build in progress: %@"

    static let rowRefresh = "Refresh"
    static let rowShare = "Share"

    static let shareHeaderToday = "Xcode Build Times - Today, %@"
    static let shareHeaderTodayFilter = "Xcode Build Times - Today (%@), %@"
    static let shareHeaderTotalSince = "Xcode Build Times - Total, since: %@"
    static let shareHeaderTotalSinceFilter = "Xcode Build Times - Total, since: %@ (%@)"
    static let shareHeaderTotal = "Xcode Build Times - Total"
    static let shareHeaderTotalFilter = "Xcode Build Times - Total (%@)"
    static let shareFooter = "Tracked with Xcode Build Times - %@"

    static let rowSettings = "Settings"
    static let rowSettingsFilter = "Filter"
    static let rowSettingsFilterAll = "Show all"
    static let rowSettingsReset = "Reset"
    static let rowSettingsResetReally = "Really?"
    static let rowSettingsResetReallyYes = "Yes"

    static let rowAbout = "About"
    static let rowAboutIcon = "Icon by Icons8"
    static let rowAboutSourceCode = "Source Code & Info"
    static let rowAboutUpdate = "Update"
    static let rowAboutUpdateReally = "Really?"
    static let rowAboutUpdateReallyYes = "Yes"

    static let msgNoBuildsYet = "no builds yet"

    static let updateAlertTitle = "Xcode build times"
    static let updateAlertSuccessMessage = "Plugin was updated successfully."
    static let updateAlertFailMessage = "Unable to update."
    static let updateAlertNoUpdatesMessage = "No updates available."
}

// MARK: - BuildStatus

enum BuildStatus: String {
    case success
    case fail
}

// MARK: - Data Models

struct DataRow {
    let date: Date
    let count: Int
    let type: BuildStatus
    let workspace: String
    let project: String

    init(date: Date, count: Int, type: BuildStatus, workspace: String = "", project: String = "") {
        self.date = date
        self.count = count
        self.type = type
        self.workspace = workspace
        self.project = project
    }
}

struct BuildTimesData {
    let buildCount: Int
    let successCount: Int
    let failCount: Int
    let averageBuildTime: Int
    let averageSuccessBuildTime: Int
    let averageFailBuildTime: Int
    let buildTime: Int
    let successBuildTime: Int
    let failBuildTime: Int
    let dataFrom: Date?
    let dataTo: Date?
}

struct DailyTimesData {
    let days: Int
    let averageBuildCount: Int
    let averageSuccessCount: Int
    let averageFailCount: Int
    let averageBuildTime: Int
    let averageSuccessBuildTime: Int
    let averageFailBuildTime: Int
}

struct BuildTimesOutput {
    var todayData: BuildTimesData?
    var totalData: BuildTimesData?
    var dailyData: DailyTimesData?
    var lastBuild: DataRow?
    var inProgress: [Int] = []
    var warnings: [String] = []
    var workspaces: [String] = []
    var projects: [String] = []
    var selectedWorkspaces: [String] = []
    var selectedProjects: [String] = []
}

// MARK: - CSV

enum CSV {
    static func parseLine(_ line: String) -> [String] {
        var fields: [String] = []
        var current = ""
        var inQuotes = false
        var i = line.startIndex

        while i < line.endIndex {
            let c = line[i]
            if inQuotes {
                if c == "\"" {
                    let next = line.index(after: i)
                    if next < line.endIndex && line[next] == "\"" {
                        current.append("\"")
                        i = line.index(after: next)
                        continue
                    } else {
                        inQuotes = false
                        i = line.index(after: i)
                        continue
                    }
                } else {
                    current.append(c)
                }
            } else {
                if c == "\"" {
                    inQuotes = true
                } else if c == "," {
                    fields.append(current)
                    current = ""
                } else {
                    current.append(c)
                }
            }
            i = line.index(after: i)
        }
        fields.append(current)
        return fields
    }

    static func escapeField(_ field: String) -> String {
        if field.contains(",") || field.contains("\"") || field.contains("\n") {
            return "\"" + field.replacingOccurrences(of: "\"", with: "\"\"") + "\""
        }
        return field
    }

    static func writeLine(_ fields: [String]) -> String {
        return fields.map { escapeField($0) }.joined(separator: ",")
    }
}

// MARK: - Environment

private enum Environment {
    static subscript(key: String) -> String? {
        ProcessInfo.processInfo.environment[key]
    }
}

// MARK: - Error Handling

func exitWithError(_ message: String, showAlertMessage: String? = nil) -> Never {
    FileHandle.standardError.write(message.data(using: .utf8)!)
    if let alertMessage = showAlertMessage {
        showAlert(alertMessage)
    }
    exit(1)
}

// MARK: - DateFormatter Cache

private enum DateFormatters {
    static let oldFormat: DateFormatter = {
        let df = DateFormatter()
        df.dateFormat = "yyyy-MM-dd HH:mm:ss"
        df.locale = Locale(identifier: "en_US_POSIX")
        return df
    }()

    static let iso8601: ISO8601DateFormatter = {
        let f = ISO8601DateFormatter()
        f.formatOptions = [.withInternetDateTime]
        return f
    }()

    static let iso8601Fractional: ISO8601DateFormatter = {
        let f = ISO8601DateFormatter()
        f.formatOptions = [.withInternetDateTime, .withFractionalSeconds]
        return f
    }()

    static func dateOnly(timeZone: TimeZone?) -> DateFormatter {
        let df = DateFormatter()
        df.dateFormat = "yyyy-MM-dd"
        if let tz = timeZone { df.timeZone = tz }
        return df
    }
}

// MARK: - BuildTimesConfig

class BuildTimesConfig {
    var selectedWorkspaces: [String] = []
    var selectedProjects: [String] = []
    var localTimeZone: TimeZone?
    private var localTimeZoneAutodetect = true

    private struct ConfigData: Codable {
        var selectedWorkspaces: [String] = []
        var selectedProjects: [String] = []
        var localTimeZone: String?
    }

    init(configFile: String) {
        guard let data = try? Data(contentsOf: URL(fileURLWithPath: configFile)),
              let configData = try? JSONDecoder().decode(ConfigData.self, from: data) else {
            localTimeZone = BuildTimesConfig.getLocalTimeZone()
            return
        }

        selectedWorkspaces = configData.selectedWorkspaces
        selectedProjects = configData.selectedProjects

        if let tzString = configData.localTimeZone,
           let tz = TimeZone(identifier: tzString) {
            localTimeZone = tz
            localTimeZoneAutodetect = false
        } else {
            localTimeZone = BuildTimesConfig.getLocalTimeZone()
        }
    }

    func toggleWorkspace(_ name: String, add: Bool) {
        if let idx = selectedWorkspaces.firstIndex(of: name) {
            selectedWorkspaces.remove(at: idx)
        } else {
            if add {
                selectedWorkspaces.append(name)
            } else {
                selectedWorkspaces = [name]
                selectedProjects = []
            }
        }
    }

    func toggleProject(_ name: String, add: Bool) {
        if let idx = selectedProjects.firstIndex(of: name) {
            selectedProjects.remove(at: idx)
        } else {
            if add {
                selectedProjects.append(name)
            } else {
                selectedWorkspaces = []
                selectedProjects = [name]
            }
        }
    }

    func selectAll() {
        selectedWorkspaces = []
        selectedProjects = []
    }

    func save(to configFile: String) {
        var configData = ConfigData(
            selectedWorkspaces: selectedWorkspaces,
            selectedProjects: selectedProjects
        )

        if let tz = localTimeZone, !localTimeZoneAutodetect {
            configData.localTimeZone = tz.identifier
        }

        let encoder = JSONEncoder()
        encoder.outputFormatting = [.prettyPrinted, .sortedKeys]
        guard let jsonData = try? encoder.encode(configData) else {
            exitWithError("Unable to serialize config\n")
        }
        try? jsonData.write(to: URL(fileURLWithPath: configFile))
    }

    static func getLocalTimeZone() -> TimeZone? {
        return TimeZone.current
    }
}

// MARK: - BuildTimesFileParser

struct BuildTimesFileParser {
    private let dataFile: String
    private let startTimeFilesPattern: String
    private let config: BuildTimesConfig
    private let localTimeZone: TimeZone?

    init(dataFile: String, startTimeFilesPattern: String, config: BuildTimesConfig) {
        self.dataFile = dataFile
        self.startTimeFilesPattern = startTimeFilesPattern
        self.config = config
        self.localTimeZone = config.localTimeZone
    }

    func getOutput() -> BuildTimesOutput {
        var result = BuildTimesOutput()

        if let swiftBarVersion = Environment["SWIFTBAR_VERSION"],
           !swiftBarVersion.isEmpty {
            if compareVersions(swiftBarVersion, "1.4.3") < 0 {
                result.warnings.append(Strings.warningOldSwiftBarVersion + "| href=" + Config.swiftBarURL)
            }
        }

        guard let content = try? String(contentsOfFile: dataFile, encoding: .utf8) else {
            result.warnings.append(Strings.warningUnableToReadDataFile)
            result.selectedWorkspaces = config.selectedWorkspaces
            result.selectedProjects = config.selectedProjects

            // Check in-progress
            let files = globFiles(startTimeFilesPattern)
            for file in files {
                let duration = getProgressDuration(file)
                if duration > 0 && duration < 86400 {
                    result.inProgress.append(duration)
                }
            }
            return result
        }

        let (rows, problemWithData, parsedWorkspaces, parsedProjects) = parseFileContent(content, config: config)
        if problemWithData {
            result.warnings.append(Strings.warningProblemWithDataFile)
        }

        // Merge and deduplicate workspaces
        var allWorkspaces = Set<String>()
        for w in config.selectedWorkspaces { if !w.isEmpty { allWorkspaces.insert(w) } }
        for w in parsedWorkspaces { if !w.isEmpty { allWorkspaces.insert(w) } }
        result.workspaces = allWorkspaces.sorted()

        // Merge and deduplicate projects
        var allProjects = Set<String>()
        for p in config.selectedProjects { if !p.isEmpty { allProjects.insert(p) } }
        for p in parsedProjects { if !p.isEmpty { allProjects.insert(p) } }
        result.projects = allProjects.sorted()

        // Flatten all rows and sort by date
        var allRows: [DataRow] = []
        for (_, dayRows) in rows {
            allRows.append(contentsOf: dayRows)
        }
        allRows.sort { $0.date < $1.date }

        if allRows.isEmpty {
            result.warnings.append(Strings.warningNoData)
        } else {
            result.totalData = getData(allRows)
            result.lastBuild = allRows.last
        }

        // Today rows
        let todayKey = DateFormatters.dateOnly(timeZone: localTimeZone).string(from: Date())

        if let todayRows = rows[todayKey] {
            result.todayData = getData(todayRows)
        }

        // Daily data (exclude today)
        var days = 0
        var bt = 0, sbt = 0, fbt = 0
        var bc = 0, sbc = 0, fbc = 0

        for (key, data) in rows {
            if key >= todayKey {
                continue
            }
            let dayData = getData(data)
            days += 1
            bt += dayData.buildTime
            sbt += dayData.successBuildTime
            fbt += dayData.failBuildTime
            bc += dayData.buildCount
            sbc += dayData.successCount
            fbc += dayData.failCount
        }

        if days > 0 {
            result.dailyData = DailyTimesData(
                days: days,
                averageBuildCount: bc / days,
                averageSuccessCount: sbc / days,
                averageFailCount: fbc / days,
                averageBuildTime: bt / days,
                averageSuccessBuildTime: sbt / days,
                averageFailBuildTime: fbt / days
            )
        }

        result.selectedWorkspaces = config.selectedWorkspaces
        result.selectedProjects = config.selectedProjects

        // In-progress builds
        let files = globFiles(startTimeFilesPattern)
        for file in files {
            let duration = getProgressDuration(file)
            if duration > 0 && duration < 86400 {
                result.inProgress.append(duration)
            }
        }

        return result
    }

    private func parseFileContent(_ content: String, config: BuildTimesConfig) -> (rows: [String: [DataRow]], problemWithData: Bool, workspaces: [String], projects: [String]) {
        var workspacesDict: [String: Bool] = [:]
        var projectsDict: [String: Bool] = [:]
        var rows: [String: [DataRow]] = [:]
        var problemWithData = false
        let keyFormatter = DateFormatters.dateOnly(timeZone: localTimeZone)

        let lines = content.components(separatedBy: "\n")
        for line in lines {
            if line.isEmpty { continue }
            let row = CSV.parseLine(line)
            if row.count < 3 {
                problemWithData = true
                continue
            }

            // Check date format
            let dateStr = row[0]
            let oldFormatPattern = "^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$"
            let dateInOldFormat = dateStr.range(of: oldFormatPattern, options: .regularExpression) != nil

            // Check duration is numeric
            let durationPattern = "^[0-9]+$"
            guard row[1].range(of: durationPattern, options: .regularExpression) != nil else {
                problemWithData = true
                continue
            }

            // Check type
            guard let buildStatus = BuildStatus(rawValue: row[2]) else {
                problemWithData = true
                continue
            }

            var date: Date?

            if dateInOldFormat {
                date = DateFormatters.oldFormat.date(from: dateStr)
                if date == nil {
                    problemWithData = true
                    continue
                }
            } else {
                date = DateFormatters.iso8601.date(from: dateStr)
                if date == nil {
                    date = DateFormatters.iso8601Fractional.date(from: dateStr)
                }
                if date == nil {
                    problemWithData = true
                    continue
                }
            }

            guard let parsedDate = date else {
                problemWithData = true
                continue
            }

            let workspace = row.count >= 5 ? row[3] : ""
            let project = row.count >= 5 ? row[4] : ""
            let dataRow = DataRow(date: parsedDate, count: Int(row[1])!, type: buildStatus, workspace: workspace, project: project)

            if row.count >= 5 {
                workspacesDict[workspace] = true
                projectsDict[project] = true
            }

            let key = keyFormatter.string(from: parsedDate)

            if isSelected(dataRow, config: config) {
                if rows[key] == nil {
                    rows[key] = []
                }
                rows[key]!.append(dataRow)
            }
        }

        return (rows, problemWithData, Array(workspacesDict.keys), Array(projectsDict.keys))
    }

    private func getData(_ rows: [DataRow]) -> BuildTimesData {
        var buildTime = 0
        var buildCount = 0
        var successCount = 0
        var failCount = 0
        var failBuildTime = 0
        var successBuildTime = 0

        for row in rows {
            buildCount += 1
            if row.type == .success {
                successCount += 1
                successBuildTime += row.count
            } else {
                failCount += 1
                failBuildTime += row.count
            }
            buildTime += row.count
        }

        let averageBuildTime = buildCount == 0 ? 0 : buildTime / buildCount
        let averageFailBuildTime = failCount == 0 ? 0 : failBuildTime / failCount
        let averageSuccessBuildTime = successCount == 0 ? 0 : successBuildTime / successCount

        return BuildTimesData(
            buildCount: buildCount,
            successCount: successCount,
            failCount: failCount,
            averageBuildTime: averageBuildTime,
            averageSuccessBuildTime: averageSuccessBuildTime,
            averageFailBuildTime: averageFailBuildTime,
            buildTime: buildTime,
            successBuildTime: successBuildTime,
            failBuildTime: failBuildTime,
            dataFrom: rows.first?.date,
            dataTo: rows.last?.date
        )
    }

    private func isSelected(_ dataRow: DataRow, config: BuildTimesConfig) -> Bool {
        if config.selectedWorkspaces.isEmpty && config.selectedProjects.isEmpty {
            return true
        }
        return config.selectedWorkspaces.contains(dataRow.workspace) || config.selectedProjects.contains(dataRow.project)
    }
}

// MARK: - BitBarRenderer

struct BitBarRenderer {
    private let data: BuildTimesOutput
    private let localTimeZone: TimeZone?
    private let selfPath: String

    init(data: BuildTimesOutput, config: BuildTimesConfig, selfPath: String) {
        self.data = data
        self.localTimeZone = config.localTimeZone
        self.selfPath = selfPath
    }

    func render() {
        renderHeader()
        renderContent()
        renderFooter()
    }

    private func renderHeader() {
        var row = ""
        let buildTime = data.todayData?.buildTime ?? 0
        row += buildTime.formattedDuration

        if !data.warnings.isEmpty {
            row += " ⚠️"
        }
        row += " | templateImage=" + Config.icon

        renderRows([row, "---"])
    }

    private func renderContent() {
        var rows: [String] = []

        // Warnings
        if !data.warnings.isEmpty {
            rows.append(contentsOf: data.warnings.map { String(format: Strings.rowWarning, $0) })
            rows.append("---")
        }

        let todayData = data.todayData
        let totalData = data.totalData
        let dailyData = data.dailyData

        let alternate = "| alternate=true"
        let selectedFilter = data.selectedWorkspaces + data.selectedProjects
        let formattedSelectedFilter = selectedFilter.map { sanitizeName($0) }.joined(separator: ", ")

        if selectedFilter.isEmpty {
            rows.append(Strings.rowHeaderToday)
        } else {
            rows.append(String(format: Strings.rowHeaderTodayFilter, formattedSelectedFilter))
        }

        if let totalData = data.totalData, let dataFrom = totalData.dataFrom {
            let dateFromFormatted = DateFormatters.dateOnly(timeZone: localTimeZone).string(from: dataFrom)
            if selectedFilter.isEmpty {
                rows.append(String(format: Strings.rowHeaderTotalSince, dateFromFormatted) + alternate)
            } else {
                rows.append(String(format: Strings.rowHeaderTotalSinceFilter, dateFromFormatted, formattedSelectedFilter) + alternate)
            }
        } else {
            if selectedFilter.isEmpty {
                rows.append(Strings.rowHeaderTotal + alternate)
            } else {
                rows.append(String(format: Strings.rowHeaderTotalFilter, formattedSelectedFilter) + alternate)
            }
        }

        rows.append(getBuildCountsRow(todayData))
        rows.append(getBuildCountsRow(totalData) + alternate)

        let todayBuildTime = getFormattedTimeRow(todayData, property: \.buildTime, title: Strings.rowBuildTime)
        let totalBuildTime = getFormattedTimeRow(totalData, property: \.buildTime, title: Strings.rowBuildTime)

        let todaySuccessBuildTime = getFormattedTimeRow(todayData, property: \.successBuildTime, title: Strings.rowSuccessBuildTime)
        let totalSuccessBuildTime = getFormattedTimeRow(totalData, property: \.successBuildTime, title: Strings.rowSuccessBuildTime)

        let todayFailBuildTime = getFormattedTimeRow(todayData, property: \.failBuildTime, title: Strings.rowFailBuildTime)
        let totalFailBuildTime = getFormattedTimeRow(totalData, property: \.failBuildTime, title: Strings.rowFailBuildTime)

        let todayAverageBuildTime = getFormattedTimeRow(todayData, property: \.averageBuildTime, title: Strings.rowAverageBuildTime)

        let todayAverageSuccessBuildTime = getFormattedTimeRow(todayData, property: \.averageSuccessBuildTime, title: Strings.rowSuccessBuildTime)
        let totalAverageSuccessBuildTime = getFormattedTimeRow(totalData, property: \.averageSuccessBuildTime, title: Strings.rowSuccessBuildTime)
        let todayAverageFailBuildTime = getFormattedTimeRow(todayData, property: \.averageFailBuildTime, title: Strings.rowFailBuildTime)
        let totalAverageFailBuildTime = getFormattedTimeRow(totalData, property: \.averageFailBuildTime, title: Strings.rowFailBuildTime)
        let totalAverageBuildTime = getFormattedTimeRow(totalData, property: \.averageBuildTime, title: Strings.rowAverageBuildTime)

        // Build time
        let buildTimeSubMenu = [
            "-- " + todaySuccessBuildTime,
            "-- " + totalSuccessBuildTime + alternate,
            "-- " + todayFailBuildTime,
            "-- " + totalFailBuildTime + alternate
        ]

        rows.append(todayBuildTime)
        rows.append(contentsOf: buildTimeSubMenu)

        rows.append(totalBuildTime + alternate)
        rows.append(contentsOf: buildTimeSubMenu)

        // Average build time
        let averageBuildTimeSubMenu = [
            "-- " + todayAverageSuccessBuildTime,
            "-- " + totalAverageSuccessBuildTime + alternate,
            "-- " + todayAverageFailBuildTime,
            "-- " + totalAverageFailBuildTime + alternate
        ]

        rows.append(todayAverageBuildTime)
        rows.append(contentsOf: averageBuildTimeSubMenu)

        rows.append(totalAverageBuildTime + alternate)
        rows.append(contentsOf: averageBuildTimeSubMenu)

        // Daily data
        if let dailyData = dailyData {
            rows.append(String(format: Strings.rowDailyAverageBuildTime, dailyData.averageBuildTime.formattedDuration, "\(dailyData.averageBuildCount)"))
            rows.append("-- " + String(format: Strings.rowDailySuccessBuildTime, dailyData.averageSuccessBuildTime.formattedDuration, "\(dailyData.averageSuccessCount)"))
            rows.append("-- " + String(format: Strings.rowDailyFailBuildTime, dailyData.averageFailBuildTime.formattedDuration, "\(dailyData.averageFailCount)"))
        }

        if let lastBuild = data.lastBuild {
            rows.append("---")
            rows.append(String(format: Strings.rowLastBuild, lastBuild.type.rawValue, lastBuild.count.formattedDuration))
        }

        for inProgress in data.inProgress {
            rows.append(String(format: Strings.rowBuildInProgress, inProgress.formattedDuration))
        }

        renderRows(rows)
    }

    private func getBuildCountsRow(_ data: BuildTimesData?) -> String {
        guard let data = data else {
            return Strings.rowBuildCountsNoBuilds
        }
        return String(format: Strings.rowBuildCounts, data.buildCount, data.failCount)
    }

    private func getFormattedTimeRow(_ data: BuildTimesData?, property: KeyPath<BuildTimesData, Int>, title: String) -> String {
        guard let data = data else {
            return String(format: title, Strings.msgNoBuildsYet)
        }
        let formatted = data[keyPath: property].formattedDuration
        return String(format: title, formatted)
    }

    private func renderFooter() {
        var rows: [String] = []
        rows.append("---")
        rows.append(Strings.rowRefresh + "| refresh=true")
        rows.append(Strings.rowShare + "| bash='\(selfPath)' param1=share param2=today refresh=false terminal=false")
        rows.append(Strings.rowShare + "| bash='\(selfPath)' param1=share param2=total refresh=false terminal=false alternate=true")

        renderRows(rows)

        renderPreferences()
        renderAbout()
    }

    private func renderPreferences() {
        renderRows([Strings.rowSettings])

        renderFilter()

        var rows: [String] = []
        rows.append("-- " + Strings.rowSettingsReset)
        rows.append("---- " + Strings.rowSettingsResetReally)
        rows.append("------ " + Strings.rowSettingsResetReallyYes + "| bash='\(selfPath)' param1=reset refresh=true terminal=false")
        renderRows(rows)
    }

    private func renderFilter() {
        let check = "✔ "
        let alternate = " alternate=true"
        let allSelected = data.selectedWorkspaces.isEmpty && data.selectedProjects.isEmpty

        var rows: [String] = []

        rows.append("-- " + Strings.rowSettingsFilter)
        rows.append("---- " + (allSelected ? check : "") + Strings.rowSettingsFilterAll + getActionForProjectSelection("all", name: "-", add: false))

        for workspace in data.workspaces {
            let isSelected = data.selectedWorkspaces.contains(workspace)
            var row = "---- "
            if isSelected {
                row += check
            }
            row += sanitizeName(workspace)
            rows.append(row + getActionForProjectSelection("workspace", name: workspace, add: false))
            rows.append(row + getActionForProjectSelection("workspace", name: workspace, add: true) + alternate)
        }

        for project in data.projects {
            let isSelected = data.selectedProjects.contains(project)
            var row = "---- "
            if isSelected {
                row += check
            }
            row += sanitizeName(project)
            rows.append(row + getActionForProjectSelection("project", name: project, add: false))
            rows.append(row + getActionForProjectSelection("project", name: project, add: true) + alternate)
        }

        renderRows(rows)
    }

    private func sanitizeName(_ name: String) -> String {
        var result = name
        result = result.replacingOccurrences(of: "\n", with: "_")
        result = result.replacingOccurrences(of: "|", with: "_")
        return result
    }

    private func renderAbout() {
        var rows: [String] = []
        rows.append(Strings.rowAbout)
        rows.append("-- " + Strings.rowAboutSourceCode + "| href=" + Config.aboutURL)
        rows.append("-- " + Strings.rowAboutIcon + "| href=" + Config.iconURL)
        rows.append("-- " + Strings.rowAboutUpdate)
        rows.append("---- " + Strings.rowAboutUpdateReally)
        rows.append("------ " + Strings.rowAboutUpdateReallyYes + "| bash='\(selfPath)' param1=update param2=showAlerts refresh=true terminal=false")

        renderRows(rows)
    }

    private func renderRows(_ rows: [String]) {
        print(rows.joined(separator: "\n"))
    }

    func getShareText(_ mode: String) -> String {
        let isToday = mode == "today"
        let shareData = isToday ? data.todayData : data.totalData
        let dailyData = data.dailyData

        var lines: [String] = []

        let selectedFilter = data.selectedWorkspaces + data.selectedProjects
        let formattedFilter = selectedFilter.map { sanitizeName($0) }.joined(separator: ", ")

        if isToday {
            let todayDate = DateFormatters.dateOnly(timeZone: localTimeZone).string(from: Date())
            if selectedFilter.isEmpty {
                lines.append(String(format: Strings.shareHeaderToday, todayDate))
            } else {
                lines.append(String(format: Strings.shareHeaderTodayFilter, formattedFilter, todayDate))
            }
        } else {
            if let totalData = data.totalData, let dataFrom = totalData.dataFrom {
                let dateFromFormatted = DateFormatters.dateOnly(timeZone: localTimeZone).string(from: dataFrom)
                if selectedFilter.isEmpty {
                    lines.append(String(format: Strings.shareHeaderTotalSince, dateFromFormatted))
                } else {
                    lines.append(String(format: Strings.shareHeaderTotalSinceFilter, dateFromFormatted, formattedFilter))
                }
            } else {
                if selectedFilter.isEmpty {
                    lines.append(Strings.shareHeaderTotal)
                } else {
                    lines.append(String(format: Strings.shareHeaderTotalFilter, formattedFilter))
                }
            }
        }

        if let d = shareData {
            lines.append(String(format: Strings.rowBuildCounts, d.buildCount, d.failCount))
            lines.append(String(format: Strings.rowBuildTime, d.buildTime.formattedDuration))
            lines.append("  " + String(format: Strings.rowSuccessBuildTime, d.successBuildTime.formattedDuration))
            lines.append("  " + String(format: Strings.rowFailBuildTime, d.failBuildTime.formattedDuration))
            lines.append(String(format: Strings.rowAverageBuildTime, d.averageBuildTime.formattedDuration))
            lines.append("  " + String(format: Strings.rowSuccessBuildTime, d.averageSuccessBuildTime.formattedDuration))
            lines.append("  " + String(format: Strings.rowFailBuildTime, d.averageFailBuildTime.formattedDuration))
        } else {
            lines.append(Strings.rowBuildCountsNoBuilds)
        }

        if let dailyData = dailyData {
            lines.append(String(format: Strings.rowDailyAverageBuildTime, dailyData.averageBuildTime.formattedDuration, "\(dailyData.averageBuildCount)"))
            lines.append("  " + String(format: Strings.rowDailySuccessBuildTime, dailyData.averageSuccessBuildTime.formattedDuration, "\(dailyData.averageSuccessCount)"))
            lines.append("  " + String(format: Strings.rowDailyFailBuildTime, dailyData.averageFailBuildTime.formattedDuration, "\(dailyData.averageFailCount)"))
        }

        lines.append("")
        lines.append(String(format: Strings.shareFooter, Config.aboutURL))

        return lines.joined(separator: "\n")
    }

    private func getActionForProjectSelection(_ type: String, name: String, add: Bool) -> String {
        if name.contains("\"") || name.contains("'") {
            return ""
        }
        let mode = add ? "add" : "set"
        return "| bash='\(selfPath)' param1=config param2=filter_toggle param3=\(type) param4=\"\(name)\" param5=\(mode) refresh=true terminal=false"
    }
}

// MARK: - Duration Formatting

extension Int {
    var formattedDuration: String {
        if self < 60 {
            return "\(self)s"
        } else if self < 3600 {
            return "\(self / 60)m \(self % 60)s"
        } else if self < 86400 {
            return "\(self / 3600)h \((self % 3600) / 60)m"
        } else {
            return "\(self / 86400)d \((self % 86400) / 3600)h"
        }
    }
}

// MARK: - Helper Functions

func md5(_ string: String) -> String {
    let data = Data(string.utf8)
    var digest = [UInt8](repeating: 0, count: Int(CC_MD5_DIGEST_LENGTH))
    data.withUnsafeBytes { buffer in
        _ = CC_MD5(buffer.baseAddress, CC_LONG(data.count), &digest)
    }
    return digest.map { String(format: "%02x", $0) }.joined()
}

func sha256(_ string: String) -> String {
    let data = Data(string.utf8)
    var digest = [UInt8](repeating: 0, count: Int(CC_SHA256_DIGEST_LENGTH))
    data.withUnsafeBytes { buffer in
        _ = CC_SHA256(buffer.baseAddress, CC_LONG(data.count), &digest)
    }
    return digest.map { String(format: "%02x", $0) }.joined()
}

func getBuildHash() -> String {
    var buildHash = ""
    if let workspacePath = Environment["XcodeWorkspacePath"] {
        buildHash += workspacePath
    }
    if let projectPath = Environment["XcodeProjectPath"] {
        buildHash += projectPath
    }
    return md5(buildHash)
}

func markStart(_ startTimeFilePath: String) {
    let timestamp = "\(Int(Date().timeIntervalSince1970))"
    try? timestamp.write(toFile: startTimeFilePath, atomically: true, encoding: .utf8)
}

func markEnd(_ type: BuildStatus, startTimeFilePath: String, dataFilePath: String) {
    guard let content = try? String(contentsOfFile: startTimeFilePath, encoding: .utf8) else {
        exitWithError("Unable to open file: \(startTimeFilePath)\n")
    }
    try? FileManager.default.removeItem(atPath: startTimeFilePath)

    let startTime = Int(content) ?? 0
    let duration = Int(Date().timeIntervalSince1970) - startTime

    if duration < 0 || startTime == 0 {
        exitWithError("File \(startTimeFilePath) has invalid format\n")
    }

    let workspace = Environment["XcodeWorkspace"] ?? ""
    let project = Environment["XcodeProject"] ?? ""

    var xcodeVersion = ""
    var xcodeBuild = ""

    if let developerDirectory = Environment["XcodeDeveloperDirectory"] {
        let plistPath = URL(fileURLWithPath: developerDirectory).deletingLastPathComponent().appendingPathComponent("version.plist").path
        if let plistData = try? Data(contentsOf: URL(fileURLWithPath: plistPath)),
           let plist = try? PropertyListSerialization.propertyList(from: plistData, format: nil) as? [String: Any] {
            xcodeVersion = plist["CFBundleShortVersionString"] as? String ?? ""
            xcodeBuild = plist["ProductBuildVersion"] as? String ?? ""
        }
    }

    let isoFormatter = ISO8601DateFormatter()
    isoFormatter.formatOptions = [.withInternetDateTime]
    let dateStr = isoFormatter.string(from: Date())

    let fields = [dateStr, "\(duration)", type.rawValue, workspace, project, xcodeVersion, xcodeBuild]
    let csvLine = CSV.writeLine(fields)

    guard let handle = FileHandle(forWritingAtPath: dataFilePath) else {
        // File doesn't exist yet, create it
        if FileManager.default.createFile(atPath: dataFilePath, contents: (csvLine + "\n").data(using: .utf8)) {
            return
        }
        exitWithError("Unable to open file: \(dataFilePath)\n")
    }

    handle.seekToEndOfFile()
    handle.write((csvLine + "\n").data(using: .utf8)!)
    handle.closeFile()
}

func getProgressDuration(_ startTimeFilePath: String) -> Int {
    guard let content = try? String(contentsOfFile: startTimeFilePath, encoding: .utf8) else {
        return 0
    }
    let startTime = Int(content.trimmingCharacters(in: .whitespacesAndNewlines)) ?? 0
    let duration = Int(Date().timeIntervalSince1970) - startTime
    if duration < 0 || startTime == 0 {
        return 0
    }
    return duration
}

func showAlert(_ message: String) {
    let escapedTitle = Strings.updateAlertTitle.replacingOccurrences(of: "\"", with: "\\\"")
    let escapedMessage = message.replacingOccurrences(of: "\"", with: "\\\"")
    let script = "display alert \"\(escapedTitle)\" message \"\(escapedMessage)\""
    let process = Process()
    process.executableURL = URL(fileURLWithPath: "/usr/bin/osascript")
    process.arguments = ["-e", script]
    process.standardOutput = FileHandle.nullDevice
    process.standardError = FileHandle.nullDevice
    try? process.run()
    // Don't wait - background it like PHP does with &
}

func processConfigChange(_ args: [String], configFilePath: String) {
    if args.count < 3 { return }

    let config = BuildTimesConfig(configFile: configFilePath)

    switch args[2] {
    case "filter_toggle":
        let type = args.count > 3 ? args[3] : ""
        let name = args.count > 4 ? args[4] : ""
        var mode = args.count > 5 ? args[5] : "set"
        if mode != "set" && mode != "add" {
            mode = "set"
        }
        switch type {
        case "all":
            config.selectAll()
            config.save(to: configFilePath)
        case "workspace":
            config.toggleWorkspace(name, add: mode == "add")
            config.save(to: configFilePath)
        case "project":
            config.toggleProject(name, add: mode == "add")
            config.save(to: configFilePath)
        default:
            break
        }
    default:
        break
    }
}

func update(_ dataDirectory: String, showAlerts: Bool, selfPath: String) {
    // Build download URL from the current filename (e.g. xcodeBuildTimes.1m.swift)
    let selfFileName = URL(fileURLWithPath: selfPath).lastPathComponent
    let updateURL = Config.updateBaseURL + selfFileName

    let url = URL(string: updateURL)!
    var request = URLRequest(url: url)
    request.httpMethod = "GET"
    request.setValue(Config.updateUserAgent, forHTTPHeaderField: "User-Agent")
    request.setValue("no-cache", forHTTPHeaderField: "Cache-Control")
    request.setValue("no-cache", forHTTPHeaderField: "Pragma")

    let semaphore = DispatchSemaphore(value: 0)
    var downloadedData: Data?
    var downloadError: Error?
    var httpStatusCode: Int = 0

    let task = URLSession.shared.dataTask(with: request) { data, response, error in
        downloadedData = data
        downloadError = error
        if let httpResponse = response as? HTTPURLResponse {
            httpStatusCode = httpResponse.statusCode
        }
        semaphore.signal()
    }
    task.resume()
    semaphore.wait()

    guard let newData = downloadedData, downloadError == nil,
          httpStatusCode == 200,
          let newContent = String(data: newData, encoding: .utf8) else {
        let detail = httpStatusCode != 0 && httpStatusCode != 200 ? " (HTTP \(httpStatusCode))" : ""
        exitWithError("Unable to download update file from: \(updateURL)\(detail)\n",
                      showAlertMessage: showAlerts ? Strings.updateAlertFailMessage : nil)
    }

    guard let selfData = try? String(contentsOfFile: selfPath, encoding: .utf8) else {
        exitWithError("Unable to read self file\n",
                      showAlertMessage: showAlerts ? Strings.updateAlertFailMessage : nil)
    }

    // Compare hash without shebangs
    let removeShebang: (String) -> String = { content in
        if content.hasPrefix("#!") {
            if let newlineIdx = content.firstIndex(of: "\n") {
                return String(content[content.index(after: newlineIdx)...])
            }
        }
        return content
    }

    let hash1 = sha256(removeShebang(newContent))
    let hash2 = sha256(removeShebang(selfData))

    if hash1 == hash2 {
        exitWithError("No update available.\n",
                      showAlertMessage: showAlerts ? Strings.updateAlertNoUpdatesMessage : nil)
    }

    // Preserve local shebang
    var finalContent = newContent
    if selfData.hasPrefix("#!") {
        if let selfNewline = selfData.firstIndex(of: "\n") {
            let selfShebang = String(selfData[selfData.startIndex...selfNewline])
            if finalContent.hasPrefix("#!") {
                if let newNewline = finalContent.firstIndex(of: "\n") {
                    finalContent = selfShebang + String(finalContent[finalContent.index(after: newNewline)...])
                }
            } else {
                finalContent = selfShebang + finalContent
            }
        }
    }

    let updateFile = dataDirectory + "/" + Config.updateTmpFileName
    do {
        try finalContent.write(toFile: updateFile, atomically: true, encoding: .utf8)
    } catch {
        exitWithError("Unable to write update file to: \(updateFile)\n",
                      showAlertMessage: showAlerts ? Strings.updateAlertFailMessage : nil)
    }

    // Set permissions to 0755
    do {
        try FileManager.default.setAttributes([.posixPermissions: 0o755], ofItemAtPath: updateFile)
    } catch {
        exitWithError("Unable change permission of \(updateFile) to 0755\n",
                      showAlertMessage: showAlerts ? Strings.updateAlertFailMessage : nil)
    }

    // Atomic rename
    do {
        let fm = FileManager.default
        if fm.fileExists(atPath: selfPath) {
            _ = try fm.replaceItemAt(URL(fileURLWithPath: selfPath), withItemAt: URL(fileURLWithPath: updateFile))
        } else {
            try fm.moveItem(atPath: updateFile, toPath: selfPath)
        }
    } catch {
        exitWithError("Unable to rename \(updateFile) to \(selfPath)\n",
                      showAlertMessage: showAlerts ? Strings.updateAlertFailMessage : nil)
    }

    print("Update successful")
    if showAlerts { showAlert(Strings.updateAlertSuccessMessage) }
    exit(0)
}

func compareVersions(_ v1: String, _ v2: String) -> Int {
    let parts1 = v1.split(separator: ".").map { Int($0) ?? 0 }
    let parts2 = v2.split(separator: ".").map { Int($0) ?? 0 }
    let maxLen = max(parts1.count, parts2.count)
    for i in 0..<maxLen {
        let a = i < parts1.count ? parts1[i] : 0
        let b = i < parts2.count ? parts2[i] : 0
        if a < b { return -1 }
        if a > b { return 1 }
    }
    return 0
}

func globFiles(_ pattern: String) -> [String] {
    // pattern is like /path/to/buildStartTime.*
    // We need to list files matching this pattern
    let patternURL = URL(fileURLWithPath: pattern)
    let dir = patternURL.deletingLastPathComponent().path
    let prefix = patternURL.lastPathComponent.replacingOccurrences(of: ".*", with: ".")

    guard let contents = try? FileManager.default.contentsOfDirectory(atPath: dir) else {
        return []
    }

    return contents
        .filter { $0.hasPrefix(prefix) }
        .map { dir + "/" + $0 }
        .sorted()
}

// MARK: - Main

func main() {
    let selfPath: String = {
        let args = CommandLine.arguments
        if !args.isEmpty {
            let arg0 = args[0]
            if arg0.hasSuffix(".swift") || arg0.contains("xcodeBuildTimes") {
                // resolvingSymlinksInPath() resolves symlinks like PHP's realpath(__FILE__)
                return URL(fileURLWithPath: arg0).resolvingSymlinksInPath().path
            }
        }
        return URL(fileURLWithPath: CommandLine.arguments.first { $0.hasSuffix(".swift") } ?? CommandLine.arguments[0]).resolvingSymlinksInPath().path
    }()

    let scriptDirectoryURL = URL(fileURLWithPath: selfPath).deletingLastPathComponent()
    let dataDirectoryURL = scriptDirectoryURL.appendingPathComponent(Config.dataFileDir)
    let dataDirectory = dataDirectoryURL.path
    let dataFilePath = dataDirectoryURL.appendingPathComponent(Config.dataFileName).path
    let configFilePath = dataDirectoryURL.appendingPathComponent(Config.configFileName).path
    let startTimeBase = dataDirectoryURL.appendingPathComponent(Config.startTimeFileName).path
    let startTimeFilesPattern = startTimeBase + ".*"
    let buildHash = getBuildHash()
    let startTimeFilePath = startTimeBase + "." + buildHash

    // Create data directory if needed
    if !FileManager.default.fileExists(atPath: dataDirectory) {
        try? FileManager.default.createDirectory(atPath: dataDirectory, withIntermediateDirectories: true)
    }

    let idAlertMessage = Environment["IDEAlertMessage"]
    let args = CommandLine.arguments
    let arg: String? = args.count > 1 ? args[1] : nil

    if idAlertMessage == "Build Started" || arg == "start" {
        markStart(startTimeFilePath)
        exit(0)
    } else if idAlertMessage == "Build Succeeded" || arg == "success" {
        markEnd(.success, startTimeFilePath: startTimeFilePath, dataFilePath: dataFilePath)
        exit(0)
    } else if idAlertMessage == "Build Failed" || arg == "fail" {
        markEnd(.fail, startTimeFilePath: startTimeFilePath, dataFilePath: dataFilePath)
        exit(0)
    } else if arg == "reset" {
        try? FileManager.default.removeItem(atPath: dataFilePath)
        exit(0)
    } else if arg == "update" {
        let showAlerts = args.count > 2 && args[2] == "showAlerts"
        update(dataDirectory, showAlerts: showAlerts, selfPath: selfPath)
    } else if arg == "config" {
        processConfigChange(Array(args), configFilePath: configFilePath)
        exit(0)
    } else if arg == "share" {
        let mode = args.count > 2 ? args[2] : "today"
        let config = BuildTimesConfig(configFile: configFilePath)
        let parser = BuildTimesFileParser(dataFile: dataFilePath, startTimeFilesPattern: startTimeFilesPattern, config: config)
        let output = parser.getOutput()
        let renderer = BitBarRenderer(data: output, config: config, selfPath: selfPath)
        let text = renderer.getShareText(mode)
        let process = Process()
        process.executableURL = URL(fileURLWithPath: "/usr/bin/pbcopy")
        process.standardInput = Pipe()
        let inputPipe = process.standardInput as! Pipe
        try? process.run()
        inputPipe.fileHandleForWriting.write(text.data(using: .utf8)!)
        inputPipe.fileHandleForWriting.closeFile()
        process.waitUntilExit()
        showAlert("Copied to clipboard:\n\n" + text)
        exit(0)
    } else if arg == "configure" {
        print("Swift version does not need shebang configuration.")
        exit(0)
    }

    // Default: render
    let config = BuildTimesConfig(configFile: configFilePath)
    let parser = BuildTimesFileParser(dataFile: dataFilePath, startTimeFilesPattern: startTimeFilesPattern, config: config)
    let output = parser.getOutput()
    let renderer = BitBarRenderer(data: output, config: config, selfPath: selfPath)
    renderer.render()
}

main()
