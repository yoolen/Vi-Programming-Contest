<?php
/*
Matt Wolfman
Terry Chern
CS 491
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\db-info.php');

class Contest
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

    //Inserts the contest into the contest bank.
    public static function insert_competition($date, $hour, $minute, $seconds, $duration, $creator_FK)
    {
        $conn = self::get_connection_pdo();

        $stmt = $conn->prepare("INSERT INTO contest (starttime, duration, creator_FK) VALUES (:starttime, :duration, :creator_FK);");

        $stmt->bindParam(':starttime', $s);
        $stmt->bindParam(':duration', $d);
        $stmt->bindParam(':creator_FK', $c);

        $starttime = $date . " " . $hour . ":" . $minute . ":" . $seconds;
        $s = $starttime;
        $d = $duration;
        $c = $creator_FK;

        $status = $stmt->execute();

        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //Insert a teams checkin to the checkin bank.
    public static function set_checkin($contest_FK, $team_FK)
    {
        $conn = self::get_connection_pdo();

        $stmt = $conn->prepare("INSERT INTO checkin (contest_FK, team_FK) VALUES (:contest_FK, :team_FK);");

        $stmt->bindParam(':contest_FK', $co);
        $stmt->bindParam(':team_FK', $t);

        $co = $contest_FK;
        $t = $team_FK;

        $status = $stmt->execute();

        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_checkin_status($contest, $team){
        $conn = self::get_connection_mysqli();
        $sql = "SELECT COUNT(checkin_PK) FROM checkin WHERE contest_FK=? AND team_FK=?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('ii',$contest,$team);
            $stmt->execute();
            $stmt->bind_result($checkin);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo 'Error querying.';
        }
        $conn->close();
        return $checkin;
    }
    //Returns all the competitions
    public static function get_all_competitions()
    {
        $conn = self::get_connection_pdo();

        $stmt = $conn->prepare("SELECT * from contest");

        $status = $stmt->execute();
        if ($status) {
            $stmt->bindColumn('contest_PK', $contest_PK);
            $stmt->bindColumn('starttime', $starttime);
            $stmt->bindColumn('duration', $duration);
            $stmt->bindColumn('creator_FK', $creator_FK);

            $competitions = array();

            while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
                array_push($competitions, array('contest_PK' => $contest_PK, 'starttime' => $starttime, 'duration' => $duration, 'creator_FK' => $creator_FK));
            }

            return $competitions;
        } else {
            return false;
        }
    }

    public static function add_question_to_contest($cid, $qid, $seqnum)
    {
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
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

    public static function update_competition_time($contest_PK, $date, $hour, $minute, $seconds){
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

    public static function assign_contestants($team, $contest){
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);

        $sql = "INSERT INTO contestview(contest_FK,team_FK) VALUES(?,?)";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('ii',$contest,$team);
            $stmt->execute();
            $stmt->close();
        } else {
            echo 'Insert error.';
        }
        if(!$conn->commit()){
            print("Commit error.");
            $conn->close();
            exit();
        }
        $conn->close();
    }


    public static function get_contest_question_count($cid){
        $conn = self::get_connection_mysqli();

        $sql = "SELECT COUNT(question_PK) FROM cs491.question INNER JOIN cs491.contestquestions ON
                cs491.question.question_PK=cs491.contestquestions.question_FK WHERE cs491.contestquestions.contest_FK=?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $cid);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo 'Error querying database.';
        }
        $conn->close();
        return $count;
    }

    public static function get_contest_questions($cid)
    {
        // this function accepts a contest id and returns an associated array of contest problems in the form:
        // 'qid'=>$qid, 'seqnum'=>$seqnum,'question'=>$question,'answer'=>$answer
        $conn = self::get_connection_mysqli();

        $sql = "SELECT question_PK, title, qtext, answer, sequencenum FROM cs491.question INNER JOIN cs491.contestquestions
                ON cs491.question.question_PK=cs491.contestquestions.question_FK WHERE cs491.contestquestions.contest_FK=?
                ORDER BY sequencenum ASC";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $cid);
            $stmt->execute();
            $stmt->bind_result($qid, $title, $qtext, $answer, $seqnum);
            $questions = array();
            while ($stmt->fetch()) {
                array_push($questions, array('qid'=>$qid,'title'=>$title,'seqnum' => $seqnum, 'question' => $qtext, 'answer' => $answer));
            }
            $stmt->close();
        } else {
            echo 'Error querying database.';
        }
        $conn->close();
        return $questions;
    }

    public static function get_seq($cid, $qid){
        $conn = self::get_connection_mysqli();

        $sql = "SELECT sequencenum FROM contestquestions WHERE contest_FK=? and question_FK=?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ii', $cid, $qid);
            $stmt->execute();
            $stmt->bind_result($seqnum);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo 'Error querying database.';
        }
        $conn->close();
        return $seqnum;
    }

    public static function set_seq($seqnum, $cid, $qid){
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);

        $sql = "UPDATE contestquestions SET sequencenum=? WHERE contest_FK=? and question_FK=?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('iii', $seqnum, $cid, $qid);
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

    public static function get_max_seq($cid){
        $conn = self::get_connection_mysqli();

        $sql = "SELECT MAX(sequencenum) FROM cs491.question INNER JOIN cs491.contestquestions ON
                cs491.question.question_PK=cs491.contestquestions.question_FK WHERE cs491.contestquestions.contest_FK=?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $cid);
            $stmt->execute();
            $stmt->bind_result($max);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo 'Error querying database.';
        }
        $conn->close();
        return $max;
    }

    public static function remove_contest_question($qid, $cid){
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
        $sql = "DELETE FROM contestquestions WHERE question_FK=? and contest_FK=?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ii', $qid, $cid);
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