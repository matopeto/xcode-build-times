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
    static let ICON = "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DEwlCw2cQ+CYleoN5kOCSNk1iorrkOzTCcXrh6/9K/wc/g/iTy7+CmVuZHN0cmVhbQplbmRvYmoKNCAwIG9iagogICAyNjY1CmVuZG9iagoyIDAgb2JqCjw8CiAgIC9FeHRHU3RhdGUgPDwKICAgICAgL2EwIDw8IC9DQSAxIC9jYSAxID4+CiAgID4+Cj4+CmVuZG9iago1IDAgb2JqCjw8IC9UeXBlIC9QYWdlCiAgIC9QYXJlbnQgMSAwIFIKICAgL01lZGlhQm94IFsgMCAwIDE3IDE3IF0KICAgL0NvbnRlbnRzIDMgMCBSCiAgIC9Hcm91cCA8PAogICAgICAvVHlwZSAvR3JvdXAKICAgICAgL1MgL1RyYW5zcGFyZW5jeQogICAgICAvSSB0cnVlCiAgICAgIC9DUyAvRGV2aWNlUkdCCiAgID4+CiAgIC9SZXNvdXJjZXMgMiAwIFIKPj4KZW5kb2JqCjEgMCBvYmoKPDwgL1R5cGUgL1BhZ2VzCiAgIC9LaWRzIFsgNSAwIFIgXQogICAvQ291bnQgMQo+PgplbmRvYmoKNiAwIG9iago8PCAvQ3JlYXRvciAoY2Fpcm8gMS4xNC44IChodHRwOi8vY2Fpcm9ncmFwaGljcy5vcmcpKQogICAvUHJvZHVjZXIgKGNhaXJvIDEuMTQuOCAoaHR0cDovL2NhaXJvZ3JhcGhpY3Mub3JnKSkKPj4KZW5kb2JqCjcgMCBvYmoKPDwgL1R5cGUgL0NhdGFsb2cKICAgL1BhZ2VzIDEgMCBSCj4+CmVuZG9iagp4cmVmCjAgOAowMDAwMDAwMDAwIDY1NTM1IGYgCjAwMDAwMDMwNjQgMDAwMDAgbiAKMDAwMDAwMjc4MCAwMDAwMCBuIAowMDAwMDAwMDE1IDAwMDAwIG4gCjAwMDAwMDI3NTcgMDAwMDAgbiAKMDAwMDAwMjg1MiAwMDAwMCBuIAowMDAwMDAzMTI5IDAwMDAwIG4gCjAwMDAwMDMyNTYgMDAwMDAgbiAKdHJhaWxlcgo8PCAvU2l6ZSA4CiAgIC9Sb290IDcgMCBSCiAgIC9JbmZvIDYgMCBSCj4+CnN0YXJ0eHJlZgozMzA4CiUlRU9GCg=="
    static let ICON_URL = "https://icons8.com"

    static let ABOUT_URL = "https://github.com/matopeto/xcode-build-times"
    static let SWIFTBAR_URL = "https://github.com/swiftbar/SwiftBar"

    static let UPDATE_URL = "https://raw.githubusercontent.com/matopeto/xcode-build-times/master/sources/xcodeBuildTimes.1m.swift"
    static let UPDATE_USER_AGENT = "matopeto/xcode-build-times"

    static let DATE_FORMAT = "yyyy-MM-dd"

    static let DATA_FILE_DIR = ".xcodeBuildTimes"
    static let DATA_FILE_NAME = "buildTimes.csv"
    static let START_TIME_FILE_NAME = "buildStartTime"
    static let UPDATE_TMP_FILE_NAME = ".newVersion"
    static let CONFIG_FILE_NAME = "config.json"
}

// MARK: - Strings

enum Strings {
    static let WARNING_UNABLE_TO_READ_DATA_FILE = "Unable to read data file. that's ok as long as no build has been done yet"
    static let WARNING_PROBLEM_WITH_DATA_FILE = "There is some problem with data"
    static let WARNING_NO_DATA = "No data"
    static let WARNING_OLD_SWIFTBAR_VERSION = "Please use the latest SwiftBar for proper functionality"
    static let ROW_WARNING = "⚠️ %@"

    static let ROW_HEADER_TODAY = "Today"
    static let ROW_HEADER_TODAY_FILTER = "Today (%@)"
    static let ROW_HEADER_TOTAL = "Total"
    static let ROW_HEADER_TOTAL_FILTER = "Total (%@)"
    static let ROW_HEADER_TOTAL_SINCE = "Total, since: %@"
    static let ROW_HEADER_TOTAL_SINCE_FILTER = "Total, since: %@ (%@)"

    static let ROW_BUILD_COUNTS = "Builds %d, %d failed"
    static let ROW_BUILD_COUNTS_NO_BUILDS = "Builds: no builds yet"
    static let ROW_BUILD_TIME = "Build time: %@"
    static let ROW_AVERAGE_BUILD_TIME = "Average build time: %@"

    static let ROW_SUCCESS_BUILD_TIME = "Success: %@"
    static let ROW_FAIL_BUILD_TIME = "Fail: %@"

    static let ROW_DAILY_AVERAGE_BUILD_TIME = "Daily average: %@, %@ builds"
    static let ROW_DAILY_SUCCESS_BUILD_TIME = "Success: %@, %@ builds"
    static let ROW_DAILY_FAIL_BUILD_TIME = "Fail: %@, %@ builds"

    static let ROW_LAST_BUILD = "Last build: %@, %@"
    static let ROW_BUILD_IN_PROGRESS = "Build in progress: %@"

    static let ROW_REFRESH = "Refresh"
    static let ROW_SHARE = "Share"

    static let SHARE_HEADER_TODAY = "Xcode Build Times - Today, %@"
    static let SHARE_HEADER_TODAY_FILTER = "Xcode Build Times - Today (%@), %@"
    static let SHARE_HEADER_TOTAL_SINCE = "Xcode Build Times - Total, since: %@"
    static let SHARE_HEADER_TOTAL_SINCE_FILTER = "Xcode Build Times - Total, since: %@ (%@)"
    static let SHARE_HEADER_TOTAL = "Xcode Build Times - Total"
    static let SHARE_HEADER_TOTAL_FILTER = "Xcode Build Times - Total (%@)"
    static let SHARE_FOOTER = "Tracked with Xcode Build Times - %@"

    static let ROW_SETTINGS = "Settings"
    static let ROW_SETTINGS_FILTER = "Filter"
    static let ROW_SETTINGS_FILTER_ALL = "Show all"
    static let ROW_SETTINGS_RESET = "Reset"
    static let ROW_SETTINGS_RESET_REALLY = "Really?"
    static let ROW_SETTINGS_RESET_REALLY_YES = "Yes"

    static let ROW_ABOUT = "About"
    static let ROW_ABOUT_ICON = "Icon by Icons8"
    static let ROW_ABOUT_SOURCE_CODE = "Source Code & Info"
    static let ROW_ABOUT_UPDATE = "Update"
    static let ROW_ABOUT_UPDATE_REALLY = "Really?"
    static let ROW_ABOUT_UPDATE_REALLY_YES = "Yes"

    static let MSG_NO_BUILDS_YET = "no builds yet"

    static let UPDATE_ALERT_TITLE = "Xcode build times"
    static let UPDATE_ALERT_SUCCESS_MESSAGE = "Plugin was updated successfully."
    static let UPDATE_ALERT_FAIL_MESSAGE = "Unable to update."
    static let UPDATE_ALERT_NO_UPDATES_MESSAGE = "No updates available."
}

// MARK: - Data Models

struct DataRow {
    var date: Date
    var count: Int
    var type: String
    var workspace: String = ""
    var project: String = ""
}

struct BuildTimesData {
    var buildCount: Int = 0
    var successCount: Int = 0
    var failCount: Int = 0
    var averageBuildTime: Int = 0
    var averageSuccessBuildTime: Int = 0
    var averageFailBuildTime: Int = 0
    var buildTime: Int = 0
    var successBuildTime: Int = 0
    var failBuildTime: Int = 0
    var dataFrom: Date?
    var dataTo: Date?
}

struct DailyTimesData {
    var days: Int = 0
    var averageBuildCount: Int = 0
    var averageSuccessCount: Int = 0
    var averageFailCount: Int = 0
    var averageBuildTime: Int = 0
    var averageSuccessBuildTime: Int = 0
    var averageFailBuildTime: Int = 0
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

// MARK: - CSV Parsing Helpers

func parseCSVLine(_ line: String) -> [String] {
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

func escapeCSVField(_ field: String) -> String {
    if field.contains(",") || field.contains("\"") || field.contains("\n") {
        return "\"" + field.replacingOccurrences(of: "\"", with: "\"\"") + "\""
    }
    return field
}

func writeCSVLine(_ fields: [String]) -> String {
    return fields.map { escapeCSVField($0) }.joined(separator: ",")
}

// MARK: - BuildTimesConfig

class BuildTimesConfig {
    var selectedWorkspaces: [String] = []
    var selectedProjects: [String] = []
    var localTimeZone: TimeZone?
    private var localTimeZoneAutodetect = true

    static let selectedWorkspacesKey = "selectedWorkspaces"
    static let selectedProjectsKey = "selectedProjects"
    static let selectedLocalTimeZoneKey = "localTimeZone"

    init(configFile: String) {
        guard let data = try? Data(contentsOf: URL(fileURLWithPath: configFile)),
              let json = try? JSONSerialization.jsonObject(with: data) as? [String: Any] else {
            localTimeZone = BuildTimesConfig.getLocalTimeZone()
            return
        }

        if let workspaces = json[BuildTimesConfig.selectedWorkspacesKey] as? [Any] {
            for w in workspaces {
                if let s = w as? String {
                    selectedWorkspaces.append(s)
                }
            }
        }

        if let projects = json[BuildTimesConfig.selectedProjectsKey] as? [Any] {
            for p in projects {
                if let s = p as? String {
                    selectedProjects.append(s)
                }
            }
        }

        if let tzString = json[BuildTimesConfig.selectedLocalTimeZoneKey] as? String,
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
        var dataToSave: [String: Any] = [
            BuildTimesConfig.selectedProjectsKey: selectedProjects,
            BuildTimesConfig.selectedWorkspacesKey: selectedWorkspaces
        ]

        if let tz = localTimeZone, !localTimeZoneAutodetect {
            dataToSave[BuildTimesConfig.selectedLocalTimeZoneKey] = tz.identifier
        }

        guard let jsonData = try? JSONSerialization.data(withJSONObject: dataToSave, options: [.prettyPrinted, .sortedKeys]) else {
            FileHandle.standardError.write("Unable to serialize config\n".data(using: .utf8)!)
            exit(1)
        }
        try? jsonData.write(to: URL(fileURLWithPath: configFile))
    }

    static func getLocalTimeZone() -> TimeZone? {
        return TimeZone.current
    }
}

// MARK: - BuildTimesFileParser

class BuildTimesFileParser {
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

        if let swiftBarVersion = ProcessInfo.processInfo.environment["SWIFTBAR_VERSION"],
           !swiftBarVersion.isEmpty {
            if compareVersions(swiftBarVersion, "1.4.3") < 0 {
                result.warnings.append(Strings.WARNING_OLD_SWIFTBAR_VERSION + "| href=" + Config.SWIFTBAR_URL)
            }
        }

        guard let content = try? String(contentsOfFile: dataFile, encoding: .utf8) else {
            result.warnings.append(Strings.WARNING_UNABLE_TO_READ_DATA_FILE)
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
            result.warnings.append(Strings.WARNING_PROBLEM_WITH_DATA_FILE)
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
            result.warnings.append(Strings.WARNING_NO_DATA)
        } else {
            result.totalData = getData(allRows)
            result.lastBuild = allRows.last
        }

        // Today rows
        let todayKey: String?
        if let tz = localTimeZone {
            let df = DateFormatter()
            df.dateFormat = "yyyy-MM-dd"
            df.timeZone = tz
            todayKey = df.string(from: Date())
        } else {
            let df = DateFormatter()
            df.dateFormat = "yyyy-MM-dd"
            todayKey = df.string(from: Date())
        }

        if let tk = todayKey, let todayRows = rows[tk] {
            result.todayData = getData(todayRows)
        }

        // Daily data (exclude today)
        var days = 0
        var bt = 0, sbt = 0, fbt = 0
        var bc = 0, sbc = 0, fbc = 0

        for (key, data) in rows {
            if let tk = todayKey, key >= tk {
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
            var dailyData = DailyTimesData()
            dailyData.days = days
            dailyData.averageBuildTime = bt / days
            dailyData.averageSuccessBuildTime = sbt / days
            dailyData.averageFailBuildTime = fbt / days
            dailyData.averageBuildCount = bc / days
            dailyData.averageSuccessCount = sbc / days
            dailyData.averageFailCount = fbc / days
            result.dailyData = dailyData
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

        let lines = content.components(separatedBy: "\n")
        for line in lines {
            if line.isEmpty { continue }
            let row = parseCSVLine(line)
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
            guard row[2] == "fail" || row[2] == "success" else {
                problemWithData = true
                continue
            }

            var date: Date?

            if dateInOldFormat {
                let df = DateFormatter()
                df.dateFormat = "yyyy-MM-dd HH:mm:ss"
                df.locale = Locale(identifier: "en_US_POSIX")
                date = df.date(from: dateStr)
                if date == nil {
                    problemWithData = true
                    continue
                }
            } else {
                // ISO8601 format
                let isoFormatter = ISO8601DateFormatter()
                isoFormatter.formatOptions = [.withInternetDateTime]
                date = isoFormatter.date(from: dateStr)
                if date == nil {
                    // Try with fractional seconds
                    isoFormatter.formatOptions = [.withInternetDateTime, .withFractionalSeconds]
                    date = isoFormatter.date(from: dateStr)
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

            var dataRow = DataRow(date: parsedDate, count: Int(row[1])!, type: row[2])

            if row.count >= 5 {
                dataRow.workspace = row[3]
                dataRow.project = row[4]
                workspacesDict[dataRow.workspace] = true
                projectsDict[dataRow.project] = true
            }

            // Compute the date key in local timezone
            let keyDate: Date = parsedDate
            let df = DateFormatter()
            df.dateFormat = "yyyy-MM-dd"
            if let tz = localTimeZone {
                df.timeZone = tz
            }
            let key = df.string(from: keyDate)

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
            if row.type == "success" {
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

class BitBarRenderer {
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
        row += format(buildTime)

        if !data.warnings.isEmpty {
            row += " ⚠️"
        }
        row += " | templateImage=" + Config.ICON

        renderRows([row, "---"])
    }

    private func renderContent() {
        var rows: [String] = []

        // Warnings
        if !data.warnings.isEmpty {
            rows.append(contentsOf: data.warnings.map { String(format: Strings.ROW_WARNING, $0) })
            rows.append("---")
        }

        let todayData = data.todayData
        let totalData = data.totalData
        let dailyData = data.dailyData

        let alternate = "| alternate=true"
        let selectedFilter = data.selectedWorkspaces + data.selectedProjects
        let formattedSelectedFilter = selectedFilter.map { sanitizeName($0) }.joined(separator: ", ")

        if selectedFilter.isEmpty {
            rows.append(Strings.ROW_HEADER_TODAY)
        } else {
            rows.append(String(format: Strings.ROW_HEADER_TODAY_FILTER, formattedSelectedFilter))
        }

        if let totalData = data.totalData, let dataFrom = totalData.dataFrom {
            let df = DateFormatter()
            df.dateFormat = Config.DATE_FORMAT
            if let tz = localTimeZone {
                df.timeZone = tz
            }
            let dateFromFormatted = df.string(from: dataFrom)
            if selectedFilter.isEmpty {
                rows.append(String(format: Strings.ROW_HEADER_TOTAL_SINCE, dateFromFormatted) + alternate)
            } else {
                rows.append(String(format: Strings.ROW_HEADER_TOTAL_SINCE_FILTER, dateFromFormatted, formattedSelectedFilter) + alternate)
            }
        } else {
            if selectedFilter.isEmpty {
                rows.append(Strings.ROW_HEADER_TOTAL + alternate)
            } else {
                rows.append(String(format: Strings.ROW_HEADER_TOTAL_FILTER, formattedSelectedFilter) + alternate)
            }
        }

        rows.append(getBuildCountsRow(todayData))
        rows.append(getBuildCountsRow(totalData) + alternate)

        let todayBuildTime = getFormattedTimeRow(todayData, property: \.buildTime, title: Strings.ROW_BUILD_TIME)
        let totalBuildTime = getFormattedTimeRow(totalData, property: \.buildTime, title: Strings.ROW_BUILD_TIME)

        let todaySuccessBuildTime = getFormattedTimeRow(todayData, property: \.successBuildTime, title: Strings.ROW_SUCCESS_BUILD_TIME)
        let totalSuccessBuildTime = getFormattedTimeRow(totalData, property: \.successBuildTime, title: Strings.ROW_SUCCESS_BUILD_TIME)

        let todayFailBuildTime = getFormattedTimeRow(todayData, property: \.failBuildTime, title: Strings.ROW_FAIL_BUILD_TIME)
        let totalFailBuildTime = getFormattedTimeRow(totalData, property: \.failBuildTime, title: Strings.ROW_FAIL_BUILD_TIME)

        let todayAverageBuildTime = getFormattedTimeRow(todayData, property: \.averageBuildTime, title: Strings.ROW_AVERAGE_BUILD_TIME)

        let todayAverageSuccessBuildTime = getFormattedTimeRow(todayData, property: \.averageSuccessBuildTime, title: Strings.ROW_SUCCESS_BUILD_TIME)
        let totalAverageSuccessBuildTime = getFormattedTimeRow(totalData, property: \.averageSuccessBuildTime, title: Strings.ROW_SUCCESS_BUILD_TIME)
        let todayAverageFailBuildTime = getFormattedTimeRow(todayData, property: \.averageFailBuildTime, title: Strings.ROW_FAIL_BUILD_TIME)
        let totalAverageFailBuildTime = getFormattedTimeRow(totalData, property: \.averageFailBuildTime, title: Strings.ROW_FAIL_BUILD_TIME)
        let totalAverageBuildTime = getFormattedTimeRow(totalData, property: \.averageBuildTime, title: Strings.ROW_AVERAGE_BUILD_TIME)

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
            rows.append(String(format: Strings.ROW_DAILY_AVERAGE_BUILD_TIME, format(dailyData.averageBuildTime), "\(dailyData.averageBuildCount)"))
            rows.append("-- " + String(format: Strings.ROW_DAILY_SUCCESS_BUILD_TIME, format(dailyData.averageSuccessBuildTime), "\(dailyData.averageSuccessCount)"))
            rows.append("-- " + String(format: Strings.ROW_DAILY_FAIL_BUILD_TIME, format(dailyData.averageFailBuildTime), "\(dailyData.averageFailCount)"))
        }

        if let lastBuild = data.lastBuild {
            rows.append("---")
            rows.append(String(format: Strings.ROW_LAST_BUILD, lastBuild.type, format(lastBuild.count)))
        }

        for inProgress in data.inProgress {
            rows.append(String(format: Strings.ROW_BUILD_IN_PROGRESS, format(inProgress)))
        }

        renderRows(rows)
    }

    private func getBuildCountsRow(_ data: BuildTimesData?) -> String {
        guard let data = data else {
            return Strings.ROW_BUILD_COUNTS_NO_BUILDS
        }
        return String(format: Strings.ROW_BUILD_COUNTS, data.buildCount, data.failCount)
    }

    private func getFormattedTimeRow(_ data: BuildTimesData?, property: KeyPath<BuildTimesData, Int>, title: String) -> String {
        guard let data = data else {
            return String(format: title, Strings.MSG_NO_BUILDS_YET)
        }
        let formatted = format(data[keyPath: property])
        return String(format: title, formatted)
    }

    private func renderFooter() {
        var rows: [String] = []
        rows.append("---")
        rows.append(Strings.ROW_REFRESH + "| refresh=true")
        rows.append(Strings.ROW_SHARE + "| bash='\(selfPath)' param1=share param2=today refresh=false terminal=false")
        rows.append(Strings.ROW_SHARE + "| bash='\(selfPath)' param1=share param2=total refresh=false terminal=false alternate=true")

        renderRows(rows)

        renderPreferences()
        renderAbout()
    }

    private func renderPreferences() {
        renderRows([Strings.ROW_SETTINGS])

        renderFilter()

        var rows: [String] = []
        rows.append("-- " + Strings.ROW_SETTINGS_RESET)
        rows.append("---- " + Strings.ROW_SETTINGS_RESET_REALLY)
        rows.append("------ " + Strings.ROW_SETTINGS_RESET_REALLY_YES + "| bash='\(selfPath)' param1=reset refresh=true terminal=false")
        renderRows(rows)
    }

    private func renderFilter() {
        let check = "✔ "
        let alternate = " alternate=true"
        let allSelected = data.selectedWorkspaces.isEmpty && data.selectedProjects.isEmpty

        var rows: [String] = []

        rows.append("-- " + Strings.ROW_SETTINGS_FILTER)
        rows.append("---- " + (allSelected ? check : "") + Strings.ROW_SETTINGS_FILTER_ALL + getActionForProjectSelection("all", name: "-", add: false))

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
        rows.append(Strings.ROW_ABOUT)
        rows.append("-- " + Strings.ROW_ABOUT_SOURCE_CODE + "| href=" + Config.ABOUT_URL)
        rows.append("-- " + Strings.ROW_ABOUT_ICON + "| href=" + Config.ICON_URL)
        rows.append("-- " + Strings.ROW_ABOUT_UPDATE)
        rows.append("---- " + Strings.ROW_ABOUT_UPDATE_REALLY)
        rows.append("------ " + Strings.ROW_ABOUT_UPDATE_REALLY_YES + "| bash='\(selfPath)' param1=update param2=showAlerts refresh=true terminal=false")

        renderRows(rows)
    }

    private func renderRows(_ rows: [String]) {
        print(rows.joined(separator: "\n"))
    }

    func format(_ seconds: Int) -> String {
        let s = seconds
        if s < 60 {
            return "\(s)s"
        } else if s < 3600 {
            let m = s / 60
            let sec = s % 60
            return "\(m)m \(sec)s"
        } else if s < 86400 {
            let h = s / 3600
            let m = (s % 3600) / 60
            return "\(h)h \(m)m"
        } else {
            let d = s / 86400
            let h = (s % 86400) / 3600
            return "\(d)d \(h)h"
        }
    }

    func getShareText(_ mode: String) -> String {
        let isToday = mode == "today"
        let shareData = isToday ? data.todayData : data.totalData
        let dailyData = data.dailyData

        var lines: [String] = []

        let selectedFilter = data.selectedWorkspaces + data.selectedProjects
        let formattedFilter = selectedFilter.map { sanitizeName($0) }.joined(separator: ", ")

        if isToday {
            let df = DateFormatter()
            df.dateFormat = Config.DATE_FORMAT
            if let tz = localTimeZone {
                df.timeZone = tz
            }
            let todayDate = df.string(from: Date())
            if selectedFilter.isEmpty {
                lines.append(String(format: Strings.SHARE_HEADER_TODAY, todayDate))
            } else {
                lines.append(String(format: Strings.SHARE_HEADER_TODAY_FILTER, formattedFilter, todayDate))
            }
        } else {
            if let totalData = data.totalData, let dataFrom = totalData.dataFrom {
                let df = DateFormatter()
                df.dateFormat = Config.DATE_FORMAT
                if let tz = localTimeZone {
                    df.timeZone = tz
                }
                let dateFromFormatted = df.string(from: dataFrom)
                if selectedFilter.isEmpty {
                    lines.append(String(format: Strings.SHARE_HEADER_TOTAL_SINCE, dateFromFormatted))
                } else {
                    lines.append(String(format: Strings.SHARE_HEADER_TOTAL_SINCE_FILTER, dateFromFormatted, formattedFilter))
                }
            } else {
                if selectedFilter.isEmpty {
                    lines.append(Strings.SHARE_HEADER_TOTAL)
                } else {
                    lines.append(String(format: Strings.SHARE_HEADER_TOTAL_FILTER, formattedFilter))
                }
            }
        }

        if let d = shareData {
            lines.append(String(format: Strings.ROW_BUILD_COUNTS, d.buildCount, d.failCount))
            lines.append(String(format: Strings.ROW_BUILD_TIME, format(d.buildTime)))
            lines.append("  " + String(format: Strings.ROW_SUCCESS_BUILD_TIME, format(d.successBuildTime)))
            lines.append("  " + String(format: Strings.ROW_FAIL_BUILD_TIME, format(d.failBuildTime)))
            lines.append(String(format: Strings.ROW_AVERAGE_BUILD_TIME, format(d.averageBuildTime)))
            lines.append("  " + String(format: Strings.ROW_SUCCESS_BUILD_TIME, format(d.averageSuccessBuildTime)))
            lines.append("  " + String(format: Strings.ROW_FAIL_BUILD_TIME, format(d.averageFailBuildTime)))
        } else {
            lines.append(Strings.ROW_BUILD_COUNTS_NO_BUILDS)
        }

        if let dailyData = dailyData {
            lines.append(String(format: Strings.ROW_DAILY_AVERAGE_BUILD_TIME, format(dailyData.averageBuildTime), "\(dailyData.averageBuildCount)"))
            lines.append("  " + String(format: Strings.ROW_DAILY_SUCCESS_BUILD_TIME, format(dailyData.averageSuccessBuildTime), "\(dailyData.averageSuccessCount)"))
            lines.append("  " + String(format: Strings.ROW_DAILY_FAIL_BUILD_TIME, format(dailyData.averageFailBuildTime), "\(dailyData.averageFailCount)"))
        }

        lines.append("")
        lines.append(String(format: Strings.SHARE_FOOTER, Config.ABOUT_URL))

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
    if let workspacePath = ProcessInfo.processInfo.environment["XcodeWorkspacePath"] {
        buildHash += workspacePath
    }
    if let projectPath = ProcessInfo.processInfo.environment["XcodeProjectPath"] {
        buildHash += projectPath
    }
    return md5(buildHash)
}

func markStart(_ startTimeFilePath: String) {
    let timestamp = "\(Int(Date().timeIntervalSince1970))"
    try? timestamp.write(toFile: startTimeFilePath, atomically: true, encoding: .utf8)
}

func markEnd(_ type: String, startTimeFilePath: String, dataFilePath: String) {
    guard let content = try? String(contentsOfFile: startTimeFilePath, encoding: .utf8) else {
        FileHandle.standardError.write("Unable to open file: \(startTimeFilePath)\n".data(using: .utf8)!)
        exit(1)
    }
    try? FileManager.default.removeItem(atPath: startTimeFilePath)

    let startTime = Int(content) ?? 0
    let duration = Int(Date().timeIntervalSince1970) - startTime

    if duration < 0 || startTime == 0 {
        FileHandle.standardError.write("File \(startTimeFilePath) has invalid format\n".data(using: .utf8)!)
        exit(1)
    }

    let workspace = ProcessInfo.processInfo.environment["XcodeWorkspace"] ?? ""
    let project = ProcessInfo.processInfo.environment["XcodeProject"] ?? ""

    var xcodeVersion = ""
    var xcodeBuild = ""

    if let developerDirectory = ProcessInfo.processInfo.environment["XcodeDeveloperDirectory"] {
        let plistPath = (developerDirectory as NSString).deletingLastPathComponent + "/version.plist"
        if let plistData = try? Data(contentsOf: URL(fileURLWithPath: plistPath)),
           let plist = try? PropertyListSerialization.propertyList(from: plistData, format: nil) as? [String: Any] {
            xcodeVersion = plist["CFBundleShortVersionString"] as? String ?? ""
            xcodeBuild = plist["ProductBuildVersion"] as? String ?? ""
        }
    }

    let isoFormatter = ISO8601DateFormatter()
    isoFormatter.formatOptions = [.withInternetDateTime]
    let dateStr = isoFormatter.string(from: Date())

    let fields = [dateStr, "\(duration)", type, workspace, project, xcodeVersion, xcodeBuild]
    let csvLine = writeCSVLine(fields)

    guard let handle = FileHandle(forWritingAtPath: dataFilePath) else {
        // File doesn't exist yet, create it
        if FileManager.default.createFile(atPath: dataFilePath, contents: (csvLine + "\n").data(using: .utf8)) {
            return
        }
        FileHandle.standardError.write("Unable to open file: \(dataFilePath)\n".data(using: .utf8)!)
        exit(1)
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
    let escapedTitle = Strings.UPDATE_ALERT_TITLE.replacingOccurrences(of: "\"", with: "\\\"")
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
    let url = URL(string: Config.UPDATE_URL)!
    var request = URLRequest(url: url)
    request.httpMethod = "GET"
    request.setValue(Config.UPDATE_USER_AGENT, forHTTPHeaderField: "User-Agent")
    request.setValue("no-cache", forHTTPHeaderField: "Cache-Control")
    request.setValue("no-cache", forHTTPHeaderField: "Pragma")

    let semaphore = DispatchSemaphore(value: 0)
    var downloadedData: Data?
    var downloadError: Error?

    let task = URLSession.shared.dataTask(with: request) { data, _, error in
        downloadedData = data
        downloadError = error
        semaphore.signal()
    }
    task.resume()
    semaphore.wait()

    guard let newData = downloadedData, downloadError == nil,
          let newContent = String(data: newData, encoding: .utf8) else {
        FileHandle.standardError.write("Unable to download update file from: \(Config.UPDATE_URL)\n".data(using: .utf8)!)
        if showAlerts { showAlert(Strings.UPDATE_ALERT_FAIL_MESSAGE) }
        exit(1)
    }

    guard let selfData = try? String(contentsOfFile: selfPath, encoding: .utf8) else {
        FileHandle.standardError.write("Unable to read self file\n".data(using: .utf8)!)
        if showAlerts { showAlert(Strings.UPDATE_ALERT_FAIL_MESSAGE) }
        exit(1)
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
        FileHandle.standardError.write("No update available.\n".data(using: .utf8)!)
        if showAlerts { showAlert(Strings.UPDATE_ALERT_NO_UPDATES_MESSAGE) }
        exit(1)
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

    let updateFile = dataDirectory + "/" + Config.UPDATE_TMP_FILE_NAME
    do {
        try finalContent.write(toFile: updateFile, atomically: true, encoding: .utf8)
    } catch {
        FileHandle.standardError.write("Unable to write update file to: \(updateFile)\n".data(using: .utf8)!)
        if showAlerts { showAlert(Strings.UPDATE_ALERT_FAIL_MESSAGE) }
        exit(1)
    }

    // Set permissions to 0755
    do {
        try FileManager.default.setAttributes([.posixPermissions: 0o755], ofItemAtPath: updateFile)
    } catch {
        FileHandle.standardError.write("Unable change permission of \(updateFile) to 0755\n".data(using: .utf8)!)
        if showAlerts { showAlert(Strings.UPDATE_ALERT_FAIL_MESSAGE) }
        exit(1)
    }

    // Atomic rename
    do {
        // Remove destination first if it exists (rename on macOS replaces atomically)
        let fm = FileManager.default
        if fm.fileExists(atPath: selfPath) {
            _ = try fm.replaceItemAt(URL(fileURLWithPath: selfPath), withItemAt: URL(fileURLWithPath: updateFile))
        } else {
            try fm.moveItem(atPath: updateFile, toPath: selfPath)
        }
    } catch {
        FileHandle.standardError.write("Unable to rename \(updateFile) to \(selfPath)\n".data(using: .utf8)!)
        if showAlerts { showAlert(Strings.UPDATE_ALERT_FAIL_MESSAGE) }
        exit(1)
    }

    print("Update successful")
    if showAlerts { showAlert(Strings.UPDATE_ALERT_SUCCESS_MESSAGE) }
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
    let dir = (pattern as NSString).deletingLastPathComponent
    let prefix = (pattern as NSString).lastPathComponent.replacingOccurrences(of: ".*", with: ".")

    guard let contents = try? FileManager.default.contentsOfDirectory(atPath: dir) else {
        return []
    }

    return contents
        .filter { $0.hasPrefix(prefix) }
        .map { dir + "/" + $0 }
        .sorted()
}

// MARK: - Main

let selfPath: String = {
    // When running as compiled binary or swift script, we need to find ourselves
    // First check if there's a compiled binary path, then fall back to __FILE__ equivalent
    let args = CommandLine.arguments
    if !args.isEmpty {
        let arg0 = args[0]
        // If running via `swift`, args[0] is the swift path; the script path might be in args
        // If running as compiled binary, args[0] is the binary path
        if arg0.hasSuffix(".swift") || arg0.contains("xcodeBuildTimes") {
            return (arg0 as NSString).standardizingPath
        }
    }
    // For Swift scripts run via `swift script.swift`, we use #file equivalent
    // Since #file is compile-time, we need to figure out the actual script location
    return (CommandLine.arguments.first { $0.hasSuffix(".swift") } ?? CommandLine.arguments[0]) as String
}()

let scriptDirectory = (selfPath as NSString).deletingLastPathComponent
let dataDirectory = scriptDirectory + "/" + Config.DATA_FILE_DIR
let dataFilePath = dataDirectory + "/" + Config.DATA_FILE_NAME
let configFilePath = dataDirectory + "/" + Config.CONFIG_FILE_NAME
let startTimeFilePathWithoutHash = dataDirectory + "/" + Config.START_TIME_FILE_NAME
let startTimeFilesPattern = startTimeFilePathWithoutHash + ".*"
let buildHash = getBuildHash()
let startTimeFilePath = startTimeFilePathWithoutHash + "." + buildHash

// Create data directory if needed
let fm = FileManager.default
if !fm.fileExists(atPath: dataDirectory) {
    try? fm.createDirectory(atPath: dataDirectory, withIntermediateDirectories: true)
}

let env = ProcessInfo.processInfo.environment
let idAlertMessage = env["IDEAlertMessage"]
let args = CommandLine.arguments
let arg: String? = args.count > 1 ? args[1] : nil

if idAlertMessage == "Build Started" || arg == "start" {
    markStart(startTimeFilePath)
    exit(0)
} else if idAlertMessage == "Build Succeeded" || arg == "success" {
    markEnd("success", startTimeFilePath: startTimeFilePath, dataFilePath: dataFilePath)
    exit(0)
} else if idAlertMessage == "Build Failed" || arg == "fail" {
    markEnd("fail", startTimeFilePath: startTimeFilePath, dataFilePath: dataFilePath)
    exit(0)
} else if arg == "reset" {
    try? fm.removeItem(atPath: dataFilePath)
    exit(0)
} else if arg == "update" {
    let showAlerts = args.count > 2 && args[2] == "showAlerts"
    update(dataDirectory, showAlerts: showAlerts, selfPath: selfPath)
    // update calls exit() internally
} else if arg == "config" {
    processConfigChange(Array(args), configFilePath: configFilePath)
    exit(0)
} else if arg == "share" {
    let mode = args.count > 2 ? args[2] : "today"
    let config = BuildTimesConfig(configFile: configFilePath)
    let parser = BuildTimesFileParser(dataFile: dataFilePath, startTimeFilesPattern: startTimeFilesPattern, config: config)
    let data = parser.getOutput()
    let renderer = BitBarRenderer(data: data, config: config, selfPath: selfPath)
    let text = renderer.getShareText(mode)
    let pipe = Process()
    pipe.executableURL = URL(fileURLWithPath: "/usr/bin/pbcopy")
    pipe.standardInput = Pipe()
    let inputPipe = pipe.standardInput as! Pipe
    try? pipe.run()
    inputPipe.fileHandleForWriting.write(text.data(using: .utf8)!)
    inputPipe.fileHandleForWriting.closeFile()
    pipe.waitUntilExit()
    showAlert("Copied to clipboard:\n\n" + text)
    exit(0)
} else if arg == "configure" {
    print("Swift version does not need shebang configuration.")
    exit(0)
}

// Default: render
let config = BuildTimesConfig(configFile: configFilePath)
let parser = BuildTimesFileParser(dataFile: dataFilePath, startTimeFilesPattern: startTimeFilesPattern, config: config)
let data = parser.getOutput()
let renderer = BitBarRenderer(data: data, config: config, selfPath: selfPath)
renderer.render()
