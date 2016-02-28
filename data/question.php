 <?php
 /*
 Matt Wolfman
 Terry Chern
 CS 491
 */
<<<<<<< HEAD
<<<<<<< HEAD
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\db-info.php');
 
=======
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
 class Question{
	protected static $db;

	public function __construct(){

	}
	private function __clone(){
		
    }
<<<<<<< HEAD
<<<<<<< HEAD
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
	
	public static function get_connection_mysqli() {
<<<<<<< HEAD
<<<<<<< HEAD
=======
		require_once($_SERVER['DOCUMENT_ROOT'] . '/data/db-info.php');
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
		require_once($_SERVER['DOCUMENT_ROOT'] . '/data/db-info.php');
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
		
		if (!self::$db) {
			self::$db = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
			if (self::$db->connect_error) {
				die("Connection failed: " . self::$db->connect_error);
			}
		}
		return self::$db;
	}
	
<<<<<<< HEAD
<<<<<<< HEAD
	//Inserts the question to the question bank.
	function insert_question($title, $qtext, $answer, $deleteable) {
		$conn = self::get_connection_pdo();
		$conn->beginTransaction();
		$stmt = $conn->prepare("INSERT INTO question (title, qtext, answer, deleteable) VALUES (:title, :qtext, :answer, :deleteable)");
		
		$stmt->bindParam(':title', $t);
		$stmt->bindParam(':qtext', $q);
		$stmt->bindParam(':answer', $a);
		$stmt->bindParam(':deleteable', $d);
	
		$t = $title;
		$q = $qtext;
		$a = $answer;
		$d = $deleteable;

		$stmt->execute();
		$qid = $conn->lastInsertId();
		if(!$conn->commit()){
			print("Transaction commit failed!\n");
			$conn = null;
			exit();
		}
		$conn = null;
		return $qid;
	}
	
	//Inserts the new question into the questionio
	function insert_question_io($question_FK, $input, $output, $notes){
		$conn = self::get_connection_pdo();
		$stmt = $conn->prepare("INSERT INTO questionio (question_FK, input, output, notes) VALUES (:question_FK, :input, :output, :notes);");
		$stmt->bindParam(':question_FK', $q);
=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
	function insert_question($qtext, $answer, $input, $output, $notes) {
		$conn = self::get_connection_pdo();
		
		//Inserts the question to the question bank.
		$stmt = $conn->prepare("INSERT INTO question (qtext, answer) VALUES (:qtext, :answer);");
		
		$stmt->bindParam(':qtext', $q);
		$stmt->bindParam(':answer', $a);
	
		$q = $qtext;
		$a = $answer;

		$status = $stmt->execute();	
		
		if($status){
			$question_PK = $this->get_last_question();
			$outcome = $this->insert_question_io($question_PK, $input, $output, $notes);
			return $outcome;
		} else {
			return false;
		}
	}
	//Gets the question_PK of the last inserted question.
	function get_last_question(){
		$conn = self::get_connection_pdo();
		$stmt = $conn->prepare("SELECT question_PK FROM question ORDER BY question_PK DESC LIMIT 1");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC); 
		$result = $stmt->fetch();
		$question_PK = $result['question_PK'];

		return $question_PK;
	}
	
	function insert_question_io($question_PK, $input, $output, $notes){
		$conn = self::get_connection_pdo();
		$stmt = $conn->prepare("INSERT INTO questionio (question_PK, input, output, notes) VALUES (:question_PK, :input, :output, :notes);");
		$stmt->bindParam(':question_PK', $q);
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
		$stmt->bindParam(':input', $i);
		$stmt->bindParam(':output', $o);
		$stmt->bindParam(':notes', $n);

<<<<<<< HEAD
<<<<<<< HEAD
		$q = $question_FK;
=======
		$q = $question_PK;
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
		$q = $question_PK;
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
		$i = $input;
		$o = $output;
		$n = $notes;
		
		$status = $stmt->execute();	
	
		if($status){
			return true;
		} else {
			return false;
<<<<<<< HEAD
<<<<<<< HEAD
		}	
=======
		}
		
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
		}
		
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
	}
	
	function get_all_questions(){
		$conn = self::get_connection_mysqli();
		//$qid = $question = $answer = '';
		$sql = "SELECT * FROM cs491.question";
		if ($stmt = $conn->prepare($sql)) {
			$stmt->execute();
<<<<<<< HEAD
<<<<<<< HEAD
			$stmt->bind_result($qid, $title, $question, $answer, $deleteable);
			$questions = array();
			while($stmt->fetch()){
				array_push($questions, array('qid'=>$qid, 'title'=>$title, 'question'=>$question, 'answer'=>$answer, 'deleteable'=>$deleteable));
=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
			$stmt->bind_result($qid, $question, $answer);
			$stmt->fetch();
			$questions = array();
			while($stmt->fetch()){
				array_push($questions, array('qid'=>$qid,'question'=>$question,'answer'=>$answer));
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
			}
			$stmt->close();
		} else {
			echo 'Error querying database.';
		}
		$conn->close();
		return $questions;
	}
<<<<<<< HEAD
<<<<<<< HEAD

    function get_contest_questions($cid){
    // this function accepts a contest id and returns an associated array of contest problems in the form:
    // 'seqnum'=>$seqnum,'question'=>$question,'answer'=>$answer
		 $conn = self::get_connection_mysqli();

		 $sql = "SELECT qtext, answer, sequencenum FROM cs491.question INNER JOIN cs491.contestquestions ON cs491.question.question_PK=cs491.contestquestions.question_FK WHERE cs491.contestquestions.contest_FK=?";
		 if ($stmt = $conn->prepare($sql)) {
			 $stmt->bind_param('i',$cid);
			 $stmt->execute();
			 $stmt->bind_result($qtext, $answer, $seqnum);
			 $questions = array();
			 while($stmt->fetch()){
				 array_push($questions, array('seqnum'=>$seqnum,'question'=>$qtext,'answer'=>$answer));
			 }
			 $stmt->close();
		 } else {
			 echo 'Error querying database.';
		 }
		 $conn->close();
		 return $questions;
	 }
=======
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
 }
 ?>