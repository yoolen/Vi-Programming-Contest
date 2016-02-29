<html>
	<head>
		<?php
			require 'back.php';
			$contests = json_encode(get_contests());
			$userID = 12;
		?>
		<title>Current Contests</title>
	</head>
	<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
	<script src="http://njit1.initiateid.com/library/check_in.js"></script>
	<body onload="start_checkin()">
		
		<div id="contests">	
		</div>
		
	</body>
	<script>
		var contest_array = <?php echo $contests;?>;
		var user_ID = <?php echo $userID;?>;
		
		function start_checkin(){
			show_contests(contest_array, user_ID);
			setInterval(function() {
			checkButtons(user_ID);
			}, 1000);
		}
	</script>
	
	
</html>