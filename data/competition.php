 <?php
 /*
 Matt Wolfman
 CS 491
 */
 
 
 class Competition{
	protected static $db;

	public function __construct(){

	}
	private function __clone(){
		
    }
	public static function get_connection_pdo() {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/data/db-info.php');
		
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
	
	function insert_competition($hour, $minute, $duration, $creator_FK) {
		$conn = self::get_connection_pdo();
		
		//Inserts the contest into the contest bank.
		$stmt = $conn->prepare("INSERT INTO contest (starttime, duration, creator_FK) VALUES (:starttime, :duration, :creator_FK);");
		
		$stmt->bindParam(':starttime', $s);
		$stmt->bindParam(':duration', $d);
		$stmt->bindParam(':creator_FK', $c);
	
		$starttime = $hour . ":" . $minute;
		$s = $starttime;
		$d = $duration;
		$c = $creator_FK;

		$status = $stmt->execute();	
		
		if($status){
			return true;
		} else {
			return false;
		}
	}
	
	
	function insert_checkin($contest_FK, $team_FK, $checkedin) {
		$conn = self::get_connection_pdo();
		
		//Inserts the contest into the contest bank.
		$stmt = $conn->prepare("INSERT INTO checkin (contest_FK, team_FK, checkedin) VALUES (:contest_FK, :team_FK, :checkedin);");
		
		$stmt->bindParam(':contest_FK', $co);
		$stmt->bindParam(':team_FK', $t);
		$stmt->bindParam(':checkedin', $ch);
	
		$co = $contest_FK;
		$t = $team_FK;
		$ch = $checkedin;

		$status = $stmt->execute();	
		
		if($status){
			return true;
		} else {
			return false;
		}
	}
	
	function get_all_competitions(){
		$conn = self::get_connection_pdo();
		
		$stmt = $conn->prepare("SELECT * from contest");

		$status = $stmt->execute();
		if($status){
			$stmt->bindColumn('contest_PK', $contest_PK);
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$stmt->bindColumn('creator_FK', $creator_FK);
			
			$competitions = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
					array_push($competitions, array('contest_PK'=>$contest_PK, 'starttime'=>$starttime, 'duration'=>$duration, 'creator_FK'=>$creator_FK));
			}
			
			return $competitions;
		} else {
			return false;
		}
	}
 }
 ?>