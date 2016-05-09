<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'\data\submission.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'\data\grade.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'\data\contest.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'\data\question.php');
	if(isset($_POST['submissionID'])){
		//DO NOT ECHO IN HERE!
		//Do any grade modification logic here.
		
		$updateGrade = Grade :: update_grade($_POST['submissionID'], $_POST['correct']);
		$getScore = Grade :: get_score($_POST['teamID'], $_POST['contestID']);
		
		if($_POST['correct'] == '1')
			$stat = Grade :: update_score($_POST['contestID'], $_POST['teamID'], $getScore + 1);
		else
			$stat = Grade :: update_score($_POST['contestID'], $_POST['teamID'], $getScore - 1);
			
		if($updateGrade)
			$msg = "Updated succesfully!";
		else
			$msg = "Update failed!";

		header("location: ?msg=".$msg);
		//Echoing may resume.
	}
	else{
		if(isset($_GET['msg'])) echo $_GET['msg'];
		
		if(!isset($_GET['submission'])){
			if(isset($_GET['mode']) && $_GET['mode'] == 'teamview'){
				//team view
				echo '<h1>Team View</h1>';
				if(isset($_GET['team'])){
					if(isset($_GET['contest'])){
						$submissions = Submission::get_submissions_by_team_and_contest($_GET['team'], $_GET['contest']);
						echo '<h1>Modify Grade: Submissions</h1><ul>';
						foreach($submissions as $s){
							echo '<li><a href="?submission='.$s['sub_PK'].'&question='.$s['question_FK'].'&contest='.$_GET['contest'].'">'.'Submission ID: '.$s['sub_PK'].'</a></li>';
						}
						echo '
						<li><a href="?mode=teamview&team='.$_GET['team'].'">Go Back</a></li>
						</ul>'; 
					}
					else{
						$contests = Contest::get_all_contests();
						echo '<h1>Modify Grade: Contests</h1><ul>';
						foreach($contests as $c){
							echo '<li><a href="?mode=teamview&team='.$_GET['team'].'&contest='.$c['cid'].'">'.$c['name'].'</a></li>';	
						}
						echo '
						<li><a href="?mode=teamview">Go Back</a></li>
						</ul>'; 
					}
				}
				else{	
					require_once($_SERVER['DOCUMENT_ROOT'].'/data/team.php');
					$teams = Team::get_all_teams();
					echo '<h1>Modify Grade: Submissions</h1><ul>';
					foreach ($teams as $t){
						echo '<li><a href="?mode=teamview&team='.$t['team_PK'].'">'.$t['teamname'].'</a></li>';
					}
					echo '</ul>';
				}
			}
			else{
				echo '<h1>Contest View</h1>';
				if(isset($_GET['contest'])){
					if(isset($_GET['question'])){
						$submissions = Submission::get_submissions($_GET['question']);
						echo '<h1>Modify Grade: Submissions</h1><ul>';
						foreach($submissions as $s){
							echo '<li><a href="?submission='.$s['sub_PK'].'&question='.$_GET['question'].'&contest='.$_GET['contest'].'">'.'Submission ID: '.$s['sub_PK'].' Team ID: '.$s['team_FK'].'</a></li>';
						}
						echo '
						<li><a href="?contest='.$_GET['contest'].'">Go Back</a></li>
						</ul>'; 
					}
					else{
						$questions = Contest::get_contest_questions($_GET['contest']);
						echo '<h1>Modify Grade: Questions</h1><ul>';
						foreach($questions as $q){
							echo '<li><a href="?question='.$q['qid'].'&contest='.$_GET['contest'].'">'.$q['title'].'</a></li>';	
						}
						echo '
						<li><a href="?">Go Back</a></li>
						</ul>';
					}
				}
				else{
					$contests = Contest::get_all_contests();
					echo '<h1>Modify Grade: Contests</h1><ul>';
					foreach($contests as $c){
						echo '<li><a href="?contest='.$c['cid'].'">'.$c['name'].'</a></li>';	
					}
					echo '</ul>';
				}
			}
			echo '<center><a href="?mode=contestview">Contest View Mode</a> | <a href="?mode=teamview">Team View Mode</a></center>';
		}
		else{
			$submission = Submission::get_submission($_GET["submission"]);
			$questionInfo = Question :: get_answers($_GET['question']);
			$ques = Question :: get_question($_GET['question']);

			echo '<h1>'.$ques['title'].'</h1>';
			echo '<h3><div style="white-space: pre-wrap;">'.$ques['qtext'].'</div></h3>';
			echo '	<form action="?" method="POST">
					<table border="1">';
			//Cheulando code here!
			echo '<table border= "1" id="gradeTable" style="width:100%;  border: 1px solid black; border-collapse: collapse;">
			<tr height="100">
				<th>Submitted Code</th>
				<td>';
				
			echo '<code style="white-space: pre-wrap; background-color: transparent">';
				$find = array('<', '>');
				$rep = array('&lt;', '&gt;');
                echo str_replace($find, $rep, $submission[0]['submission']);
			echo '</code>';
			
			echo '</td>
			</tr>
			<tr height="100">
				<th>Test Cases</th>
				<td>
					<table border="1" height="100" style="width:100%">
					<tr>
					'.(count($questionInfo) == 1 ? '<th>Input</th>' : '<th>Inputs</th>').
					(count($questionInfo) == 1 ? '<th>Expected Output</th>' : '<th>Expected Outputs</th>').
					(count($questionInfo) == 1 ? '<th>Resulted Output</th>' : '<th>Resulted Outputs</th>').
					(count($questionInfo) == 1 ? '<th>Score</th>' : '<th>Scores</th>').
					'</tr>
					';
					$flg = true;
					foreach($questionInfo as $question){
						$ans = Submission::get_answer($submission[0]['team_FK'], $question['qio_PK']);
						if($ans['grade']==0) $flg = false;
						echo '<tr>';
							echo '<td>';
								echo '<div style="white-space: pre-wrap;">';
									echo $question['input'];
								echo '</div>';
							echo '</td>';
							echo '<td>';
								echo '<div style="white-space: pre-wrap;">';
									echo $question['output'];
								echo '</div>';
							echo '</td>';
							echo '<td>';
								echo '<div style="white-space: pre-wrap;">';
									echo $ans['output'];
								echo '</div>';
							echo '</td>';
							echo '<td>';
								echo '<div style="white-space: pre-wrap; text-align: center">';
									echo $ans['grade'];
								echo '</div>';
							echo '</td>';
						echo '</tr>';
					}
					
				echo '</table>
				</td>
			</tr>';
			echo '	<input type="hidden" name="submissionID" value="'.$_GET["submission"].'">
					<input type="hidden" name="teamID" value="'.$submission[0]['team_FK'].'">
					<input type="hidden" name="contestID" value="'.$_GET["contest"].'">
					<tr><td>
					<select name="correct">';
					if($flg) {
						echo '	<option value="1">Correct</option>
								<option value="0">Incorrect</option>';
					}
					else{
						echo '	<option value="0">Incorrect</option>
								<option value="1">Correct</option>';
					}
					echo '</select>
					</td><td><input type="submit" value="Change Grade"></td></tr>
					</table>
					</form>';
		}
	}
?>
