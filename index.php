<?php

// PARAMS
// --------
$currentYear = "2022";
$currentSemester = "spring";

$cellWidth = "10";
$cellHeight = "6";
$cellPadding = "0.1";
$cellInset = 2 * $cellPadding;

$colors = array("#a69dcf", "#ff99da", "#00bbe3", "#0db39e", "#16db93", "#83e377", "#b9e769", "#efea5a", "#f1c453", "#f29e4c", "#f58282");
shuffle($colors);
// --------

error_reporting(0);
// https://docs.shib.ncsu.edu/docs/testpage.php.txt
// fastcgi-php friendly getRedirEnv()
// replaces getenv($var) or $_SERVER[$var]
function getRedirEnv($var)
{

    if (getenv($var)) return getenv($var);
    if (getenv('REDIRECT_' . $var)) return getenv('REDIRECT_' . $var);
    // httpd can rewrite vars on redirects, this is the most common case
    $var = preg_replace('/-/', '_', $var);
    if (getenv($var)) return getenv($var);
    if (getenv('REDIRECT_' . $var)) return getenv('REDIRECT_' . $var);
    return FALSE;
}

// https://stackoverflow.com/a/18097502
function cached_file_get_contents($filename)
{
    $cache_file = "./cache/" . urlencode($filename);
    if (file_exists($cache_file)) {
        if (time() - filemtime($cache_file) > 86400) {
            // too old , re-fetch
            $cache = file_get_contents($filename);
            file_put_contents($cache_file, $cache);
            return $cache;
        } else {
            // cache is still fresh
            return file_get_contents($cache_file);
        }
    } else {
        // no cache, create one
        $cache = file_get_contents($filename);
        file_put_contents($cache_file, $cache);
        return $cache;
    }
}

function day_to_col($day)
{
    switch ($day) {
        case "U":
            return 1;
        case "M":
            return 2;
        case "T":
            return 3;
        case "W":
            return 4;
        case "R":
            return 5;
        case "F":
            return 6;
        case "S":
            return 7;
        default:
            return 8;
    }
}

function is_course($maybeCourse)
{
    global $currentYear, $currentSemester;
    return strpos($maybeCourse, "{$currentYear} {$currentSemester}") !== false;
}

function course_url($subject, $num, $section)
{
    $engSubjects = array('ABE', 'AE', 'BIOE', 'CEE', 'CS', 'CSE', 'ECE', 'ENG', 'IE', 'ME', 'MSE', 'NPRE', 'PHYS', 'SE', 'TAM', 'TE');
    if (in_array($subject, $engSubjects)) {
        $url = "https://courses.grainger.illinois.edu/{$subject}{$num}";
        if ($num == '498' or $num == '598') {
            $url .= $section;
        }
        return $url;
    }
    return false;
}

function meeting_url($building)
{
    return "https://google.com/maps/search/".urlencode($building);
}

$drawableMeetings = array();
$nonDrawableCourses = array();

$memberString = getRedirEnv('member');
$courses = array_filter(explode(";", $memberString), "is_course");
$coursesList = $courses;

$colorIdx = 0;

foreach ($courses as $courseString) {
    $courseInfo = explode(" ", $courseString);
    $courseSubject = strtoupper(explode(":", $courseInfo[1])[2]);
    $courseNum = $courseInfo[2];
    $courseSection = $courseInfo[3];
    $courseCRN = substr($courseInfo[6], 3);
    $xmlString = cached_file_get_contents("https://courses.illinois.edu/cisapp/explorer/schedule/{$currentYear}/{$currentSemester}/{$courseSubject}/{$courseNum}/{$courseCRN}.xml");

    $objXmlDocument = simplexml_load_string($xmlString);
    $objJsonDocument = json_encode($objXmlDocument);
    $xmlArr = json_decode($objJsonDocument, TRUE);

    $reqDate = substr(getRedirEnv('Shib-Authentication-Instant'), 0, 10);
    // $startDate = substr($xmlArr["startDate"], 0, 10);
    $startDate = substr($xmlArr["startDate"], 0, 8) . "01";
    $endDate = substr($xmlArr["endDate"], 0, 10);

    if (!(($startDate <= $reqDate) && ($reqDate <= $endDate))) {
        // course may be an 8 week class.
        continue;
    }

    $potentialMeetings = array_merge($xmlArr["meetings"], $xmlArr["meetings"]["meeting"]);

    $areAllMeetingsNotScheduled = true;
    foreach ($potentialMeetings as $meeting) {
        if (!(isset($meeting["start"]) and isset($meeting["end"]))) {
            // class may be online without scheduled meetings
            continue;
        }
        $areAllMeetingsNotScheduled = false;

        foreach (str_split(trim($meeting["daysOfTheWeek"])) as $day) {
            $drawableMeetings[] = array(
                "subject" => $courseSubject,
                "num" => $courseNum,
                "section" => $courseSection,
                "crn" => $courseCRN,
                "day" => $day,
                "start" => date_parse($meeting["start"]),
                "end" => date_parse($meeting["end"]),
                "type" => $meeting["type"],
                "building" => $meeting["buildingName"],
                "room" => $meeting["roomNumber"],
                "colorIdx" => $colorIdx,
                "course_url" => course_url($courseSubject, $courseNum, $courseSection),
            );
        }
        $colorIdx = ($colorIdx + 1) % count($colors);
    }

    if ($areAllMeetingsNotScheduled) {
        $nonDrawableCourses[] = array(
            "subject" => $courseSubject,
            "num" => $courseNum,
            "section" => $courseSection,
            "crn" => $courseCRN,
            "day" => $day,
            "course_url" => course_url($courseSubject, $courseNum, $courseSection),
        );
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Course Schedule</title>
    <style>
        html {
            font-family: sans-serif;
        }

        table {
            border-collapse: collapse;
            /* border: 2px solid rgb(200, 200, 200); */
            letter-spacing: 1px;
            font-size: 0.8rem;
            position: relative;
        }

        td,
        th {
            border: <?= $cellPadding ?>rem solid rgb(190, 190, 190);
            width: <?= $cellWidth ?>rem;
            max-width: <?= $cellWidth ?>rem;
            min-width: <?= $cellWidth ?>rem;
            height: <?= $cellHeight ?>rem;
            max-height: <?= $cellHeight ?>rem;
            min-height: <?= $cellHeight ?>rem;
            /* padding: 2em 3em; */
        }

        th {
            background-color: rgb(235, 235, 235);
        }

        td {
            text-align: center;
        }

        tr:nth-child(even) td {
            background-color: rgb(250, 250, 250);
        }

        tr:nth-child(odd) td {
            background-color: rgb(245, 245, 245);
        }

        caption {
            padding: 10px;
        }

        h4 {
            margin-bottom: 0px;
            line-height: 0px;
        }
    </style>
</head>

<body>
    <h1><?php print getRedirEnv('givenName'); ?>'s class schedule</h1>
    
    <?php if(count($nonDrawableCourses)) : ?>
        <h4> Courses with at least 1 unscheduled meeting: </h4>
        <?php foreach($nonDrawableCourses as $meeting) : ?>
            <p><b>- <?= $meeting["course_url"]? "<a href=\"{$meeting["course_url"]}\">{$meeting['subject']} {$meeting['num']}</a> {$meeting['section']}" : "${$meeting['subject']} {$meeting['num']} {$meeting['section']}" ?></b></p>
        <?php endforeach ?>
    <?php endif ?>

    <div style"position:relative">
        <table>
            <thead>
                <tr>
                    <td colspan="1">
                        made by Vasista (vovveti2@illinois.edu)
                        <a href="https://github.com/TheTripleV/uiuc-course-calendar">[Code on Github]</a>

                        <?php foreach ($drawableMeetings as $meeting) : ?>
                            <?php
                                $startTime = $meeting["start"];
                                $endTime = $meeting["end"];

                                $rowStart = 1 + ($startTime["hour"] - 7) % 24 + $startTime["minute"] / 60;
                                $rowEnd = 1 + ($endTime["hour"] - 7) % 24 + $endTime["minute"] / 60;

                                $colStart = day_to_col($meeting["day"]);

                                $posTop = $rowStart * $cellHeight + $rowStart * $cellPadding * 2;
                                $posLeft = $colStart * $cellWidth + $colStart * $cellPadding * 2;
                                $height = (($endTime["hour"] - $startTime["hour"]) + ($endTime["minute"] - $startTime["minute"]) / 60) * $cellHeight;
                            ?>
                            <div style="position:absolute;
                                        left:<?= $posLeft + $cellInset ?>rem;
                                        top:<?= $posTop + $cellInset ?>rem;
                                        width:<?= $cellWidth - 2  * $cellInset ?>rem;
                                        height:<?= $height ?>rem;
                                        /* border:2px solid black; */
                                        background-color: <?= $colors[$meeting["colorIdx"]] ?>;
                                        z-index: <?= $meeting["colorIdx"] ?>;
                            ">
                                <div>
                                    <p>
                                        <b><?= $meeting["course_url"]? "<a href=\"{$meeting["course_url"]}\">{$meeting['subject']} {$meeting['num']}</a> {$meeting['section']}" : "${$meeting['subject']} {$meeting['num']} {$meeting['section']}" ?></b>
                                        <?= isset($meeting["type"]) ? "<br>{$meeting["type"]}" : "" ?>
                                        <?= isset($meeting["building"]) ? '<br><a href="'.meeting_url($meeting["building"]).'">'.$meeting['building'].'</a>' : "" ?> <?= isset($meeting["room"]) ? $meeting["room"] : "" ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </td>
                    <th scope="col">Sunday</th>
                    <th scope="col">Monday</th>
                    <th scope="col">Tuesday</th>
                    <th scope="col">Wednesday</th>
                    <th scope="col">Thursday</th>
                    <th scope="col">Friday</th>
                    <th scope="col">Saturday</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach(array('7am', '8am', '9am', '10am', '11am', '12pm', '1pm', '2pm', '3pm', '4pm', '5pm', '6pm', '7pm', '8pm', '9pm', '10pm', '11pm', '12am', '1am', '2am', '3am', '4am', '5am', '6am') as $time) : ?>
                    <tr>
                        <th scope="row" valign="top"><?= $time ?></th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endforeach ?>

            </tbody>

        </table>

    </div>

</body>

</html>