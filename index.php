<?php

// PARAMS
// --------
$currentYear = "2021";
$currentSemester = "fall";

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
    if ($var == "givenName") return "Vasista";
    if ($var == "member") return "urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 126 sl1 2018 fall crn66322;urn:mace:uiuc.edu:urbana:register:class rosters:sections:phys 212 a3 2021 spring crn52875;urn:mace:uiuc.edu:urbana:register:class rosters:courses:cs 439 current;urn:mace:uiuc.edu:urbana:register:class rosters:sections:math 415 al1 2019 fall crn63037;urn:mace:uiuc.edu:urbana:cites:cites-services:webstore:adobe student subscribers:adobe student exp 07-01-2021;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 233 bl1 2019 spring crn61827;urn:mace:uiuc.edu:urbana:register:class rosters:sections:up 185 fm 2019 spring crn53015;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 440 q3 2020 spring crn31423;urn:mace:uiuc.edu:urbana:engineering:usersandgroups:serviceobjects:enx:enx-ews-flat;urn:mace:uiuc.edu:urbana:register:class rosters:courses:cs 421 current;urn:mace:uiuc.edu:urbana:register:class rosters:sections:math 231 edf 2018 fall crn49233;urn:mace:uiuc.edu:urbana:cites-campus wiki:cites-campus wiki groups:campus-wiki-math 231 el1 2018 fall crn46880;urn:mace:uiuc.edu:urbana:las:atlas:inf:arw:apps:atlas:directoryrosterpopulator:prodgroups:atldp_120208_72889;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 421 a4 2021 fall crn65907;urn:mace:uiuc.edu:urbana:register:class rosters:sections:hist 104 ad5 2020 fall crn73084;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 241 ade 2019 fall crn51474;urn:mace:uiuc.edu:urbana:engineering:usersandgroups:classes:students:cs-421-stu;urn:mace:uiuc.edu:urbana:register:class rosters:sections:phys 211 d3j 2018 fall crn34608;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 126 sd1 2018 fall crn69200;urn:mace:uiuc.edu:urbana:cites:cites-services:webstore:internal:adobe sync groups:adobe-uiuc-edw-sync;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 483 ab 2020 fall crn58793;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 199 inc 2019 summer crn39428;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 225 ayl 2019 spring crn56810;urn:mace:uiuc.edu:urbana:cites:cites-services:webstore:groups:webstore_uiuc_allstaff;urn:mace:uiuc.edu:urbana:register:class rosters:sections:phys 211 l3m 2018 fall crn34670;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 100 al1 2018 fall crn30094;urn:mace:uiuc.edu:urbana:uiuc campus accounts student;urn:mace:uiuc.edu:urbana:authman:app-shield-service-policy-affiliation-graduate;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 439 b4 2021 fall crn61201;urn:mace:uiuc.edu:urbana:cites-campus wiki:cites-campus wiki groups:campus-wiki-uofiwiki-engr-all;urn:mace:uiuc.edu:urbana:las:atlas:infrastructure:groups:lms:citlmoodleplacementtests:citlmoodleplacementtests2018sufa;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 241 al2 2019 fall crn66268;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 498 wn3 2019 fall crn67900;urn:mace:uiuc.edu:urbana:register:class rosters:sections:math 299 el1 2018 fall crn50014;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 445 c3 2019 fall crn65086;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 498 ps3 2020 spring crn39660;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 361 add 2019 fall crn66303;urn:mace:uiuc.edu:urbana:engineering:usersandgroups:personnel:cs:cs-cs-newgrads;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 497 dwh 2019 fall crn69124;urn:mace:uiuc.edu:urbana:authman:app-aad-service-policy-license-m365_a3_stu;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 361 al1 2019 fall crn66298;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 225 al2 2019 spring crn31213;urn:mace:uiuc.edu:urbana:uiuc campus accounts;urn:mace:uiuc.edu:urbana:authman:test-test4-service-policy-largegrouptest;urn:mace:uiuc.edu:urbana:las:atlas:servers:application and web development:applications:sqladgrouppopulator:production:atlasprodgrplangprofgermanpopulatedmembers;urn:mace:uiuc.edu:urbana:engineering:usersandgroups:personnel:cs:cs-cs-grad-mcs;urn:mace:uiuc.edu:urbana:register:class rosters:sections:math 231 el1 2018 fall crn46880;urn:mace:uiuc.edu:urbana:gidgroups:gid-vovveti2;urn:mace:uiuc.edu:urbana:authman:org-engr-vmock-eligible;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 173 bda 2018 fall crn51500;urn:mace:uiuc.edu:ad exchange lync administration:cites-ei:winsg:o365:haso365exchangelicense;urn:mace:uiuc.edu:urbana:authman:app-iam-duo-service-policy-challenged;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 173 bl2 2018 fall crn40083;urn:mace:uiuc.edu:urbana:las:atlas:servers:application and web development:applications:sqladgrouppopulator:production:atlasprodgrplangproffrenchpopulatedmembers;urn:mace:uiuc.edu:urbana:vcl:accounts:vclappusers;urn:mace:uiuc.edu:urbana:ncsa:students:students-virtual:reu-inclusion 1104:ncsa-w10vm-23-admins;urn:mace:uiuc.edu:urbana:register:class rosters:sections:phys 211 a1 2018 fall crn55650;urn:mace:uiuc.edu:urbana:register:class rosters:sections:phys 212 l2m 2021 spring crn56035;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 421 a3 2020 fall crn65906;urn:mace:uiuc.edu:urbana:register:class rosters:sections:phys 212 d4n 2021 spring crn61748;urn:mace:uiuc.edu:urbana:register:class rosters:sections:phil 316 e2 2020 spring crn32664;urn:mace:uiuc.edu:urbana:register:class rosters:sections:rel 215 a 2020 fall crn72889;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 498 tc3 2021 spring crn67775;urn:mace:uiuc.edu:urbana:register:class rosters:sections:math 241 bl1 2019 spring crn55921;urn:mace:uiuc.edu:urbana:las:atlas:servers:application and web development:applications:sqladgrouppopulator:production:atlasprodgrplangprofspanishpopulatedmembers;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 483 al2 2020 fall crn67070;urn:mace:uiuc.edu:urbana:register:class rosters:sections:hist 104 al1 2020 fall crn72869;urn:mace:uiuc.edu:urbana:engineering:usersandgroups:classes:students:cs-439-stu;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 374 al1 2020 spring crn65088;urn:mace:uiuc.edu:urbana:authman:app-shield-service-policy-affiliation-student;urn:mace:uiuc.edu:urbana:authman:app-zoom-service-policy-eligibleusers;urn:mace:uiuc.edu:urbana:cites:cites-services:webstore:internal:adobe sync groups:adobe-student-auto;urn:mace:uiuc.edu:urbana:uiuc campus accounts degree;urn:mace:uiuc.edu:urbana:cites-campus wiki:cites-campus wiki groups:campus-wiki-cs 233 bl1 2019 spring crn61827;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 447 n3 2020 fall crn63292;urn:mace:uiuc.edu:urbana:cites-campus wiki:cites-campus wiki groups:campus-wiki-math 231 edf 2018 fall crn49233;urn:mace:uiuc.edu:urbana:authman:app-rokwire-service-policy-safer two tests;urn:mace:uiuc.edu:urbana:register:class rosters:sections:badm 310 ol 2021 spring crn57199;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 465 ay4 2021 spring crn72235;urn:mace:uiuc.edu:urbana:register:class rosters:sections:math 241 bdh 2019 spring crn55922;urn:mace:uiuc.edu:urbana:las:atlas:inf:arw:apps:atlas:directoryrosterpopulator:prodgroups:atldp_120208_72869;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 465 m1 2021 spring crn72221;urn:mace:uiuc.edu:urbana:engineering:usersandgroups:classes:all:cs-421-all;urn:mace:uiuc.edu:urbana:urbana ui verify enrolled users;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 357 m 2020 spring crn61476;urn:mace:uiuc.edu:urbana:las:atlas:inf:arw:apps:atlas:directoryrosterpopulator:prodgroups:atldp_120208_73084;urn:mace:uiuc.edu:urbana:register:class rosters:sections:math 415 adg 2019 fall crn63104;urn:mace:uiuc.edu:urbana:engineering:usersandgroups:classes:students:ece-439-stu;urn:mace:uiuc.edu:urbana:register:class rosters:sections:eng 100 cs1 2018 fall crn34155;urn:mace:uiuc.edu:urbana:register:class rosters:sections:cs 374 ayg 2020 spring crn65095;urn:mace:uiuc.edu:urbana:authman:app-aad-service-policy-license-o365_a1_fac;urn:mace:uiuc.edu:urbana:cites-campus wiki:cites-campus wiki groups:campus-wiki-uofiwiki-engr-all-grads;urn:mace:uiuc.edu:urbana:uiuc campus accounts staff;urn:mace:uiuc.edu:urbana:register:class rosters:sections:bioe 460 ayg 2021 fall crn75221;urn:mace:uiuc.edu:urbana:register:class rosters:sections:ece 495 ad1 2021 fall crn75578;";
    if ($var == "Shib-Authentication-Instant") return "2021-08-23";

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
    $startDate = substr($xmlArr["startDate"], 0, 10);
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

    <h4> Courses with at least 1 unscheduled meeting: </h4>
    <?php foreach($nonDrawableCourses as $meeting) : ?>
        <p><b>- <?= $meeting["course_url"]? "<a href=\"{$meeting["course_url"]}\">{$meeting['subject']} {$meeting['num']}</a> {$meeting['section']}" : "${$meeting['subject']} {$meeting['num']} {$meeting['section']}" ?></b></p>
    <?php endforeach ?>

    <div style"position:relative">
        <table>
            <thead>
                <tr>
                    <td colspan="1">
                        made by Vasista (vovveti2@illinois.edu)

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