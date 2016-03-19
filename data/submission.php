<?php
  /*
 Matt Wolfman
 CS 491
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');
 
 class Submission{
	public static function get_all_submissions(){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * from submission";
		$stmt = $conn->prepare($sql);

		$status = $stmt->execute();
		if($status){
			$stmt->bindColumn('sub_PK', $sub_PK);
			$stmt->bindColumn('question_FK', $question_FK);
			$stmt->bindColumn('team_FK', $team_FK);
			$stmt->bindColumn('submission', $submission);
			$stmt->bindColumn('subtime', $subtime);
			
			$submissions = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
					array_push($submissions, array('sub_PK'=>$sub_PK, 'question_FK'=>$question_FK, 'team_FK'=>$team_FK, 'submission'=>$submission, 'subtime'=>$subtime));
			}
			
			return $submissions;
		} else {
			return false;
		}
	}
}
?>