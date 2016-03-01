<?php
/*
Matt Wolfman
Terry Chern
CS 491
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\db-info.php');

class Question
{
    protected static $db;

    public function __construct()
    {

    }

    private function __clone()
    {

    }

    private function get_connection_pdo()
    {
        if (!self::$db) {
            try {
                $database = 'mysql:dbname=' . SCHEMA . ';host=' . SERVER . ';port=3306';
                self::$db = new PDO($database, USERNAME, PASSWD);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }
        }
        return self::$db;
    }

    private function get_connection_mysqli(){
        self::$db = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
        if (self::$db->connect_error) {
            die("Connection failed: " . self::$db->connect_error);
        }
        return self::$db;
    }

    //Inserts the question to the question bank.
    public static function insert_question($title, $qtext, $answer, $deleteable)
    {
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
        if (!$conn->commit()) {
            print("Transaction commit failed!\n");
            $conn = null;
            exit();
        }
        $conn = null;
        return $qid;
    }

    //Inserts the new question into the questionio
    public static function insert_question_io($question_FK, $input, $output, $notes)
    {
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

        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    public static function modify_question($qid, $title, $qtext, $answer, $deleteable){
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
        $sql = "UPDATE cs491.question SET title=?, qtext=?, answer=?, deleteable=? WHERE question_PK=?";

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('sssii',$title,$qtext,$answer,$deleteable,$qid);
            $stmt->execute();
            $stmt->close();
        }

        if(!$conn->commit()){
            print('Error committing.');
            $conn->close();
            exit();
        }
        $conn->close();
    }

    public static function modify_questionio($qioid, $input, $output, $notes){
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
        $sql = "UPDATE cs491.questionio SET input=?, output=?, notes=? WHERE qio_PK=?";

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('sssi',$input,$output,$notes,$qioid);
            $stmt->execute();
            $stmt->close();
        }

        if(!$conn->commit()){
            print('Error committing.');
            $conn->close();
            exit();
        }
        $conn->close();
    }

    public static function get_all_questions()
    {
        $conn = self::get_connection_mysqli();
        //$qid = $question = $answer = '';
        $sql = "SELECT * FROM cs491.question";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->execute();
            $stmt->bind_result($qid, $title, $question, $answer, $deleteable);
            $questions = array();
            while ($stmt->fetch()) {
                array_push($questions, array('qid' => $qid, 'title' => $title, 'question' => $question, 'answer' => $answer, 'deleteable' => $deleteable));
            }
            $stmt->close();
        } else {
            echo 'Error querying database.';
        }
        $conn->close();
        return $questions;
    }

    public static function delete_question($qid){ // this function needs to check for deleteability
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
        $sql = "DELETE FROM question WHERE question_PK=?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $qid);
            $stmt->execute();
            $stmt->close();
        } else {
            echo 'Error querying database.';
        }
        if(!$conn->commit()){
            print("Commit error.");
            $conn->close();
            exit();
        }
        $conn->close();
    }
}
?>