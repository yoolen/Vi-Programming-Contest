<html lang="en">
	<head>
		<!--These should be the session variables for this page-->
		<?php $contestID = 2; $teamID = 112?>
	</head>
	<script src="http://njit1.initiateid.com/library/jquery.js"></script>
	<body>
		<div id="message">
			<h1>Welcome to the contest <?php echo $contestID; ?>! </h1>
			Please click the button to check-in!
		</div>
		<button id="compButton" onclick="check_In_Valid()">Check In</button>
	</body>
	<script>
		/* Function to validate the session variables. If they correspond to the
			contest that is stored in the database, then they can successfully
			participate in the contest. Otherwise, the admin has to rectify the
			issue.
		*/
		function check_In_Valid(){
			var cID = <?php echo $contestID; ?>;
			var success = $.ajax({
				// This should be the php to check credentials
				url:"back.php",
				method:"POST",
				data: {contestID: cID, teamID: <?php echo $teamID;?>},
			});
			
			success.done(function(response){
				var parsed_Resp = JSON.parse(response);
				
				if(parsed_Resp['checked_in']){
					alert("Check in successful! Click OK to continue to the contest page");
					window.location = "http://njit1.initiateid.com/contest/pre_post/front.php?unit="+cID;
				} else {
					alert("Check in unsuccessful! Please see administrator");
				}
			});
		}
	</script>
	
	
</html>