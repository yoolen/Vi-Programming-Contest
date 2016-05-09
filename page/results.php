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
		$contestName = $contest->get_contest_name($contestNumber); 
        echo "<div style='border-style:solid; margin-top:10px; overflow:scroll; overflow-x:hidden;'><h2 style='text-align:center; font-weight:bold;'>Results of " . $contestName . "</h2>";
        echo '<div id="results" style="text-align:center">';
        for ($i = 0; $i < count($results); $i++) {
			echo "<h3><b>" .Results::ordinal($i+1)." Place:</b> <i>". $results[$i]['teamname'] . "</i></h3>";
        }
		/*
		for ($i = 0; $i < count($results); $i++) {
			var_dump($results[$i]); echo '<br/>';
        }
		*/
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
	//Gets the total time of the 
    public static function get_total_time($contest_FK, $team_FK) {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
        $contest = new Contest();
        $contestTimes = $contest->get_contest_time($contest_FK, $team_FK);
		$starttime = $contest->get_contest_sched($contest_FK)['starttime'];
		$totalTime = 0;
        for ($i = 0; $i < count($contestTimes); $i++) {
            $st = explode(" ", $starttime);
            $time = explode(" ", $contestTimes[$i]);
			$begin = new DateTime($st[1]);
			$end = new DateTime($time[1]);
			$interval = $begin->diff($end);
			$totalTime += ((int)($interval->format("%H")*3600) + (int)($interval->format("%I")*60) + (int)$interval->format("%S"))/60;
        }
        return $totalTime;
    }
	public static function ordinal($number) {
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number % 100) >= 11) && (($number%100) <= 13))
			return $number. 'th';
		else
			return $number. $ends[$number % 10];
	}
	
	public static function resort($arr){
		
	}

}

?>