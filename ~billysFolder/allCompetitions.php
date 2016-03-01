<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\contest.php');
require_once($_SERVER['DOCUMENT_ROOT'].'\data\question.php');
if( isset($_POST['competitionID']) == false )
	;//header("Location: ./allCompetitions.php");
?>
<html>
<head>
</head>
<body>
All Competitions
<table>
<?php
$competition = Competition::get_all_competitions();
foreach ($competition as $key => $value) {
	echo '<tr><td>Competition '.$value['contest_PK'].'</td><td><form method="post" action="competitionQuestions.php" style="display:inline;"><input type="hidden" name="competitionID" value="'.$value['contest_PK'].'"><input type="submit" value="Edit Competition"></form></td></tr>';
}
?>
</table>
</body>
</html>