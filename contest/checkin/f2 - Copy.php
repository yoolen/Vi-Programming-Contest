<html lang="en">
	<head>
		<?php
			require_once ($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
			require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
			require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
			require_once ($_SERVER['DOCUMENT_ROOT'].'/data/submission.php');
			
			$contests = Contest :: get_all_contests();
			$contests_teams = array();
			$info_each_team_and_contest = array();
			
			foreach($contests as $contest){
				$teams = Contest :: get_contest_teams($contest['cid']);
				$contests_teams[$contest['cid']] = $teams;
			}
			
			foreach($contests_teams as $contestID => $teams){
				if(count($contests_teams[$contestID]) != 0){
					$info_each_team_and_contest[$contestID] = array();
					//$question = Question :: get_question(61);
					foreach($teams as $team){
						$team_info = Submission :: get_submissions_by_team_and_contest($contestID,$team['team_FK']);
						$team_array= array();
						$team_question_array = array();
						
						
						/*print_r($team_info);
					echo '<br>';*/
					}
					//print_r($question);
					//echo '<br>';
				}
			}
			//$c = Submission :: get_submissions_by_team_and_contest(1,1);
			//$cc = Contest :: get_contest_questions(1);
			//print_r($cc);
			//print_r($c);
			//print_r($contests_teams);
		?>
	</head>
	<script src="http://njit1.initiateid.com/library/jquery.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<body onload="setContests()">
		<ul id="contests">
		</ul>
		<div id="message"></div>
		<table id="gradeTable" style="width:100%;  border: 1px solid black; border-collapse: collapse;" hidden>
			<tr>
				<th>Questions</th>
				<th>Answers</th>
				<th>Grade</th>
				<th>Notes</th>
			</tr>
		</table>
	</body>
	<script>
		var contests = <?php echo json_encode($contests); ?>;
		var contests_teams = <?php echo json_encode($contests_teams); ?>;
		//console.log(contests_teams);
		var cLen = contests.length;
		function setContests(){
			var ul = document.getElementById("contests");
			
			for (var i = 0; i < cLen; i++){
				var li = document.createElement("li");
				var teams = contests_teams[ contests[i]['cid'] ];
				//console.log(teams);
				li.id = contests[i]['cid'];
				li.innerHTML = contests[i]['name'];
				
				if(teams.length != 0){
					var ul2 = document.createElement("ul");
					ul2.id = "team_contest_" + contests[i]['cid'];
					
					for(var j = 0; j < teams.length; j++){
						var li2 = document.createElement("li");
						li2.value = contests[i]['cid'];
						li2.id = teams[j]['team_FK'];
						li2.innerHTML = "Team " + teams[j]['team_FK'];
						
						li2.onclick = function(e){
							var table = document.getElementById("gradeTable");
							var message = document.getElementById("message");
							
							message.innerHTML = "Currently viewing Team " + this.id + " answers"
							table.hidden = false;
							message.hidden = false;
							
							e.stopPropagation();
						};
						ul2.appendChild(li2);
					}
					ul2.hidden = true;
					li.appendChild(ul2);
				} else {
					var p = document.createElement("p");
					p.id = "team_contest_" + contests[i]['cid'];
					p.innerHTML = "No team assigned to this contest";
					p.hidden = true;
					li.appendChild(p);
				}
				
				li.onclick = function (){
					var table = document.getElementById("gradeTable");
					var message = document.getElementById("message");
					var T = document.getElementById("team_contest_" + this.id);
					if(T.hidden)
						T.hidden = false;
					else
						T.hidden = true;
					table.hidden = true;
					message.hidden = true;
				}
				ul.appendChild(li);
			}
			
		}
	</script>
</html>