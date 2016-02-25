<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/data/db-info.php');

function getaffs()
{
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "select aff_PK, affname from affiliation";

    $affiliates = array();

    if ($stmt = $conn->prepare($sql)) {
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

function verify($usrname, $passwd){
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    // Query for username and select password hash from database
    if ($stmt = $conn->prepare("SELECT usr_PK, passhash, creds FROM cs491.usr WHERE usrname=?")) {
        $stmt->bind_param("s", $usrname);
        $stmt->execute();
        $stmt->bind_result($usrID, $passhash, $usrlvl);
        $stmt->fetch();
        $stmt->close();
    }
    // Close connection
    $conn->close();
    // Check hashed password against stored password
    // 1 - admin; 2 - judge; 3 - grader; 4 - contestant; -1 - not found; -2 - bad username/password; 0 - unused
    if ($passhash != null) {    // if there was no passhash user was not found
        if (password_verify($passwd, $passhash)){
            return array("usrID"=>"$usrID", "usrlvl" => "$usrlvl");
        } else {
            return array("usrID"=>"$usrID", "usrlvl" => -2);
        }
    } else {
        return array("usrID" => "$usrID","usrlvl" => -1);
    }
}
?>