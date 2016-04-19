<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\contest.php');
require_once($_SERVER['DOCUMENT_ROOT'].'\data\question.php');
if( isset($_POST['contestID']) == false || isset($_POST['qid']) == false )
	header("Location: ./allContests.php");
else if(isset($_POST['input']) && isset($_POST['output'])){
	if($_POST['output']!=''){
		Question::insert_question_io($_POST['qid'], $_POST['input'], $_POST['output'], $_POST['notes']);
		echo 'Successful Creation.<br>';
	}
	else
	{
		echo 'Missing a required field.';
	}
}
?>


<html>
<head>
</head>
<body>
<h1>Create Test Case</h1>
<h2>Test Case: <?php echo 'Contest '.$_POST['contestID'].' Question '.$_POST['qid'];?></h2>
<table> <form action="../_contestManager_modify_atc" method="POST">
		<input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>>
		<input type="hidden" name="qid" value=<?php echo '"'.$_POST['qid'].'"'; ?>>
        <tr>
            <td>
                <label>Input:</label>
            </td>
            <td>
                <textarea name="input" id="input" rows="4" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Output:</label>
                </td>
            <td>
                <textarea name="output" id="output" rows="4" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Notes:</label>
            </td>
            <td>
                <textarea name="notes" id="notes" rows="2" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="add" value="Add Test Case">

            </td>
        </tr>
    </form></table>
<form method="POST" action="../_contestManager_modify_qtc">
<input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>>
<input type="hidden" name="qid" value=<?php echo '"'.$_POST['qid'].'"'; ?>>
<input type="submit" value="Return to Test Cases">
</form>
	</body>
</html>