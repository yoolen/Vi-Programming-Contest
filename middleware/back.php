<?php
	/* this function is a prototype for the backend. Feel
		free to edit the date, start time and duration to test the timer */

require_once($_SERVER['DOCUMENT_ROOT'] .'/data/db-info.php');

	function get_contest_sched($qid){
		$conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
		if ($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT starttime, duration FROM cs491.contest WHERE contest_PK=?";
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i', $qid);
			$stmt->execute();
			$stmt->bind_result($starttime, $duration);
			$stmt->fetch();
			$sched = array('starttime'=>$starttime,'duration'=>$duration);
			$stmt->close();
		} else {
			echo 'Error querying the database.';
		}
		$conn->close();
		return $sched;
	}

?>