<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\submission.php');
require_once($_SERVER['DOCUMENT_ROOT'].'\data\grade.php');
require_once($_SERVER['DOCUMENT_ROOT'].'\data\contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'\data\question.php');

if(!isset($_GET['submission'])){
	if(isset($_GET['contest'])){
		if(isset($_GET['question'])){
			$submissions = Submission::get_submissions($_GET['question']);
			$teams = array();
			$questionInfo = Question :: get_answers($_GET['question']);
			foreach($submissions as $s){
				if(!array_key_exists($s['team_FK'], $teams))
					$teams[$s['team_FK']] = array($s);
				else
					$teams[ $s['team_FK'] ][] = $s;
			}
			echo '<h1>Modify Grade: Submissions</h1><ul>';

			foreach($teams as $key=>$ts){
				echo 'Team '.$ts[$key]['team_FK'];
				echo '<table border= "1" id="gradeTable" style="width:100%;  border: 1px solid black; border-collapse: collapse;">
				<tr height="100">
					<th>Submitted Code</th>
					<td>';
					
				echo '<pre>';
				echo $ts[$key]['submission'];
				echo '</pre>';
				
				echo '</td>
				</tr>
				<tr height="100">
					<th>Test Cases</th>
					<td>
						<table border="1" style="width:100%">
						<tr>
						<th>Inputs</th>
						<th>Expected Outputs</th>
						<th>Resulted Outputs</th>
						<th>Scores</th>
						</tr>
						';
						foreach($questionInfo as $question){
							echo '<tr>';
								echo '<td>';
									echo '<pre>';
										echo $question['input'];
									echo '</pre>';
								echo '</td>';
								echo '<td>';
									echo '<pre>';
										echo $question['output'];
									echo '</pre>';
								echo '</td>';
							echo '</tr>';
						}
					
					echo '</table>
					</td>
				</tr>
				</table>';
				echo '
				<div id=selectPoint>Point Value
				<select>
					<option value="0">0</option>
					<option value="1">1</option>
				</select>
				</div>
				';
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
else{
	echo 'FORM';
}
?>