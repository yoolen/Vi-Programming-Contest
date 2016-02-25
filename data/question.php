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
		$stmt->bindParam(':input', $i);
		$stmt->bindParam(':output', $o);
		$stmt->bindParam(':notes', $n);

		$q = $question_PK;
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
			$stmt->fetch();
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
 }
 ?>