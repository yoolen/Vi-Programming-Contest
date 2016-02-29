<?php
/**
 * Created by PhpStorm.
 * User: yoole
 * Date: 2/25/2016
 * Time: 1:39 PM
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/db-info.php');

class User
{

    protected static $db;

    public function __construct()
    {
    }

    private function __clone()
    {
    }

    private function get_connection_mysqli()
    {
        self::$db = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
        if (self::$db->connect_error) {
            die("Connection failed: " . self::$db->connect_error);
        }
        return self::$db;
    }

    public static function verify($usrname, $passwd)
    {
        /*  Takes 2 string variables as input and returns an associated array of the form:
         *  array( usrID => $usrID, usrlvl => $usrlvl ); where $usrID is a unique identifier
         *  and $usrlvl is an integer for which 1 - admin; 2 - judge; 3 - grader; 4 - contestant;
         *  If an error occurs (either incorrect username or password) the following values will be returned:
         *  -1 - not found; -2 - bad username/password
         */
        $conn = self::get_connection_mysqli();

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
            if (password_verify($passwd, $passhash)) {
                return array("usrID" => "$usrID", "usrlvl" => "$usrlvl");
            } else {
                return array("usrID" => "$usrID", "usrlvl" => -2);
            }
        } else {
            return array("usrID" => "$usrID", "usrlvl" => -1);
        }
    }

    public static function create_user($userinfo)
    {
        /*  This function takes an associated array that must contain the following fields:
         *  usr, fname, lname, aff, email, phone, street1, street2, city, state, zip, and cred
         */
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
//    var_dump($_POST);
        $date = date("Y-m-d H:i:s");

        $passhash = password_hash($userinfo['passwd'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usr (usrname, fname, lname, joindate, aff_FK, email, phone, street1, street2, city, state, zip, passhash, creds) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssissssssssi", $userinfo['usr'], $userinfo['fname'], $userinfo['lname'], $date, $userinfo['aff'],
                $userinfo['email'], $userinfo['phone'], $userinfo['street1'], $userinfo['street2'], $userinfo['city'], $userinfo['state'],
                $userinfo['zip'], $passhash, $userinfo['cred']);
            $stmt->execute();
            $userid = $stmt->insert_id;
            $stmt->close();
        }
        if (!$conn->commit()) {
            print("Transaction commit failed\n");
            $conn->close();
            exit();
        }
        $conn->close();
        return $userid;
    }

    public static function admin_modify_user($userinfo)
    {
        /*  This function takes an associated array that must contain the following fields:
         *  usr, fname, lname, aff, email, phone, street1, street2, city, state, zip, and cred
         *  The user's username may not be changed. The password much be changed in a separate operation.
         */
        $conn = self::get_connection_mysqli();

        $sql = "UPDATE usr SET fname=?, lname=?, aff_FK=?, email=?, phone=?, street1=?, street2=?, city=?,
            state=?, zip=?, creds=? WHERE usr = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssisssssssis", $userinfo['fname'], $userinfo['lname'], $userinfo['aff'], $userinfo['email'],
                $userinfo['phone'], $userinfo['street1'], $userinfo['street2'], $userinfo['city'], $userinfo['state'],
                $userinfo['zip'], $userinfo['cred'], $userinfo['usr']);
            $stmt->execute();
            $stmt->close();
            if ($conn->commit()) {
                echo "User successfully updated.";
            } else {
                echo "Submission failed.";
            }
        } else {
            echo "Database error.";
        }
        $conn->close();
    }

    public static function get_all_users()
    {
        // This function returns an array of associated arrays containing the user ID as uid, the username as 'usr',
        // the first name as 'fname', the last name as 'lname', and their email as 'email'.
        $conn = self::get_connection_mysqli();
        $sql = "SELECT usr_PK, usrname, fname, lname, email FROM cs491.usr";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->execute();
            $stmt->bind_result($uid, $usr, $fname, $lname, $email);
            $users = array();
            while ($stmt->fetch()) {
                array_push($users, array('uid' => $uid, 'usr' => $usr, 'fname' => $fname, 'lname' => $lname, 'email' => $email));
            }
            $stmt->close();
        } else {
            echo "Query failed:" . $conn->error;
        }
        $conn->close();
        return $users;
    }

    public static function set_user_password($uid, $passwd)
    {
        // This function accepts a user id and a password and updates the user's password in the database accordingly
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
        $passhash = password_hash($passwd, PASSWORD_DEFAULT);
        echo $passhash;
        $sql = "UPDATE cs491.usr SET passhash=? WHERE usr_PK=?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('si', $passhash, $uid);
            $stmt->execute();
        }
        if (!$conn->commit()) {
            print("Transaction commit failed\n");
            $conn->close();
            exit();
        }
        $conn->close();
    }

    public static function get_user($uid)
    {
        // This function returns an associated array with all user information except password. This may be used to
        // populate forms with information (written specifically with modify_user() in mind).
        $conn = self::get_connection_mysqli();

        $sql = "SELECT * FROM cs491.usr WHERE usr_PK=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $stmt->bind_result($uid, $usrname, $fname, $lname, $joindate, $aff_FK, $email, $phone, $street1, $street2, $city, $state, $zip, $passhash, $creds);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo 'Query failed: ' . $conn->error;
        }
        return (array('uid' => $uid, 'usr' => $usrname, 'fname' => $fname, 'lname' => $lname, 'joindate' => $joindate, 'aff' => $aff_FK,
            'email' => $email, 'phone' => $phone, 'street1' => $street1, 'street2' => $street2, 'city' => $city, 'state' => $state,
            'zip' => $zip, 'creds' => $creds));
    }


    public static function get_user_email($uid)
    {
        // This function takes a user_PK as an int and returns their email as a string.
        $conn = self::get_connection_mysqli();

        $sql = "SELECT email FROM cs491.usr WHERE usr_PK=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $stmt->bind_result($email);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo 'Error querying database: ' . $stmt->error;
        }
        $conn->close();
        return $email;
    }

}

?>