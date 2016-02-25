 <?php
 /*
 Matt Wolfman
 CS 491
 */
 class ModifyGrade{
	protected static $db;

	public function __construct(){

	}
	private function __clone(){
		
    }
	public static function getConnection() {
		if (!self::$db) {
			try {
				$database = 'mysql:dbname=cs491;host=initiateid.com;port=3306';
				$username = 'cs490';
				$pass = 'projprojproj';
				self::$db = new PDO($database, $username, $pass);
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