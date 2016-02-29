<html>
	<head>
		<!-- These should be session variables. The 'require' part is necessary in
				order to get the question for the contest from the backend -->
		<?php
<<<<<<< HEAD
			require 'back.php';	
			$contestID = 7; 
			$teamID = 112;
=======
			require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
			$cID = $_GET['unit'];
			$q = new Question();
			
			$contestqs = $q->get_contest_questions($cID);
>>>>>>> database-admin
		?>
	</head>
	<script src="http://njit1.initiateid.com/library/jquery.js"></script>
	<script src="http://njit1.initiateid.com/library/timer.js"></script>
	
<<<<<<< HEAD
	<body onload="setTime(<?php echo $_GET['unit']; ?>, 'on-time')">
		<div id="timer" style = "float: right; border: 2px solid; min-width:37vw; padding-left: 10px; font-size: 10vw; text-align: center;"></div>
		<div id="message">
			<h1>Welcome to the contest <?php echo $_GET['unit']; ?>! </h1>
			<p><?php getQuest();?></p>
=======
	<body onload="setTime(<?php echo $cID; ?>, 'on-time')">
		<div id="timer" style = "float: right; border: 2px solid; min-width:37vw; padding-left: 10px; font-size: 10vw; text-align: center;"></div>
		<div id="message">
			<h1>Welcome to the contest <?php echo $cID; ?>! </h1>
			
			<ol id="question_list">
			</ol>
>>>>>>> database-admin
		</div>
		<button id="beginButton" onclick="">Begin!</button>
	</body>
	
<<<<<<< HEAD
=======
	<script>
		var contestQuests = <?php echo json_encode($contestqs); ?>;
		var ol_div = document.getElementById('question_list');

		for(var i = 0; i < contestQuests.length; i++){
			var li_div = document.createElement('li');
			li_div.id = contestQuests[i]['seqnum'];
			li_div.innerHTML = contestQuests[i]['question'];
			
			li_div.style = "margin-bottom: 10px";
			ol_div.appendChild(li_div);
			//ul_div.appendChild(br)
		}
	</script>
>>>>>>> database-admin
</html>