#!/usr/bin/php
<?php

# <bitbar.title>Xcode build times</bitbar.title>
# <bitbar.version>v1.0</bitbar.version>
# <bitbar.author>Mato Peto</bitbar.author>
# <bitbar.author.github>mato-peto</bitbar.author.github>
# <bitbar.desc>Shows today's and total time you spend waiting to Xcode finish building.</bitbar.desc>
# <bitbar.image>https://raw.githubusercontent.com/matopeto/xcode-build-times/master/screenshots/menubar-extended.png</bitbar.image>
# <bitbar.dependencies>php,xcode</bitbar.dependencies>
# <bitbar.abouturl>https://github.com/matopeto/xcode-build-timese</bitbar.abouturl>

final class Config
{
    const ICON = "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DEwlCw2cQ+CYleoN5kOCSNk1iorrkOzTCcXrh6/9K/wc/g/iTy7+CmVuZHN0cmVhbQplbmRvYmoKNCAwIG9iagogICAyNjY1CmVuZG9iagoyIDAgb2JqCjw8CiAgIC9FeHRHU3RhdGUgPDwKICAgICAgL2EwIDw8IC9DQSAxIC9jYSAxID4+CiAgID4+Cj4+CmVuZG9iago1IDAgb2JqCjw8IC9UeXBlIC9QYWdlCiAgIC9QYXJlbnQgMSAwIFIKICAgL01lZGlhQm94IFsgMCAwIDE3IDE3IF0KICAgL0NvbnRlbnRzIDMgMCBSCiAgIC9Hcm91cCA8PAogICAgICAvVHlwZSAvR3JvdXAKICAgICAgL1MgL1RyYW5zcGFyZW5jeQogICAgICAvSSB0cnVlCiAgICAgIC9DUyAvRGV2aWNlUkdCCiAgID4+CiAgIC9SZXNvdXJjZXMgMiAwIFIKPj4KZW5kb2JqCjEgMCBvYmoKPDwgL1R5cGUgL1BhZ2VzCiAgIC9LaWRzIFsgNSAwIFIgXQogICAvQ291bnQgMQo+PgplbmRvYmoKNiAwIG9iago8PCAvQ3JlYXRvciAoY2Fpcm8gMS4xNC44IChodHRwOi8vY2Fpcm9ncmFwaGljcy5vcmcpKQogICAvUHJvZHVjZXIgKGNhaXJvIDEuMTQuOCAoaHR0cDovL2NhaXJvZ3JhcGhpY3Mub3JnKSkKPj4KZW5kb2JqCjcgMCBvYmoKPDwgL1R5cGUgL0NhdGFsb2cKICAgL1BhZ2VzIDEgMCBSCj4+CmVuZG9iagp4cmVmCjAgOAowMDAwMDAwMDAwIDY1NTM1IGYgCjAwMDAwMDMwNjQgMDAwMDAgbiAKMDAwMDAwMjc4MCAwMDAwMCBuIAowMDAwMDAwMDE1IDAwMDAwIG4gCjAwMDAwMDI3NTcgMDAwMDAgbiAKMDAwMDAwMjg1MiAwMDAwMCBuIAowMDAwMDAzMTI5IDAwMDAwIG4gCjAwMDAwMDMyNTYgMDAwMDAgbiAKdHJhaWxlcgo8PCAvU2l6ZSA4CiAgIC9Sb290IDcgMCBSCiAgIC9JbmZvIDYgMCBSCj4+CnN0YXJ0eHJlZgozMzA4CiUlRU9GCg==";
    const ICON_URL = "https://icons8.com";

    const ABOUT_URL = "https://github.com/matopeto/xcode-build-times";

    const UPDATE_URL = "https://raw.githubusercontent.com/matopeto/xcode-build-times/master/sources/xcodeBuildTimes.1m.php";
    const UPDATE_USER_AGENT = "matopeto/xcode-build-times";

    const DATE_FORMAT = "Y-m-d";

    const DATA_FILE_DIR = ".xcodeBuildTimes"; // Must be hidden (start with ".")
    const DATA_FILE_NAME = "buildTimes.csv";
    const START_TIME_FILE_NAME = "buildStartTime";
    const UPDATE_TMP_FILE_NAME = ".newVersion";
    const CONFIG_FILE_NAME = "config.json";
}

final class Strings
{
    const WARNING_UNABLE_TO_READ_DATA_FILE = "Unable to read data file";
    const WARNING_PROBLEM_WITH_DATA_FILE = "There is some problem with data";
    const WARNING_NO_DATA = "No data";
    const ROW_WARNING = ":warning: %s"; // %s will be replaced by warning message

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

    const ROW_REFRESH = "Refresh";

    const ROW_SETTINGS = "Settings";
    const ROW_SETTINGS_FILTER = "Filter";
    const ROW_SETTINGS_FILTER_ALL = "Show all";
    const ROW_SETTINGS_RESET = "Reset";
    const ROW_SETTINGS_RESET_REALLY = "Really?";
    const ROW_SETTINGS_RESET_REALLY_YES = "Yes";


    const ROW_ABOUT = "About";
    const ROW_ABOUT_ICON = "Icon by Icons8";
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

$buildHash = getBuildHash();

$scriptDirectory = realpath(__DIR__);
$dataDirectory = $scriptDirectory . DIRECTORY_SEPARATOR . Config::DATA_FILE_DIR;
$dataFilePath = $dataDirectory . DIRECTORY_SEPARATOR . Config::DATA_FILE_NAME;
$configFilePath = $dataDirectory . DIRECTORY_SEPARATOR . Config::CONFIG_FILE_NAME;
$startTimeFilePath = $dataDirectory . DIRECTORY_SEPARATOR . Config::START_TIME_FILE_NAME . "." . $buildHash;

if (!file_exists($dataDirectory)) {
    mkdir($dataDirectory);
}

$idAlertMessage = getenv("IDEAlertMessage");
$arg = $argc > 1 ? $argv[1] : null;

if ($idAlertMessage === "Build Started" || $arg === "start") {
    markStart($startTimeFilePath);
    die;
} elseif ($idAlertMessage === "Build Succeeded" || $arg === "success") {
    markEnd("success", $startTimeFilePath, $dataFilePath);
    die;
} elseif ($idAlertMessage === "Build Failed" || $arg === "fail") {
    markEnd("fail", $startTimeFilePath, $dataFilePath);
    die;
} elseif ($arg === "reset") {
    unlink($dataFilePath);
    die;
} elseif ($arg === "update") {
    $showAlerts = $argc > 2 && $argv[2] == "showAlerts";
    update($dataDirectory, $showAlerts);
    die;
} elseif ($arg === "config") {
    processConfigChange($argv, $configFilePath);
    die;
}

$config = new BuildTimesConfig($configFilePath);
$parser = new BuildTimesFileParser($dataFilePath, $config);
$data = $parser->getOutput();
$renderer = new BitBarRenderer($data, $config);
$renderer->render();

final class BuildTimesFileParser
{
    /** @var string */
    private $dataFile;

    /** @var BuildTimesConfig */
    private $config;

    /** @var DateTimeZone */
    private $localTimeZone;

    /**
     * BuildTimesFileParser constructor.
     * @param string $dataFile
     * @param BuildTimesConfig $config
     */
    public function __construct($dataFile, $config)
    {
        $this->dataFile = $dataFile;
        $this->config = $config;
        $this->localTimeZone = $config->localTimeZone;
    }

    /**
     * Returns output data parsed from file
     * @return BuildTimesOutput
     */
    public function getOutput()
    {
        $result = new BuildTimesOutput();

        // Read CSV
        $handle = @fopen($this->dataFile, "r");

        if ($handle === FALSE) {
            $result->warnings[] = Strings::WARNING_UNABLE_TO_READ_DATA_FILE;
        } else {
            /**
             * @var DataRow[][] $rows
             * @var boolean $problemWithData
             * @var string[] $workspaces
             * @var string[] $projects
             */
            list($rows, $problemWithData, $workspaces, $projects) = $this->parseFile($handle, $this->config);
            if ($problemWithData) {
                $result->warnings[] = Strings::WARNING_PROBLEM_WITH_DATA_FILE;
            }

            $workspaces = array_unique(
                array_filter(
                    array_merge(
                        $this->config->selectedWorkspaces,
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
                        $this->config->selectedProjects,
                        $projects
                    ),
                    function ($item) {
                        return $item !== null && !empty($item);
                    }
                )
            );
            sort($projects);
            $result->projects = $projects;

            /** @var DataRow[] $allRows */
            $allRows = array_reduce($rows, function ($all, $item) {
                return array_merge($all, $item);
            }, []);

            usort($allRows, function ($a, $b) {
                return $a->date > $b->date;
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
                $todayKey = (new DateTime("now", $this->localTimeZone))->format("Y-m-d");
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

        $result->selectedWorkspaces = $this->config->selectedWorkspaces;
        $result->selectedProjects = $this->config->selectedProjects;

        return $result;
    }

    /**
     * @param resource $handle
     *
     * @param BuildTimesConfig $config
     * @return array - array with two values, first is [DataRow], second is boolean
     */
    private function parseFile($handle, $config)
    {
        $workspaces = [];
        $projects = [];
        $rows = [];
        $problemWithData = false;
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($row) < 3) {
                $problemWithData = true;
                continue;
            }

            $dateInOldFormat = false;
            if (preg_match('~[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}~', $row[0])) {
                $dateInOldFormat = true;
            }

            if (!preg_match('~[0-9]+~', $row[1])) {
                $problemWithData = true;
                continue;
            }

            if (!preg_match('~fail|success~', $row[2])) {
                $problemWithData = true;
                continue;
            }

            /** @var DateTime $date */
            $date = NULL;

            $dataRow = new DataRow();

            if ($dateInOldFormat) {
                // Old format (without time zone)
                $date = DateTime::createFromFormat("Y-m-d H:i:s", $row[0]);
                if ($date == false) {
                    $problemWithData = true;
                    continue;
                }
            } else {
                // New format (with time zone)
                try {
                    $date = new DateTime($row[0]);
                } catch (Exception $e) {
                    $problemWithData = true;
                    continue;
                }
            }

            $dataRow->date = $date;
            $dataRow->count = intval($row[1]);
            $dataRow->type = $row[2];
            if (count($row) >= 5) {
                $dataRow->workspace = $row[3];
                $dataRow->project = $row[4];
                $workspaces[$dataRow->workspace] = true;
                $projects[$dataRow->project] = true;
            }

            $keyDate = clone $date;
            if ($this->localTimeZone !== null) {
                $keyDate->setTimezone($this->localTimeZone);
            }
            $key = $keyDate->format("Y-m-d");

            if ($this->isSelected($dataRow, $config)) {
                $rows[$key][] = $dataRow;
            }
        }

        fclose($handle);

        $workspaces = array_keys($workspaces);
        $projects = array_keys($projects);

        return [$rows, $problemWithData, $workspaces, $projects];
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
        if (empty($config->selectedWorkspaces) && empty($config->selectedProjects)) {
            // All is selected
            return true;
        }

        return in_array($dataRow->workspace, $config->selectedWorkspaces) || in_array($dataRow->project, $config->selectedProjects);
    }
}

final class BuildTimesConfig
{
    /** @var [string] */
    var $selectedWorkspaces = [];

    /** @var [string] */
    var $selectedProjects = [];

    /** @var DateTimeZone|null */
    var $localTimeZone = null;

    /** @var Bool */
    private $localTimeZoneAutodetect = true;

    const selectedWorkspacesKey = "selectedWorkspaces";
    const selectedProjectsKey = "selectedProjects";
    const selectedLocalTimeZoneKey = "localTimeZone";

    public function __construct($configFile)
    {
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

    function selectAll()
    {
        $this->selectedWorkspaces = [];
        $this->selectedProjects = [];
    }

    function save($configFile)
    {
        $dataToSave = [
            self::selectedProjectsKey => $this->selectedProjects,
            self::selectedWorkspacesKey => $this->selectedWorkspaces,
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
        // On macOS /etc/localtime is symlink to file with time zone info, e.g.
        // /etc/localtime -> /var/db/timezone/zoneinfo/Europe/Prague
        // we read this file to determine local time zone
        $link = "/etc/localtime";
        if (is_link($link)) {
            $realPath = realpath($link);
            $timeZone = preg_replace("~.*zoneinfo/~", "", $realPath);

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
     * @param $data BuildTimesOutput
     * @param $config BuildTimesConfig
     */
    public function __construct($data, $config)
    {
        $this->data = $data;
        $this->localTimeZone = $config->localTimeZone;
    }

    public function render()
    {
        $this->renderHeader();
        $this->renderContent();
        $this->renderFooter();
    }

    private function renderHeader()
    {
        $row = "";
        $buildTime = @$this->data->todayData->buildTime ?: 0;
        $row .= $this->format($buildTime);

        if (count($this->data->warnings) > 0) {
            $row .= " :warning:";
        }
        $row .= " | templateImage=" . Config::ICON;

        $this->renderRows([$row, "---"]);
    }

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

        // Today and on alt total info
        $todayData = $this->data->todayData;
        $totalData = $this->data->totalData;
        $dailyData = $this->data->dailyData;

        $alternate = "| alternate=true";
        $selectedFilter = array_merge($this->data->selectedWorkspaces, $this->data->selectedProjects);
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

    private function renderFooter()
    {
        $rows = [];
        $rows[] = "---";
        $rows[] = Strings::ROW_REFRESH . "| refresh=true";

        $this->renderRows($rows);

        $this->renderPreferences();
        $this->renderAbout();
    }

    private function renderPreferences()
    {
        $rows = [];
        $rows[] = Strings::ROW_SETTINGS;
        $this->renderRows($rows);

        $this->renderFilter();

        $rows = [];
        $rows[] = "-- " . Strings::ROW_SETTINGS_RESET;
        $rows[] = "---- " . Strings::ROW_SETTINGS_RESET_REALLY;
        $rows[] = "------ " . Strings::ROW_SETTINGS_RESET_REALLY_YES . "| bash='" . __FILE__ . "' param1=reset refresh=true terminal=false";
        $this->renderRows($rows);
    }

    private function renderFilter()
    {
        $check = ":heavy_check_mark: ";
        $alternate = " alternate=true";
        $allSelected = empty($this->data->selectedWorkspaces) && empty($this->data->selectedProjects);

        $rows = [];

        $rows[] = "-- " . Strings::ROW_SETTINGS_FILTER;
        $rows[] = "---- " . ($allSelected ? $check : "") . Strings::ROW_SETTINGS_FILTER_ALL . $this->getActionForProjectSelection("all", "-", false);

        foreach ($this->data->workspaces as $workspace) {
            $isSelected = in_array($workspace, $this->data->selectedWorkspaces);
            $row = "---- ";
            if ($isSelected) {
                $row .= $check;
            }
            $row .= $this->sanitizeName($workspace);
            $rows[] = $row . $this->getActionForProjectSelection("workspace", $workspace, false);
            $rows[] = $row . $this->getActionForProjectSelection("workspace", $workspace, true) . $alternate;
        }

        foreach ($this->data->projects as $project) {
            $isSelected = in_array($project, $this->data->selectedProjects);
            $row = "---- ";
            if ($isSelected) {
                $row .= $check;
            }
            $row .= $this->sanitizeName($project);
            $rows[] = $row . $this->getActionForProjectSelection("project", $project, false);
            $rows[] = $row . $this->getActionForProjectSelection("project", $project, true) . $alternate;
        }

        $this->renderRows($rows);
    }

    private function sanitizeName($name)
    {
        return preg_replace('~[\\n|]~', "_", $name);
    }

    private function renderAbout()
    {
        $rows = [];
        $rows[] = Strings::ROW_ABOUT;
        $rows[] = "-- " . Strings::ROW_ABOUT_SOURCE_CODE . "| href=" . Config::ABOUT_URL;
        $rows[] = "-- " . Strings::ROW_ABOUT_ICON . "| href=" . Config::ICON_URL;
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
    /** @var string[] */
    var $warnings = [];
    /** @var string[] */
    var $workspaces = [];
    /** @var string[] */
    var $projects = [];
    /** @var string[] - empty means all */
    var $selectedWorkspaces = [];
    /** @var string[] - empty means all */
    var $selectedProjects = [];
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
}

function markStart($startTimeFilePath)
{
    @file_put_contents($startTimeFilePath, "" . time());
}

function markEnd($type, $startTimeFilePath, $dataFilePath)
{
    $content = @file_get_contents($startTimeFilePath);
    unlink($startTimeFilePath);
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
        $xcodeBuild = "build";
        $xcodeBuild = "version";
        // Get Xcode version and build from version plist
        $plist = @simplexml_load_file($developerDirectory . "/../version.plist");
        if ($plist !== false) {
            $xcodeVersion = getPlistValue($plist, "CFBundleShortVersionString");
            $xcodeBuild = getPlistValue($plist, "ProductBuildVersion");
        }
    }

    $data = [
        (new DateTime())->format("c"),
        $duration,
        $type,
        $workspace,
        $project,
        $xcodeVersion,
        $xcodeBuild
    ];

    $handle = @fopen($dataFilePath, "a");
    if ($handle === false) {
        error_log("Unable to open file: $dataFilePath");
        exit(1);
    }

    fputcsv($handle, $data, ",");

    fclose($handle);
}

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
        // Compare hash
        $hash1 = hash("sha256", $data);
        $hash2 = hash("sha256", $selfData);
        if ($hash1 === $hash2) {
            // Show alert message:
            error_log("No update available.");
            if ($showAlerts) {
                showAlert(Strings::UPDATE_ALERT_NO_UPDATES_MESSAGE);
            }
            exit(1);
        }
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

function showAlert($message)
{
    $command = "osascript -e " . escapeshellarg('display alert "' . str_replace('"', '\"', Strings::UPDATE_ALERT_TITLE) . '" message "' . str_replace('"', '\"', $message) . '"') . "> /dev/null 2>&1 &";
    exec($command);
}

function processConfigChange($argv, $configFilePath)
{
    if (count($argv) < 3) {
        return;
    }

    $config = new BuildTimesConfig($configFilePath);

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
                    $config->selectAll();
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
            }
            break;
    }
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
