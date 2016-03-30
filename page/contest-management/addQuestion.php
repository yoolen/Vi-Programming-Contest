<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
	if(isset($_POST['contestID'], $_POST['qtext'], $_POST['ans'])){
		$qid = Question::insert_question($_POST['title'],$_POST['qtext'], $_POST['ans'], 1);
	    Contest::add_question_to_contest($_POST['contestID'],$qid, Contest::get_max_seq($_POST['contestID'])+1);
	}
	else{
		header("location: ./");
	}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Question Added.</h1>
<form method="post" action="../_contestManager_modify_qtc"><input type="hidden" name="qid" value=<?php echo '"'.$qid.'"';?>><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"';?>><input type="submit" value="Add Test Cases"></form>
<form method="post" action="../_contestManager_modify_newQ"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="submit" value="Add Another Question"></form>
<form method="post" action="../_contestManager_modify"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="submit" value="Back to Contest Page"></form>
</body>
</html>
