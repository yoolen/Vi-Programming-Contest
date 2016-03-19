<?php
	//include 'back.php';
	require_once($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/data/team.php');
	
	$type = $_POST['type'];//'checking_in';
	
	if($type == 'checking_in'){
		$cID = $_POST['contestID'];//1;
		$uID = $_POST['userID'];//1;
		$checked = Contest::get_checkin_status($cID, $uID);
		echo json_encode($checked);
	}
	else if ($type == 'checked_in'){
		$cID = $_POST['contestID'];//1;
		$uID = $_POST['userID'];//1;
		$checked = Contest::set_checkin($cID, $uID);
		echo json_encode($checked);
	}
	else if ($type == 'get_team'){
		$uID = $_POST['userID'];
		$teamID = User::get_teamid($uID);

		$return_arr = array('teamID' => $teamID);

		echo json_encode($return_arr);
	} 
	else if ($type == 'get_contests'){
		$teamID = $_POST['teamID'];
		$contests = Team::get_assigned_contests($teamID);
		
		$contest_info_arr = array();
		foreach($contests as $cid){
			$contestInfo = Contest::get_contest_info($cid['cid']);
			$contest_info_arr[] = $contestInfo;
		}
		
		echo json_encode($contest_info_arr);
	}
?>