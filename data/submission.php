<?php
  /*
 Matt Wolfman
 CS 491
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\db-info.php');
 
 class Submission{
	protected static $db;

	public function __construct(){

	}
	private function __clone(){
		
    }
	public static function get_connection_pdo()
	{
		if (!self::$db) {
			try {
				$database = 'mysql:dbname='. SCHEMA .';host='. SERVER .';port=3306';
				self::$db = new PDO($database, USERNAME, PASSWD);
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				die("Error: " . $e->getMessage());
			}
		}
		return self::$db;
	}
	
	function get_all_sbumissions(){
		$conn = self::get_connection_pdo();
		
		$stmt = $conn->prepare("SELECT * from contest");

		$status = $stmt->execute();
		if($status){
			$stmt->bindColumn('sub_PK', $sub_PK);
			$stmt->bindColumn('question_FK', $question_FK);
			$stmt->bindColumn('team_FK', $team_FK);
			$stmt->bindColumn('submission', $submission);
			$stmt->bindColumn('subtime', $subtime);
			
			$submissions = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
					array_push($submissions, array('sub_PK'=>$sub_PK, 'question_FK'=>$question_FK, 'team_FK'=>$team_FK, 'submission'=>$submission, 'subtime'=>$subtime);
			}
			
			return $submissions;
		} else {
			return false;
		}
	}
}

$t = new Submission();
