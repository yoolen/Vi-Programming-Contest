<html>
	<head>
		<?php
			$teamID = 5;
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
		
		var user_ID = <?php echo $teamID;?>;
		
		function start_checkin(){
			setupCurrentContest(user_ID);
		}
		
	</script>
	
	
</html>