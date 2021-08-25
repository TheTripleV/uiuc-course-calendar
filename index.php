<!DOCTYPE html>
<html lang="en">
<?php
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
?>

<? $cellWidth = "10"; ?>
<? $cellHeight = "6"; ?>
<? $cellPadding = "0.1"; ?>
<? $cellInset = 2 * $cellPadding; ?>

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
    </style>
</head>

<body>
    <h1><?php print getRedirEnv('givenName'); ?>'s class schedule</h1>
    <div style"position:relative">
        <table>
            <thead>
                <tr>
                    <td colspan="1">
                        made by Vasista (vovveti2@illinois.edu)
                        <?php

                        // https://stackoverflow.com/a/18097502
                        function my_file_read($filename) {
                            $cache_file = "./cache/".urlencode($filename);
                            if(file_exists($cache_file)) {
                                if(time() - filemtime($cache_file) > 86400) {
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

                        $currentYear = "2021";
                        $currentSemester = "fall";

                        $memberString = getRedirEnv('member');

                        $coursesList = explode(";", $memberString);

                        $zindex = 1;

                        $colors = array("#54478c", "#2c699a", "#048ba8", "#0db39e", "#16db93", "#83e377", "#b9e769", "#efea5a", "#f1c453", "#f29e4c", "#d00000");
                        shuffle($colors);
                        $colorIdx = -1;

                        foreach ($coursesList as $course) {
                            if (strpos($course, "{$currentYear} {$currentSemester}") !== false) {
                                $courseInfo = explode(" ", $course);
                                $courseSubject = strtoupper(explode(":", $courseInfo[1])[2]);
                                $courseNum = $courseInfo[2];
                                $courseSection = $courseInfo[3];
                                $courseCRN = substr($courseInfo[6], 3);
                                $xmlString = my_file_read("https://courses.illinois.edu/cisapp/explorer/schedule/{$currentYear}/{$currentSemester}/{$courseSubject}/{$courseNum}/{$courseCRN}.xml");

                                $objXmlDocument = simplexml_load_string($xmlString);
                                $objJsonDocument = json_encode($objXmlDocument);
                                $xmlArr = json_decode($objJsonDocument, TRUE);
                                
                                $reqDate = substr(getRedirEnv('Shib-Authentication-Instant'), 0, 10);
                                $startDate = substr($xmlArr["startDate"], 0, 10);
                                $endDate = substr($xmlArr["endDate"], 0, 10);
                                
                                if ( ! (($startDate <= $reqDate ) && ($reqDate <= $endDate)) ) {
                                    continue;
                                }
                                
                                // print_r($xmlArr["meetings"]);
                                $mergedArrays = array_merge($xmlArr["meetings"], $xmlArr["meetings"]["meeting"]);
                                
                                foreach ($mergedArrays as $meeting) {
                                    // print_r($meeting);
                                    // print_r(str_split($meeting["daysOfTheWeek"]));
                                    
                                    if( ! (isset($meeting["start"])) ) {
                                        continue;
                                    }
                                        
                                    if( ! (isset($meeting["end"])) ) {
                                        continue;
                                    }
                                    
                                    $colorIdx += 1;
                                    foreach (str_split(trim($meeting["daysOfTheWeek"])) as $day) {
                                        // print_r( $meeting["daysOfTheWeek"]);
                                        
                                        $startTime = date_parse($meeting["start"]);
                                        $endTime = date_parse($meeting["end"]);

                                        $rowStart = 1 + ($startTime["hour"] - 7) % 24 + $startTime["minute"] / 60;
                                        $rowEnd = 1 + ($endTime["hour"] - 7) % 24 + $endTime["minute"] / 60;

                                        $colStart = 1;
                                        switch ($day) {
                                            case "U":
                                                $colStart = 1;
                                                break;
                                            case "M":
                                                $colStart = 2;
                                                break;
                                            case "T":
                                                $colStart = 3;
                                                break;
                                            case "W":
                                                $colStart = 4;
                                                break;
                                            case "R":
                                                $colStart = 5;
                                                break;
                                            case "F":
                                                $colStart = 6;
                                                break;
                                            case "S":
                                                $colStart = 7;
                                                break;
                                        }

                                        $posTop = $rowStart * $cellHeight + $rowStart * $cellPadding * 2;
                                        $posLeft = $colStart * $cellWidth + $colStart * $cellPadding * 2;
                                        $height = (($endTime["hour"] - $startTime["hour"]) + ($endTime["minute"] - $startTime["minute"])/60 ) * $cellHeight;
                                        $zindex = ($zindex + 1) % 11;



                        ?>
                        <div style="
                            position:absolute;
                            left:<?=$posLeft + $cellInset?>rem;
                            top:<?=$posTop + $cellInset?>rem;
                            width:<?=$cellWidth - 2  * $cellInset?>rem;
                            height:<?=$height?>rem;
                            /* border:2px solid black; */
                            background-color: <?=$colors[$colorIdx]?>;
                            z-index: <?=$zindex?>;
                        ">
                            <div>
                                <p>
                                    <?=in_array($courseSubject, array('ABE', 'AE', 'BIOE', 'CEE', 'CS', 'CSE', 'ECE', 'ENG', 'IE', 'ME', 'MSE', 'NPRE', 'PHYS', 'SE', 'TAM', 'TE'))? "<a href=\"https://courses.grainger.illinois.edu/$courseSubject$courseNum".(($courseNum == '498' or $courseNum == '598')?$courseSection : '')."\">$courseSubject $courseNum</a> $courseSection" : "$courseSubject $courseNum $courseSection" ?>
                                    <!--<?=$courseSubject?> <?=$courseNum?> <?=$courseSection?>-->
                                    <?=isset($meeting["type"])?"<br>".$meeting["type"] : "" ?>
                                    <?=isset($meeting["buildingName"])?'<br><a href="https://google.com/maps/search/'.urlencode($meeting["buildingName"]).'">'.$meeting["buildingName"].'</a>' : "" ?> <?=isset($meeting["roomNumber"])?$meeting["roomNumber"] : "" ?>
                                </p>
                            </div>
                        </div>
                        <?php


                                    }
                                }
                            }
                        }
                        ?>
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

                <tr>
                    <th scope="row" valign="top">7am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">8am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">9am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">10am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">11am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">12pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">1pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">2pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">3pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">4pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">5pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">6pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">7pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">8pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">9pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">10pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">11pm</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">12am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">1am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">2am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">3am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">4am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">5am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row" valign="top">6am</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>

        </table>

    </div>

</body>

</html>