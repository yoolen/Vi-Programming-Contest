<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\contest.php');
if( isset($_POST['contestID']) == false )
	header("Location: ./allContests.php");
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
?>
<html>
<head>
</head>
<body>
<h1>Contest Options</h1>
<h2>Contest: <?php echo $_POST['contestID']; ?></h2>
<table border="1">
<tr><th>Question</th><th>Operations</th></tr>
<?php 
	$x = 0;
	foreach ($question as $q => $value) {
		echo '<tr>
			   	  <td><form method="post" action="contestQuestions.php" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'">
				      <input type="submit" value="&#x25B2;" name="up">
			   	      <input type="submit" value="&#x25BC;" name="down"><input type="hidden" value="'.$x.'" name="pos"></form> 
			   	      Question: '.$value['title'].'</td>
				  <td><form method="post" action="editQuestion.php" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="Edit" name="Edit"></form>
				  <form method="post" action="questionTestCases.php" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="Test Cases"></form>
				  <form method="post" action="contestQuestions.php" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="X" name="delete"></form></td></tr>';
				$x+=1;
	}
	if($x == 0) echo "<tr><td>There are no questions.</td></tr>";
?>
</table>
<br>
<form method="post" action="newQuestion.php"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="submit" value="Add Question"></form>

<a href="./allContests.php">Back to Contest Dashboard</a>
</body>
</html>