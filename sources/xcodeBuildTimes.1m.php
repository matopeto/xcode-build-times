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

final class Config
{
    const ABOUT_URL = "https://github.com/matopeto/xcode-build-times";
    const SWIFTBAR_URL = "https://github.com/swiftbar/SwiftBar";

    const UPDATE_URL = "https://raw.githubusercontent.com/matopeto/xcode-build-times/master/sources/xcodeBuildTimes.1m.php";
    const UPDATE_USER_AGENT = "matopeto/xcode-build-times";

    const DATE_FORMAT = "Y-m-d";

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

    const ROW_SUCCESS_BUILD_TIME = 'Success: %s'; // %s will be replaced with corresponding build time
    const ROW_FAIL_BUILD_TIME = 'Fail: %s'; // %s will be replaced with corresponding build time

    const ROW_DAILY_AVERAGE_BUILD_TIME = 'Daily average: %1$s, %2$s builds'; // // %1$s will be replaced with corresponding build time, %1$s with build counts
    const ROW_DAILY_SUCCESS_BUILD_TIME = 'Success: %1$s, %2$s builds'; // %1$s will be replaced with corresponding build time, %1$s with build counts
    const ROW_DAILY_FAIL_BUILD_TIME = 'Fail: %1$s, %2$s builds'; // %1$s will be replaced with corresponding build time, %1$s with build counts

    const ROW_LAST_BUILD = 'Last build: %1$s, %2$s'; // %1$d will be replaced with status (success/fail) %2$d with duration
    const ROW_BUILD_IN_PROGRESS = 'Build in progress: %s'; // %s will be replaced with current duration of build in progress

    const ROW_REFRESH = "Refresh";
    const ROW_SHARE = "Share";

    const SHARE_HEADER_TODAY = 'Xcode Build Times - Today, %s'; // %s - current date
    const SHARE_HEADER_TODAY_FILTER = 'Xcode Build Times - Today (%1$s), %2$s'; // %1$s - filter, %2$s - current date
    const SHARE_HEADER_TOTAL_SINCE = 'Xcode Build Times - Total, since: %s'; // %s - first date
    const SHARE_HEADER_TOTAL_SINCE_FILTER = 'Xcode Build Times - Total, since: %1$s (%2$s)'; // %1$s - first date, %2$s - filter
    const SHARE_HEADER_TOTAL = 'Xcode Build Times - Total';
    const SHARE_HEADER_TOTAL_FILTER = 'Xcode Build Times - Total (%s)'; // %s - filter
    const SHARE_FOOTER = 'Tracked with Xcode Build Times - %s'; // %s - ABOUT_URL

    const ROW_SETTINGS = "Settings";
    const ROW_SETTINGS_FILTER = "Filter";
    const ROW_SETTINGS_FILTER_ALL = "Show all";
    const ROW_SETTINGS_FILTER_WORKSPACES_PROJECTS = "Project / Workspace";
    const ROW_SETTINGS_FILTER_MACHINE = "Machine";
    const ROW_SETTINGS_FILTER_MACHINE_CURRENT = "(this Mac)";
    const ROW_SETTINGS_FILTER_MACHINE_UNKNOWN = "(unknown)";
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

    const IMPORT_ALERT_CONFIRM = "This will replace all existing build data. Continue?";
    const IMPORT_ALERT_SUCCESS = "Data imported successfully.";
    const IMPORT_ALERT_FAIL = "Unable to import data.\n\n%s"; // %s will be replaced with error message
    const IMPORT_ALERT_INVALID = "Selected file is not a valid build times CSV.";
    const EXPORT_ALERT_SUCCESS = "Data exported successfully.";
    const EXPORT_ALERT_FAIL = "Unable to export data.\n\n%s"; // %s will be replaced with error message
    const EXPORT_ALERT_NO_DATA = "No data file to export.";


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
            $confirmed = @trim(shell_exec("osascript -e " . escapeshellarg('display alert "' . escapeAppleScript(Strings::UPDATE_ALERT_TITLE) . '" message "' . escapeAppleScript($confirmMessage) . '" buttons {"Cancel", "OK"} default button "Cancel"')) ?? "");
            if (strpos($confirmed, "OK") !== false) {
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
    $pipe = popen("pbcopy", "w");
    if ($pipe !== false) {
        fwrite($pipe, $text);
        pclose($pipe);
    }
    showAlert("Copied to clipboard:\n\n" . $text);
    die;
} elseif ($arg === "export") {
    if (!file_exists($dataFilePath)) {
        showAlert(Strings::EXPORT_ALERT_NO_DATA);
        die;
    }
    $output = @trim(shell_exec("osascript -e 'POSIX path of (choose file name with prompt \"Export Build Times Data\" default name \"buildTimes.csv\")'") ?? "");
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
    $selectedFile = @trim(shell_exec("osascript -e 'POSIX path of (choose file of type {\"public.comma-separated-values-text\"} with prompt \"Import Build Times Data\")'") ?? "");
    if ($selectedFile === "" || !file_exists($selectedFile)) {
        die;
    }
    if (!BuildTimesFileParser::isValidFile($selectedFile)) {
        showAlert(Strings::IMPORT_ALERT_INVALID);
        die;
    }
    $confirmed = @trim(shell_exec("osascript -e " . escapeshellarg('display alert "' . escapeAppleScript(Strings::UPDATE_ALERT_TITLE) . '" message "' . escapeAppleScript(Strings::IMPORT_ALERT_CONFIRM) . '" buttons {"Cancel", "OK"} default button "Cancel"')) ?? "");
    if (strpos($confirmed, "OK") === false) {
        die;
    }
    $copyError = copyFile($selectedFile, $dataFilePath);
    if ($copyError === null) {
        showAlert(Strings::IMPORT_ALERT_SUCCESS);
    } else {
        showAlert(sprintf(Strings::IMPORT_ALERT_FAIL, $copyError));
    }
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
                $result->totalData = $this->getData($allRows);
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
                $result->todayData = $this->getData($todayRows);
            }

            // Daily data
            $days = 0;
            $bt = 0;
            $sbt = 0;
            $fbt = 0;
            $bc = 0;
            $sbc = 0;
            $fbc = 0;

            foreach ($rows as $key => $data) {
                // Count only past days
                if ($key >= $todayKey) {
                    continue;
                }

                $dayData = $this->getData($data);
                $days++;
                $bt += $dayData->buildTime;
                $sbt += $dayData->successBuildTime;
                $fbt += $dayData->failBuildTime;
                $bc += $dayData->buildCount;
                $sbc += $dayData->successCount;
                $fbc += $dayData->failCount;
            }

            if ($days > 0) {
                $dailyData = new DailyTimesData();
                $dailyData->days = $days;
                $dailyData->averageBuildTime = intval($bt / $days);
                $dailyData->averageSuccessBuildTime = intval($sbt / $days);
                $dailyData->averageFailBuildTime = intval($fbt / $days);

                $dailyData->averageBuildCount = intval($bc / $days);
                $dailyData->averageSuccessCount = intval($sbc / $days);
                $dailyData->averageFailCount = intval($fbc / $days);

                $result->dailyData = $dailyData;
            }
        }

        $result->selectedWorkspaces = $config->selectedWorkspaces;
        $result->selectedProjects = $config->selectedProjects;
        $result->selectedMachines = $config->selectedMachines;
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
     * @param DataRow[] $rows
     *
     * @return BuildTimesData
     */
    private function getData($rows)
    {
        $buildTime = 0;
        $buildCount = 0;
        $successCount = 0;
        $failCount = 0;
        $failBuildTime = 0;
        $successBuildTime = 0;

        foreach ($rows as $row) {
            $buildCount++;

            if ($row->type == "success") {
                $successCount++;
                $successBuildTime += $row->count;
            } else {
                $failCount++;
                $failBuildTime += $row->count;
            }

            $buildTime += $row->count;
        }

        $averageBuildTime = intval($buildCount == 0 ? 0 : $buildTime / $buildCount);
        $averageFailBuildTime = intval($failCount === 0 ? 0 : $failBuildTime / $failCount);
        $averageSuccessBuildTime = intval($successCount === 0 ? 0 : $successBuildTime / $successCount);

        $result = new BuildTimesData();
        $result->buildCount = $buildCount;
        $result->successCount = $successCount;
        $result->failCount = $failCount;
        $result->averageBuildTime = $averageBuildTime;
        $result->averageSuccessBuildTime = $averageSuccessBuildTime;
        $result->averageFailBuildTime = $averageFailBuildTime;
        $result->buildTime = $buildTime;
        $result->successBuildTime = $successBuildTime;
        $result->failBuildTime = $failBuildTime;
        $result->dataFrom = !empty($rows) ? reset($rows)->date : null;
        $result->dataTo = !empty($rows) ? end($rows)->date : null;

        return $result;
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

    /** @var string - id of the machine the plugin currently runs on */
    var $currentMachineId = "";

    /** @var string - spec of the current machine, for display */
    var $currentMachineSpec = "";

    /** @var DateTimeZone|null */
    var $localTimeZone = null;

    /** @var bool */
    private $localTimeZoneAutodetect = true;

    const selectedWorkspacesKey = "selectedWorkspaces";
    const selectedProjectsKey = "selectedProjects";
    const selectedMachinesKey = "selectedMachines";
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
        } elseif ($add) {
            $this->selectedMachines[] = $machineId;
        } else {
            $this->selectedMachines = [$machineId];
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
        $buildTime = @$this->data->todayData->buildTime ?: 0;
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

        $todayBuildTime = $this->getFormatedTimeRow($todayData, "buildTime", Strings::ROW_BUILD_TIME);
        $totalBuildTime = $this->getFormatedTimeRow($totalData, "buildTime", Strings::ROW_BUILD_TIME);

        $todaySuccessBuildTime = $this->getFormatedTimeRow($todayData, "successBuildTime", Strings::ROW_SUCCESS_BUILD_TIME);
        $totalSuccessBuildTime = $this->getFormatedTimeRow($totalData, "successBuildTime", Strings::ROW_SUCCESS_BUILD_TIME);

        $todayFailBuildTime = $this->getFormatedTimeRow($todayData, "failBuildTime", Strings::ROW_FAIL_BUILD_TIME);
        $totalFailBuildTime = $this->getFormatedTimeRow($totalData, "failBuildTime", Strings::ROW_FAIL_BUILD_TIME);

        $todayAverageBuildTime = $this->getFormatedTimeRow($todayData, "averageBuildTime", Strings::ROW_AVERAGE_BUILD_TIME);

        $todayAverageSuccessBuildTime = $this->getFormatedTimeRow($todayData, "averageSuccessBuildTime", Strings::ROW_SUCCESS_BUILD_TIME);
        $totalAverageSuccessBuildTime = $this->getFormatedTimeRow($totalData, "averageSuccessBuildTime", Strings::ROW_SUCCESS_BUILD_TIME);
        $todayAverageFailBuildTime = $this->getFormatedTimeRow($todayData, "averageFailBuildTime", Strings::ROW_FAIL_BUILD_TIME);
        $totalAverageFailBuildTime = $this->getFormatedTimeRow($totalData, "averageFailBuildTime", Strings::ROW_FAIL_BUILD_TIME);
        $totalAverageBuildTime = $this->getFormatedTimeRow($totalData, "averageBuildTime", Strings::ROW_AVERAGE_BUILD_TIME);

        // Build time

        $buildTimeSubMenu = [];
        $buildTimeSubMenu[] = "-- " . $todaySuccessBuildTime;
        $buildTimeSubMenu[] = "-- " . $totalSuccessBuildTime . $alternate;
        $buildTimeSubMenu[] = "-- " . $todayFailBuildTime;
        $buildTimeSubMenu[] = "-- " . $totalFailBuildTime . $alternate;

        $rows[] = $todayBuildTime;
        $rows = array_merge($rows, $buildTimeSubMenu);

        $rows[] = $totalBuildTime . $alternate;
        $rows = array_merge($rows, $buildTimeSubMenu);

        // Average build time

        $averageBuildTimeSubMenu = [];
        $averageBuildTimeSubMenu[] = "-- " . $todayAverageSuccessBuildTime;
        $averageBuildTimeSubMenu[] = "-- " . $totalAverageSuccessBuildTime . $alternate;
        $averageBuildTimeSubMenu[] = "-- " . $todayAverageFailBuildTime;
        $averageBuildTimeSubMenu[] = "-- " . $totalAverageFailBuildTime . $alternate;

        $rows[] = $todayAverageBuildTime;
        $rows = array_merge($rows, $averageBuildTimeSubMenu);

        $rows[] = $totalAverageBuildTime . $alternate;
        $rows = array_merge($rows, $averageBuildTimeSubMenu);

        // Daily data

        if ($dailyData !== null) {
            $rows[] = sprintf(Strings::ROW_DAILY_AVERAGE_BUILD_TIME, $this->format($dailyData->averageBuildTime), $dailyData->averageBuildCount);
            $rows[] = "-- " . sprintf(Strings::ROW_DAILY_SUCCESS_BUILD_TIME, $this->format($dailyData->averageSuccessBuildTime), $dailyData->averageSuccessCount);
            $rows[] = "-- " . sprintf(Strings::ROW_DAILY_FAIL_BUILD_TIME, $this->format($dailyData->averageFailBuildTime), $dailyData->averageFailCount);
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
     * @param BuildTimesData $data
     * @return string
     */
    private function getBuildCountsRow($data)
    {
        if ($data === null) {
            return Strings::ROW_BUILD_COUNTS_NO_BUILDS;
        }

        return sprintf(Strings::ROW_BUILD_COUNTS, $data->buildCount, $data->successCount, $data->failCount);
    }

    /**
     * @param BuildTimesData $data
     * @param string $property
     * @param string $title
     *
     * @return string
     */
    private function getFormatedTimeRow($data, $property, $title)
    {
        if ($data === null) {
            return sprintf($title, Strings::MSG_NO_BUILDS_YET);
        }

        $formatted = $this->format($data->{$property});

        return sprintf($title, $formatted);
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
     *
     * @return string
     */
    private function format($seconds)
    {
        $seconds = round($seconds);
        $dtF = new DateTime('@0');
        $dtT = new DateTime("@$seconds");
        $interval = $dtT->diff($dtF);
        if ($seconds < 60) {
            return "{$seconds}s";
        } else if ($seconds < 3600) {
            return $interval->format("%im %ss");
        } elseif ($seconds < 86400) {
            return $interval->format("%hh %im");
        } else {
            return $interval->format("%ad %hh");
        }
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
            $lines[] = sprintf(Strings::ROW_BUILD_COUNTS, $data->buildCount, $data->successCount, $data->failCount);
            $lines[] = sprintf(Strings::ROW_BUILD_TIME, $this->format($data->buildTime));
            $lines[] = "  " . sprintf(Strings::ROW_SUCCESS_BUILD_TIME, $this->format($data->successBuildTime));
            $lines[] = "  " . sprintf(Strings::ROW_FAIL_BUILD_TIME, $this->format($data->failBuildTime));
            $lines[] = sprintf(Strings::ROW_AVERAGE_BUILD_TIME, $this->format($data->averageBuildTime));
            $lines[] = "  " . sprintf(Strings::ROW_SUCCESS_BUILD_TIME, $this->format($data->averageSuccessBuildTime));
            $lines[] = "  " . sprintf(Strings::ROW_FAIL_BUILD_TIME, $this->format($data->averageFailBuildTime));
        }

        if ($dailyData !== null) {
            $lines[] = sprintf(Strings::ROW_DAILY_AVERAGE_BUILD_TIME, $this->format($dailyData->averageBuildTime), $dailyData->averageBuildCount);
            $lines[] = "  " . sprintf(Strings::ROW_DAILY_SUCCESS_BUILD_TIME, $this->format($dailyData->averageSuccessBuildTime), $dailyData->averageSuccessCount);
            $lines[] = "  " . sprintf(Strings::ROW_DAILY_FAIL_BUILD_TIME, $this->format($dailyData->averageFailBuildTime), $dailyData->averageFailCount);
        }

        $lines[] = "";
        $lines[] = sprintf(Strings::SHARE_FOOTER, Config::ABOUT_URL);

        return implode("\n", $lines);
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
    /** @var string - id of the machine the plugin currently runs on */
    var $currentMachineId = "";
    /** @var string - spec of the current machine */
    var $currentMachineSpec = "";
}

final class BuildTimesData
{
    /** @var int */
    var $buildCount;
    /** @var int */
    var $successCount;
    /** @var int */
    var $failCount;
    /** @var int */
    var $averageBuildTime;
    /** @var int */
    var $averageSuccessBuildTime;
    /** @var int */
    var $averageFailBuildTime;
    /** @var int */
    var $buildTime;
    /** @var int */
    var $successBuildTime;
    /** @var int */
    var $failBuildTime;
    /** @var DateTime */
    var $dataFrom;
    /** @var DateTime */
    var $dataTo;
}

final class DailyTimesData
{
    /** @var int */
    var $days;
    /** @var int */
    var $averageBuildCount;
    /** @var int */
    var $averageSuccessCount;
    /** @var int */
    var $averageFailCount;
    /** @var int */
    var $averageBuildTime;
    /** @var int */
    var $averageSuccessBuildTime;
    /** @var int */
    var $averageFailBuildTime;
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
