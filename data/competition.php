<<<<<<< HEAD
<<<<<<< HEAD
<?php
  /*
 Matt Wolfman
 Terry Chern
 CS 491
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\db-info.php');

class Competition
{
	protected static $db;

	public function __construct()
	{

	}

	private function __clone()
	{

	}

	public static function get_connection_pdo()
	{
		if (!self::$db) {
			try {
				$database = 'mysql:dbname='. SCHEMA .';host='. SERVER .';port=3306';
				self::$db = new PDO($database, USERNAME, PASSWD);
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
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
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
				die("Error: " . $e->getMessage());
			}
		}
		return self::$db;
	}
<<<<<<< HEAD
<<<<<<< HEAD

	public static function get_connection_mysqli()
	{
		if (!self::$db) {
			self::$db = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
			if (self::$db->connect_error) {
				die("Connection failed: " . self::$db->connect_error);
			}
		}
		return self::$db;
	}

	//Inserts the contest into the contest bank.
	function insert_competition($date, $hour, $minute, $seconds, $duration, $creator_FK)
	{
		$conn = self::get_connection_pdo();

		$stmt = $conn->prepare("INSERT INTO contest (starttime, duration, creator_FK) VALUES (:starttime, :duration, :creator_FK);");

		$stmt->bindParam(':starttime', $s);
		$stmt->bindParam(':duration', $d);
		$stmt->bindParam(':creator_FK', $c);

		$starttime = $date . " " . $hour . ":" . $minute . ":" . $seconds;
=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
	
	function insert_competition($hour, $minute, $duration, $creator_FK) {
		$conn = self::get_connection_pdo();
		
		//Inserts the contest into the contest bank.
		$stmt = $conn->prepare("INSERT INTO contest (starttime, duration, creator_FK) VALUES (:starttime, :duration, :creator_FK);");
		
		$stmt->bindParam(':starttime', $s);
		$stmt->bindParam(':duration', $d);
		$stmt->bindParam(':creator_FK', $c);
	
		$starttime = $hour . ":" . $minute;
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
		$s = $starttime;
		$d = $duration;
		$c = $creator_FK;

<<<<<<< HEAD
<<<<<<< HEAD
		$status = $stmt->execute();

		if ($status) {
=======
		$status = $stmt->execute();	
		
		if($status){
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
		$status = $stmt->execute();	
		
		if($status){
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
			return true;
		} else {
			return false;
		}
	}
<<<<<<< HEAD
<<<<<<< HEAD

	//Insert a teams checkin to the checkin bank.
	function insert_checkin($contest_FK, $team_FK, $checkedin)
	{
		$conn = self::get_connection_pdo();

		$stmt = $conn->prepare("INSERT INTO checkin (contest_FK, team_FK, checkedin) VALUES (:contest_FK, :team_FK, :checkedin);");

		$stmt->bindParam(':contest_FK', $co);
		$stmt->bindParam(':team_FK', $t);
		$stmt->bindParam(':checkedin', $ch);

=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
	
	
	function insert_checkin($contest_FK, $team_FK, $checkedin) {
		$conn = self::get_connection_pdo();
		
		//Inserts the contest into the contest bank.
		$stmt = $conn->prepare("INSERT INTO checkin (contest_FK, team_FK, checkedin) VALUES (:contest_FK, :team_FK, :checkedin);");
		
		$stmt->bindParam(':contest_FK', $co);
		$stmt->bindParam(':team_FK', $t);
		$stmt->bindParam(':checkedin', $ch);
	
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
		$co = $contest_FK;
		$t = $team_FK;
		$ch = $checkedin;

<<<<<<< HEAD
<<<<<<< HEAD
		$status = $stmt->execute();

		if ($status) {
=======
		$status = $stmt->execute();	
		
		if($status){
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
		$status = $stmt->execute();	
		
		if($status){
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
			return true;
		} else {
			return false;
		}
	}
<<<<<<< HEAD
<<<<<<< HEAD
	//Returns all the competitions
	function get_all_competitions()
	{
		$conn = self::get_connection_pdo();

		$stmt = $conn->prepare("SELECT * from contest");

		$status = $stmt->execute();
		if ($status) {
=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
	
	function get_all_competitions(){
		$conn = self::get_connection_pdo();
		
		$stmt = $conn->prepare("SELECT * from contest");

		$status = $stmt->execute();
		if($status){
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
			$stmt->bindColumn('contest_PK', $contest_PK);
			$stmt->bindColumn('starttime', $starttime);
			$stmt->bindColumn('duration', $duration);
			$stmt->bindColumn('creator_FK', $creator_FK);
<<<<<<< HEAD
<<<<<<< HEAD

			$competitions = array();

			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($competitions, array('contest_PK' => $contest_PK, 'starttime' => $starttime, 'duration' => $duration, 'creator_FK' => $creator_FK));
			}

=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
			
			$competitions = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
					array_push($competitions, array('contest_PK'=>$contest_PK, 'starttime'=>$starttime, 'duration'=>$duration, 'creator_FK'=>$creator_FK));
			}
			
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
			return $competitions;
		} else {
			return false;
		}
	}
<<<<<<< HEAD
<<<<<<< HEAD

	function add_question_to_contest($cid, $qid, $seqnum)
	{
		$conn = self::get_connection_mysqli();
		$conn->autocommit(false);
		echo $qid;
		echo $cid;
		echo $seqnum;
		$sql = "INSERT INTO cs491.contestquestions(contest_FK, question_FK, sequencenum) VALUES (?,?,?)";
		if ($stmt = $conn->prepare($sql)) {
			$stmt->bind_param('iii', $cid, $qid, $seqnum);
			$stmt->execute();
			$stmt->close();
		} else {
			echo 'Error inserting elements.';
		}

		if (!$conn->commit()) {
			print("Transaction commit failed\n");
			$conn->close();
			exit();
		}
		$conn->close();
	}
	
	function update_competition_time($contest_PK, $date, $hour, $minute, $seconds){
		$conn = self::get_connection_pdo();
		
		$stmt = $conn->prepare("UPDATE contest SET starttime = :starttime WHERE contest_PK = :contest_PK");
		
		$stmt->bindParam('contest_PK', $c);
		$stmt->bindParam('starttime', $s);
		
		$c = $contest_PK;
		
		$starttime = $date . " " . $hour . ":" . $minute . ":" . $seconds;
		echo $starttime;
		$s = $starttime;
		
		$status = $stmt->execute();
		
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

}
?>
=======
 }
 ?>
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
 }
 ?>
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
