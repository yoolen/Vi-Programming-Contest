<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/22/2016
 * Time: 11:15 PM
 */
    include_once ("/dbtools/db-info.php");
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);

    $usr = $_POST['usr'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $aff = $_POST['aff'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $str1 = $_POST['street1'];
    $str2 = $_POST['street2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $passwd = $_POST['pwd'];
    $cred = $_POST['ulevel'];
    $date = date("Y-m-d H:i:s");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->autocommit(false);
    if (isset($usrname) and isset($passwd) and isset($fname) and isset($lname)) {
        if ($usr == '' or $passwd == '') {
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

?>