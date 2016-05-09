<?php
/**
 * @author Matt Wolfman
 * @auther Terry Chern
 * @version 2.0
 * @since 4/19/2016
 * @see DatabaseConnection::getConnection() for information about the database connection
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');

class Contest{
	/**
	 * This function inserts a new contest into the contest table
	 * @param date       date   this is the date of the contest
	 * @param hours      int    this is the amount of hours for the starttime input 
	 * @param minutes    int    this is the amount of minutes for the starttime input 
	 * @param seconds    int    this is the amount of seconds for the starttime input 
	 * @param duration   time   this is the amount of time given to a contest
	 * @param creator_FK int    this is creator id of the creator of the contest 	 
	 * @param name       string this is the name of the specific contest
	 * @return boolean true if the insert was successful and false if it fails at any point.
	 */
	public static function insert_contest($date, $hours, $minutes, $seconds, $duration, $creator_FK, $name){
		$conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO contest (starttime, duration, creator_FK, name) VALUES (:starttime, :duration, :creator_FK, :name)";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':starttime', $s);
			$stmt->bindParam(':duration', $duration);
			$stmt->bindParam(':creator_FK', $creator_FK);
			$stmt->bindParam(':name', $name);
			//FireFox cannot pass datetimes through HTML so instead we build the datetime object from strings of the date, hours, minutes, and seconds.
			$starttime = $date . " " . $hours . ":" . $minutes . ":" . $seconds;
			$s = $starttime;
			try {
                $stmt->execute();
            } catch (PDOException $e){ 
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * Insert a teams checkin to the checkin bank.
	 * (NOTE: Pending revision)
	 * @param contest_FK int this is the id of the contest
	 * @param team_FK    int this is the id of the team
	 * @return boolean true if the insert was successful and false if it fails at any point.
	 */
	public static function set_checkin($contest_FK, $team_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO checkin (contest_FK, team_FK) VALUES (:contest_FK, :team_FK)";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':team_FK', $team_FK);
			try {
                $stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                //return false;
            }
            return 1;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            //return false;
        }
	}
	/**
	 * 
	 * @param contest_FK int this is the id of the contest
	 * @param team_FK    int this is the id of the team
	 * @return 
	 */
	public static function get_checkin_status($contest_FK, $team_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT COUNT(*) FROM checkin WHERE contest_FK=:contest_FK AND team_FK=:team_FK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':team_FK', $team_FK);
			try {   
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
			return $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * Returns all the contests
	 * @return array cid refers to contest_PK, (month, day, year, hours, minutes, and seconds) come from starttime, (dhours, dminutes, and dseconds) come from duration, creator_FK, and name. If it fails at any point it returns false
	 */
	public static function get_all_contests(){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * from contest";
		if($stmt = $conn->prepare($sql)){
			try {   
				$stmt->execute();
			} catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
			$stmt->bindColumn('contest_PK', $contest_PK);
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$stmt->bindColumn('creator_FK', $creator_FK);
			$stmt->bindColumn('name', $name);
			$contests = array();
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				$dateAndTime = explode(" ", $starttime);
				$date = explode("-", $dateAndTime[0]);
				$time = explode(":", $dateAndTime[1]);
				$dur = explode(":", $duration);
				array_push($contests, array('cid' => $contest_PK, 'month' => $date[1], 'day' => $date[2], 'year' => $date[0], 'hours' => $time[0], 'minutes' => $time[1], 'seconds' => $time[2], 'dhours' => $dur[0], 'dminutes' =>$dur[1], 'dseconds' => $dur[2], 'creator_FK' => $creator_FK, 'name' => $name));
			}
			return $contests;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * This function inserts a new question into the contestquestions table
	 * @param contest_FK  int this is the id of the contest
	 * @param question_FK int this is the id of the question
	 * @param sequencenum int this is the number that cooresponds to the number the question on the specified contest
	 * @return boolean true if the insert was successful and false if it fails at any point.
	 */
	public static function add_question_to_contest($contest_FK, $question_FK, $sequencenum){
		$conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO contestquestions(contest_FK, question_FK, sequencenum) VALUES (:contest_FK, :question_FK, :sequencenum)";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':question_FK', $question_FK);
			$stmt->bindParam(':sequencenum', $sequencenum);
			try {
                $stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * This function updates the starttime of the specified contest
	 * @param contest_FK int  this is the id of the contest
	 * @param date       date this is the new or old date of the contest
	 * @param hour       int  this is the new or old hour of the contest
	 * @param minute     int  this is the new or old minute of the contest
	 * @param seconds    int  this is the new or old seconds of the contest
	 * @return boolean true if the update was successful and false if it fails at any point.
	 */
	public static function update_contest_time($contest_PK, $date, $hour, $minute, $seconds){
		$conn = DatabaseConnection::get_connection();
		$sql = "UPDATE contest SET starttime = :starttime WHERE contest_PK = :contest_PK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_PK', $contest_PK);
			$stmt->bindParam(':starttime', $starttime);
			$starttime = $date . " " . $hour . ":" . $minute . ":" . $seconds;
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * This function assigns teams to a specified contest
	 * @param contest_FK int this is the id of the contest
	 * @param team_FK    int this is the id of the team
	 * @return boolean true if the insert was successful and false if it fails at any point.
	 */
    public static function set_contest_team($contest_FK, $team_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "INSERT INTO contestview(contest_FK,team_FK) VALUES(:contest_FK, :team_FK)";
        if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':team_FK', $team_FK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}

	public static function get_contest_teams($contest_PK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT team_FK FROM contestview WHERE contest_FK=:contest_FK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK',$contest_PK);
			try {
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}
			$stmt->bindColumn('team_FK',$team_FK);
			$teams = array();
			while($row = $stmt->fetch(PDO::FETCH_BOUND)){
				array_push($teams, array('team_FK'=>$team_FK));
			}
			return $teams;
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}

	/**
	 * This function returns a number of questions in contestquestions
	 * @param contest_FK int this is the id of the contest
	 * @return int if it fails returns false, if it passes it returns the number of questions in contestquestions
	 */
    public static function get_contest_question_count($contest_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT COUNT(question_PK) FROM question INNER JOIN contestquestions ON
                question.question_PK = contestquestions.question_FK WHERE contestquestions.contest_FK=:contest_FK";
        if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
			return $stmt->fetch(PDO::FETCH_ASSOC)['COUNT(question_PK)'];
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
    }
	/**
	 * This function returns the questions for a specified contest
	 * @param contest_FK int this is the id of the contest
	 * @return array qid refers to question_PK, sequencenum, title, qtext, and answer. If it fails at any point it returns false
	 */
    public static function get_contest_questions($contest_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT question_PK, title, qtext, answer, sequencenum FROM question INNER JOIN contestquestions
                ON question.question_PK=contestquestions.question_FK WHERE contestquestions.contest_FK=:contest_FK
                ORDER BY sequencenum ASC";
        if($stmt = $conn->prepare($sql)){
			$stmt->bindParam('contest_FK', $contest_FK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
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
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
    }
	/**
	 * This function returns sequencenum of the question on a specified contest
	 * @param contest_FK  int this is the id of the contest
	 * @param question_FK int this is the id of the question
	 * @return int the sequencenum. If it fails it return false.
	 */
    public static function get_seq($contest_FK, $question_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT sequencenum FROM contestquestions WHERE contest_FK=:contest_FK AND question_FK=:question_FK";
        if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':question_FK', $question_FK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
			return $stmt->fetch(PDO::FETCH_ASSOC)['sequencenum'];
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
    }
	/**
	 * This function updates a questions sequencenum
	 * @param sequencenum int this is the number that cooresponds to the number the question on the specified contest
	 * @param contest_FK  int this is the id of the contest
	 * @param question_FK int this is the id of the question
	 * @return boolean true if the update was successful and false if it fails at any point.
	 */
    public static function set_seq($sequencenum, $contest_FK, $question_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE contestquestions SET sequencenum=:sequencenum WHERE contest_FK=:contest_FK AND question_FK=:question_FK";
        if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':sequencenum', $sequencenum);
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':question_FK', $question_FK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
    }
	/**
	 * This function returns the max sequencenum
	 * @param contest_FK int this is the id of the contest
	 * @return int the max sequencenum. If it fails it return false.
	 */
    public static function get_max_seq($contest_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT MAX(sequencenum) FROM question INNER JOIN contestquestions ON
                question.question_PK=contestquestions.question_FK WHERE contestquestions.contest_FK=:contest_FK";
        if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
			return $stmt->fetch(PDO::FETCH_NUM)[0];
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * This function deletes a question from contestquestions. (NOTE: function will fail if a contest has been taken)
	 * @param contest_FK  int this is the id of the contest
	 * @param question_FK int this is the id of the question
	 * @return boolean true if the delete was successful and false if it fails at any point.
	 */
    public static function remove_contest_question($contest_FK, $question_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "DELETE FROM contestquestions WHERE contest_FK=:contest_FK AND question_FK=:question_FK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':question_FK', $question_FK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
			return true;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
    }
	/**
	 * Returns a specified contest
	 * @param contest_FK  int this is the id of the contest
	 * @return array (month, day, year, hours, minutes, and seconds) come from starttime, (dhours, dminutes, and dseconds) come from duration, creator_FK, and name. If it fails at any point it returns false
	 */
	public static function get_contest($contest_PK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM contest WHERE contest_PK=:contest_PK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_PK', $contest_PK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$stmt->bindColumn('creator_FK', $creator_FK);
			$stmt->bindColumn('name', $name);
			$stmt->fetch(PDO::FETCH_BOUND);
			$dateAndTime = explode(" ", $starttime);
			$date = explode("-", $dateAndTime[0]);
			$time = explode(":", $dateAndTime[1]);
			$dur = explode(":", $duration);
			$contests = array('month' => $date[1], 'day' => $date[2], 'year' => $date[0], 'hours' => $time[0], 'minutes' => $time[1], 'seconds' => $time[2], 'dhours' => $dur[0], 'dminutes' =>$dur[1], 'dseconds' => $dur[2], 'creator_FK' => $creator_FK, 'name' => $name);
			return $contests;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * Returns the information for a contest
	 * @param contest_FK  int this is the id of the contest
	 * @return array cid refers to contest_PK, starttime, duration, creator_FK, and name. If it fails at any point it returns false
	 */
	public static function get_contest_info($contest_PK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM contest WHERE contest_PK=:contest_PK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_PK', $contest_PK);
			try {
				$stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$stmt->bindColumn('contest_PK', $contest_PK);
			$stmt->bindColumn('name', $name);
			$stmt->fetch(PDO::FETCH_BOUND);
			$contests = array('cid'=>$contest_PK, 'starttime'=> $starttime, 'duration'=>$duration, 'name'=>$name);
			return $contests;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * This function updates a contest
	 * @param date       date   this is the date of the contest
	 * @param hours      int    this is the amount of hours for the starttime input 
	 * @param minutes    int    this is the amount of minutes for the starttime input 
	 * @param seconds    int    this is the amount of seconds for the starttime input 
	 * @param duration   time   this is the amount of time given to a contest
	 * @param creator_FK int    this is creator id of the creator of the contest 	 
	 * @param name       string this is the name of the specific contest
	 * @return boolean true if the insert was successful and false if it fails at any point.
	 */
	public static function update_contest($contest_PK, $date, $hours, $minutes, $seconds, $duration, $creator_FK, $name){
		$conn = DatabaseConnection::get_connection();
		$sql = "UPDATE contest SET starttime=:starttime, duration=:duration, creator_FK=:creator_FK, name=:name WHERE contest_PK = :contest_PK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_PK', $contest_PK);
			$stmt->bindParam(':starttime', $starttime);
			$stmt->bindParam(':duration', $duration);
			$stmt->bindParam(':creator_FK', $creator_FK);
			$stmt->bindParam(':name', $name);
			$starttime = $date . " " . $hours . ":" . $minutes . ":" . $seconds;
			try {
                $stmt->execute();
            } catch (PDOException $e){
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * Returns the name of the specified contest 
	 * @param contest_FK  int this is the id of the contest
	 * @return string the name of the contest. If it fails at any point it returns false
	 */
	public static function get_contest_name($contest_PK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT name FROM contest WHERE contest_PK = :contest_PK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_PK', $contest_PK);
			try {
                $stmt->execute();
            } catch (PDOException $e){
                echo $e->getMessage();
                return false;
            }
			$stmt->bindColumn('name', $name);
			$stmt->fetch(PDO::FETCH_BOUND);
			return $name;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * Selects all the contest times
	 * @returns array contest_PK, starttime, and duration. If it fails it returns false
	 */
	public static function get_contest_times(){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT contest_PK, starttime, duration FROM contest";
		if($stmt = $conn->prepare($sql)){
			try {
                $stmt->execute();
            } catch (PDOException $e){
                echo $e->getMessage();
                return false;
            }
			$stmt->bindColumn('contest_PK', $contest_PK);
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$contests = array();
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($contests, array('contest_PK'=>$contest_PK, 'starttime'=>$starttime, 'duration'=>$duration));
			}
			return $contests;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
	/**
	 * This function returns the starttime and duration of a specified contest.
	 * @param contest_FK  int this is the id of the contest
	 * @return string the name of the contest. If it fails at any point it returns false
	 */
	public static function get_contest_sched($contest_PK) {
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT starttime, duration FROM cs491.contest WHERE contest_PK=:contest_PK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_PK', $contest_PK);
			try {
                $stmt->execute();
            } catch (PDOException $e){
                echo $e->getMessage();
                return false;
            }
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$stmt->fetch(PDO::FETCH_ASSOC);
			$sched = array('starttime' => $starttime, 'duration' => $duration);
			return $sched;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
/**
	 * This function returns the total time spent on a contest
	 * @param contest_FK  int this is the id of the contest
	 * @return array of times spent on each question, flase if it fails 
	 */
	public static function get_contest_time($contest_FK, $team_FK) {
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT subtime FROM submission INNER JOIN contestquestions ON submission.question_FK = contestquestions.question_FK WHERE contest_FK=:contest_FK AND team_FK=:team_FK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':team_FK', $team_FK);
			try {
                $stmt->execute();
            } catch (PDOException $e){
                echo $e->getMessage();
                return false;
            }
			$stmt->bindColumn('subtime', $subtime);
			$contests = array();
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($contests, $subtime);
			}
			return $contests;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
	}
}
?>