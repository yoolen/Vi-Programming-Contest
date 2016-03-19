<?php
	//include 'back.php';
	require_once($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/data/team.php');
	require_once('time.php');
	//include 'back.php';
	
	$type = $_POST['type'];//'checking_in';
	function c (){
		$contest_info_arr = array(
				array('cid' => '1',
				'starttime' => '03/20/2016 22:25',
				'duration' => '02:00:00',
				'creator_FK'=> '100'),
				array('cid' => '2',
				'starttime' => '04/12/2016 13:45',
				'duration' => '02:00:30',
				'creator_FK'=> '101')
			);
			return $contest_info_arr;
	}
	
	switch($type){
		case 'checking_in':
			/*$cID = $_POST['contestID'];//1;
			$tID = $_POST['teamID'];//1;
			$checking_in = Contest::get_checkin_status($cID, $tID);
				echo json_encode($checking_in);
			*/
				echo json_encode(0);
			break;
			
		case 'checked_in':
			/*$cID = $_POST['contestID'];//1;
			$tID = $_POST['teamID'];//1;
			$checked = Contest::set_checkin($cID, $tID);
				echo json_encode($checked);*/
				echo json_encode(1);
			break;
			
		case 'get_team':
			//$uID = $_POST['userID'];
			$teamID = 1;//User::get_teamid(1);

			$return_arr = array('teamID' => $teamID);
				echo json_encode($return_arr);
			break;
			
		case 'get_current':
			/*$teamID = $_POST['tID'];
			$contests = Team::get_assigned_contests($teamID);
			$contest_info_arr = array();
			
			foreach($contests as $cid){
				$contestInfo = Contest::get_contest_info($cid['cid']);
				
				if(!filter_past_contests($contestInfo));
					$contest_info_arr[] = $contestInfo;
			}
			*/
			
				echo json_encode(c());
			break;
		
		case 'get_date':
			$cID = $_POST['contestID'];
				echo json_encode(get_contest_sched($cID));
			break;
	}

?>