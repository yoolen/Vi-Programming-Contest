<?php

class Results extends Page {

    public function getPageTitle() {
        return "Results";
    }

    public function getInitialization() {
        return "";
    }

    public function getPageContent() {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/data/grade.php');
        require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
        $contest = new Contest();
        $grade = new Grade();
        $contestNumber = Results::get_last_finished_contest();
        $results = $grade->get_results($contestNumber);
        echo '<!--img src="/images/jollidude.png" style="float:right;"-->';
        $contestName = $contest->get_contest_name($contestNumber); 
        echo "<div style='border-style:solid; margin-top:10px;'><h2 style='text-align:center; font-weight:bold;'>Results of " . $contestName . "</h2>";
        echo '<div id="results" style="text-align:center">';
        for ($i = 0; $i < count($results); $i++) {
            $newI = $i + 11;
            if (intval(substr(strval($newI), -1)) === 1) {
                echo nl2br("<h3> " . strval($i + 1) . "st Place </h3>");
            } elseif (intval(substr(strval($newI), -1)) === 2) {
                echo nl2br("<h3> " . strval($i + 1) . "nd Place </h3>");
            } elseif (intval(substr(strval($newI), -1)) === 3) {
                echo nl2br("<h3> " . strval($i + 1) . "rd Place </h3>");
            } else {
                echo nl2br("<h3> " . strval($i + 1) . "th Place </h3>");
            }
			echo nl2br("<h4>" . $results[$i]['teamname'] . "</h4>");
        }
        echo '</div></div>';
    }

    //This adds the duration to the starttime and compares it with the actual time, if the endDate is less then the current date, that means the contest is over. Then it gets the most recent date.
    public static function get_last_finished_contest() {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
        $contest = new Contest();
        $contests = $contest->get_contest_times();
        $date = date("Y-m-d H:i:s");
        $tempDate = null;
        $tempId = null;
        for ($i = 0; $i < count($contests); $i++) {
            $starttime = $contests[$i]['starttime'];
            $st = explode(" ", $starttime);
            $duration = $contests[$i]['duration'];
            $endTime = strtotime($st[1]) + strtotime($duration) - strtotime('00:00:00');
            $st[1] = date("H:i:s", $endTime);
            $endDate = implode(" ", $st);
            if ($endDate < $date) {
                if (!isset($tempDate)) {
                    $tempDate = $endDate;
                    $tempId = $contests[$i]['contest_PK'];
                } else {
                    if ($endDate > $tempDate) {
                        $tempDate = $endDate;
                        $tempId = $contests[$i]['contest_PK'];
                    }
                }
            }
        }
        return $tempId;
    }

}

?>