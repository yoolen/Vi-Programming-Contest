 <?php
 /*
 Matt Wolfman
 CS 491
 */
 
 require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');
 
 class Grade{
	public static function updateGrade($contest_FK, $team_FK, $score) {
		$conn = DatabaseConnection::get_connection();
		$sql = "UPDATE teamscore SET score=:score WHERE contest_FK=:contest_FK AND team_FK=:team_FK";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':contest_FK', $contest_FK);
		$stmt->bindParam(':team_FK', $team_FK);
		$stmt->bindParam(':score', $score);
		$status = $stmt->execute();	
		if($status){
			return true;
		} else {
			return false;
		}
	}

	 public static function set_grade($sub_FK, $qio_FK, $grade){
		 /* Ulenn Terry Chern 
		  * 
		  */
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

	public static function get_results($contest_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM teamscore INNER JOIN team ON teamscore.team_FK = team.team_PK WHERE contest_FK=:contest_FK ORDER BY score DESC";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':contest_FK', $contest_FK);	
		$status = $stmt->execute();	
		if($status){
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
			return false;
		}
	}
 }
 ?>