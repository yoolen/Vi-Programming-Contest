<?php
	
	
	function get_contests(){
	$contests = array(
		array('contest_PK' => '1',
		'starttime' => '02/28/2016 14:30',
		'duration' => '02:30:00',
		'creator_FK'=> '100'),
		array('contest_PK' => '2',
		'starttime' => '02/28/2016 15:30',
		'duration' => '01:30:00',
		'creator_FK'=> '101')
	);
	return $contests;
	}
	
	function checkin($cID, $teamID){
		if($cID == 1 && $teamID == 12){
			return 1;
		}
		return 0;
	}
	
	function getcheckstatus(){
		$checked = 1;
		return $checked;
	}
	//echo json_encode($contest);
?>