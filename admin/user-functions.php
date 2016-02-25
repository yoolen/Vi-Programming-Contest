<?php
/**
 * Created by PhpStorm.
 * User: yoole
 * Date: 2/25/2016
 * Time: 1:39 PM
 */
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/db-info.php');
function verify($usrname, $passwd){
    /*  Takes 2 string variables as input and returns an associated array of the form:
     *  array( usrID => $usrID, usrlvl => $usrlvl ); where $usrID is a unique identifier
     *  and $usrlvl is an integer for which 1 - admin; 2 - judge; 3 - grader; 4 - contestant;
     *  If an error occurs (either incorrect username or password) the following values will be returned:
     *  -1 - not found; -2 - bad username/password
     */
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