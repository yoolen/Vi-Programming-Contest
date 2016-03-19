<?php
/*
Matt Wolfman
Terry Chern
CS 491
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');

class Contest{
	//Inserts the contest into the contest bank.
	public static function insert_contest($date, $hour, $minute, $seconds, $duration, $creator_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO contest (starttime, duration, creator_FK) VALUES (:starttime, :duration, :creator_FK)";
		$stmt = $conn->prepare($sql);

		$stmt->bindParam(':starttime', $s);
		$stmt->bindParam(':duration', $duration);
		$stmt->bindParam(':creator_FK', $creator_FK);

		$starttime = $date . " " . $hour . ":" . $minute . ":" . $seconds;
		$s = $starttime;

		$status = $stmt->execute();
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	//Insert a teams checkin to the checkin bank.
	public static function insert_checkin($contest_FK, $team_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO checkin (contest_FK, team_FK) VALUES (:contest_FK, :team_FK)";
		$stmt = $conn->prepare($sql);

		$stmt->bindParam(':contest_FK', $contest_FK);
		$stmt->bindParam(':team_FK', $team_FK);

		$status = $stmt->execute();
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	public static function get_checkin_status($contest_FK, $team_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT COUNT(*) FROM checkin WHERE contest_FK=:contest_FK AND team_FK=:team_FK";
		$stmt = $conn->prepare($sql);

		$stmt->bindParam(':contest_FK', $contest_FK);
		$stmt->bindParam(':team_FK', $team_FK);

		$status = $stmt->execute();
		if($status){
			return $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
		} else {
			return false;
		}

	}

	//Returns all the contests
	public static function get_all_contests(){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * from contest";
		$stmt = $conn->prepare($sql);

		$status = $stmt->execute();
		if ($status) {
			$stmt->bindColumn('contest_PK', $contest_PK);
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$stmt->bindColumn('creator_FK', $creator_FK);

			$contests = array();

			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($contests, array('cid' => $contest_PK, 'starttime' => $starttime, 'duration' => $duration, 'creator_FK' => $creator_FK));
			}
			return $contests;
		} else {
			return false;
		}
	}

	public static function add_question_to_contest($contest_FK, $question_FK, $sequencenum){
		$conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO contestquestions(contest_FK, question_FK, sequencenum) VALUES (:contest_FK, :question_FK, :sequencenum)";
		$stmt = $conn->prepare($sql);
		
		$stmt->bindParam(':contest_FK', $contest_FK);
        $stmt->bindParam(':question_FK', $question_FK);
        $stmt->bindParam(':sequencenum', $sequencenum);
		
		$status = $stmt->execute();
		if ($status) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function update_contest_time($contest_PK, $date, $hour, $minute, $seconds){
		$conn = DatabaseConnection::get_connection();
		$sql = "UPDATE contest SET starttime = :starttime WHERE contest_PK = :contest_PK";
		$stmt = $conn->prepare($sql);
		
		$stmt->bindParam(':contest_PK', $contest_PK);
		$stmt->bindParam(':starttime', $s);
		
		$starttime = $date . " " . $hour . ":" . $minute . ":" . $seconds;
		$s = $starttime;
		
		$status = $stmt->execute();
		
		if ($status) {
			return true;
		} else {
			return false;
		}
	}
    // test from here
    public static function assign_contestants($contest_FK, $team_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "INSERT INTO contestview(contest_FK,team_FK) VALUES(:contest_FK, :team_FK)";
        $stmt = $conn->prepare($sql);
		
		$stmt->bindParam(':contest_FK', $contest_FK);
		$stmt->bindParam(':team_FK', $team_FK);
		
        $status = $stmt->execute();
		if ($status) {
			return true;
		} else {
			return false;
		}
	}
	//returns a number of questions in contestquestions
    public static function get_contest_question_count($contest_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT COUNT(question_PK) FROM question INNER JOIN contestquestions ON
                question.question_PK = contestquestions.question_FK WHERE contestquestions.contest_FK=:contest_FK";
        $stmt = $conn->prepare($sql);
		
		$stmt->bindParam(':contest_FK', $contest_FK);
		
		$status = $stmt->execute();
		if ($status) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$count = $result['COUNT(question_PK)'];
			return $count;
		} else {
			return false;
		}
    }

    public static function get_contest_questions($contest_FK){
        // this function accepts a contest id AND returns an associated array of contest problems in the form:
        // 'qid'=>$qid, 'seqnum'=>$seqnum,'question'=>$question,'answer'=>$answer
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT question_PK, title, qtext, answer, sequencenum FROM question INNER JOIN contestquestions
                ON question.question_PK=contestquestions.question_FK WHERE contestquestions.contest_FK=:contest_FK
                ORDER BY sequencenum ASC";
        $stmt = $conn->prepare($sql);
		
		$stmt->bindParam('contest_FK', $contest_FK);
		
		$status = $stmt->execute();
		if($status){
			$stmt->bindColumn('question_PK', $question_PK);
			$stmt->bindColumn('sequencenum', $sequencenum);
			$stmt->bindColumn('title', $title);
			$stmt->bindColumn('qtext', $qtext);
			$stmt->bindColumn('answer', $answer);
			
			$questions = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($questions, array('qid'=>$question_PK, 'sequencenum'=>$sequencenum, 'title'=>$title, 'qtext'=>$qtext, 'answer'=>$answer));
			}
			return $questions;
		} else {
			return false;
		}
    }
	//Returns the number of the sequence
    public static function get_seq($contest_FK, $question_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT sequencenum FROM contestquestions WHERE contest_FK=:contest_FK AND question_FK=:question_FK";
        $stmt = $conn->prepare($sql);
		
		$stmt->bindParam(':contest_FK', $contest_FK);
		$stmt->bindParam(':question_FK', $question_FK);
		
		$status = $stmt->execute();
		if ($status) {
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$seq = $result['sequencenum'];
			return $seq;
		} else {
			return false;
		}
    }

    public static function set_seq($sequencenum, $contest_FK, $question_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE contestquestions SET sequencenum=:sequencenum WHERE contest_FK=:contest_FK AND question_FK=:question_FK";
        $stmt = $conn->prepare($sql);
        
		$stmt->bindParam(':sequencenum', $sequencenum);
	    $stmt->bindParam(':contest_FK', $contest_FK);
		$stmt->bindParam(':question_FK', $question_FK);
		
        $status = $stmt->execute();
		if ($status) {
			return true;
		} else {
			return false;
		}
    }
	//returns the max sequencenum
    public static function get_max_seq($contest_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT MAX(sequencenum) FROM question INNER JOIN contestquestions ON
                question.question_PK=contestquestions.question_FK WHERE contestquestions.contest_FK=:contest_FK";
        $stmt = $conn->prepare($sql);
		
		$stmt->bindParam(':contest_FK', $contest_FK);
		
		$status = $stmt->execute();
		if ($status) {
			$result = $stmt->fetch(PDO::FETCH_NUM);
			$max = $result[0];
			return $max;
		} else {
			return false;
		}
	}
	
    public static function remove_contest_question($contest_FK, $question_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "DELETE FROM contestquestions WHERE contest_FK=:contest_FK AND question_FK=:question_FK";
		$stmt = $conn->prepare($sql);
		
        $stmt->bindParam(':contest_FK', $contest_FK);
		$stmt->bindParam(':question_FK', $question_FK);
		
		$status = $stmt->execute();
		if ($status) {
			return true;
		} else {
			return false;
		}
    }
	
	public static function get_contest($contest_PK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM contest WHERE contest_PK=:contest_PK";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':contest_PK', $contest_PK);
		$status = $stmt->execute();
		if ($status) {
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$stmt->bindColumn('creator_FK', $creator_FK);
			$stmt->bindColumn('name', $name);
			$stmt->fetch(PDO::FETCH_BOUND);
			$contests = array('starttime' => $starttime, 'duration' => $duration, 'creator_FK' => $creator_FK, 'name' => $name);
			return $contests;
		} else {
			return false;
		}
	}
}
?>