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
	public static function get_answers($question_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT input, output FROM questionio WHERE question_FK=:question_FK";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':question_FK', $question_FK);
		$status = $stmt->execute();	
		if($status){
			$stmt->bindColumn('input', $input);
			$stmt->bindColumn('output', $output);
			
			$answers = array();
			
			while($rows = $stmt->fetch(PDO::FETCH_BOUND)){
				array_push($answers, array('input'=>$input, 'output'=>$output));
			}
			
			return $answers;
		} else {
			return false;
		}
	}
	public static function get_results($contest_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM teamscore WHERE contest_FK=:contest_FK ORDER BY score DESC";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':contest_FK', $contest_FK);	
		$status = $stmt->execute();	
		if($status){
			$stmt->bindColumn('contest_FK', $contest_FK);
			$stmt->bindColumn('team_FK', $team_FK);
			$stmt->bindColumn('score', $score);
			$results = array();
			while($rows = $stmt->fetch(PDO::FETCH_BOUND)){
				array_push($results, array('contest_FK'=>$contest_FK, 'team_FK'=>$team_FK, 'score'=>$score));
			}
			return $results;
		} else {
			return false;
		}
	}
 }
 ?>