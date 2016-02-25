<html>
	<head>
		<!-- These should be session variables. The 'require' part is necessary in
				order to get the question for the contest from the backend -->
		<?php
			require 'back.php';	
			$contestID = 7; 
			$teamID = 112;
		?>
	</head>
	<script src="http://njit1.initiateid.com/library/jquery.js"></script>
	<script src="http://njit1.initiateid.com/library/timer.js"></script>
	
	<body onload="setTime(<?php echo $_GET['unit']; ?>, 'on-time')">
		<div id="timer" style = "float: right; border: 2px solid; min-width:37vw; padding-left: 10px; font-size: 10vw; text-align: center;"></div>
		<div id="message">
			<h1>Welcome to the contest <?php echo $_GET['unit']; ?>! </h1>
			<p><?php getQuest();?></p>
		</div>
		<button id="beginButton" onclick="">Begin!</button>
	</body>
	
</html>