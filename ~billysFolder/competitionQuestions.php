<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\question.php');
if( isset($_POST['competitionID']) == false )
	header("Location: ./allCompetitions.php");
echo 'Editing Competition '.$_POST['competitionID'];
?>
<html>
<head>
</head>
<body>
<br>
Competition Questions
<table>
<?php 
$question = Question::get_contest_questions($_POST['competitionID']);
foreach ($question as $q => $value) {
	echo '<tr><td>Question: '.$value['seqnum'].'</td><td><form method="post" action="editQuestion.php" style="display:inline;"><input type="hidden" name="questionID" value="'.$value['seqnum'].'"><input type="submit" value="Edit Question"></form></td></tr>';
}

?>
<tr><td><form method="post" action="newQuestion.php"><input type="hidden" name="competitionID" value=<?php echo '"'.$_POST['competitionID'].'"'; ?>><input type="submit" value="Add Question"></form></td></tr>
</table>
</body>
</html>