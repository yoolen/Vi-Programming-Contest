<html>
	<head>
		<?php
			//require_once($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
			//require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
			//require_once($_SERVER['DOCUMENT_ROOT'].'/data/team.php');
			//require 'back.php';
			//$contests = json_encode(Contest::get_all_contests());
			$teamID = 1;//User::get_teamid(1);
			//$userID = 1;
			//$contests = json_encode(Team::get_assigned_contests($teamID));
			//var_dump ($contests);
		?>
		<title>Current Contests</title>
	</head>
	<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
	<script src="http://njit1.initiateid.com/library/checkinTimer.js"></script>
	<script src="http://njit1.initiateid.com/library/moment.js"></script>
	<body onload="start_checkin()">
		
		<div id="contests">	
		</div>
		
	</body>
	<script>
		//var contest_array = <?php echo $contests;?>;
		var user_ID = <?php echo $teamID;?>;//$_GET['unit'];
		
		function start_checkin(){
			/*get_team_info(user_ID);
			setInterval(function(){
				checkButtons(user_ID);
			}, 5000);*/
			setupCurrentContest(user_ID);
		}
		
	</script>
	
	
</html>