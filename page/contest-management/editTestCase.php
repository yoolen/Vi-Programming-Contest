<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\contest.php');
require_once($_SERVER['DOCUMENT_ROOT'].'\data\question.php');
if( isset($_POST['contestID']) == false || isset($_POST['qid']) == false || isset($_POST['qioid']) == false)
	header("Location: ./allContests.php");
else if(isset($_POST['input']) && isset($_POST['output'])){
	if($_POST['output']!=''){
		Question::modify_question_io($_POST['qioid'], $_POST['input'], $_POST['output'], $_POST['notes']);
		echo 'Successful Update.<br>';
	}
	else
	{
		echo 'Missing a required field.';
	}
}
$testcase = Question::get_question_io($_POST['qioid']);

?>

<html>
<head>
</head>
<body>
<h1>Edit Test Case</h1>
<h2>Test Case: <?php echo 'Contest '.$_POST['contestID'].' Question '.$_POST['qid'];?></h2>
<table> <form action="../_contestManager_modify_etc" method="POST">
		<input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>>
		<input type="hidden" name="qid" value=<?php echo '"'.$_POST['qid'].'"'; ?>>
		<input type="hidden" name="qioid" value=<?php echo '"'.$_POST['qioid'].'"'; ?>>
        <tr>
            <td>
                <label>Input:</label>
            </td>
            <td>
                <input type="text" name="input" id="input" value=<?php echo '"'.$testcase['input'].'"'; ?>><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Output:</label>
                </td>
            <td>
                <textarea name="output" id="output" rows="4" cols="40"><?php echo $testcase['output']; ?></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Notes:</label>
            </td>
            <td>
                <textarea name="notes" id="notes" rows="2" cols="40"><?php echo $testcase['notes']; ?></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="update" value="Update Test Case">

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