<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/24/2016
 * Time: 5:34 PM
 */
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/db-info.php');

function add_question($qtext, $answer){
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->autocommit(false);

    $sql = 'INSERT INTO cs491.question(qtext, answer) VALUES (?, ?)';
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('ss',$qtext,$answer);
        $stmt->execute();
        $qid = $stmt->insert_id;
        $stmt->close();
    } else {
        echo 'Insert error.';
    }

    if(!$conn->commit()){
        print("Transaction commit failed\n");
        $conn->close();
        exit();
    }
    $conn->close();
    return $qid;
}

function get_all_questions(){   // done
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $qid = $question = $answer = "";
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

function get_question($qid){ // done
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT qtext, answer FROM cs491.question WHERE question_PK=?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('i', $qid);
        $stmt->execute();
        $stmt->bind_result($qtext, $answer);
        $stmt->fetch();
        $qdets = array('question'=>$qtext,'answer'=>$answer);
        $stmt->close();
    } else {
        echo 'Error querying the database.';
    }
    $conn->close();
    return $qdets;
}

function get_question_io($qid){ // done
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT input, output, notes FROM cs491.questionio WHERE question_FK=?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('i', $qid);
        $stmt->execute();
        $stmt->bind_result($input, $output, $notes);
        $stmt->fetch();
        $qdets = array('input'=>$input,'output'=>$output,'notes'=>$notes);
        $stmt->close();
    } else {
        echo 'Error querying the database.';
    }
    $conn->close();
    return $qdets;
}

function update_question($qinfo){ // this function accepts an associated array of values and updates the table accordingly
    return 'hello';
}