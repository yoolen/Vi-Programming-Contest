<?php
    include_once ('db-info.php');

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

function verify($loginInfo){
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $user = json_decode($loginInfo);

    // Query for username and select password hash from database
    if ($stmt = $conn->prepare("SELECT user_PK, passhash, permissions FROM authentication WHERE username=?")) {
        $stmt->bind_param("s", $user->{"username"});
        $stmt->execute();
        $stmt->bind_result($uid, $passhash, $permissions);
        $stmt->fetch();
        $stmt->close();
    }

    // Close connection
    $conn->close();

    // Check hashed password against stored password
    // Return JSON file with permissions:
    // 1 - student; 2 - professor; 3 - admin; -1 - not found; -2 - bad username/password; 0 - unused
    if ($passhash != null) {
        if (password_verify($user->{"passwd"}, $passhash)){
            return json_encode(array("uid"=>"$uid", "perms" => "$permissions"));
        } else {
            return json_encode(array("uid" => "$uid","perms" => -2));
        }
    } else {
        return json_encode(array("uid" => "$uid","perms" => -1));
    }
}
?>