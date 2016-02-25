 <?php
 /*
 Matt Wolfman
 CS 491
 */
 class CreateQuestion{
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
	
	function insertQuestion($qtext, $answer, $input, $output, $notes) {
		$conn = self::getConnection();
		
		//Inserts the question to the question bank.
		$stmt = $conn->prepare("INSERT INTO question (qtext, answer) VALUES (:qtext, :answer);");
		
		$stmt->bindParam(':qtext', $q);
		$stmt->bindParam(':answer', $a);
	
		$q = $qtext;
		$a = $answer;

		$status = $stmt->execute();	
		
		if($status){
			//Gets the question_PK of the last inserted question.
			$stmt2 = $conn->prepare("SELECT question_PK FROM question ORDER BY question_PK DESC LIMIT 1");
			$stmt2->execute();
			$stmt2->setFetchMode(PDO::FETCH_ASSOC); 
			$result = $stmt2->fetch();
			
			$question_PK = $result['question_PK'];
			
			//Inserts the question to questionio bank
			$stmt3 = $conn->prepare("INSERT INTO questionio (question_PK, input, output, notes) VALUES (:question_PK, :input, :output, :notes);");
			
			$stmt3->bindParam(':question_PK', $q);
			$stmt3->bindParam(':input', $i);
			$stmt3->bindParam(':output', $o);
			$stmt3->bindParam(':notes', $n);
	
			$q = $question_PK;
			$i = $input;
			$o = $output;
			$n = $notes;
			
			$status2 = $stmt3->execute();	
		
			if($status2){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
 }
 ?>