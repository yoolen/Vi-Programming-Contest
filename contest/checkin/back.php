<?php
	// There should be a function to get the credentials for the contest.
	$credentials = array(
		'contestID' => 2,
		'teamID' => 112
	);
	
	$returnArr = array('checked_in' => false);
	
	foreach($credentials as $key => $value){
		if($credentials[$key] != $_POST[$key]){
			echo json_encode($returnArr);
			return;
		}
	}
	
	$returnArr['checked_in'] = true;
	echo json_encode($returnArr);
?>