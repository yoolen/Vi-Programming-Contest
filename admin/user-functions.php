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

function create_user($userinfo){
<<<<<<< HEAD
/*  This function takes an associated array
 *
=======
/*  This function takes an associated array that must contain the following fields:
 *  usr, fname, lname, aff, email, phone, street1, street2, city, state, zip, and cred
>>>>>>> database-admin
 */
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->autocommit(false);

//    var_dump($_POST);
    $date = date("Y-m-d H:i:s");

    $passhash = password_hash($userinfo['passwd'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usr (usrname, fname, lname, joindate, aff_FK, email, phone, street1, street2, city, state, zip, passhash, creds) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssissssssssi",$userinfo['usr'],$userinfo['fname'],$userinfo['lname'],$date,$userinfo['aff'],
            $userinfo['email'],$userinfo['phone'],$userinfo['street1'],$userinfo['street2'],$userinfo['city'],$userinfo['state'],
            $userinfo['zip'],$passhash,$userinfo['cred']);
        $stmt->execute();
        $userid = $stmt->insert_id;
        $stmt->close();
        $success = ($userid != 0) ? true : false;
        if ($success == true) {
            $conn->commit();
            echo "User successfully added.";
        } else {
            echo "Submission failed.";
        }
    } else {
        echo "Database error.";
    }
    $conn->close();
}

<<<<<<< HEAD
=======
function admin_modify_user($userinfo){
/*  This function takes an associated array that must contain the following fields:
 *  usr, fname, lname, aff, email, phone, street1, street2, city, state, zip, and cred
 *  The user's username may not be changed.
 */
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->autocommit(false);

    $passhash = password_hash($userinfo['passwd'], PASSWORD_DEFAULT);

    $sql = "UPDATE usr SET fname=?, lname=?, aff_FK=?, email=?, phone=?, street1=?, street2=?, city=?,
            state=?, zip=?, passhash=?, creds=? WHERE usr = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssissssssssis",$userinfo['fname'],$userinfo['lname'],$userinfo['aff'],$userinfo['email'],
            $userinfo['phone'],$userinfo['street1'],$userinfo['street2'],$userinfo['city'],$userinfo['state'],
            $userinfo['zip'],$passhash,$userinfo['cred'],$userinfo['usr']);
        $stmt->execute();
        $stmt->close();
        if($conn->commit()){
            echo "User successfully added.";
        } else {
            echo "Submission failed.";
        }
    } else {
        echo "Database error.";
    }
    $conn->close();
}

function get_all_users(){
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "";
}
>>>>>>> database-admin
?>