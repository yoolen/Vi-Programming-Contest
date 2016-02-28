 <?php
 /*
 Matt Wolfman
 Terry Chern
 CS 491
 */
 class Question{
	protected static $db;

	public function __construct(){

	}
	private function __clone(){
		
    }
	public static function get_connection_pdo() {
		//require_once($_SERVER['DOCUMENT_ROOT'] . '/data/db-info.php');

		if (!self::$db) {
			try {
				$database = 'mysql:dbname=cs491;host=localhost;port=3306';
				self::$db = new PDO($database, "cs490", "projprojproj");
				self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e) {
				die("Error: " . $e->getMessage());
			}
		}
		return self::$db;
	}
	
	public static function get_connection_mysqli() {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/data/db-info.php');
		
		if (!self::$db) {
			self::$db = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
			if (self::$db->connect_error) {
				die("Connection failed: " . self::$db->connect_error);
			}
		}
		return self::$db;
	}
	
	function insert_question($qtext, $answer) {
		$conn = self::get_connection_pdo();
		$conn->beginTransaction();
		//Inserts the question to the question bank.
		$stmt = $conn->prepare("INSERT INTO question (qtext, answer) VALUES (:qtext, :answer)");
		
		$stmt->bindParam(':qtext', $q);
		$stmt->bindParam(':answer', $a);
	
		$q = $qtext;
		$a = $answer;

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
		$stmt->bindParam(':input', $i);
		$stmt->bindParam(':output', $o);
		$stmt->bindParam(':notes', $n);

		$q = $question_FK;
		$i = $input;
		$o = $output;
		$n = $notes;
		
		$status = $stmt->execute();	
	
		if($status){
			return true;
		} else {
			return false;
		}
		
	}
	
	function get_all_questions(){
		$conn = self::get_connection_mysqli();
		//$qid = $question = $answer = '';
		$sql = "SELECT * FROM cs491.question";
		if ($stmt = $conn->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($qid, $question, $answer);
			$questions = array();
			while($stmt->fetch()){
				array_push($questions, array('qid'=>$qid,'question'=>$question,'answer'=>$answer));
			}
			$stmt->close();
		} else {
			echo 'Error querying database.';
		}
		$conn->close();
		return $questions;
	}

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
 }
 ?>