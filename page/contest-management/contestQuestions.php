<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\contest.php');

if( isset($_POST['contestID']) == false )
	header("Location: ../page/contest-management/allContests.php");
if( isset($_POST['delete']) ){
	Contest::remove_contest_question($_POST['contestID'], $_POST['qid']);
}
$question = Contest::get_contest_questions($_POST['contestID']);
if( isset($_POST['up'])){
	if($_POST['pos']>0){
		echo 'q'.$_POST['contestID'].'c'.$question[$_POST['pos']]['qid'].'s'.$_POST['pos'];
		$a = Contest::get_seq($_POST['contestID'], $question[$_POST['pos']]['qid']);
		$b = Contest::get_seq($_POST['contestID'], $question[$_POST['pos']-1]['qid']);
		echo $a.'vvv';
		echo $b;
		Contest::set_seq($b, $_POST['contestID'], $question[$_POST['pos']]['qid']);
		Contest::set_seq($a, $_POST['contestID'], $question[$_POST['pos']-1]['qid']);
		$question = Contest::get_contest_questions($_POST['contestID']);
	}
}
if( isset($_POST['down']) ){
	if($_POST['pos']<(sizeof($question)-1)){
		$a = Contest::get_seq($_POST['contestID'], $question[$_POST['pos']]['qid']);
		$b = Contest::get_seq($_POST['contestID'], $question[$_POST['pos']+1]['qid']);
		Contest::set_seq($b, $_POST['contestID'], $question[$_POST['pos']]['qid']);
		Contest::set_seq($a, $_POST['contestID'], $question[$_POST['pos']+1]['qid']);
		$question = Contest::get_contest_questions($_POST['contestID']);
	}
}
if(isset($_POST['name'], $_POST['month'],$_POST['day'],$_POST['year'], $_POST['dur-hour'], $_POST['dur-min'])){
	$date = date("Y-m-d", mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']));
	$duration = new DateInterval("P0Y0M0DT".$_POST['dur-hour']."H".$_POST['dur-min']."M0S");
	
	$r = Contest::update_contest($_POST['contestID'], $date, $_POST['hour'], $_POST['minute'], 0, $duration ->format('%H:%I:%S'), 1, $_POST['name']);
}

$contest = Contest::get_contest($_POST['contestID']);
?>
<html>
<head>
</head>
<body>
<h1>Contest Options</h1>
<h2>Contest: <?php echo $contest['name']; ?></h2>
<table border="1">
<tr><th>Question</th><th>Operations</th></tr>
<?php 
	$x = 0;
	foreach ($question as $q => $value) {
		echo '<tr>
			   	  <td><form method="post" action="../_contestManager_modify" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'">
				      <input type="submit" value="&#x25B2;" name="up">
			   	      <input type="submit" value="&#x25BC;" name="down"><input type="hidden" value="'.$x.'" name="pos"></form> 
			   	      Question: '.$value['title'].'</td>
				  <td><form method="post" action="../_contestManager_modify_editQ" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="Edit" name="Edit"></form>
				  <form method="post" action="../_contestManager_modify_qtc" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="Test Cases"></form>
				  <form method="post" action="../_contestManager_modify" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="X" name="delete"></form></td></tr>';
				$x+=1;
	}
	if($x == 0) echo "<tr><td>There are no questions.</td></tr>";
?>
</table>
<br>
<form method="post" action="../_contestManager_modify_newQ"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="submit" value="Add Question"></form>

<form method="post" action="../_contestManager_modify">
<table>
<tr><td><b>Name</b></td><td><input type="text" name="name" value="<?php echo $contest["name"]; ?>"></td></tr>
<tr><td><b>Date</b></td><td><b>M</b><input type="text" name="month" value="<?php echo $contest["month"]; ?>"></td><td><b>D</b><input type="text" name="day" value="<?php echo $contest["day"]; ?>"></td><td><b>Y</b><input type="text" name="year" value="<?php echo $contest["year"]; ?>"></td></tr>
<tr><td><b>Time</b></td><td><b>H</b><input type="text" name="hour" value="<?php echo $contest["hours"]; ?>"></td><td><b>M</b><input type="text" name="minute" value="<?php echo $contest["minutes"]; ?>"></td></tr>
<tr><td><b>Duration</b></td><td><b>H</b><input type="text" name="dur-hour" value="<?php echo $contest["dhours"]; ?>"></td><td><b>M</b><input type="text" name="dur-min" value="<?php echo $contest["dminutes"]; ?>"></td></tr>
</table>
<input type="hidden" name="contestID" value="<?php echo $_POST["contestID"]; ?>">
<input type="submit" value="Update Contest">
</form>

<a href="../_contestManager">Back to Contest Dashboard</a>

</body>
</html>