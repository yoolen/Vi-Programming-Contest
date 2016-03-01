<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\competition.php');
if( isset($_POST['competitionID']) == false )
	header("Location: ./allCompetitions.php");
if( isset($_POST['delete']) ){
	Competition::remove_contest_question($_POST['qid'], $_POST['competitionID']);
}
echo 'Editing Competition '.$_POST['competitionID'];
?>
<html>
<head>
<script>

</script>
</head>
<body>
<br>
Competition Questions
<table>
<?php 
	$question = Competition::get_contest_questions($_POST['competitionID']);
	foreach ($question as $q => $value) {
		echo '<tr>
			   	  <td><form method="post" action="competitionQuestions.php" style="display:inline;"><input type="submit" value="&#x25B2;" name="up">
			   	      <input type="submit" value="&#x25BC;" name="down"</form> 
			   	      Question: '.$value['title'].'</td>
				  <td><form method="post" action="editQuestion.php" style="display:inline;"><input type="hidden" name="questionID" value="'.$value['qid'].'"><input type="submit" value="Edit Question"></form></td>
				  <td><form method="post" action="competitionQuestions.php" style="display:inline;"><input type="hidden" name="competitionID" value="'.$_POST['competitionID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="X" name="delete"></form></td></tr>';
	}
?>
<tr><td><form method="post" action="newQuestion.php"><input type="hidden" name="competitionID" value=<?php echo '"'.$_POST['competitionID'].'"'; ?>><input type="submit" value="Add Question"></form></td></tr>
</table>
</body>
</html>