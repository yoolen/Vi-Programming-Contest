<?php
    include_once ('db-info.php');

function getaffs(){
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "select aff_PK, affname from cs491.affiliation";

    $affiliates = array();

    if($stmt = $conn->prepare($sql)) {
        $stmt->execute();       // execute the query
        $stmt->store_result();  // store results to get properties
        $stmt->bind_result($affid, $affname);   // bind results to variables to use
        while ($stmt->fetch()) {  // get each result and store it into an array
            $affiliates[] = $affid . ' - ' . $affname;
        }
        $stmt->free_result();   // free results
        $stmt->close();         // close the statement
    }
    $conn->close();       // close connection
    return $affiliates;
}



function createUser()
{
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);

    $usrname = $_POST['usr'];
    $passwd = $_POST['pwd'];
    $cred = $_POST['usrlvl'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $aff = $_POST['aff'];
    $dept = $_POST['dept'];
    $date = date('Y-m-d');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->autocommit(false);
    if (isset($usrname) and isset($passwd)) {
        if ($usrname == '' or $passwd == '' or is_numeric($cred) == FALSE) {
            echo "Bad Username/Password/Credentials";
        } else {
            $passhash = password_hash($passwd, PASSWORD_DEFAULT);
            $sql = "INSERT INTO authentication (username, passhash, permissions) VALUES (?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssi", $usrname, $passhash, $cred);
                $stmt->execute();
                $userid = $stmt->insert_id;
                $stmt->close();
                $success = ($userid != 0) ? true : false;
            }
            $sql = "INSERT INTO userinfo (user_FPK, fname, lname, affiliation, dept_FK, joindate) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("isssis", $userid, $fname, $lname, $aff, $dept, $date);
                $stmt->execute();
                $stmt->close();
                $success = ($userid != 0) ? true : false;
            }
            if ($success == true) {
                $conn->commit();
                echo "Successful insert.";
            } else {
                echo "Not inserted.";
            }

        }
    } else {
        echo "error";
    }
    $conn->close();
    echo 'PHP finished';

}
?>

