<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/22/2016
 * Time: 11:15 PM
 */

require_once($_SERVER['DOCUMENT_ROOT'] .'/data/db-info.php');
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
<<<<<<< HEAD
<<<<<<< HEAD
//    var_dump($_POST);
    $usr = strtolower($_POST['usr']);
=======
    var_dump($_POST);
    $usr = $_POST['usr'];
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
    var_dump($_POST);
    $usr = $_POST['usr'];
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
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

    $passhash = password_hash($passwd, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usr (usrname, fname, lname, joindate, aff_FK, email, phone, street1, street2, city, state, zip, passhash, creds) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssissssssssi", $usr, $fname, $lname, $date, $aff, $email, $phone, $str1, $str2, $city, $state, $zip, $passhash, $cred);
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
?>