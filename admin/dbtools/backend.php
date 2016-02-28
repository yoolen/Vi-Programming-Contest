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

?>