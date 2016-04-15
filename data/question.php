<?php
/*
Matt Wolfman
Terry Chern
CS 491
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');

class Question{
    //Inserts the question to the question bank.
    public static function insert_question($title, $qtext, $answer, $deleteable){
        $conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO question (title, qtext, answer, deleteable) VALUES (:title, :qtext, :answer, :deleteable)";
        $stmt = $conn->prepare($sql);   
		$stmt->bindParam(':title', $title);
        $stmt->bindParam(':qtext', $qtext);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':deleteable', $deleteable);
        $status = $stmt->execute();
		if($status){
			$qid = $conn->lastInsertId();
			return $qid;
		} else {
			return false;
		}
    }

    public static function delete_question_io($qio_PK){
        $conn = DatabaseConnection::get_connection();
        $sql = "DELETE FROM questionio WHERE qio_PK=:qio_PK";
        if($stmt = $conn->prepare($sql)){
            $stmt->bindParam(':qio_PK', $qio_PK);

            try{
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            echo 'Successfully deleted';
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    //Inserts the new question into the questionio
    public static function insert_question_io($question_FK, $input, $output, $notes){
        $conn = DatabaseConnection::get_connection();
		$sql = "INSERT INTO questionio (question_FK, input, output, notes) VALUES (:question_FK, :input, :output, :notes)";
        $stmt = $conn->prepare($sql);
		$stmt->bindParam(':question_FK', $question_FK);
        $stmt->bindParam(':input', $input);
        $stmt->bindParam(':output', $output);
        $stmt->bindParam(':notes', $notes);
        $status = $stmt->execute();
		if ($status) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_answers($question_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT qio_PK, input, output, notes FROM questionio WHERE question_FK=:question_FK";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':question_FK', $question_FK);
        $status = $stmt->execute();
        if($status){
            $stmt->bindColumn('qio_PK', $qio_PK);
            $stmt->bindColumn('input', $input);
            $stmt->bindColumn('output', $output);
            $stmt->bindColumn('notes', $notes);

            $answers = array();

            while($rows = $stmt->fetch(PDO::FETCH_BOUND)){
                array_push($answers, array('qio_PK'=>$qio_PK, 'input'=>$input, 'output'=>$output, 'notes'=>$notes));
            }

            return $answers;
        } else {
            return false;
        }
    }

    public static function get_all_question_io($question_FK, $contest_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT qio_PK,questionio.question_FK,input,output,notes FROM questionio INNER JOIN contestquestions ON contestquestions.question_FK = questionio.question_FK WHERE contest_FK=:contest_FK";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':contest_FK', $contest_FK);

        $status = $stmt->execute();
        if($status){
            $stmt->bindColumn('qio_PK', $qio_PK);
            $stmt->bindColumn('question_FK', $question_FK);
            $stmt->bindColumn('input', $input);
            $stmt->bindColumn('output', $output);
            $stmt->bindColumn('notes', $notes);

            $ios = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($ios, array('qioid'=>$qio_PK, 'qid'=>$question_FK, 'input'=>$input, 'output'=>$output, 'notes'=>$notes));
			}
			return $ios;
        } else {
            return false;
        }
    }

    public static function get_question_ios($question_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT qio_PK,input,output,notes FROM questionio WHERE question_FK=:question_FK";
        if($stmt = $conn->prepare($sql)) {

            $stmt->bindParam(':question_FK', $question_FK);

            try {
                $stmt->execute();

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }

            $stmt->bindColumn('qio_PK', $qio_PK);
            $stmt->bindColumn('input', $input);
            $stmt->bindColumn('output', $output);
            $stmt->bindColumn('notes', $notes);

            $ios = array();

            while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
                array_push($ios, array('qioid' => $qio_PK, 'qid' => $question_FK, 'input' => $input, 'output' => $output, 'notes' => $notes));
            }
            return $ios;

        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    public static function get_question_io($qio_PK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT question_FK,input,output,notes FROM questionio WHERE qio_PK=:qio_PK";
        if($stmt = $conn->prepare($sql)) {

            $stmt->bindParam(':qio_PK', $qio_PK);

            try {
                $stmt->execute();

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            $stmt->bindColumn('question_FK', $question_FK);
            $stmt->bindColumn('input', $input);
            $stmt->bindColumn('output', $output);
            $stmt->bindColumn('notes', $notes);
            $stmt->fetch(PDO::FETCH_BOUND);
            return array('qioid' => $qio_PK, 'qid' => $question_FK, 'input' => $input, 'output' => $output, 'notes' => $notes);
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

	//Modify the question in the question bank 
    public static function modify_question($question_PK, $title, $qtext, $answer, $deleteable){
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE question SET title=:title, qtext=:qtext, answer=:answer, deleteable=:deleteable WHERE question_PK=:question_PK";
		$stmt = $conn->prepare($sql);	
		$stmt->bindParam(':question_PK', $question_PK);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':qtext', $qtext);
        $stmt->bindParam(':answer', $answer);
        $stmt->bindParam(':deleteable', $deleteable);	
        $status = $stmt->execute();
		if ($status) {
            return true;
        } else {
            return false;
        }
    }
	//Modify the question in the questionio bank 
    public static function modify_question_io($qio_PK, $input, $output, $notes){
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE questionio SET input=:input, output=:output, notes=:notes WHERE qio_PK=:qio_PK";
		$stmt = $conn->prepare($sql);

		$stmt->bindParam(':qio_PK', $qio_PK);
        $stmt->bindParam(':input', $input);
        $stmt->bindParam(':output', $output);
        $stmt->bindParam(':notes', $notes);
		$status = $stmt->execute();
		if ($status) {
            return true;
        } else {
            return false;
        }  
    }

    public static function get_all_questions(){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT * FROM question";
        $stmt = $conn->prepare($sql);
		
        $status = $stmt->execute();
		if($status){
			$stmt->bindColumn('question_PK', $question_PK);
			$stmt->bindColumn('title', $title);
			$stmt->bindColumn('qtext', $qtext);
			$stmt->bindColumn('answer', $answer);
			$stmt->bindColumn('deleteable', $deleteable);
			
			$questions = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($questions, array('qid'=>$question_PK, 'title'=>$title, 'qtext'=>$qtext, 'answer'=>$answer, 'deleteable'=>$deleteable));
			}
			return $questions;
		} else {
			return false;
		}
    }

    public static function get_all_questions_no_ans(){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT question_PK, title, qtext FROM question";
        $stmt = $conn->prepare($sql);

        $status = $stmt->execute();
        if($status){
            $stmt->bindColumn('question_PK', $question_PK);
            $stmt->bindColumn('title', $title);
            $stmt->bindColumn('qtext', $qtext);

            $questions = array();

            while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
                array_push($questions, array('qid'=>$question_PK, 'title'=>$title, 'qtext'=>$qtext));
            }
            return $questions;
        } else {
            return false;
        }
    }
	
	public static function get_question($question_PK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT * FROM question WHERE question_PK = :question_PK";
        $stmt = $conn->prepare($sql);
		
		$stmt->bindParam(':question_PK', $q);
		
		$q = $question_PK;
		$status = $stmt->execute();
        if($status){
			$stmt->bindColumn('question_PK', $question_PK);
			$stmt->bindColumn('title', $title);
			$stmt->bindColumn('qtext', $qtext);
			$stmt->bindColumn('answer', $answer);
			$stmt->bindColumn('deleteable', $deleteable);
			
			$questions = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($questions, array('qid'=>$question_PK, 'title'=>$title, 'qtext'=>$qtext, 'answer'=>$answer, 'deleteable'=>$deleteable));
			}
			return $questions[0];
		} else {
			return false;
		}
    }
	
	// this function needs to check for deleteability
	//Is deleteability  = 0?
    public static function delete_question($question_PK){ 
        $conn = DatabaseConnection::get_connection();
        $sql = "DELETE FROM question WHERE question_PK=:question_PK AND deleteable = 1";
        $stmt = $conn->prepare($sql);	
        $stmt->bindParam(':question_PK', $question_PK);
        $status = $stmt->execute();
		if ($status) {
            return true;
        } else {
            return false;
        } 
    }
}
?>