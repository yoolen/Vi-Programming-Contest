 <?php
 class CreateCompetition{
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
	
	function insertCompetition($hour, $minute, $duration, $creator_FK) {
		$conn = self::getConnection();
		
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
 }
 ?>