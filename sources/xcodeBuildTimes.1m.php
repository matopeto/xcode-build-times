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

    const DATE_FORMAT = "Y-m-d";

    const DATA_FILE_NAME = "buildTimes.csv";
    const START_TIME_FILE = "buildStartTime";
    const DATA_FILE_DIR = ".xcodeBuildTimes"; // Must be hidden (start with ".")
}

final class Strings
{
    const WARNING_UNABLE_TO_READ_DATA_FILE = "Unable to read data file";
    const WARNING_PROBLEM_WITH_DATA_FILE = "There is some problem with data";
    const WARNING_NO_DATA = "No data";
    const ROW_WARNING = ":warning: %s"; // %s will be replaced by warning message

    const ROE_HEADER_TODAY = "Today";
    const ROW_HEADER_TOTAL = "Total";
    const ROW_HEADER_TOTAL_SINCE = "Total, since: %s"; // %s will be replaced with first date of data

    const ROW_BUILD_COUNTS = 'Builds %1$d, %3$d failed'; // %1$d - total, %2$d - succeeded, %3$d - failed
    const ROW_BUILD_COUNTS_NO_BUILDS = 'Builds: no builds yet';
    const ROW_BUILD_TIME = 'Build time: %s'; // %s will be replaced with corresponding build time
    const ROW_AVERAGE_BUILD_TIME = 'Average build time: %s'; // %s will be replaced with corresponding build time

    const ROW_SUCCESS_BUILD_TIME = 'Success: %s'; // %s will be replaced with corresponding build time
    const ROW_FAIL_BUILD_TIME = 'Fail: %s'; // %s will be replaced with corresponding build time

    const ROW_LAST_BUILD = 'Last build: %1$s, %2$s'; // %1$d will be replaced with status (success/fail) %2$d with duration

    const ROW_REFRESH = "Refresh";
    const ROW_ABOUT = "About";
    const ROW_ABOUT_ICON = "Icon by Icons8";
    const ROW_ABOUT_SOURCE_CODE = "Source Code & Info";
    const ROW_ABOUT_RESET = "Reset";
    const ROW_ABOUT_RESET_REALLY = "Really?";
    const ROW_ABOUT_RESET_REALLY_YES = "Yes";

    const MSG_NO_BUILDS_YET = "no builds yet";
}

$buildHash = getBuildHash();

$scriptDirectory = realpath(__DIR__);
$dataDirectory = $scriptDirectory . DIRECTORY_SEPARATOR . Config::DATA_FILE_DIR;
$dataFilePath = $dataDirectory . DIRECTORY_SEPARATOR . Config::DATA_FILE_NAME;
$startTimeFilePath = $dataDirectory . DIRECTORY_SEPARATOR . Config::START_TIME_FILE . "." . $buildHash;


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
}

$parser = new BuildTimesFileParser($dataFilePath);
$data = $parser->getOutput();
$renderer = new BitBarRenderer($data);
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
            list($rows, $problemWithData) = $this->parseFile($handle);
            if ($problemWithData) {
                $result->warnings[] = Strings::WARNING_PROBLEM_WITH_DATA_FILE;
            }

            // All rows
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
            $todayKey = (new DateTime())->format("Y-m-d");
            if (key_exists($todayKey, $rows)) {
                // Today data
                $todayRows = $rows[$todayKey];
                $result->todayData = $this->getData($todayRows);
            }
        }

        return $result;
    }

    /**
     * @param resource $handle
     *
     * @return array - array with two values, first is [DataRow], second is boolean
     */
    private function parseFile($handle)
    {
        $rows = [];
        $problemWithData = false;
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($row) < 3) {
                $problemWithData = true;
                continue;
            }

            if (!preg_match('~[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}~', $row[0])) {
                $problemWithData = true;
                continue;
            }

            if (!preg_match('~[0-9]+~', $row[1])) {
                $problemWithData = true;
                continue;
            }

            if (!preg_match('~fail|success~', $row[2])) {
                $problemWithData = true;
                continue;
            }

            $dataRow = new DataRow();
            $date = DateTime::createFromFormat("Y-m-d H:i:s", $row[0]);
            if ($date == false) {
                $problemWithData = true;
                continue;
            }

            $dataRow->date = $date;
            $dataRow->count = intval($row[1]);
            $dataRow->type = $row[2];

            $key = $date->format("Y-m-d");

            $rows[$key][] = $dataRow;
        }

        fclose($handle);

        return [$rows, $problemWithData];
    }

    /**
     * @param DataRow[] $rows
     *
     * @return BuildTimesData
     */
    private function getData($rows)
    {
        $buildTime = 0;
        $buildCount = count($rows);
        $successCount = 0;
        $failCount = 0;
        $failBuildTime = 0;
        $successBuildTime = 0;

        /** @var DataRow $row */
        foreach ($rows as $row) {
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
        $result->dataFrom = reset($rows)->date;
        $result->dataTo = end($rows)->date;

        return $result;
    }
}

final class BitBarRenderer
{
    /** @var BuildTimesOutput */
    private $data;

    /**
     * @param $data BuildTimesOutput
     */
    public function __construct($data)
    {
        $this->data = $data;
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

        $alternate = "| alternate=true";
        $rows[] = Strings::ROE_HEADER_TODAY;

        if ($this->data->totalData != null) {
            $rows[] = sprintf(Strings::ROW_HEADER_TOTAL_SINCE, $this->data->totalData->dataFrom->format(Config::DATE_FORMAT)) . $alternate;
        } else {
            $rows[] = Strings::ROW_HEADER_TOTAL . $alternate;

        }

        $rows[] = $this->getBuildCountsRow($todayData);
        $rows[] = $this->getBuildCountsRow($totalData) . $alternate;


        $rows[] = $this->getFormatedTimeRow($todayData, "buildTime", Strings::ROW_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($todayData, "successBuildTime", Strings::ROW_SUCCESS_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($totalData, "successBuildTime", Strings::ROW_SUCCESS_BUILD_TIME) . $alternate;
        $rows[] = "-- " . $this->getFormatedTimeRow($todayData, "failBuildTime", Strings::ROW_FAIL_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($totalData, "failBuildTime", Strings::ROW_FAIL_BUILD_TIME) . $alternate;

        $rows[] = $this->getFormatedTimeRow($totalData, "buildTime", Strings::ROW_BUILD_TIME) . $alternate;
        $rows[] = "-- " . $this->getFormatedTimeRow($todayData, "successBuildTime", Strings::ROW_SUCCESS_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($totalData, "successBuildTime", Strings::ROW_SUCCESS_BUILD_TIME) . $alternate;
        $rows[] = "-- " . $this->getFormatedTimeRow($todayData, "failBuildTime", Strings::ROW_FAIL_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($totalData, "failBuildTime", Strings::ROW_FAIL_BUILD_TIME) . $alternate;

        $rows[] = $this->getFormatedTimeRow($todayData, "averageBuildTime", Strings::ROW_AVERAGE_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($todayData, "averageSuccessBuildTime", Strings::ROW_SUCCESS_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($totalData, "averageSuccessBuildTime", Strings::ROW_SUCCESS_BUILD_TIME) . $alternate;
        $rows[] = "-- " . $this->getFormatedTimeRow($todayData, "averageFailBuildTime", Strings::ROW_FAIL_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($totalData, "averageFailBuildTime", Strings::ROW_FAIL_BUILD_TIME) . $alternate;

        $rows[] = $this->getFormatedTimeRow($totalData, "averageBuildTime", Strings::ROW_AVERAGE_BUILD_TIME) . $alternate;
        $rows[] = "-- " . $this->getFormatedTimeRow($todayData, "averageSuccessBuildTime", Strings::ROW_SUCCESS_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($totalData, "averageSuccessBuildTime", Strings::ROW_SUCCESS_BUILD_TIME) . $alternate;
        $rows[] = "-- " . $this->getFormatedTimeRow($todayData, "averageFailBuildTime", Strings::ROW_FAIL_BUILD_TIME);
        $rows[] = "-- " . $this->getFormatedTimeRow($totalData, "averageFailBuildTime", Strings::ROW_FAIL_BUILD_TIME) . $alternate;

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

        $this->renderAbout();
    }

    private function renderAbout()
    {
        $rows = [];
        $rows[] = Strings::ROW_ABOUT;
        $rows[] = "-- " . Strings::ROW_ABOUT_SOURCE_CODE . "| href=" . Config::ABOUT_URL;
        $rows[] = "-- " . Strings::ROW_ABOUT_ICON . "| href=" . Config::ICON_URL;
        $rows[] = "-- " . Strings::ROW_ABOUT_RESET;
        $rows[] = "---- " . Strings::ROW_ABOUT_RESET_REALLY;
        $rows[] = "------ " . Strings::ROW_ABOUT_RESET_REALLY_YES . "| bash='" . __FILE__ . "' param1=reset refresh=true terminal=false";

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
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
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
}

final class BuildTimesOutput
{
    /** @var BuildTimesData */
    var $todayData;
    /** @var BuildTimesData */
    var $totalData;
    /** @var DataRow */
    var $lastBuild;
    /** @var string[] */
    var $warnings = [];
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

final class DataRow
{
    /** @var DateTime */
    var $date;
    /** @var integer */
    var $count;
    /** @var string */
    var $type;
}

function markStart($startTimeFilePath)
{
    file_put_contents($startTimeFilePath, "" . time());
}

function markEnd($type, $startTimeFilePath, $dataFilePath)
{
    $content = file_get_contents($startTimeFilePath);
    unlink($startTimeFilePath);
    if ($content === false) {
        exit("Unable to open file: $startTimeFilePath");
    }

    $startTime = intval($content);

    $duration = time() - $startTime;

    if ($duration < 0 || $startTime === 0) {
        exit("File $startTimeFilePath has invalid format");
        // Invalid duration
        return;
    }

    $workspace = getenv("XcodeWorkspace");
    $workspace = $workspace === false ? "" : $workspace;

    $project = getenv("XcodeProject");
    $project = $project === false ? "" : $project;

    $data = [
        date("Y-m-d H:i:s"),
        $duration,
        $type,
        $workspace,
        $project,
    ];

    $handle = @fopen($dataFilePath, "a");
    if ($handle === false) {
        exit("Unable to open file: $dataFilePath");
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
