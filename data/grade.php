 <?php
/**
 * @author Matt Wolfman
 * @auther Terry Chern
 * @version 2.0
 * @since 4/19/2016
 * @see DatabaseConnection::getConnection() for information about the database connection
 */
 require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');
 
 class Grade{
	 /**
	 * This function updates the starttime of the specified contest
	 * @param contest_FK int this is the id of the contest
	 * @param team_FK    int this is the new or old date of the contest
	 * @param score      int this is the score of the contest
	 * @return boolean true if the update was successful and false if it fails at any point.
	 */
	public static function updateGrade($contest_FK, $team_FK, $score) {
		$conn = DatabaseConnection::get_connection();
		$sql = "UPDATE teamscore SET score=:score WHERE contest_FK=:contest_FK AND team_FK=:team_FK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);
			$stmt->bindParam(':team_FK', $team_FK);
			$stmt->bindParam(':score', $score);
			try{
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}
			return true;
		} else {
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
	 
	public static function set_grade($sub_FK, $qio_FK, $grade){
		$conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO subgrade(sub_FK, qio_FK, grade) VALUES (:sub_FK, :qio_FK, :grade)";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':sub_FK', $sub_FK);
			$stmt->bindParam(':qio_FK', $qio_FK);
			$stmt->bindParam(':grade', $grade);
			try{
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}
			return true;
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}

	/**
	 * Returns all the results
	 * @param contest_FK int this is the id of the contest
	 * @return array contest_FK, team_FK, score, and teamname. If it fails at any point it returns false
	 */
	public static function get_results($contest_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM teamscore INNER JOIN team ON teamscore.team_FK = team.team_PK WHERE contest_FK=:contest_FK ORDER BY score DESC";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':contest_FK', $contest_FK);	
			try{
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}
			$stmt->bindColumn('contest_FK', $contest_FK);
			$stmt->bindColumn('team_FK', $team_FK);
			$stmt->bindColumn('score', $score);
			$stmt->bindColumn('teamname', $teamname);
			$results = array();
			while($rows = $stmt->fetch(PDO::FETCH_BOUND)){
				array_push($results, array('contest_FK'=>$contest_FK, 'team_FK'=>$team_FK, 'score'=>$score, 'teamname'=>$teamname));
			}
			return $results;
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}
 }
 ?>