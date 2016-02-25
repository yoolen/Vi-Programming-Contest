 <?php
 /*
 Matt Wolfman
 CS 491
 */
 class Grade{
	protected static $db;

	public function __construct(){

	}
	private function __clone(){
		
    }
	public static function getConnection() {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dbtools/db-info.php');
		
		if (!self::$db) {
			try {
				$database = 'mysql:dbname=' . SCHEMA . ';host=' . SERVER . ';port=3306';
				self::$db = new PDO($database, USERNAME, PASSWD);
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e) {
				die("Error: " . $e->getMessage());
			}
		}
		return self::$db;
	}
	
	function updateGrade($contest_FK, $team_FK, $score) {
		$conn = self::getConnection();
		
		//Modify grade
		$stmt = $conn->prepare("UPDATE teamscore SET score=:score WHERE contest_FK=:contest_FK AND team_FK=:team_FK");
		
		$stmt->bindParam(':contest_FK', $c);
		$stmt->bindParam(':team_FK', $t);
		$stmt->bindParam(':score', $s);
	
		
		$c = $contest_FK;
		$t = $team_FK;
		$s = $score;

		$status = $stmt->execute();	
		if($status){
			return true;
		} else {
			return false;
		}
	}
 }
 ?>