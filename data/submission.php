<?php
/*
Matt Wolfman
CS 491
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');

class Submission{
    public static function get_all_submissions(){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT * from submission";
        $stmt = $conn->prepare($sql);

        $status = $stmt->execute();
        if($status){
            $stmt->bindColumn('sub_PK', $sub_PK);
            $stmt->bindColumn('question_FK', $question_FK);
            $stmt->bindColumn('team_FK', $team_FK);
            $stmt->bindColumn('submission', $submission);
            $stmt->bindColumn('subtime', $subtime);

            $submissions = array();

            while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
                array_push($submissions, array('sub_PK'=>$sub_PK, 'question_FK'=>$question_FK, 'team_FK'=>$team_FK, 'submission'=>$submission, 'subtime'=>$subtime));
            }

            return $submissions;
        } else {
            return false;
        }
    }

    public static function get_submissions_by_team($team_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT * FROM submission WHERE team_FK=:team_FK";
        if($stmt = $conn->prepare($sql)){
            $stmt->bindParam(':team_FK',$team_FK);
            try {
                $stmt->execute();
            } catch (PDOException $e){
                echo $e->getMessage();
                return false;
            }
            $stmt->bindColumn('sub_PK', $sub_PK);
            $stmt->bindColumn('question_FK', $question_FK);
            $stmt->bindColumn('team_FK', $team_FK);
            $stmt->bindColumn('submission', $submission);
            $stmt->bindColumn('subtime', $subtime);

            $submissions = array();

            while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
                array_push($submissions, array('sub_PK'=>$sub_PK, 'question_FK'=>$question_FK, 'team_FK'=>$team_FK, 'submission'=>$submission, 'subtime'=>$subtime));
            }

            return $submissions;
        } else {
            echo $stmt->errorCode();
            return false;
        }

    }

    public static function add_submission($question_FK, $team_FK, $submission){
        /* Terry Chern - 30 March 2016 - 9:35PM
         * This function accepts a question_ID, team_ID, and the submission for that question and stores it into the database
         */
        $conn = DatabaseConnection::get_connection();
        $maketime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO submission(question_FK, team_FK, submission, subtime) VALUES (:question_FK, :team_FK, :submission, :maketime)";
        if($stmt = $conn->prepare($sql)){
            $stmt->bindParam(':question_FK',$question_FK);
            $stmt->bindParam(':team_FK',$team_FK);
            $stmt->bindParam(':submission',$submission);
            $stmt->bindParam(':maketime',$maketime);
            try {
                $stmt->execute();
            } catch (PDOException $e){
                return $e->getMessage();
                //return false;
            }
            //echo 'Successfully submitted';
            return $conn->lastInsertId();
        } else {
            return $stmt->errorCode();
            //return false;
        }
    }
}
?>