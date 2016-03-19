<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\contest.php');
require_once($_SERVER['DOCUMENT_ROOT'].'\data\question.php');
if( isset($_POST['contestID']) == false || isset($_POST['qid']) == false )
	header("Location: ./allContests.php");
else if(isset($_POST['title']) && isset($_POST['qtext']) && isset($_POST['ans'])){
	if($_POST['title']!='' && $_POST['qtext']!=''){
		Question::modify_question($_POST['qid'], $_POST['title'], $_POST['qtext'], $_POST['ans'], 1);
		echo 'Successful Modification.<br>';
	}
	else
	{
		echo 'Missing Input.';
	}
}
$question = Question::get_question($_POST['qid']);

?>

<html>
<head>
</head>
<body>
<h1>Edit Question</h1>
<h2><?php echo 'Contest '.$_POST['contestID'].' Question '.$_POST['qid'];?></h2>
<table> <form action="editQuestion.php" method="POST">
		<input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>>
		<input type="hidden" name="qid" value=<?php echo '"'.$_POST['qid'].'"'; ?>>
        <tr>
            <td>
                <label>Question Title:</label>
            </td>
            <td>
                <input type="text" name="title" id="title" value=<?php echo '"'.$question['title'].'"'; ?>><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Question:</label>
                </td>
            <td>
                <textarea name="qtext" id="qtext" rows="4" cols="40"><?php echo $question['qtext']; ?></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Answer (optional):</label>
            </td>
            <td>
                <textarea name="ans" id="ans" rows="2" cols="40"><?php echo $question['answer']; ?></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="update" value="Update Question">

            </td>
        </tr>
    </form></table>
	<form method="post" action="contestQuestions.php"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="submit" value="Back to Contest Page"></form>

</body>
</html>