<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/data/team.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/data/submission.php');
	require_once('time.php');
	
	$type = $_POST['type'];

	switch($type){
		case 'checking_in':
			$cID = $_POST['contestID'];
			$tID = $_POST['teamID'];
			$checking_in = Contest::get_checkin_status($cID, $tID);
				echo json_encode($checking_in);

			break;
			
		case 'checked_in':
			$cID = $_POST['contestID'];//1;
			$tID = $_POST['teamID'];//1;
			$checked = Contest::set_checkin($cID, $tID);
				echo json_encode($checked);
				
			break;
			
		case 'get_team':
			$uID = $_POST['userID'];
			$teamID = User::get_teamid($uID);

			$return_arr = array('teamID' => $teamID);
				echo json_encode($return_arr);
			break;
			
		case 'get_current':
			$teamID = $_POST['tID'];
			$contests = Team::get_assigned_contests($teamID);
			$contest_info_arr = array();
			
			foreach($contests as $cid){
				$contestInfo = Contest::get_contest_info($cid['cid']);
				
				if(!filter_past_contests($contestInfo))
					$contest_info_arr[] = $contestInfo;
			}
			
			echo json_encode($contest_info_arr);
			
			break;
		
		case 'get_date':
			$cID = $_POST['contestID'];
				echo json_encode(Contest::get_contest_sched($cID));
			break;
		
		case 'submissions_complete':
			$cID = $_POST['contestID'];
			$tID = $_POST['teamID'];
			$subs = Submission :: get_submissions_by_team_and_contest($cID, $tID);
			$quests = Contest :: get_contest_questions($cID);
			if($subs === $quests)
				echo json_encode(1);
			else
				echo json_encode(0);
			break;
	}

?>