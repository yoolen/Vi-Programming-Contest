<?php

/* this function is a prototype for the backend. Feel
  free to edit the date, start time and duration to test the timer */

require_once($_SERVER['DOCUMENT_ROOT'] . '/data/db-info.php');

function get_contest_sched($cid) {
    $conn = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT starttime, duration FROM cs491.contest WHERE contest_PK=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $cid);
        $stmt->execute();
        $stmt->bind_result($starttime, $duration);
        $stmt->fetch();
        $sched = array('starttime' => $starttime, 'duration' => $duration);
        $stmt->close();
    } else {
        echo 'Error querying the database.';
    }
    $conn->close();
    return $sched;
}

//var_dump(get_contest_sched(1));
/*
function get_contest_sched($cID) {
    if ($cID == 1)
        $arr = array(
            "starttime" => "2016-3-29 14:34",
            "duration" => "00:08:05"
        );
    else if ($cID == 2)
        $arr = array(
            'starttime' => '05/12/2016 16:30',
            'duration' => '01:05:30'
        );

    return($arr);
}
*/
?>