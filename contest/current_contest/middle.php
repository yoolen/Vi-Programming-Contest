<?php
	include 'back.php';
	
	$type = $_POST['type'];
	$cID = $_POST['contestID'];
	$uID = $_POST['userID'];
	
	if($type == 'checking_in'){
		$checked = getcheckstatus();
	}
	else if ($type == 'checked_in'){
		$checked = checkin($cID, $uID);
	}
	
	echo json_encode($checked);
?>