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

<<<<<<< HEAD
<<<<<<< HEAD
function update_question($qtext, $answer){ // working on this
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

=======
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
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
<<<<<<< HEAD
<<<<<<< HEAD
=======
        $stmt->fetch();
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
        $stmt->fetch();
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
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

<<<<<<< HEAD
<<<<<<< HEAD
function get_contest_questions($cid){ // done, refactored
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT qtext, answer, sequencenum FROM cs491.question INNER JOIN cs491.contestquestions
            ON cs491.question.question_PK=cs491.contestquestions.question_FK WHERE cs491.contestquestions.contest_FK=?";
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
function get_question_io($qid){ // done
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

<<<<<<< HEAD
<<<<<<< HEAD
    $sql = "SELECT qio_PK, input, output, notes FROM cs491.questionio WHERE question_FK=?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('i', $qid);
        $stmt->execute();
        $stmt->bind_result($qioid, $input, $output, $notes);
        $stmt->fetch();
        $qdets = array('qioid'=>$qioid,'input'=>$input,'output'=>$output,'notes'=>$notes);
=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
    $sql = "SELECT input, output, notes FROM cs491.questionio WHERE question_FK=?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('i', $qid);
        $stmt->execute();
        $stmt->bind_result($input, $output, $notes);
        $stmt->fetch();
        $qdets = array('input'=>$input,'output'=>$output,'notes'=>$notes);
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
        $stmt->close();
    } else {
        echo 'Error querying the database.';
    }
    $conn->close();
    return $qdets;
}

<<<<<<< HEAD
<<<<<<< HEAD
function add_question_to_contest($qid, $cid, $seqnum){
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->autocommit(false);

    $sql = "INSERT INTO cs491.contestquestions (contest_FK, question_FK, sequencenum) VALUE (?,?,?)";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param('iii', $cid, $qid, $seqnum);
        $stmt->execute();
    } else {
        echo 'Error inserting elements.';
    }

    if(!$conn->commit()){
        print("Transaction commit failed\n");
        $conn->close();
        exit();
    }
    $conn->close();
=======
function update_question($qinfo){ // this function accepts an associated array of values and updates the table accordingly
    return 'hello';
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
function update_question($qinfo){ // this function accepts an associated array of values and updates the table accordingly
    return 'hello';
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
}