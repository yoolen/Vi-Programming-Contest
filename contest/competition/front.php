<html>
	<head>
		<!-- These should be session variables. The 'require' part is necessary in
				order to get the question for the contest from the backend -->
		<?php
			require_once ($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
			$cID = $_GET['unit'];
			$q = new Question();
			
			$contestqs = Contest::get_contest_questions($cID);
		?>
		<title>Contest <?php echo $cID; ?></title>
	</head>
	<script src="http://njit1.initiateid.com/library/jquery.js"></script>
	<script src="http://njit1.initiateid.com/library/timer.js"></script>
	
	<body onload="setTime(<?php echo $cID; ?>, 'on-time')">
		<div id="timer" style = "float: right; border: 2px solid; min-width:37vw; padding-left: 10px; font-size: 10vw; text-align: center; margin: 10px"></div>
		<div id="message">
			<h1>Welcome to the contest <?php echo $cID; ?>! </h1>
			
			<ol id="question_list" style="font-weight:bold">
			</ol>
		</div>
		<button id="beginButton" onclick="">Begin!</button>
	</body>
	
	<script>
		var contestQuests = <?php echo json_encode($contestqs); ?>;
		var ol_div = document.getElementById('question_list');

			
		for(var i = 0; i < contestQuests.length; i++){
			var li_div = document.createElement('li');
			var span = document.createElement('span');
				span.style = "font-weight:normal";
			
			li_div.id = contestQuests[i]['seqnum'];
			
			span.innerHTML = contestQuests[i]['question'];
			li_div.appendChild(span);
			
			li_div.style = "margin-bottom: 10px";
			ol_div.appendChild(li_div);
			//ul_div.appendChild(br)
		}
	</script>
</html>