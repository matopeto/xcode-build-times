#!/usr/bin/env php
<?php

# <bitbar.title>Xcode build times</bitbar.title>
# <bitbar.version>v1.0</bitbar.version>
# <bitbar.author>Mato Peto</bitbar.author>
# <bitbar.author.github>matopeto</bitbar.author.github>
# <bitbar.desc>Shows today's and total time you spend waiting to Xcode finish building.</bitbar.desc>
# <bitbar.image>https://raw.githubusercontent.com/matopeto/xcode-build-times/master/screenshots/menubar-extended.png</bitbar.image>
# <bitbar.dependencies>php,xcode</bitbar.dependencies>
# <bitbar.abouturl>https://github.com/matopeto/xcode-build-times</bitbar.abouturl>
# <swiftbar.hideAbout>true</swiftbar.hideAbout>
# <swiftbar.hideRunInTerminal>true</swiftbar.hideRunInTerminal>
# <swiftbar.hideLastUpdated>true</swiftbar.hideLastUpdated>
# <swiftbar.hideDisablePlugin>true</swiftbar.hideDisablePlugin>
# <swiftbar.hideSwiftBar>true</swiftbar.hideSwiftBar>
# <swiftbar.refreshOnOpen>true</swiftbar.refreshOnOpen>

const MINIMUM_PHP_VERSION = "8.3";
if (version_compare(PHP_VERSION, MINIMUM_PHP_VERSION, "<")) {
    error_log("Unable to run on PHP " . PHP_VERSION . ", " . MINIMUM_PHP_VERSION . " or newer is needed");
    exit(1);
}

final class Config
{
    const ABOUT_URL = "https://github.com/matopeto/xcode-build-times";
    const SWIFTBAR_URL = "https://github.com/swiftbar/SwiftBar";

    const UPDATE_URL = "https://raw.githubusercontent.com/matopeto/xcode-build-times/master/sources/xcodeBuildTimes.1m.php";
    const UPDATE_USER_AGENT = "matopeto/xcode-build-times";

    const DATE_FORMAT = "Y-m-d";

    const CLIPBOARD_LOCALE = "en_US.UTF-8"; // Only picked for its encoding, pbcopy reads it from the locale

    const DISTRIBUTION_FONT = "Menlo"; // Must be monospaced, the bars are aligned with spaces
    // Parts a bar is drawn from, ordered from the whole one down to the smallest
    const DISTRIBUTION_BAR = ["█", "▉", "▊", "▋", "▌", "▍", "▎", "▏"];
    const DISTRIBUTION_SHARE_BAR = ["="]; // Plain ASCII, the shared text lands in fonts and encodings we do not control
    const DISTRIBUTION_BAR_WIDTH = 16; // Characters the longest bar is drawn in
    const DISTRIBUTION_BUCKETS = 10;
    const DISTRIBUTION_LABEL_TOLERANCE = 0.1; // How far a rounded bucket label may sit from the real bound

    const DATA_FILE_DIR = ".xcodeBuildTimes"; // Must be hidden (start with ".")
    const DATA_FILE_NAME = "buildTimes.csv";
    const START_TIME_FILE_NAME = "buildStartTime";
    const UPDATE_TMP_FILE_NAME = ".newVersion";
    const CONFIG_FILE_NAME = "config.json";
    const MACHINE_FILE_NAME = "machine.json";
}

final class Strings
{
    const WARNING_UNABLE_TO_READ_DATA_FILE = "Unable to read data file. that's ok as long as no build has been done yet";
    const WARNING_PROBLEM_WITH_DATA_FILE = "There is some problem with data";
    const WARNING_NO_DATA = "No data";
    const WARNING_OLD_SWIFTBAR_VERSION = "Please use the latest SwiftBar for proper functionality";
    const ROW_WARNING = "⚠️ %s"; // %s will be replaced by warning message

    const ROW_MIGRATION_NOTICE = "New data format — action needed"; // rendered via ROW_WARNING
    const ROW_MIGRATION_FILL = "Mark all older builds as built on this Mac"; // confirm dialog names the exact Mac
    const ROW_MIGRATION_IGNORE = "Keep older builds without a Mac (won't show under this Mac)";

    const ROW_HEADER_TODAY = "Today";
    const ROW_HEADER_TODAY_FILTER = "Today (%s)"; // %s will be replaced with selected workspaces and projects
    const ROW_HEADER_TOTAL = "Total";
    const ROW_HEADER_TOTAL_FILTER = "Total (%s)"; // %s will be replaced with selected workspaces and projects
    const ROW_HEADER_TOTAL_SINCE = "Total, since: %s"; // %s will be replaced with first date of data
    const ROW_HEADER_TOTAL_SINCE_FILTER = 'Total, since: %1$s (%2$s)'; // %1$s will be replaced with first date of data, 2$s will be replaced with selected workspaces and projects

    const ROW_BUILD_COUNTS = 'Builds %1$d, %3$d failed'; // %1$d - total, %2$d - succeeded, %3$d - failed
    const ROW_BUILD_COUNTS_NO_BUILDS = 'Builds: no builds yet';
    const ROW_BUILD_TIME = 'Build time: %s'; // %s will be replaced with corresponding build time
    const ROW_AVERAGE_BUILD_TIME = 'Average build time: %s'; // %s will be replaced with corresponding build time
    const ROW_MEDIAN_BUILD_TIME = 'Median build time: %s'; // %s will be replaced with corresponding build time

    const ROW_DISTRIBUTION = "Distribution";
    // Both take the same arguments: %1$s - bucket label, %2$s - bar, %3$s - number of builds, %4$s - share of all builds
    const ROW_DISTRIBUTION_BUCKET = '%1$s %2$s %3$s %4$s%%';
    const SHARE_DISTRIBUTION_BUCKET = '%2$s %1$s: %4$s%%, %3$s builds';
    const ROW_DISTRIBUTION_RANGE = '%1$s-%2$s'; // %1$s - duration the bucket starts at, %2$s - the one it ends at
    const ROW_DISTRIBUTION_RANGE_OPEN = '%s+'; // %s - duration the last bucket starts at, it has no end

    const ROW_SUCCESS_BUILD_TIME = 'Success: %s'; // %s will be replaced with corresponding build time
    const ROW_FAIL_BUILD_TIME = 'Fail: %s'; // %s will be replaced with corresponding build time

    const ROW_DAILY_AVERAGE_BUILD_TIME = 'Daily average: %1$s, %2$s builds'; // // %1$s will be replaced with corresponding build time, %1$s with build counts
    const ROW_DAILY_MEDIAN_BUILD_TIME = 'Daily median: %1$s, %2$s builds'; // %1$s will be replaced with corresponding build time, %2$s with build counts
    const ROW_DAILY_SUCCESS_BUILD_TIME = 'Success: %1$s, %2$s builds'; // %1$s will be replaced with corresponding build time, %1$s with build counts
    const ROW_DAILY_FAIL_BUILD_TIME = 'Fail: %1$s, %2$s builds'; // %1$s will be replaced with corresponding build time, %1$s with build counts

    const ROW_LAST_BUILD = 'Last build: %1$s, %2$s'; // %1$d will be replaced with status (success/fail) %2$d with duration
    const ROW_BUILD_IN_PROGRESS = 'Build in progress: %s'; // %s will be replaced with current duration of build in progress

    // How a duration is written out, in DateInterval format, the WHOLE ones for nothing left over
    const FORMAT_SECONDS = '%ss';
    const FORMAT_MINUTES = '%im %ss';
    const FORMAT_MINUTES_WHOLE = '%im';
    const FORMAT_HOURS = '%hh %im';
    const FORMAT_HOURS_WHOLE = '%hh';
    const FORMAT_DAYS = '%ad %hh';
    const FORMAT_DAYS_WHOLE = '%ad';

    const ROW_REFRESH = "Refresh";
    const ROW_SHARE = "Share";

    const SHARE_HEADER_TODAY = 'Xcode Build Times - Today, %s'; // %s - current date
    const SHARE_HEADER_TODAY_FILTER = 'Xcode Build Times - Today (%1$s), %2$s'; // %1$s - filter, %2$s - current date
    const SHARE_HEADER_TOTAL_SINCE = 'Xcode Build Times - Total, since: %s'; // %s - first date
    const SHARE_HEADER_TOTAL_SINCE_FILTER = 'Xcode Build Times - Total, since: %1$s (%2$s)'; // %1$s - first date, %2$s - filter
    const SHARE_HEADER_TOTAL = 'Xcode Build Times - Total';
    const SHARE_HEADER_TOTAL_FILTER = 'Xcode Build Times - Total (%s)'; // %s - filter
    const SHARE_FOOTER = 'Tracked with Xcode Build Times - %s'; // %s - ABOUT_URL
    const SHARE_COPIED_SUCCESS = "Copied to clipboard:\n\n%s"; // %s - shared text

    const ROW_SETTINGS = "Settings";
    const ROW_SETTINGS_FILTER = "Filter";
    const ROW_SETTINGS_FILTER_ALL = "Show all";
    const ROW_SETTINGS_FILTER_WORKSPACES_PROJECTS = "Project / Workspace";
    const ROW_SETTINGS_FILTER_MACHINE = "Machine";
    const ROW_SETTINGS_FILTER_MACHINE_CURRENT = "(this Mac)";
    const ROW_SETTINGS_FILTER_MACHINE_UNKNOWN = "(unknown)";
    const ROW_SETTINGS_STATISTICS = "Statistics";
    const ROW_SETTINGS_STATISTICS_AVERAGE = "Average";
    const ROW_SETTINGS_STATISTICS_MEDIAN = "Median";
    const ROW_SETTINGS_STATISTICS_DISTRIBUTION = "Distribution";
    const ROW_SETTINGS_STATISTICS_ON = "On";
    const ROW_SETTINGS_STATISTICS_OFF = "Off";
    const ROW_SETTINGS_DATA = "Data";
    const ROW_SETTINGS_DATA_EXPORT = "Export";
    const ROW_SETTINGS_DATA_IMPORT = "Import";
    const ROW_SETTINGS_DATA_OPEN_LOCATION = "Open Data Location";
    const ROW_SETTINGS_DATA_FILL_MACHINE = "Assign builds without a machine to this Mac";
    const ROW_SETTINGS_RESET = "Reset";
    const ROW_SETTINGS_RESET_REALLY = "Really?";
    const ROW_SETTINGS_RESET_REALLY_YES = "Yes";

    const MIGRATION_FILL_CONFIRM = "Mark all builds that aren't linked to a Mac as built on this Mac:\n\n%s\n\nThis can't be undone."; // %s will be replaced with this Mac's spec
    const MIGRATION_FILL_SUCCESS = "Assigned %d builds to this Mac."; // %d will be replaced with the number of builds updated
    const MIGRATION_FILL_NO_MACHINE = "Couldn't detect this Mac. Please try again.";
    const MIGRATION_FAIL = "Couldn't update the data file.";

    const BUTTON_CANCEL = "Cancel";
    const BUTTON_OK = "OK";
    const BUTTON_MERGE = "Merge";
    const BUTTON_REPLACE = "Replace";

    const IMPORT_ALERT_CONFIRM = "Merge adds the imported builds to your current history; Replace overwrites it. Either way, your current data is backed up first.";
    const IMPORT_ALERT_SUCCESS = "Data imported successfully.";
    const IMPORT_MERGE_SUCCESS = 'Imported %1$d builds; skipped %2$d duplicates already in your history.'; // %1$d - added, %2$d - skipped
    const IMPORT_ALERT_FAIL = "Unable to import data.\n\n%s"; // %s will be replaced with error message
    const IMPORT_ALERT_INVALID = "Selected file is not a valid build times CSV.";
    const IMPORT_DIALOG_PROMPT = "Import Build Times Data";
    const EXPORT_ALERT_SUCCESS = "Data exported successfully.";
    const EXPORT_ALERT_FAIL = "Unable to export data.\n\n%s"; // %s will be replaced with error message
    const EXPORT_ALERT_NO_DATA = "No data file to export.";
    const EXPORT_DIALOG_PROMPT = "Export Build Times Data";


    const ROW_ABOUT = "About";
    const ROW_ABOUT_SOURCE_CODE = "Source Code & Info";
    const ROW_ABOUT_UPDATE = "Update";
    const ROW_ABOUT_UPDATE_REALLY = "Really?";
    const ROW_ABOUT_UPDATE_REALLY_YES = "Yes";

    const MSG_NO_BUILDS_YET = "no builds yet";

    const UPDATE_ALERT_TITLE = "Xcode build times";
    const UPDATE_ALERT_SUCCESS_MESSAGE = "Plugin was updated successfully.";
    const UPDATE_ALERT_FAIL_MESSAGE = "Unable to update.";
    const UPDATE_ALERT_NO_UPDATES_MESSAGE = "No updates available.";
}

final class Columns
{
    const DATE = 0;
    const DURATION = 1;
    const TYPE = 2;
    const WORKSPACE = 3;
    const PROJECT = 4;
    const XCODE_VERSION = 5;
    const XCODE_BUILD = 6;
    const MACHINE_ID = 7;
    const MACHINE_SPEC = 8;
    const COUNT = 9; // total number of columns, not an index
}

$buildHash = getBuildHash();

$scriptDirectory = realpath(__DIR__);
$dataDirectory = $scriptDirectory . DIRECTORY_SEPARATOR . Config::DATA_FILE_DIR;
$dataFilePath = $dataDirectory . DIRECTORY_SEPARATOR . Config::DATA_FILE_NAME;
$configFilePath = $dataDirectory . DIRECTORY_SEPARATOR . Config::CONFIG_FILE_NAME;
$machineFilePath = $dataDirectory . DIRECTORY_SEPARATOR . Config::MACHINE_FILE_NAME;
$startTimeFilePathWithoutHash = $dataDirectory . DIRECTORY_SEPARATOR . Config::START_TIME_FILE_NAME;
$startTimeFilesPattern = $startTimeFilePathWithoutHash . "\.*";
$startTimeFilePath = $startTimeFilePathWithoutHash . "." . $buildHash;

if (!file_exists($dataDirectory)) {
    if (!@mkdir($dataDirectory)) {
        error_log("Unable to create data directory: $dataDirectory");
        exit(1);
    }
}

$machine = new Machine($machineFilePath);

$idAlertMessage = getenv("IDEAlertMessage");
$arg = $argc > 1 ? $argv[1] : null;

if ($idAlertMessage === "Build Started" || $arg === "start") {
    markStart($startTimeFilePath);
    die;
} elseif ($idAlertMessage === "Build Succeeded" || $arg === "success") {
    markEnd("success", $startTimeFilePath, $dataFilePath, $machine);
    die;
} elseif ($idAlertMessage === "Build Failed" || $arg === "fail") {
    markEnd("fail", $startTimeFilePath, $dataFilePath, $machine);
    die;
} elseif ($arg === "reset") {
    unlink($dataFilePath);
    die;
} elseif ($arg === "update") {
    $showAlerts = $argc > 2 && $argv[2] == "showAlerts";
    update($dataDirectory, $showAlerts);
    die;
} elseif ($arg === "config") {
    processConfigChange($argv, $configFilePath, $machine);
    die;
} elseif ($arg === "migrateMachine") {
    $mode = $argc > 2 ? $argv[2] : "";
    $parser = new BuildTimesFileParser($dataFilePath);
    if ($mode === "fill") {
        list($machineId, $machineSpec) = $machine->forBuild();
        if ($machineId === "" || $machineSpec === "") {
            showAlert(Strings::MIGRATION_FILL_NO_MACHINE);
        } else {
            $confirmMessage = sprintf(Strings::MIGRATION_FILL_CONFIRM, $machineSpec);
            $confirmed = confirmDialog($confirmMessage, [Strings::BUTTON_CANCEL, Strings::BUTTON_OK], Strings::BUTTON_CANCEL);
            if ($confirmed === Strings::BUTTON_OK) {
                $count = $parser->fillEmptyMachineOrSpec($machineId, $machineSpec);
                showAlert($count === false ? Strings::MIGRATION_FAIL : sprintf(Strings::MIGRATION_FILL_SUCCESS, $count));
            }
        }
    } elseif ($mode === "ignore") {
        if ($parser->keepBuildsWithoutMachine() === false) {
            showAlert(Strings::MIGRATION_FAIL);
        }
    }
    die;
} elseif ($arg === "share") {
    $mode = $argc > 2 ? $argv[2] : "today";
    $config = new BuildTimesConfig($configFilePath, $machine);
    $parser = new BuildTimesFileParser($dataFilePath);
    $data = $parser->getOutput($config, $startTimeFilesPattern);
    $renderer = new BitBarRenderer($data, $config);
    $text = $renderer->getShareText($mode);
    // SwiftBar runs the plugin without any locale set, and pbcopy then falls back to an encoding
    // that mangles everything outside ASCII, such as a workspace or a machine named in one
    $pipe = popen("LC_CTYPE=" . Config::CLIPBOARD_LOCALE . " pbcopy", "w");
    if ($pipe !== false) {
        fwrite($pipe, $text);
        pclose($pipe);
    }
    showAlert(sprintf(Strings::SHARE_COPIED_SUCCESS, $text));
    die;
} elseif ($arg === "export") {
    if (!file_exists($dataFilePath)) {
        showAlert(Strings::EXPORT_ALERT_NO_DATA);
        die;
    }
    $script = 'POSIX path of (choose file name with prompt "' . escapeAppleScript(Strings::EXPORT_DIALOG_PROMPT) . '" default name "buildTimes.csv")';
    $output = @trim(shell_exec("osascript -e " . escapeshellarg($script)) ?? "");
    if ($output !== "") {
        $copyError = copyFile($dataFilePath, $output);
        if ($copyError === null) {
            showAlert(Strings::EXPORT_ALERT_SUCCESS);
        } else {
            showAlert(sprintf(Strings::EXPORT_ALERT_FAIL, $copyError));
        }
    }
    die;
} elseif ($arg === "import") {
    $script = 'POSIX path of (choose file of type {"public.comma-separated-values-text"} with prompt "' . escapeAppleScript(Strings::IMPORT_DIALOG_PROMPT) . '")';
    $selectedFile = @trim(shell_exec("osascript -e " . escapeshellarg($script)) ?? "");
    if ($selectedFile === "" || !file_exists($selectedFile)) {
        die;
    }
    if (!BuildTimesFileParser::isValidFile($selectedFile)) {
        showAlert(Strings::IMPORT_ALERT_INVALID);
        die;
    }
    $choice = confirmDialog(Strings::IMPORT_ALERT_CONFIRM, [Strings::BUTTON_CANCEL, Strings::BUTTON_REPLACE, Strings::BUTTON_MERGE], Strings::BUTTON_MERGE);
    if ($choice !== Strings::BUTTON_MERGE && $choice !== Strings::BUTTON_REPLACE) {
        die;
    }
    $merge = $choice === Strings::BUTTON_MERGE;

    backupDataFile($dataFilePath); // safety net before mutating, kept alongside the data

    if ($merge) {
        $stats = (new BuildTimesFileParser($dataFilePath))->mergeFrom($selectedFile);
        $message = $stats === false
            ? trim(sprintf(Strings::IMPORT_ALERT_FAIL, ""))
            : sprintf(Strings::IMPORT_MERGE_SUCCESS, $stats["imported"], $stats["duplicates"]);
    } else {
        $reason = copyFile($selectedFile, $dataFilePath);
        $message = $reason !== null
            ? trim(sprintf(Strings::IMPORT_ALERT_FAIL, $reason))
            : Strings::IMPORT_ALERT_SUCCESS;
    }
    showAlert($message);
    die;
} elseif ($arg === "openDataLocation") {
    exec("open " . escapeshellarg($dataDirectory));
    die;
} elseif ($arg === "configure") {
    echo "Running `which php`\n";
    $phpPath = exec("which php");
    if ($phpPath === false || $phpPath === "") {
        $path = getenv("PATH");
        error_log("Unable to find path to PHP executable. It is in your PATH?\nPATH: $path");
        exit(1);
    }

    echo "Found PHP executable: $phpPath\n";
    echo "Updating Shebang\n";
    $file = @file_get_contents(__FILE__);
    if ($file === false) {
        error_log("Unable to update Shebang");
        exit(1);
    }
    $file = preg_replace("/^#!.*?\n/", "#!$phpPath\n", $file, 1);
    if (file_put_contents(__FILE__, $file) === false) {
        error_log("Unable to update Shebang");
        exit(1);
    }

    echo "Shebang updated. Plugin is configured.";
    die;
}

$config = new BuildTimesConfig($configFilePath, $machine);
$parser = new BuildTimesFileParser($dataFilePath);
$data = $parser->getOutput($config, $startTimeFilesPattern);
$renderer = new BitBarRenderer($data, $config);
$renderer->render();

final class BuildTimesFileParser
{
    /** @var string */
    private $dataFile;

    /**
     * BuildTimesFileParser constructor.
     * @param string $dataFile
     */
    public function __construct($dataFile)
    {
        $this->dataFile = $dataFile;
    }

    /**
     * Returns output data parsed from file
     * @param BuildTimesConfig $config
     * @param string $startTimeFilesPattern
     * @return BuildTimesOutput
     */
    public function getOutput($config, $startTimeFilesPattern)
    {
        $result = new BuildTimesOutput();

        $swiftBarVersion = getenv("SWIFTBAR_VERSION");
        if ($swiftBarVersion !== false && version_compare($swiftBarVersion, "1.4.3", "<")) {
            $result->warnings[] = Strings::WARNING_OLD_SWIFTBAR_VERSION . "| href=" . Config::SWIFTBAR_URL;
        }

        /**
         * @var DataRow[][] $rows
         * @var null|boolean $status null when the file can't be read, otherwise true if some rows are invalid
         * @var string[] $workspaces
         * @var string[] $projects
         * @var string[] $machines
         * @var boolean $hasBuildsWithoutMachine
         */
        list($rows, $status, $workspaces, $projects, $machines, $hasBuildsWithoutMachine) = $this->parseFile($config);

        if ($status === null) {
            $result->warnings[] = Strings::WARNING_UNABLE_TO_READ_DATA_FILE;
        } else {
            if ($status === true) {
                $result->warnings[] = Strings::WARNING_PROBLEM_WITH_DATA_FILE;
            }
            $result->hasBuildsWithoutMachine = $hasBuildsWithoutMachine;

            $workspaces = array_unique(
                array_filter(
                    array_merge(
                        $config->selectedWorkspaces,
                        $workspaces
                    ),
                    function ($item) {
                        return $item !== null && !empty($item);
                    }
                )
            );
            sort($workspaces);
            $result->workspaces = $workspaces;

            $projects = array_unique(
                array_filter(
                    array_merge(
                        $config->selectedProjects,
                        $projects
                    ),
                    function ($item) {
                        return $item !== null && !empty($item);
                    }
                )
            );
            sort($projects);
            $result->projects = $projects;

            // A selected machine may have no rows in the data (e.g. after import); keep it listed
            // so it can be unchecked, labelled by its id. "@current"/"" are sentinels, not machines.
            foreach ($config->selectedMachines as $machineId) {
                if ($machineId !== "@current" && $machineId !== "" && !isset($machines[$machineId])) {
                    $machines[$machineId] = $machineId;
                }
            }
            // Sort by spec, case insensitive, lowercase before uppercase on ties.
            uasort($machines, function ($a, $b) {
                $insensitive = strcasecmp($a, $b);
                return $insensitive !== 0 ? $insensitive : strcmp($b, $a);
            });
            $result->machines = $machines;

            /** @var DataRow[] $allRows */
            $allRows = array_reduce($rows, function ($all, $item) {
                return array_merge($all, $item);
            }, []);

            usort($allRows, function ($a, $b) {
                if ($a->date === $b->date) {
                    return 0;
                }
                return ($a->date < $b->date) ? -1 : 1;
            });

            if (empty($allRows)) {
                $result->warnings[] = Strings::WARNING_NO_DATA;
            } else {
                $result->totalData = $this->getData($allRows, true, $config->showDistribution);
                $result->lastBuild = end($allRows);
            }

            // Today rows
            // Get today
            /** @var string|null $todayKey */
            $todayKey = null;
            try {
                $todayKey = (new DateTime("now", $config->localTimeZone))->format("Y-m-d");
            } catch (Exception $ex) {
            }

            if ($todayKey !== null && key_exists($todayKey, $rows)) {
                // Today data
                $todayRows = $rows[$todayKey];
                $result->todayData = $this->getData($todayRows, true, $config->showDistribution);
            }

            // Daily data
            /** @var BuildTimesValues[] $dayTimes */
            $dayTimes = [];
            /** @var BuildTimesValues[] $dayCounts */
            $dayCounts = [];

            foreach ($rows as $key => $data) {
                // Count only past days
                if ($key >= $todayKey) {
                    continue;
                }

                // Only the day's totals are needed, the daily medians are taken across the days below
                $dayData = $this->getData($data, false);
                $dayTimes[] = $dayData->time;
                $dayCounts[] = $dayData->counts;
            }

            $days = count($dayTimes);
            if ($days > 0) {
                $average = function ($values) use ($days) {
                    return intval(array_sum($values) / $days);
                };
                $median = function ($values) {
                    return self::median(array_count_values($values));
                };

                $dailyData = new DailyTimesData();
                $dailyData->days = $days;
                $dailyData->averageTime = self::reduceValues($dayTimes, $average);
                $dailyData->averageCount = self::reduceValues($dayCounts, $average);
                $dailyData->medianTime = self::reduceValues($dayTimes, $median);
                $dailyData->medianCount = self::reduceValues($dayCounts, $median);

                $result->dailyData = $dailyData;
            }
        }

        $result->selectedWorkspaces = $config->selectedWorkspaces;
        $result->selectedProjects = $config->selectedProjects;
        $result->selectedMachines = $config->selectedMachines;
        $result->selectedStatistics = $config->selectedStatistics;
        $result->showDistribution = $config->showDistribution;
        $result->currentMachineId = $config->currentMachineId;
        $result->currentMachineSpec = $config->currentMachineSpec;

        // Determine if some builds are in progress
        $files = glob($startTimeFilesPattern);
        foreach ($files as $file) {
            $duration = $this->getProgressDuration($file);
            if ($duration > 0 && $duration < 86400) { // We skip very long durations, probably old not removed start files.
                $result->inProgress[] = $duration;
            } elseif ($duration >= 86400) {
                @unlink($file);
            }
        }

        return $result;
    }

    /**
     * Validates that the given file is a valid build times CSV.
     * Returns true if all rows are valid and the file is not empty.
     *
     * @param string $filePath Path to the CSV file to validate
     * @return bool true if the file is a valid build times CSV, false otherwise
     */
    public static function isValidFile($filePath)
    {
        $handle = @fopen($filePath, "r");
        if ($handle === false) {
            return false;
        }

        $hasRows = false;
        while (($row = fgetcsv($handle, 1000, ",", "\"", "")) !== false) {
            if (self::parseRow($row) === false) {
                fclose($handle);
                return false;
            }
            $hasRows = true;
        }

        fclose($handle);
        return $hasRows;
    }

    /**
     * Parses and validates a single CSV row into a DataRow.
     * Supports both old format (without time zone) and new format (ISO 8601 with time zone).
     *
     * @param string[] $row CSV row as an array of strings
     * @return DataRow|false Parsed DataRow on success, false on invalid row
     */
    private static function parseRow($row)
    {
        if (count($row) <= Columns::TYPE) {
            return false;
        }
        if (!preg_match('~[0-9]+~', $row[Columns::DURATION])) {
            return false;
        }
        if (!preg_match('~fail|success~', $row[Columns::TYPE])) {
            return false;
        }
        if (preg_match('~[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}~', $row[Columns::DATE])) {
            // Old format (without time zone)
            $date = DateTime::createFromFormat("Y-m-d H:i:s", $row[Columns::DATE]);
            if ($date == false) {
                return false;
            }
        } else {
            // New format (with time zone)
            try {
                $date = new DateTime($row[Columns::DATE]);
            } catch (Exception $e) {
                return false;
            }
        }

        $dataRow = new DataRow();
        $dataRow->date = $date;
        $dataRow->count = intval($row[Columns::DURATION]);
        $dataRow->type = $row[Columns::TYPE];
        if (count($row) > Columns::PROJECT) {
            $dataRow->workspace = $row[Columns::WORKSPACE];
            $dataRow->project = $row[Columns::PROJECT];
        }
        // Machine columns are optional; read whichever are present (a row may carry the id but not the spec).
        if (count($row) > Columns::MACHINE_ID) {
            $dataRow->machineId = $row[Columns::MACHINE_ID];
        }
        if (count($row) > Columns::MACHINE_SPEC) {
            $dataRow->machineSpec = $row[Columns::MACHINE_SPEC];
        }
        return $dataRow;
    }

    /**
     * Reads the data file and groups the selected rows by day, collecting the available
     * workspaces, projects and machines along the way.
     *
     * @param BuildTimesConfig $config
     * @return array - [rows, status, workspaces, projects, machines, hasBuildsWithoutMachine], where status is
     *                 null when the file can't be read, otherwise true if some rows are invalid
     */
    private function parseFile($config)
    {
        $workspaces = [];
        $projects = [];
        $machines = [];
        $rows = [];
        $hasBuildsWithoutMachine = false;
        $localTimeZone = $config->localTimeZone;

        $dataRows = $this->readRows();
        foreach ($dataRows as $dataRow) {
            if ($dataRow->workspace !== null) {
                $workspaces[$dataRow->workspace] = true;
            }
            if ($dataRow->project !== null) {
                $projects[$dataRow->project] = true;
            }
            if ($dataRow->machineId !== null && $dataRow->machineId !== "") {
                $machines[$dataRow->machineId] = $dataRow->machineSpec !== null && $dataRow->machineSpec !== ""
                    ? $dataRow->machineSpec
                    : $dataRow->machineId;
            } else {
                $machines[""] = "";
                if ($dataRow->machineId === null) {
                    $hasBuildsWithoutMachine = true;
                }
            }

            $keyDate = clone $dataRow->date;
            if ($localTimeZone !== null) {
                $keyDate->setTimezone($localTimeZone);
            }
            $key = $keyDate->format("Y-m-d");

            if ($this->isSelected($dataRow, $config)) {
                $rows[$key][] = $dataRow;
            }
        }

        return [$rows, $dataRows->getReturn(), array_keys($workspaces), array_keys($projects), $machines, $hasBuildsWithoutMachine];
    }

    /**
     * Streams the parsed rows of the data file one at a time. Invalid rows are skipped; the
     * generator's return value is null when the file can't be read, otherwise true if some
     * rows were invalid.
     *
     * @return Generator
     */
    private function readRows()
    {
        $handle = @fopen($this->dataFile, "r");
        if ($handle === false) {
            return null;
        }

        $problem = false;
        while (($row = fgetcsv($handle, 1000, ",", "\"", "")) !== false) {
            $dataRow = self::parseRow($row);
            if ($dataRow === false) {
                $problem = true;
                continue;
            }
            yield $dataRow;
        }
        fclose($handle);

        return $problem;
    }

    /**
     * Appends a build row to the data file.
     *
     * @param string[] $row CSV row to append
     */
    public function appendBuild($row)
    {
        $handle = @fopen($this->dataFile, "a");
        if ($handle === false) {
            error_log("Unable to open file: $this->dataFile");
            exit(1);
        }

        fputcsv($handle, $row, ",", "\"", "");
        fclose($handle);
    }

    /**
     * Attributes machine-less rows to the given machine, and completes that machine's own missing
     * spec; rows of other machines and already complete rows are left untouched. Returns the number
     * of rows changed.
     *
     * @param string $machineId
     * @param string $machineSpec
     * @return int|false Number of rows changed, or false if the rewrite failed
     */
    public function fillEmptyMachineOrSpec($machineId, $machineSpec)
    {
        $changed = 0;
        $ok = $this->rewrite(function ($row) use ($machineId, $machineSpec, &$changed) {
            $dataRow = self::parseRow($row);
            if ($dataRow === false) {
                return $row;
            }
            // Assign only rows with no id (an existing id is never overwritten); also fill this Mac's own missing spec.
            $rowId = $dataRow->machineId;
            $missingOwnSpec = $rowId === $machineId && ($dataRow->machineSpec === null || $dataRow->machineSpec === "");
            if ($rowId === null || $rowId === "" || $missingOwnSpec) {
                $changed++;
                return $this->withMachine($row, $machineId, $machineSpec);
            }
            return $row;
        });
        return $ok ? $changed : false;
    }

    /**
     * Upgrades rows missing the machine columns to the new format by padding them with empty
     * machine columns; existing values, including any present id, are preserved.
     *
     * @return int|false Number of rows changed, or false if the rewrite failed
     */
    public function keepBuildsWithoutMachine()
    {
        $changed = 0;
        $ok = $this->rewrite(function ($row) use (&$changed) {
            if (self::parseRow($row) !== false && count($row) < Columns::COUNT) {
                $changed++;
                return array_pad($row, Columns::COUNT, "");
            }
            return $row;
        });
        return $ok ? $changed : false;
    }

    /**
     * Rewrites the data file row by row through $transform, atomically (temp file + rename).
     * Leaves the original untouched and returns false on any read/write failure, and skips the
     * rename entirely when no row actually changed.
     *
     * @param callable $transform Maps a CSV row to its replacement row
     * @return bool Whether the data file is intact (rewritten, or left unchanged)
     */
    private function rewrite($transform)
    {
        $in = @fopen($this->dataFile, "r");
        if ($in === false) {
            return false;
        }
        $tmp = $this->dataFile . ".tmp";
        $out = @fopen($tmp, "w");
        if ($out === false) {
            fclose($in);
            return false;
        }

        $ok = true;
        $changed = false;
        while (($row = fgetcsv($in, 1000, ",", "\"", "")) !== false) {
            $new = $transform($row);
            if ($new !== $row) {
                $changed = true;
            }
            if (fputcsv($out, $new, ",", "\"", "") === false) {
                $ok = false;
                break;
            }
        }

        fclose($in);
        return $this->commitTemp($out, $tmp, $ok, $changed);
    }

    /**
     * Returns the row padded to the full column width with the machine columns set.
     *
     * @param string[] $row
     * @param string $machineId
     * @param string $machineSpec
     * @return string[]
     */
    private function withMachine($row, $machineId, $machineSpec)
    {
        $row = array_pad($row, Columns::MACHINE_ID, "");
        $row[Columns::MACHINE_ID] = $machineId;
        $row[Columns::MACHINE_SPEC] = $machineSpec;
        return $row;
    }

    /**
     * Merges another build-times CSV into this one: combines both files, drops rows that are exact
     * duplicates (every field identical), and rewrites the data file sorted by date. Rows differing
     * in any field — a different machine id, or one having a machine and the other not — are all kept.
     *
     * @param string $otherFile Path to the CSV to merge in
     * @return array|false ["imported" => int, "duplicates" => int] on success, false on write failure
     */
    public function mergeFrom($otherFile)
    {
        // Dedup on the whole row (keyed by the parsed fields, so quoting differences don't matter).
        $unique = [];
        foreach ($this->readValidRows($this->dataFile) as $entry) {
            $unique[implode("\0", $entry["row"])] = $entry;
        }

        // Add the imported rows, counting how many are new vs. already in our history.
        $imported = 0;
        $duplicates = 0;
        foreach ($this->readValidRows($otherFile) as $entry) {
            $key = implode("\0", $entry["row"]);
            if (isset($unique[$key])) {
                $duplicates++;
            } else {
                $unique[$key] = $entry;
                $imported++;
            }
        }

        $merged = array_values($unique);
        usort($merged, function ($a, $b) {
            return $a["date"] <=> $b["date"];
        });

        if ($this->writeRows(array_column($merged, "row")) === false) {
            return false;
        }
        return ["imported" => $imported, "duplicates" => $duplicates];
    }

    /**
     * Reads the valid rows of a CSV, each as ["row" => string[], "date" => DateTime]. Invalid rows
     * are skipped.
     *
     * @param string $file
     * @return array
     */
    private function readValidRows($file)
    {
        $rows = [];
        $handle = @fopen($file, "r");
        if ($handle === false) {
            return $rows;
        }
        while (($row = fgetcsv($handle, 1000, ",", "\"", "")) !== false) {
            $dataRow = self::parseRow($row);
            if ($dataRow !== false) {
                $rows[] = ["row" => $row, "date" => $dataRow->date];
            }
        }
        fclose($handle);
        return $rows;
    }

    /**
     * Writes the given rows to the data file atomically (temp file + rename); leaves the original
     * untouched and returns false on any write failure.
     *
     * @param string[][] $rows
     * @return bool
     */
    private function writeRows($rows)
    {
        $tmp = $this->dataFile . ".tmp";
        $out = @fopen($tmp, "w");
        if ($out === false) {
            return false;
        }

        $ok = true;
        foreach ($rows as $row) {
            if (fputcsv($out, $row, ",", "\"", "") === false) {
                $ok = false;
                break;
            }
        }

        return $this->commitTemp($out, $tmp, $ok);
    }

    /**
     * Finishes an atomic write: closes the temp handle, then either renames it over the data file or
     * discards it. Returns whether the data file is intact. Pass $changed = false to skip the rename
     * (nothing to write) while still reporting success.
     *
     * @param resource $out Open temp file handle
     * @param string $tmp Temp file path
     * @param bool $ok Whether the writes so far succeeded
     * @param bool $changed Whether anything actually changed
     * @return bool
     */
    private function commitTemp($out, $tmp, $ok, $changed = true)
    {
        // fclose flushes buffered writes; a failure here means the temp file is incomplete.
        if (fclose($out) === false) {
            $ok = false;
        }
        if (!$ok || !$changed) {
            @unlink($tmp);
            return $ok;
        }
        return @rename($tmp, $this->dataFile);
    }

    /**
     * @param DataRow[] $rows
     * @param bool $withDurations Whether to count the durations, the histogram and the medians read
     *                            from it are left null when not asked for
     * @param bool $withDistribution Whether to sort the counted durations into buckets, which is
     *                               wasted work while the chart is switched off
     *
     * @return BuildTimesData
     */
    private function getData($rows, $withDurations = true, $withDistribution = true)
    {
        $buildTime = 0;
        $buildCount = 0;
        $successCount = 0;
        $failCount = 0;
        $failBuildTime = 0;
        $successBuildTime = 0;

        // Durations counted per value, so the medians need no sorted copy of every row
        $durations = [];
        $successDurations = [];
        $failDurations = [];

        foreach ($rows as $row) {
            $buildCount++;
            if ($withDurations) {
                $durations[$row->count] = ($durations[$row->count] ?? 0) + 1;
            }

            if ($row->type == "success") {
                $successCount++;
                $successBuildTime += $row->count;
                if ($withDurations) {
                    $successDurations[$row->count] = ($successDurations[$row->count] ?? 0) + 1;
                }
            } else {
                $failCount++;
                $failBuildTime += $row->count;
                if ($withDurations) {
                    $failDurations[$row->count] = ($failDurations[$row->count] ?? 0) + 1;
                }
            }

            $buildTime += $row->count;
        }

        $averageBuildTime = intval($buildCount == 0 ? 0 : $buildTime / $buildCount);
        $averageFailBuildTime = intval($failCount === 0 ? 0 : $failBuildTime / $failCount);
        $averageSuccessBuildTime = intval($successCount === 0 ? 0 : $successBuildTime / $successCount);

        $result = new BuildTimesData();
        $result->counts = new BuildTimesValues($buildCount, $successCount, $failCount);
        $result->time = new BuildTimesValues($buildTime, $successBuildTime, $failBuildTime);
        $result->average = new BuildTimesValues($averageBuildTime, $averageSuccessBuildTime, $averageFailBuildTime);
        $result->median = $withDurations ? new BuildTimesValues(
            self::median($durations),
            self::median($successDurations),
            self::median($failDurations)
        ) : null;
        $result->distribution = $withDurations && $withDistribution ? self::bucketDurations($durations) : null;
        $result->dataFrom = !empty($rows) ? reset($rows)->date : null;
        $result->dataTo = !empty($rows) ? end($rows)->date : null;

        return $result;
    }

    /**
     * Combines a list of values into a single one, reducing all, success and fail separately.
     *
     * @param BuildTimesValues[] $values
     * @param callable $reduce Turns a list of ints into the one to keep
     * @return BuildTimesValues
     */
    private static function reduceValues($values, $reduce)
    {
        return new BuildTimesValues(
            $reduce(array_column($values, "all")),
            $reduce(array_column($values, "success")),
            $reduce(array_column($values, "fail"))
        );
    }

    /**
     * Returns the median of the values counted in a histogram (value => number of occurrences).
     *
     * @param int[] $histogram Number of occurrences keyed by value
     * @return int
     */
    private static function median($histogram)
    {
        return self::percentileOfHistogram($histogram, 50);
    }

    /**
     * Returns the given percentile of the values counted in a histogram (value => number of
     * occurrences), or 0 when the histogram is empty. Ranks are interpolated between the two
     * neighbouring values, so percentile 50 is the usual median. Counting the values instead of
     * collecting them means only the distinct ones are sorted.
     *
     * @param int[] $histogram Number of occurrences keyed by value
     * @param int $percentile Percentile to return, clamped to 0-100
     * @return int
     */
    private static function percentileOfHistogram($histogram, $percentile)
    {
        $count = array_sum($histogram);
        if ($count === 0) {
            return 0;
        }

        ksort($histogram);

        $percentile = max(0, min(100, $percentile));
        $rank = ($count - 1) * $percentile / 100;
        $lowerIndex = intval(floor($rank));
        $upperIndex = intval(ceil($rank));

        $lowerValue = null;
        $upperValue = null;
        $seen = 0;
        foreach ($histogram as $value => $occurrences) {
            $seen += $occurrences;
            if ($lowerValue === null && $seen > $lowerIndex) {
                $lowerValue = $value;
            }
            if ($seen > $upperIndex) {
                $upperValue = $value;
                break;
            }
        }

        return intval($lowerValue + ($upperValue - $lowerValue) * ($rank - $lowerIndex));
    }

    /**
     * Splits the counted durations into the buckets of the distribution. Build times differ wildly
     * between projects, so the bounds grow geometrically from the 5th to the 95th percentile, which
     * leaves only the two tails in the open first and last bucket. They are kept to the second, so
     * that every bucket covers the same ratio, it is the renderer that rounds their labels.
     *
     * @param int[] $histogram Number of occurrences keyed by value
     * @return int[] Number of builds keyed by the upper bound of the bucket (excluded), the last
     *               bucket being open ended
     */
    private static function bucketDurations($histogram)
    {
        if (empty($histogram)) {
            return [];
        }

        $lowest = max(1, self::percentileOfHistogram($histogram, 5));
        $highest = max($lowest * 2, self::percentileOfHistogram($histogram, 95));

        // One bound less than there are buckets, the last one is open ended
        $steps = Config::DISTRIBUTION_BUCKETS - 2;
        $bounds = [];
        for ($step = 0; $step <= $steps; $step++) {
            $bound = intval(round($lowest * pow($highest / $lowest, $step / $steps)));
            if (!empty($bounds)) {
                $bound = max($bound, end($bounds) + 1);
            }
            $bounds[] = $bound;
        }
        $bounds[] = PHP_INT_MAX;

        $buckets = array_fill_keys($bounds, 0);
        foreach ($histogram as $duration => $builds) {
            foreach ($bounds as $bound) {
                if ($duration < $bound) {
                    $buckets[$bound] += $builds;
                    break;
                }
            }
        }

        return $buckets;
    }

    /**
     * @param DataRow $dataRow
     * @param BuildTimesConfig $config
     * @return bool
     */
    private function isSelected($dataRow, $config)
    {
        if (empty($config->selectedMachines)) {
            $machineSelected = true;
        } else {
            $rowMachine = $dataRow->machineId === null ? "" : $dataRow->machineId;
            $machineSelected = false;
            foreach ($config->selectedMachines as $selected) {
                $target = $selected === "@current" ? $config->currentMachineId : $selected;
                if ($rowMachine === $target) {
                    $machineSelected = true;
                    break;
                }
            }
        }

        $workspaceOrProjectSelected = (empty($config->selectedWorkspaces) && empty($config->selectedProjects))
            || in_array($dataRow->workspace, $config->selectedWorkspaces, true)
            || in_array($dataRow->project, $config->selectedProjects, true);

        return $machineSelected && $workspaceOrProjectSelected;
    }

    /**
     * Returns the duration in seconds of a build in progress.
     *
     * @param string $startTimeFilePath Path to the start time file
     * @return int Duration in seconds, or 0 if the file is invalid
     */
    private function getProgressDuration($startTimeFilePath)
    {
        $content = @file_get_contents($startTimeFilePath);
        if ($content === false) {
            return 0;
        }

        $startTime = intval($content);
        $duration = time() - $startTime;

        if ($duration < 0 || $startTime === 0) {
            return 0;
        }

        return $duration;
    }
}

final class BuildTimesConfig
{
    /** @var string[] */
    var $selectedWorkspaces = [];

    /** @var string[] */
    var $selectedProjects = [];

    /** @var string[] - empty means all, "" means unknown, "@current" means the current machine, otherwise specific ids */
    var $selectedMachines = [];

    /** @var string[] - which statistics to show, never empty */
    var $selectedStatistics = [self::statisticAverage];

    /** @var bool - whether the distribution chart is shown */
    var $showDistribution = false;

    /** @var string - id of the machine the plugin currently runs on */
    var $currentMachineId = "";

    /** @var string - spec of the current machine, for display */
    var $currentMachineSpec = "";

    /** @var DateTimeZone|null */
    var $localTimeZone = null;

    /** @var bool */
    private $localTimeZoneAutodetect = true;

    const statisticAverage = "average";
    const statisticMedian = "median";

    const selectedWorkspacesKey = "selectedWorkspaces";
    const selectedProjectsKey = "selectedProjects";
    const selectedMachinesKey = "selectedMachines";
    const selectedStatisticsKey = "selectedStatistics";
    const showDistributionKey = "showDistribution";
    const selectedLocalTimeZoneKey = "localTimeZone";

    /**
     * Loads configuration from JSON file.
     *
     * @param string $configFile Path to the config JSON file
     * @param Machine $machine The current machine
     */
    public function __construct($configFile, $machine)
    {
        list($this->currentMachineId, $this->currentMachineSpec) = $machine->forDisplay();
        // Default to the current machine until the user picks something (overridden below when configured).
        $this->selectedMachines = $this->currentMachineId !== "" ? ["@current"] : [];

        $data = @file_get_contents($configFile);
        if ($data === false) {
//             error_log("Unable to read config file: {$this->configFile}");
            // No exit, just return default config
            return;
        }

        $json = json_decode($data, true);
        if ($json === null) {
//             error_log("Unable decode json read config: {$json}");
            // No exit, just return default config
            return;
        }

        if (key_exists(self::selectedWorkspacesKey, $json) && is_array($json[self::selectedWorkspacesKey])) {
            foreach ($json[self::selectedWorkspacesKey] as $selectedWorkspace) {
                if (!is_string($selectedWorkspace)) {
                    continue;
                }
                $this->selectedWorkspaces[] = $selectedWorkspace;
            }
        }

        if (key_exists(self::selectedProjectsKey, $json) && is_array($json[self::selectedProjectsKey])) {
            foreach ($json[self::selectedProjectsKey] as $selectedProject) {
                if (!is_string($selectedProject)) {
                    continue;
                }
                $this->selectedProjects[] = $selectedProject;
            }
        }

        if (key_exists(self::selectedMachinesKey, $json) && is_array($json[self::selectedMachinesKey])) {
            $this->selectedMachines = [];
            foreach ($json[self::selectedMachinesKey] as $selectedMachine) {
                if (!is_string($selectedMachine)) {
                    continue;
                }
                $this->selectedMachines[] = $selectedMachine;
            }
        }

        if (key_exists(self::selectedStatisticsKey, $json) && is_array($json[self::selectedStatisticsKey])) {
            $selectedStatistics = [];
            foreach ($json[self::selectedStatisticsKey] as $selectedStatistic) {
                if ($selectedStatistic !== self::statisticAverage && $selectedStatistic !== self::statisticMedian) {
                    continue;
                }
                $selectedStatistics[] = $selectedStatistic;
            }
            // Keep the default when nothing valid is left, there must always be something to show
            if (!empty($selectedStatistics)) {
                $this->selectedStatistics = $selectedStatistics;
            }
        }

        if (key_exists(self::showDistributionKey, $json) && is_bool($json[self::showDistributionKey])) {
            $this->showDistribution = $json[self::showDistributionKey];
        }

        if (key_exists(self::selectedLocalTimeZoneKey, $json) && is_string($json[self::selectedLocalTimeZoneKey])) {
            try {
                $timeZoneString = $json[self::selectedLocalTimeZoneKey];
                $this->localTimeZone = new DateTimeZone($timeZoneString);
                $this->localTimeZoneAutodetect = false;

            } catch (Exception $e) {
                $this->localTimeZone = $this->getLocalTimeZone();
            }
        } else {
            $this->localTimeZone = $this->getLocalTimeZone();
        }
    }

    /**
     * Toggles workspace selection. If add is false, replaces current selection.
     *
     * @param string $name Workspace name
     * @param bool $add Whether to add to current selection or replace it
     */
    function toggleWorkspace($name, $add)
    {
        $key = array_search($name, $this->selectedWorkspaces, true);
        if ($key !== false) {
            unset($this->selectedWorkspaces[$key]);
            // Reindex, otherwise the gap makes json_encode write an object instead of an array
            $this->selectedWorkspaces = array_values($this->selectedWorkspaces);
        } else {
            if ($add) {
                $this->selectedWorkspaces[] = $name;
            } else {
                $this->selectedWorkspaces = [$name];
                $this->selectedProjects = [];
            }
        }
    }

    /**
     * Toggles project selection. If add is false, replaces current selection.
     *
     * @param string $name Project name
     * @param bool $add Whether to add to current selection or replace it
     */
    function toggleProject($name, $add)
    {
        $key = array_search($name, $this->selectedProjects, true);
        if ($key !== false) {
            unset($this->selectedProjects[$key]);
            $this->selectedProjects = array_values($this->selectedProjects);
        } else {
            if ($add) {
                $this->selectedProjects[] = $name;
            } else {
                $this->selectedWorkspaces = [];
                $this->selectedProjects = [$name];
            }
        }
    }

    /**
     * Toggles machine selection. If add is false, replaces the current machine selection.
     * Machines are an independent filter dimension, so this leaves workspace and project
     * selections untouched.
     *
     * @param string $machineId Machine id
     * @param bool $add Whether to add to current selection or replace it
     */
    function toggleMachine($machineId, $add)
    {
        $key = array_search($machineId, $this->selectedMachines, true);
        if ($key !== false) {
            unset($this->selectedMachines[$key]);
            $this->selectedMachines = array_values($this->selectedMachines);
        } elseif ($add) {
            $this->selectedMachines[] = $machineId;
        } else {
            $this->selectedMachines = [$machineId];
        }
    }

    /**
     * Toggles statistic selection. If add is false, replaces current selection. Deselecting the last
     * one falls back to the average, so the menu always shows something.
     *
     * @param string $name Statistic name, one of the statistic* constants
     * @param bool $add Whether to add to current selection or replace it
     */
    function toggleStatistic($name, $add)
    {
        $key = array_search($name, $this->selectedStatistics, true);
        if ($key !== false) {
            unset($this->selectedStatistics[$key]);
            $this->selectedStatistics = array_values($this->selectedStatistics);
        } elseif ($add) {
            $this->selectedStatistics[] = $name;
        } else {
            $this->selectedStatistics = [$name];
        }

        if (empty($this->selectedStatistics)) {
            $this->selectedStatistics = [self::statisticAverage];
        }
    }

    /**
     * Shows everything: clears workspace, project and machine selections.
     */
    function selectAll()
    {
        $this->selectedWorkspaces = [];
        $this->selectedProjects = [];
        $this->selectedMachines = [];
    }

    /**
     * Saves current configuration to JSON file.
     *
     * @param string $configFile Path to the config JSON file
     */
    function save($configFile)
    {
        $dataToSave = [
            self::selectedProjectsKey => $this->selectedProjects,
            self::selectedWorkspacesKey => $this->selectedWorkspaces,
            self::selectedMachinesKey => $this->selectedMachines,
            self::selectedStatisticsKey => $this->selectedStatistics,
            self::showDistributionKey => $this->showDistribution,
        ];

        if ($this->localTimeZone !== null && $this->localTimeZoneAutodetect === false) {
            $dataToSave[self::selectedLocalTimeZoneKey] = $this->localTimeZone->getName();
        }

        $data = json_encode($dataToSave, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($data === false) {
            error_log("Unable to serialize config");
            exit(1);
        }

        file_put_contents($configFile, $data);
    }

    /**
     * @return DateTimeZone|null
     */
    private function getLocalTimeZone()
    {
        // On macOS /etc/localtime is symlink to file with time zone info.
        // realpath() resolves to /var/db/timezone/zoneinfo/... on older macOS
        // or /usr/share/zoneinfo.default/... on macOS 15+.
        $link = "/etc/localtime";
        if (is_link($link)) {
            $realPath = realpath($link);
            $timeZone = preg_replace("~.*zoneinfo(\.default)?/~", "", $realPath);

            try {
                return new DateTimeZone($timeZone);
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }
}

final class BitBarRenderer
{
    /** @var BuildTimesOutput */
    private $data;

    /** @var DateTimeZone */
    private $localTimeZone;

    /**
     * @param BuildTimesOutput $data Parsed build times data
     * @param BuildTimesConfig $config User configuration
     */
    public function __construct($data, $config)
    {
        $this->data = $data;
        $this->localTimeZone = $config->localTimeZone;
    }

    /**
     * Renders the complete menu bar output.
     */
    public function render()
    {
        $this->renderHeader();
        $this->renderContent();
        $this->renderFooter();
    }

    /**
     * Renders the menu bar header with today's build time and icon.
     */
    private function renderHeader()
    {
        $row = "";
        $buildTime = $this->data->todayData?->time->all ?: 0;
        $inProgressTime = array_sum($this->data->inProgress);
        $row .= $this->format($buildTime + $inProgressTime);

        if (count($this->data->warnings) > 0) {
            $row .= " ⚠️";
        }
        $hammerIcon = count($this->data->inProgress) > 0 ? "hammer.fill" : "hammer";
        $row .= " | sfimage=$hammerIcon width=18 height=18";

        $this->renderRows([$row, "---"]);
    }

    /**
     * Renders the main content section with build statistics.
     */
    private function renderContent()
    {
        $rows = [];

        // Warnings:
        if (count($this->data->warnings) > 0) {
            $rows = array_merge($rows, array_map(function ($row) {
                return sprintf(Strings::ROW_WARNING, $row);
            }, $this->data->warnings));
            $rows[] = "---";
        }

        // One-time notice to assign builds recorded before the machine columns existed.
        if ($this->data->hasBuildsWithoutMachine) {
            $rows[] = sprintf(Strings::ROW_WARNING, Strings::ROW_MIGRATION_NOTICE);
            $rows[] = "-- " . Strings::ROW_MIGRATION_FILL . "| bash='" . __FILE__ . "' param1=migrateMachine param2=fill refresh=true terminal=false";
            $rows[] = "-- " . Strings::ROW_MIGRATION_IGNORE . "| bash='" . __FILE__ . "' param1=migrateMachine param2=ignore refresh=true terminal=false";
            $rows[] = "---";
        }

        // Today and on alt total info
        $todayData = $this->data->todayData;
        $totalData = $this->data->totalData;
        $dailyData = $this->data->dailyData;

        $alternate = "| alternate=true";
        $selectedFilter = array_merge($this->selectedMachineLabels(), $this->data->selectedWorkspaces, $this->data->selectedProjects);
        $formattedSelectedFilter = implode(", ", array_map(function ($item) {
            return $this->sanitizeName($item);
        }, $selectedFilter));
        if (empty($selectedFilter)) {
            $rows[] = Strings::ROW_HEADER_TODAY;
        } else {
            $rows[] = sprintf(Strings::ROW_HEADER_TODAY_FILTER, $formattedSelectedFilter);
        }

        if ($this->data->totalData != null) {
            $dateFrom = clone $this->data->totalData->dataFrom;
            if ($this->localTimeZone !== null) {
                $dateFrom->setTimezone($this->localTimeZone);
            }
            $dateFromFormatted = $dateFrom !== null ? $dateFrom->format(Config::DATE_FORMAT) : "never"; // TODO localizable
            if (empty($selectedFilter)) {
                $rows[] = sprintf(Strings::ROW_HEADER_TOTAL_SINCE, $dateFromFormatted) . $alternate;
            } else {
                $rows[] = sprintf(Strings::ROW_HEADER_TOTAL_SINCE_FILTER, $dateFromFormatted, $formattedSelectedFilter) . $alternate;

            }
        } else {
            if (empty($selectedFilter)) {
                $rows[] = Strings::ROW_HEADER_TOTAL . $alternate;
            } else {
                $rows[] = sprintf(Strings::ROW_HEADER_TOTAL_FILTER, $formattedSelectedFilter) . $alternate;
            }
        }

        $rows[] = $this->getBuildCountsRow($todayData);
        $rows[] = $this->getBuildCountsRow($totalData) . $alternate;

        // Build time

        $rows = array_merge($rows, $this->getBuildStatisticRows("time", Strings::ROW_BUILD_TIME));

        // Average and median build time

        if ($this->isStatisticSelected(BuildTimesConfig::statisticAverage)) {
            $rows = array_merge($rows, $this->getBuildStatisticRows(BuildTimesConfig::statisticAverage, Strings::ROW_AVERAGE_BUILD_TIME));
        }
        if ($this->isStatisticSelected(BuildTimesConfig::statisticMedian)) {
            $rows = array_merge($rows, $this->getBuildStatisticRows(BuildTimesConfig::statisticMedian, Strings::ROW_MEDIAN_BUILD_TIME));
        }

        // Daily data

        if ($dailyData !== null) {
            if ($this->isStatisticSelected(BuildTimesConfig::statisticAverage)) {
                $rows = array_merge($rows, $this->getDailyStatisticRows($dailyData->averageTime, $dailyData->averageCount, Strings::ROW_DAILY_AVERAGE_BUILD_TIME));
            }
            if ($this->isStatisticSelected(BuildTimesConfig::statisticMedian)) {
                $rows = array_merge($rows, $this->getDailyStatisticRows($dailyData->medianTime, $dailyData->medianCount, Strings::ROW_DAILY_MEDIAN_BUILD_TIME));
            }
        }

        // Distribution

        if ($this->data->showDistribution) {
            $rows = array_merge($rows, $this->getDistributionRows());
        }

        if ($this->data->lastBuild !== null) {
            $rows[] = "---";
            $rows[] = sprintf(Strings::ROW_LAST_BUILD, $this->data->lastBuild->type, $this->format($this->data->lastBuild->count));
        }

        foreach ($this->data->inProgress as $inProgress) {
            $rows[] = sprintf(Strings::ROW_BUILD_IN_PROGRESS, $this->format($inProgress));
        }

        $this->renderRows($rows);
    }

    /**
     * Returns one set of build duration values: today's row with its success/fail submenu, followed
     * by the same for the total on the alternate.
     *
     * @param string $property Values property of BuildTimesData, e.g. "average"
     * @param string $title Row title template
     * @return string[]
     */
    private function getBuildStatisticRows($property, $title)
    {
        $alternate = "| alternate=true";
        $todayValues = $this->getValues($this->data->todayData, $property);
        $totalValues = $this->getValues($this->data->totalData, $property);

        $subMenu = [];
        $subMenu[] = "-- " . $this->getFormatedTimeRow($todayValues?->success, Strings::ROW_SUCCESS_BUILD_TIME);
        $subMenu[] = "-- " . $this->getFormatedTimeRow($totalValues?->success, Strings::ROW_SUCCESS_BUILD_TIME) . $alternate;
        $subMenu[] = "-- " . $this->getFormatedTimeRow($todayValues?->fail, Strings::ROW_FAIL_BUILD_TIME);
        $subMenu[] = "-- " . $this->getFormatedTimeRow($totalValues?->fail, Strings::ROW_FAIL_BUILD_TIME) . $alternate;

        $rows = [];
        $rows[] = $this->getFormatedTimeRow($todayValues?->all, $title);
        $rows = array_merge($rows, $subMenu);
        $rows[] = $this->getFormatedTimeRow($totalValues?->all, $title) . $alternate;
        $rows = array_merge($rows, $subMenu);

        return $rows;
    }

    /**
     * Returns one statistic of the daily totals, with its success/fail submenu.
     *
     * @param BuildTimesValues $times
     * @param BuildTimesValues $counts
     * @param string $title Row title template
     * @return string[]
     */
    private function getDailyStatisticRows($times, $counts, $title)
    {
        $rows = [];
        $rows[] = sprintf($title, $this->format($times->all), $counts->all);
        $rows[] = "-- " . sprintf(Strings::ROW_DAILY_SUCCESS_BUILD_TIME, $this->format($times->success), $counts->success);
        $rows[] = "-- " . sprintf(Strings::ROW_DAILY_FAIL_BUILD_TIME, $this->format($times->fail), $counts->fail);

        return $rows;
    }

    /**
     * Returns today's distribution row with a bar chart of the build durations in its submenu,
     * followed by the same for the total on the alternate.
     *
     * @return string[]
     */
    private function getDistributionRows()
    {
        $alternate = "| alternate=true";

        $rows = $this->getDistributionBlock($this->data->todayData, "");
        return array_merge($rows, $this->getDistributionBlock($this->data->totalData, $alternate));
    }

    /**
     * @param BuildTimesData $data
     * @param string $alternate
     * @return string[]
     */
    private function getDistributionBlock($data, $alternate)
    {
        $rows = [];
        $rows[] = Strings::ROW_DISTRIBUTION . $alternate;

        $distribution = $data?->distribution;
        if (empty($distribution)) {
            $rows[] = "-- " . Strings::MSG_NO_BUILDS_YET;
            return $rows;
        }

        foreach ($this->getDistributionLines($distribution, Strings::ROW_DISTRIBUTION_BUCKET, Config::DISTRIBUTION_BAR, true) as $line) {
            $rows[] = "-- " . $line . "| font=" . Config::DISTRIBUTION_FONT;
        }

        return $rows;
    }

    /**
     * Renders one bar per bucket, the longest one being the most common bucket. Empty buckets are
     * kept, the gaps are part of the shape and the chart keeps the same height.
     *
     * @param int[] $distribution Number of builds keyed by the upper bound of the bucket
     * @param string $template Line template, taking the same arguments whatever order it puts them in
     * @param string[] $parts Parts to draw the bars with, ordered from the whole one down to the smallest
     * @param bool $monospaced Whether the text is monospaced, only then can the columns be padded
     * @return string[]
     */
    private function getDistributionLines($distribution, $template, $parts, $monospaced)
    {
        $total = array_sum($distribution);
        $mostCommon = max($distribution);
        $labels = $this->getDistributionLabels($distribution);

        // One row of columns per bucket, in the order the templates take them
        $rows = [];
        foreach ($distribution as $bound => $builds) {
            $rows[] = [
                $labels[$bound],
                $this->getBar($builds, $mostCommon, $parts),
                strval($builds),
                strval(intval(round($builds / $total * 100))),
            ];
        }

        if ($monospaced) {
            // One side per column, in the order the templates number their arguments
            foreach ([STR_PAD_RIGHT, STR_PAD_RIGHT, STR_PAD_LEFT, STR_PAD_LEFT] as $column => $side) {
                $width = max(array_map("mb_strlen", array_column($rows, $column)));
                foreach ($rows as $row => $columns) {
                    $rows[$row][$column] = mb_str_pad($columns[$column], $width, " ", $side);
                }
            }
        }

        $lines = [];
        foreach ($rows as $columns) {
            // Trimmed, a bucket nothing fell into has no bar to start the unpadded line with
            $lines[] = trim(vsprintf($template, $columns));
        }

        return $lines;
    }

    /**
     * Names every bucket by the range it covers, the last one has no upper bound.
     *
     * @param int[] $distribution Number of builds keyed by the upper bound of the bucket
     * @return string[] Label keyed by the upper bound of the bucket
     */
    private function getDistributionLabels($distribution)
    {
        $labels = [];
        $from = 0;
        $formattedFrom = $this->formatBound($from);
        foreach (array_keys($distribution) as $bound) {
            if ($bound === PHP_INT_MAX) {
                $labels[$bound] = sprintf(Strings::ROW_DISTRIBUTION_RANGE_OPEN, $formattedFrom);
                continue;
            }

            $shown = $this->labelBound($bound, $from);
            $formattedShown = $this->formatBound($shown);
            $labels[$bound] = sprintf(Strings::ROW_DISTRIBUTION_RANGE, $formattedFrom, $formattedShown);
            $from = $shown;
            $formattedFrom = $formattedShown;
        }

        return $labels;
    }

    /**
     * The duration to label a bucket bound with: whole hours, whole minutes or whole seconds,
     * whichever is the coarsest that neither misstates the bound nor repeats the one before it. A
     * bound is never rounded up into a unit it does not reach, so nothing below a minute reads as one.
     *
     * @param int $bound Real bound of the bucket, in seconds
     * @param int $previous Duration the bound before it is labelled with
     *
     * @return int
     */
    private function labelBound($bound, $previous)
    {
        foreach ([3600, 60, 1] as $unit) {
            if ($bound < $unit) {
                continue;
            }

            $rounded = intval(round($bound / $unit)) * $unit;
            if (abs($rounded - $bound) <= Config::DISTRIBUTION_LABEL_TOLERANCE * $bound && $rounded > $previous) {
                return $rounded;
            }
        }

        return max($bound, $previous + 1);
    }

    /**
     * Draws one bar out of the parts it is given, so however many of those there are is the
     * resolution it gets. Anything above zero is worth at least the smallest part.
     *
     * @param int $value
     * @param int $max Value drawn as a full width bar
     * @param string[] $parts Parts to draw with, ordered from the whole one down to the smallest
     * @return string
     */
    private function getBar($value, $max, $parts)
    {
        $steps = count($parts);

        $units = $max === 0 ? 0 : intval(round($value / $max * Config::DISTRIBUTION_BAR_WIDTH * $steps));
        if ($units === 0 && $value > 0) {
            $units = 1;
        }

        $whole = intdiv($units, $steps);
        $remainder = $units % $steps;

        $bar = str_repeat($parts[0], $whole);
        if ($remainder > 0) {
            $bar .= $parts[$steps - $remainder];
        }

        return $bar;
    }

    /**
     * @param BuildTimesData $data
     * @return string
     */
    private function getBuildCountsRow($data)
    {
        if ($data === null) {
            return Strings::ROW_BUILD_COUNTS_NO_BUILDS;
        }

        return sprintf(Strings::ROW_BUILD_COUNTS, $data->counts->all, $data->counts->success, $data->counts->fail);
    }

    /**
     * Returns one set of values of the given data, or null when there is no data at all.
     *
     * @param BuildTimesData $data
     * @param string $property Values property of BuildTimesData, e.g. "average"
     * @return BuildTimesValues|null
     */
    private function getValues($data, $property)
    {
        return $data === null ? null : $data->{$property};
    }

    /**
     * @param int|null $seconds
     * @param string $title
     *
     * @return string
     */
    private function getFormatedTimeRow($seconds, $title)
    {
        if ($seconds === null) {
            return sprintf($title, Strings::MSG_NO_BUILDS_YET);
        }

        return sprintf($title, $this->format($seconds));
    }

    /**
     * Renders the footer with refresh, share, settings, and about sections.
     */
    private function renderFooter()
    {
        $rows = [];
        $rows[] = "---";
        $rows[] = Strings::ROW_REFRESH . "| refresh=true";
        $rows[] = Strings::ROW_SHARE . "| bash='" . __FILE__ . "' param1=share param2=today refresh=false terminal=false";
        $rows[] = Strings::ROW_SHARE . "| bash='" . __FILE__ . "' param1=share param2=total refresh=false terminal=false alternate=true";

        $this->renderRows($rows);

        $this->renderPreferences();
        $this->renderAbout();
    }

    /**
     * Renders the Settings submenu with filter, data, and reset options.
     */
    private function renderPreferences()
    {
        $rows = [];
        $rows[] = Strings::ROW_SETTINGS;
        $this->renderRows($rows);

        $this->renderFilter();
        $this->renderStatistics();

        $rows = [];
        $rows[] = "-- " . Strings::ROW_SETTINGS_DATA;
        $rows[] = "---- " . Strings::ROW_SETTINGS_DATA_EXPORT . "| bash='" . __FILE__ . "' param1=export refresh=false terminal=false";
        $rows[] = "---- " . Strings::ROW_SETTINGS_DATA_IMPORT . "| bash='" . __FILE__ . "' param1=import refresh=true terminal=false";
        $rows[] = "---- " . Strings::ROW_SETTINGS_DATA_OPEN_LOCATION . "| bash='" . __FILE__ . "' param1=openDataLocation refresh=false terminal=false";
        $rows[] = "---- " . Strings::ROW_SETTINGS_DATA_FILL_MACHINE . "| bash='" . __FILE__ . "' param1=migrateMachine param2=fill refresh=true terminal=false";
        $rows[] = "-- " . Strings::ROW_SETTINGS_RESET;
        $rows[] = "---- " . Strings::ROW_SETTINGS_RESET_REALLY;
        $rows[] = "------ " . Strings::ROW_SETTINGS_RESET_REALLY_YES . "| bash='" . __FILE__ . "' param1=reset refresh=true terminal=false";
        $this->renderRows($rows);
    }

    /**
     * Renders the workspace/project filter submenu.
     */
    private function renderFilter()
    {
        $check = "✔ ";
        $alternate = " alternate=true";
        $selectedMachines = $this->data->selectedMachines;
        $allMachines = empty($selectedMachines);
        $currentMachineId = $this->data->currentMachineId;

        $allSelected = empty($this->data->selectedWorkspaces) && empty($this->data->selectedProjects) && $allMachines;

        $rows = [];

        $rows[] = "-- " . Strings::ROW_SETTINGS_FILTER;
        $rows[] = "---- " . ($allSelected ? $check : "") . Strings::ROW_SETTINGS_FILTER_ALL . $this->getActionForProjectSelection("all", "-", false);

        $rows[] = "---- " . Strings::ROW_SETTINGS_FILTER_WORKSPACES_PROJECTS;

        $workspacesProjectsAll = empty($this->data->selectedWorkspaces) && empty($this->data->selectedProjects);
        $rows[] = "------ " . ($workspacesProjectsAll ? $check : "") . Strings::ROW_SETTINGS_FILTER_ALL . $this->getActionForProjectSelection("all", "wp", false);

        foreach ($this->data->workspaces as $workspace) {
            $isSelected = in_array($workspace, $this->data->selectedWorkspaces, true);
            $row = "------ " . ($isSelected ? $check : "") . $this->sanitizeName($workspace);
            $rows[] = $row . $this->getActionForProjectSelection("workspace", $workspace, false);
            $rows[] = $row . $this->getActionForProjectSelection("workspace", $workspace, true) . $alternate;
        }

        foreach ($this->data->projects as $project) {
            $isSelected = in_array($project, $this->data->selectedProjects, true);
            $row = "------ " . ($isSelected ? $check : "") . $this->sanitizeName($project);
            $rows[] = $row . $this->getActionForProjectSelection("project", $project, false);
            $rows[] = $row . $this->getActionForProjectSelection("project", $project, true) . $alternate;
        }

        $rows[] = "---- " . Strings::ROW_SETTINGS_FILTER_MACHINE;

        $row = "------ " . ($allMachines ? $check : "") . Strings::ROW_SETTINGS_FILTER_ALL;
        $rows[] = $row . $this->getActionForProjectSelection("machine", "all", false);
        $rows[] = $row . $this->getActionForProjectSelection("machine", "all", true) . $alternate;

        if ($currentMachineId !== "") {
            $currentSpec = isset($this->data->machines[$currentMachineId]) ? $this->data->machines[$currentMachineId] : $this->data->currentMachineSpec;
            $label = Strings::ROW_SETTINGS_FILTER_MACHINE_CURRENT . ($currentSpec !== "" ? " " . $this->sanitizeName($currentSpec) : "");
            $isCurrent = in_array("@current", $selectedMachines, true) || in_array($currentMachineId, $selectedMachines, true);
            $row = "------ " . ($isCurrent ? $check : "") . $label;
            $rows[] = $row . $this->getActionForProjectSelection("machine", "current", false);
            $rows[] = $row . $this->getActionForProjectSelection("machine", "current", true) . $alternate;
        }

        foreach ($this->data->machines as $machineId => $spec) {
            if ($machineId === $currentMachineId) {
                continue;
            }
            // The unknown bucket uses an empty id; address it via "none" since empty params don't round-trip.
            $action = $machineId === "" ? "none" : $machineId;
            $label = $machineId === "" ? Strings::ROW_SETTINGS_FILTER_MACHINE_UNKNOWN : $this->sanitizeName($spec);
            $isSelected = in_array($machineId, $selectedMachines, true);
            $row = "------ " . ($isSelected ? $check : "") . $label;
            $rows[] = $row . $this->getActionForProjectSelection("machine", $action, false);
            $rows[] = $row . $this->getActionForProjectSelection("machine", $action, true) . $alternate;
        }

        $this->renderRows($rows);
    }

    /**
     * Renders the statistics submenu, where the statistics shown in the menu are picked.
     */
    private function renderStatistics()
    {
        $check = "✔ ";
        $alternate = " alternate=true";
        $statistics = [
            BuildTimesConfig::statisticAverage => Strings::ROW_SETTINGS_STATISTICS_AVERAGE,
            BuildTimesConfig::statisticMedian => Strings::ROW_SETTINGS_STATISTICS_MEDIAN,
        ];

        $rows = [];
        $rows[] = "-- " . Strings::ROW_SETTINGS_STATISTICS;

        foreach ($statistics as $statistic => $label) {
            $row = "---- " . ($this->isStatisticSelected($statistic) ? $check : "") . $label;
            $rows[] = $row . $this->getActionForStatisticSelection($statistic, false);
            $rows[] = $row . $this->getActionForStatisticSelection($statistic, true) . $alternate;
        }

        // The chart is not one of a set to pick from, so it gets switched on its own
        $shown = $this->data->showDistribution;
        $rows[] = "---- " . Strings::ROW_SETTINGS_STATISTICS_DISTRIBUTION;
        $rows[] = "------ " . ($shown ? $check : "") . Strings::ROW_SETTINGS_STATISTICS_ON . $this->getActionForDistribution(true);
        $rows[] = "------ " . ($shown ? "" : $check) . Strings::ROW_SETTINGS_STATISTICS_OFF . $this->getActionForDistribution(false);

        $this->renderRows($rows);
    }

    /**
     * Sanitizes a name for display in the menu bar by replacing newlines and pipes.
     *
     * @param string $name Name to sanitize
     * @return string Sanitized name
     */
    private function sanitizeName($name)
    {
        return preg_replace('~[\\n|]~', "_", $name);
    }

    /**
     * Maps the selected machine ids to their specs for the filter header, falling back to the id.
     * The current-machine default is left out so it doesn't clutter the header, and the unknown
     * bucket is shown as "(unknown)".
     *
     * @return string[]
     */
    private function selectedMachineLabels()
    {
        $labels = [];
        foreach ($this->data->selectedMachines as $id) {
            if ($id === "@current") {
                continue;
            }
            if ($id === "") {
                $labels[] = Strings::ROW_SETTINGS_FILTER_MACHINE_UNKNOWN;
                continue;
            }
            $labels[] = isset($this->data->machines[$id]) ? $this->data->machines[$id] : $id;
        }
        return $labels;
    }

    /**
     * @param string $statistic One of the BuildTimesConfig::statistic* constants
     * @return bool
     */
    private function isStatisticSelected($statistic)
    {
        return in_array($statistic, $this->data->selectedStatistics, true);
    }

    /**
     * Renders the About submenu with source code, icon, and update links.
     */
    private function renderAbout()
    {
        $rows = [];
        $rows[] = Strings::ROW_ABOUT;
        $rows[] = "-- " . Strings::ROW_ABOUT_SOURCE_CODE . "| href=" . Config::ABOUT_URL;
        $rows[] = "-- " . Strings::ROW_ABOUT_UPDATE;
        $rows[] = "---- " . Strings::ROW_ABOUT_UPDATE_REALLY;
        $rows[] = "------ " . Strings::ROW_ABOUT_UPDATE_REALLY_YES . "| bash='" . __FILE__ . "' param1=update param2=showAlerts refresh=true terminal=false";

        $this->renderRows($rows);
    }

    /**
     * @param string[] $rows
     */
    private function renderRows($rows)
    {
        echo implode("\n", $rows) . "\n";
    }

    /**
     * @param int $seconds
     * @param bool $trimZero Whether to leave out a part that is zero anyway, so that a whole minute
     *                       reads as "1m" and not as "1m 0s"
     *
     * @return string
     */
    private function format($seconds, $trimZero = false)
    {
        $seconds = round($seconds);
        $dtF = new DateTime('@0');
        $dtT = new DateTime("@$seconds");
        $interval = $dtT->diff($dtF);
        if ($seconds < 60) {
            return $interval->format(Strings::FORMAT_SECONDS);
        } else if ($seconds < 3600) {
            return $interval->format($trimZero && $interval->s === 0 ? Strings::FORMAT_MINUTES_WHOLE : Strings::FORMAT_MINUTES);
        } elseif ($seconds < 86400) {
            return $interval->format($trimZero && $interval->i === 0 ? Strings::FORMAT_HOURS_WHOLE : Strings::FORMAT_HOURS);
        } else {
            return $interval->format($trimZero && $interval->h === 0 ? Strings::FORMAT_DAYS_WHOLE : Strings::FORMAT_DAYS);
        }
    }

    /**
     * Formats a bucket bound, which reads better without the parts that are zero.
     *
     * @param int $seconds
     *
     * @return string
     */
    private function formatBound($seconds)
    {
        return $this->format($seconds, true);
    }

    /**
     * @param string $mode "today" or "total"
     * @return string
     */
    public function getShareText($mode)
    {
        $isToday = $mode === "today";
        $data = $isToday ? $this->data->todayData : $this->data->totalData;
        $dailyData = $this->data->dailyData;

        $lines = [];

        // Header with filter and since info, matching normal render
        $selectedFilter = array_merge($this->selectedMachineLabels(), $this->data->selectedWorkspaces, $this->data->selectedProjects);
        $formattedFilter = implode(", ", array_map(function ($item) {
            return $this->sanitizeName($item);
        }, $selectedFilter));

        if ($isToday) {
            $todayDate = (new DateTime("now", $this->localTimeZone))->format(Config::DATE_FORMAT);
            if (empty($selectedFilter)) {
                $lines[] = sprintf(Strings::SHARE_HEADER_TODAY, $todayDate);
            } else {
                $lines[] = sprintf(Strings::SHARE_HEADER_TODAY_FILTER, $formattedFilter, $todayDate);
            }
        } else {
            if ($this->data->totalData != null) {
                $dateFrom = clone $this->data->totalData->dataFrom;
                if ($this->localTimeZone !== null) {
                    $dateFrom->setTimezone($this->localTimeZone);
                }
                $dateFromFormatted = $dateFrom !== null ? $dateFrom->format(Config::DATE_FORMAT) : "never";
                if (empty($selectedFilter)) {
                    $lines[] = sprintf(Strings::SHARE_HEADER_TOTAL_SINCE, $dateFromFormatted);
                } else {
                    $lines[] = sprintf(Strings::SHARE_HEADER_TOTAL_SINCE_FILTER, $dateFromFormatted, $formattedFilter);
                }
            } else {
                if (empty($selectedFilter)) {
                    $lines[] = Strings::SHARE_HEADER_TOTAL;
                } else {
                    $lines[] = sprintf(Strings::SHARE_HEADER_TOTAL_FILTER, $formattedFilter);
                }
            }
        }

        if ($data === null) {
            $lines[] = Strings::ROW_BUILD_COUNTS_NO_BUILDS;
        } else {
            $lines[] = sprintf(Strings::ROW_BUILD_COUNTS, $data->counts->all, $data->counts->success, $data->counts->fail);
            $lines = array_merge($lines, $this->getSharedBuildStatisticLines($data->time, Strings::ROW_BUILD_TIME));
            if ($this->isStatisticSelected(BuildTimesConfig::statisticAverage)) {
                $lines = array_merge($lines, $this->getSharedBuildStatisticLines($data->average, Strings::ROW_AVERAGE_BUILD_TIME));
            }
            if ($this->isStatisticSelected(BuildTimesConfig::statisticMedian)) {
                $lines = array_merge($lines, $this->getSharedBuildStatisticLines($data->median, Strings::ROW_MEDIAN_BUILD_TIME));
            }
        }

        if ($dailyData !== null) {
            if ($this->isStatisticSelected(BuildTimesConfig::statisticAverage)) {
                $lines = array_merge($lines, $this->getSharedDailyStatisticLines($dailyData->averageTime, $dailyData->averageCount, Strings::ROW_DAILY_AVERAGE_BUILD_TIME));
            }
            if ($this->isStatisticSelected(BuildTimesConfig::statisticMedian)) {
                $lines = array_merge($lines, $this->getSharedDailyStatisticLines($dailyData->medianTime, $dailyData->medianCount, Strings::ROW_DAILY_MEDIAN_BUILD_TIME));
            }
        }

        if ($this->data->showDistribution && $data !== null && !empty($data->distribution)) {
            $lines[] = "";
            $lines[] = Strings::ROW_DISTRIBUTION;
            $lines = array_merge($lines, $this->getDistributionLines($data->distribution, Strings::SHARE_DISTRIBUTION_BUCKET, Config::DISTRIBUTION_SHARE_BAR, false));
        }

        $lines[] = "";
        $lines[] = sprintf(Strings::SHARE_FOOTER, Config::ABOUT_URL);

        return implode("\n", $lines);
    }

    /**
     * Returns one set of build duration values for the shared text.
     *
     * @param BuildTimesValues $values
     * @param string $title Line title template
     * @return string[]
     */
    private function getSharedBuildStatisticLines($values, $title)
    {
        $lines = [];
        $lines[] = sprintf($title, $this->format($values->all));
        $lines[] = "  " . sprintf(Strings::ROW_SUCCESS_BUILD_TIME, $this->format($values->success));
        $lines[] = "  " . sprintf(Strings::ROW_FAIL_BUILD_TIME, $this->format($values->fail));

        return $lines;
    }

    /**
     * Returns one statistic of the daily totals for the shared text.
     *
     * @param BuildTimesValues $times
     * @param BuildTimesValues $counts
     * @param string $title Line title template
     * @return string[]
     */
    private function getSharedDailyStatisticLines($times, $counts, $title)
    {
        $lines = [];
        $lines[] = sprintf($title, $this->format($times->all), $counts->all);
        $lines[] = "  " . sprintf(Strings::ROW_DAILY_SUCCESS_BUILD_TIME, $this->format($times->success), $counts->success);
        $lines[] = "  " . sprintf(Strings::ROW_DAILY_FAIL_BUILD_TIME, $this->format($times->fail), $counts->fail);

        return $lines;
    }

    /**
     * Returns the SwiftBar action string for a filter selection menu item.
     *
     * @param string $type Selection type ("all", "workspace", or "project")
     * @param string $name Workspace or project name
     * @param bool $add Whether to add to current selection or replace it
     * @return string SwiftBar action string, or empty string if name contains unsupported characters
     */
    private function getActionForProjectSelection($type, $name, $add)
    {
        // Bitbar doesn't handle correctly " and ' in parameters (maybe other caharcters) and no way to correctly escape
        // so if it is in name no action allowed.
        // TODO create issue on bitbar git
        if (preg_match('~["\']~', $name)) {
            return "";
        }
        $mode = $add ? "add" : "set";
        return "| bash='" . __FILE__ . "' param1=config param2=filter_toggle param3=$type param4=\"$name\" param5=$mode refresh=true terminal=false";
    }

    /**
     * Returns the SwiftBar action string for a statistics selection menu item.
     * Statistic names are fixed constants, so they need no escaping.
     *
     * @param string $statistic One of the BuildTimesConfig::statistic* constants
     * @param bool $add Whether to add to current selection or replace it
     * @return string SwiftBar action string
     */
    private function getActionForStatisticSelection($statistic, $add)
    {
        $mode = $add ? "add" : "set";
        return "| bash='" . __FILE__ . "' param1=config param2=statistic_toggle param3=$statistic param4=$mode refresh=true terminal=false";
    }

    /**
     * @param bool $shown Whether the action turns the distribution chart on or off
     * @return string
     */
    private function getActionForDistribution($shown)
    {
        $state = $shown ? "on" : "off";
        return "| bash='" . __FILE__ . "' param1=config param2=distribution_show param3=$state refresh=true terminal=false";
    }
}

final class BuildTimesOutput
{
    /** @var BuildTimesData */
    var $todayData;
    /** @var BuildTimesData */
    var $totalData;
    /** @var DailyTimesData */
    var $dailyData;
    /** @var DataRow */
    var $lastBuild;
    /** @var int[] */
    var $inProgress = [];
    /** @var string[] */
    var $warnings = [];
    /** @var string[] */
    var $workspaces = [];
    /** @var string[] */
    var $projects = [];
    /** @var string[] - machine id => spec; the "" key marks rows with no machine id (labelled at render time) */
    var $machines = [];
    /** @var bool - true if some rows predate the machine columns (old format) */
    var $hasBuildsWithoutMachine = false;
    /** @var string[] - empty means all */
    var $selectedWorkspaces = [];
    /** @var string[] - empty means all */
    var $selectedProjects = [];
    /** @var string[] - empty means all, "" means unknown, "@current" means the current machine, otherwise specific ids */
    var $selectedMachines = [];
    /** @var string[] - which statistics to show */
    var $selectedStatistics = [];
    /** @var bool */
    var $showDistribution = false;
    /** @var string - id of the machine the plugin currently runs on */
    var $currentMachineId = "";
    /** @var string - spec of the current machine */
    var $currentMachineSpec = "";
}

/**
 * One value for all builds and the same split by result, as every number in the menu is shown.
 */
final class BuildTimesValues
{
    /** @var int */
    var $all;
    /** @var int */
    var $success;
    /** @var int */
    var $fail;

    /**
     * @param int $all
     * @param int $success
     * @param int $fail
     */
    public function __construct($all, $success, $fail)
    {
        $this->all = $all;
        $this->success = $success;
        $this->fail = $fail;
    }
}

final class BuildTimesData
{
    /** @var BuildTimesValues */
    var $counts;
    /** @var BuildTimesValues */
    var $time;
    /** @var BuildTimesValues */
    var $average;
    /** @var BuildTimesValues */
    var $median;
    /** @var int[] Number of builds keyed by the upper bound of their duration bucket, in seconds */
    var $distribution;
    /** @var DateTime */
    var $dataFrom;
    /** @var DateTime */
    var $dataTo;
}

final class DailyTimesData
{
    /** @var int */
    var $days;
    /** @var BuildTimesValues */
    var $averageTime;
    /** @var BuildTimesValues */
    var $averageCount;
    /** @var BuildTimesValues */
    var $medianTime;
    /** @var BuildTimesValues */
    var $medianCount;
}

final class DataRow
{
    /** @var DateTime */
    var $date;
    /** @var integer */
    var $count;
    /** @var string */
    var $type;
    /** @var string */
    var $workspace;
    /** @var string */
    var $project;
    /** @var string */
    var $machineId;
    /** @var string */
    var $machineSpec;
}

/**
 * Identifies the Mac the plugin runs on.
 *
 * The id is a stable, non-sensitive digest of the Hardware UUID, so it is never carried
 * over by a copied config. The slow-to-detect spec is cached in the machine file keyed by
 * the id; the cache is purely a performance optimization and is safe to delete.
 */
final class Machine
{
    /** @var string */
    private $cacheFile;

    /**
     * @param string $cacheFile Path to the machine cache file
     */
    public function __construct($cacheFile)
    {
        $this->cacheFile = $cacheFile;
    }

    /**
     * Returns [machineId, spec] for recording a build. An empty spec is recomputed (and not cached),
     * so a failed detection is retried on the next build until a complete spec is captured.
     *
     * @return array - [machineId, spec], or ["", ""] when the machine can't be identified
     */
    public function forBuild()
    {
        return $this->resolve(true);
    }

    /**
     * Returns [machineId, spec] for display on the render path. An empty spec is accepted and the
     * spec is computed at most once, so the frequent render never re-runs the slow detection.
     *
     * @return array - [machineId, spec], or ["", ""] when the machine can't be identified
     */
    public function forDisplay()
    {
        return $this->resolve(false);
    }

    /**
     * Resolves [machineId, spec], reusing the cache only when its id matches the live hardware
     * (so a stale or copied cache self-heals). The spec is recomputed on a hardware mismatch;
     * $recomputeIfEmpty additionally recomputes (and skips caching) an empty spec.
     *
     * @param bool $recomputeIfEmpty Whether an empty spec should be recomputed instead of reused
     * @return array - [machineId, spec], or ["", ""] when the machine can't be identified
     */
    private function resolve($recomputeIfEmpty)
    {
        $machineId = $this->id();
        if ($machineId === "") {
            return ["", ""];
        }

        $cache = $this->readCache();
        if ($cache !== null && $cache["machineId"] === $machineId
            && !($recomputeIfEmpty && $cache["spec"] === "")) {
            return [$machineId, $cache["spec"]];
        }

        $spec = $this->spec();
        if (!$recomputeIfEmpty || $spec !== "") {
            $this->writeCache($machineId, $spec);
        }

        return [$machineId, $spec];
    }

    /**
     * Returns the machine id (12 hex chars of sha256 over the Hardware UUID), or "" if unreadable.
     *
     * @return string
     */
    private function id()
    {
        $uuid = @trim(shell_exec("ioreg -rd1 -c IOPlatformExpertDevice 2>/dev/null | awk -F'\"' '/IOPlatformUUID/{print \$4; exit}'") ?? "");
        return $uuid === "" ? "" : substr(hash("sha256", $uuid), 0, 12);
    }

    /**
     * Builds the spec, e.g. "MacBook Pro (14-inch, 2021) / M1 Pro 8xCPU/14xGPU / 16GB / 512GB".
     * Parts that can't be detected are left out.
     *
     * @return string
     */
    private function spec()
    {
        // Marketing name with screen size and year, stored as raw bytes; fall back to the model id.
        $name = @trim(shell_exec("ioreg -arc IOPlatformDevice -k product-name 2>/dev/null | plutil -extract '0.product-name' raw - 2>/dev/null | base64 --decode 2>/dev/null | tr -d '\\0'") ?? "");
        if ($name === "") {
            $name = @trim(shell_exec("sysctl -n hw.model 2>/dev/null") ?? "");
        }

        // Chip and core counts, e.g. "M1 Pro 8xCPU/14xGPU". Drop the "Apple" prefix to match Apple's spec style.
        $chip = preg_replace('~^Apple\s+~', "", @trim(shell_exec("sysctl -n machdep.cpu.brand_string 2>/dev/null") ?? ""));
        $cpuCores = intval(@shell_exec("sysctl -n hw.physicalcpu 2>/dev/null"));
        $gpuCores = intval(@shell_exec("system_profiler SPDisplaysDataType 2>/dev/null | awk '/Total Number of Cores/{print \$5; exit}'"));
        if ($chip !== "" && $cpuCores > 0) {
            $chip .= " {$cpuCores}xCPU" . ($gpuCores > 0 ? "/{$gpuCores}xGPU" : "");
        }

        $memBytes = intval(@shell_exec("sysctl -n hw.memsize 2>/dev/null"));
        $diskBytes = intval(@shell_exec("diskutil info disk0 2>/dev/null | awk -F'[()]' '/Disk Size/{print \$2}' | awk '{print \$1}'"));

        $parts = array_filter([
            $name,
            $chip,
            $memBytes > 0 ? intval(round($memBytes / 1073741824)) . "GB" : "",
            $this->formatDiskSize($diskBytes),
        ]);

        return implode(" / ", $parts);
    }

    /**
     * Snaps a raw byte capacity to the nearest standard size, e.g. "512GB" or "1TB".
     *
     * @param int $bytes
     * @return string
     */
    private function formatDiskSize($bytes)
    {
        if ($bytes <= 0) {
            return "";
        }

        $gb = $bytes / 1000000000;
        $best = 0;
        foreach ([128, 256, 512, 1024, 2048, 4096, 8192, 16384] as $size) {
            if ($best === 0 || abs($gb - $size) < abs($gb - $best)) {
                $best = $size;
            }
        }

        return $best >= 1024 ? ($best / 1024) . "TB" : $best . "GB";
    }

    /**
     * Reads and validates the machine cache file.
     *
     * @return array|null - decoded {machineId, spec}, or null when absent/invalid
     */
    private function readCache()
    {
        $data = @file_get_contents($this->cacheFile);
        if ($data === false) {
            return null;
        }
        $json = json_decode($data, true);
        return isset($json["machineId"], $json["spec"]) ? $json : null;
    }

    /**
     * Writes the machine cache file (best effort).
     *
     * @param string $machineId
     * @param string $spec
     */
    private function writeCache($machineId, $spec)
    {
        @file_put_contents($this->cacheFile, json_encode(
            ["machineId" => $machineId, "spec" => $spec],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ));
    }
}

/**
 * Records the start time of a build by writing the current timestamp to a file.
 *
 * @param string $startTimeFilePath Path to the start time file
 */
function markStart($startTimeFilePath)
{
    @file_put_contents($startTimeFilePath, "" . time());
    refreshSwiftBar();
}

/**
 * Records the end of a build by calculating duration and appending a row to the CSV data file.
 *
 * @param string $type Build result type ("success" or "fail")
 * @param string $startTimeFilePath Path to the start time file
 * @param string $dataFilePath Path to the CSV data file
 * @param Machine $machine The current machine
 */
function markEnd($type, $startTimeFilePath, $dataFilePath, $machine)
{
    $content = @file_get_contents($startTimeFilePath);
    @unlink($startTimeFilePath);
    if ($content === false) {
        error_log("Unable to open file: $startTimeFilePath");
        exit(1);
    }

    $startTime = intval($content);

    $duration = time() - $startTime;

    if ($duration < 0 || $startTime === 0) {
        error_log("File $startTimeFilePath has invalid format");
        exit(1);
    }

    $workspace = getenv("XcodeWorkspace");
    $workspace = $workspace === false ? "" : $workspace;

    $project = getenv("XcodeProject");
    $project = $project === false ? "" : $project;

    $xcodeVersion = "";
    $xcodeBuild = "";

    $developerDirectory = getenv("XcodeDeveloperDirectory");

    if ($developerDirectory !== false) {
        $xcodeBuild = "";
        // Get Xcode version and build from version plist
        $plist = @simplexml_load_file($developerDirectory . "/../version.plist");
        if ($plist !== false) {
            $xcodeVersion = getPlistValue($plist, "CFBundleShortVersionString");
            $xcodeBuild = getPlistValue($plist, "ProductBuildVersion");
        }
    }

    list($machineId, $machineSpec) = $machine->forBuild();

    $data = [];
    $data[Columns::DATE] = (new DateTime())->format("c");
    $data[Columns::DURATION] = $duration;
    $data[Columns::TYPE] = $type;
    $data[Columns::WORKSPACE] = $workspace;
    $data[Columns::PROJECT] = $project;
    $data[Columns::XCODE_VERSION] = $xcodeVersion;
    $data[Columns::XCODE_BUILD] = $xcodeBuild;
    $data[Columns::MACHINE_ID] = $machineId;
    $data[Columns::MACHINE_SPEC] = $machineSpec;

    (new BuildTimesFileParser($dataFilePath))->appendBuild($data);
    refreshSwiftBar();
}

/**
 * Refreshes the SwiftBar plugin by calling the SwiftBar URL scheme.
 */
function refreshSwiftBar()
{
    $pluginName = basename(__FILE__);
    @exec("open -g " . escapeshellarg("swiftbar://refreshplugin?name=$pluginName") . " > /dev/null 2>&1 &");
}

/**
 * Returns an MD5 hash identifying the current build based on workspace and project paths.
 *
 * @return string MD5 hash string
 */
function getBuildHash()
{
    $buildHash = "";
    $workspacePath = getenv("XcodeWorkspacePath");
    if ($workspacePath !== false) {
        $buildHash .= $workspacePath;
    }
    $projectPath = getenv("XcodeProjectPath");
    if ($projectPath !== false) {
        $buildHash .= $projectPath;
    }

    return md5($buildHash);
}

/**
 * Downloads and installs the latest version of this plugin from GitHub.
 *
 * @param string $where Directory to store the temporary update file
 * @param bool $showAlerts Whether to show macOS alert dialogs for status messages
 */
function update($where, $showAlerts)
{
    $options = array(
        'http' => array(
            'method' => "GET",
            'headers' => [
                "User-Agent: " . Config::UPDATE_USER_AGENT,
                "Cache-Control: no-cache",
                "Pragma: no-cache",
            ]
        )
    );

    $data = @file_get_contents(Config::UPDATE_URL, false, stream_context_create($options));

    if ($data === false) {
        error_log("Unable to download update file from: " . Config::UPDATE_URL);
        if ($showAlerts) {
            showAlert(Strings::UPDATE_ALERT_FAIL_MESSAGE);
        }
        exit(1);
    }

    $selfData = @file_get_contents(__FILE__, false);
    if ($selfData !== false) {
        // Compare hash (without shebangs)
        $hash1 = hash("sha256", preg_replace("/^#!.*?\n/", "", $data, 1));
        $hash2 = hash("sha256", preg_replace("/^#!.*?\n/", "", $selfData, 1));
        if ($hash1 === $hash2) {
            // Show alert message:
            error_log("No update available.");
            if ($showAlerts) {
                showAlert(Strings::UPDATE_ALERT_NO_UPDATES_MESSAGE);
            }
            exit(1);
        }
    }

    // Move current shebang to updated file.
    if (preg_match("/^(#!.*?)\n/", $selfData, $matches) === 1) {
        $data = preg_replace("/^#!.*?\n/", "$matches[1]\n", $data, 1);
    }

    $updateFile = $where . DIRECTORY_SEPARATOR . Config::UPDATE_TMP_FILE_NAME;
    $result = @file_put_contents($updateFile, $data);
    if ($result === false) {
        error_log("Unable to write update file to: " . $updateFile);
        if ($showAlerts) {
            showAlert(Strings::UPDATE_ALERT_FAIL_MESSAGE);
        }
        exit(1);
    }

    if ($result !== strlen($data)) {
        error_log("Unable to write update file to: " . $updateFile);
        if ($showAlerts) {
            showAlert(Strings::UPDATE_ALERT_FAIL_MESSAGE);
        }
        exit(1);
    }

    $result = chmod($updateFile, 0755);
    if ($result === false) {
        error_log("Unable change permission of $updateFile to 0755");
        if ($showAlerts) {
            showAlert(Strings::UPDATE_ALERT_FAIL_MESSAGE);
        }
        exit(1);
    }

    $result = rename($updateFile, __FILE__);
    if ($result === false) {
        error_log("Unable to rename $updateFile to " . __FILE__);
        if ($showAlerts) {
            showAlert(Strings::UPDATE_ALERT_FAIL_MESSAGE);
        }
        exit(1);
    }

    // Show alert message
    echo "Update successful";
    if ($showAlerts) {
        showAlert(Strings::UPDATE_ALERT_SUCCESS_MESSAGE);
    }
    exit(0);
}

/**
 * Displays a macOS alert dialog with the given message.
 *
 * @param string $message Alert message to display
 */
function escapeAppleScript($string)
{
    return str_replace(['\\', '"'], ['\\\\', '\\"'], $string);
}

function showAlert($message)
{
    $title = escapeAppleScript(Strings::UPDATE_ALERT_TITLE);
    $message = escapeAppleScript($message);
    $command = "osascript -e " . escapeshellarg('display alert "' . $title . '" message "' . $message . '"') . " > /dev/null 2>&1 &";
    exec($command);
}

/**
 * Shows a blocking alert with the given buttons and returns the chosen button name (or "" if none).
 *
 * @param string $message Alert message
 * @param string[] $buttons Button names, e.g. ["Cancel", "OK"]
 * @param string $default Default button name
 * @return string
 */
function confirmDialog($message, $buttons, $default)
{
    $buttonList = '{"' . implode('", "', $buttons) . '"}';
    $alert = 'display alert "' . escapeAppleScript(Strings::UPDATE_ALERT_TITLE) . '" message "' . escapeAppleScript($message) . '" buttons ' . $buttonList . ' default button "' . $default . '"';
    // Second -e returns just the clicked button name (the alert itself returns "button returned:X").
    return @trim(shell_exec("osascript -e " . escapeshellarg($alert) . " -e " . escapeshellarg("button returned of result")) ?? "");
}

/**
 * Processes a configuration change command from CLI arguments.
 *
 * @param string[] $argv CLI arguments
 * @param string $configFilePath Path to the config JSON file
 * @param Machine $machine The current machine
 */
function processConfigChange($argv, $configFilePath, $machine)
{
    if (count($argv) < 3) {
        return;
    }

    $config = new BuildTimesConfig($configFilePath, $machine);

    switch ($argv[2]) {
        case "filter_toggle":
            $type = isset($argv[3]) ? $argv[3] : "";
            $name = isset($argv[4]) ? $argv[4] : "";
            $mode = isset($argv[5]) ? $argv[5] : "set";
            if (!preg_match("~set|add~", $mode)) {
                $mode = "set";
            }
            switch ($type) {
                case "all":
                    // "wp" scopes the reset to the project/workspace submenu; otherwise reset everything.
                    if ($name === "wp") {
                        $config->selectedWorkspaces = [];
                        $config->selectedProjects = [];
                    } else {
                        $config->selectAll();
                    }
                    $config->save($configFilePath);
                    break;
                case "workspace":
                    $config->toggleWorkspace($name, $mode === "add");
                    $config->save($configFilePath);
                    break;
                case "project":
                    $config->toggleProject($name, $mode === "add");
                    $config->save($configFilePath);
                    break;
                case "machine":
                    // "current"/"none" name the current-machine and unknown entries, since "@current"
                    // and an empty id don't make clean menu params; "all" clears the machine filter.
                    if ($name === "all") {
                        $config->selectedMachines = [];
                    } elseif ($name === "current") {
                        $config->toggleMachine("@current", $mode === "add");
                    } elseif ($name === "none") {
                        $config->toggleMachine("", $mode === "add");
                    } else {
                        $config->toggleMachine($name, $mode === "add");
                    }
                    $config->save($configFilePath);
                    break;
            }
            break;
        case "statistic_toggle":
            $name = isset($argv[3]) ? $argv[3] : "";
            $mode = isset($argv[4]) ? $argv[4] : "set";
            if (!preg_match("~set|add~", $mode)) {
                $mode = "set";
            }
            if ($name === BuildTimesConfig::statisticAverage || $name === BuildTimesConfig::statisticMedian) {
                $config->toggleStatistic($name, $mode === "add");
                $config->save($configFilePath);
            }
            break;
        case "distribution_show":
            $config->showDistribution = isset($argv[3]) && $argv[3] === "on";
            $config->save($configFilePath);
            break;
    }
}

/**
 * Copies a file from source to destination, capturing any error message on failure.
 *
 * @param string $source Path to the source file
 * @param string $destination Path to the destination file
 * @return string|null Error message on failure, null on success
 */
function copyFile($source, $destination) {
    $error = null;
    set_error_handler(function ($errno, $errstr) use (&$error) {
        $error = $errstr;
    });
    $result = copy($source, $destination);
    restore_error_handler();
    if (!$result && $error === null) {
        $error = "Unknown error";
    }
    return $result ? null : $error;
}

/**
 * Backs up the data file next to itself as "<name>.backup.<timestamp>.<ext>" before a destructive
 * operation. The backup is kept (the plugin only ever reads the exact data file name).
 *
 * @param string $dataFilePath Path to the data file
 * @return string|null Path to the backup, or null if there was nothing to back up
 */
function backupDataFile($dataFilePath) {
    if (!file_exists($dataFilePath)) {
        return null;
    }
    $info = pathinfo($dataFilePath);
    $timestamp = (new DateTime())->format("Y-m-d_His");
    $backup = $info["dirname"] . DIRECTORY_SEPARATOR . $info["filename"] . ".backup." . $timestamp . "." . $info["extension"];
    copyFile($dataFilePath, $backup);
    return $backup;
}

/**
 * @param SimpleXMLElement $plist
 * @param string $key
 *
 * @return string
 */
function getPlistValue($plist, $key) {
    $query = '/plist/dict/key[text()="'.$key.'"]/following-sibling::*[1]';
    $result = @$plist->xpath($query);
    if ($result === false) {
        return "";
    }
    if (count($result) === 1) {
        return (string)$result[0];
    } else {
        return "";
    }
}
