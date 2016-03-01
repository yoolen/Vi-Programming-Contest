<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
	$competition = new Competition();
	$question = new Question();

	if(isset($_POST['contestnum'], $_POST['seqnum'], $_POST['qtext'], $_POST['ans'],$_POST['notes1'], $_POST['input1'], $_POST['output1'] )){
	    $result = $question->insert_question($_POST['title'],$_POST['qtext'], $_POST['ans']);
	    //var_dump($result);
	    $question->insert_question_io($result,$_POST['input1'],$_POST['output1'],$_POST['notes1']);
	    if(!($_POST['input2']=='') && !($_POST['output2']=='')){
	        $question->insert_question_io($result,$_POST['input2'],$_POST['output2'],$_POST['notes2']);
	    }
	    if(!($_POST['input3']=='') && !($_POST['output3']=='')){
	        $question->insert_question_io($result,$_POST['input3'],$_POST['output3'],$_POST['notes3']);
	    }
	    $competition->add_question_to_contest($_POST['contestnum'],$result,$_POST['seqnum']);
	}
	else{
		header("location: ./");
	}
	?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
Question Added.
<form method="post" action="newQuestion.php"><input type="hidden" name="competitionID" value=<?php echo '"'.$_POST['competitionID'].'"'; ?>><input type="submit" value="Add Another Question"></form>
<form method="post" action="competitionQuestions.php"><input type="hidden" name="competitionID" value=<?php echo '"'.$_POST['competitionID'].'"'; ?>><input type="submit" value="Back to Competition Page"></form>
</body>
</html>