<?php
//insert_question_io($question_FK, $input, $output, $notes)
//get_all_question_io($question_FK, $contest_FK)
//modify_question_io($qio_PK, $input, $output, $notes)
require_once($_SERVER['DOCUMENT_ROOT'].'\data\question.php');
if( isset($_POST['contestID']) == false )
	header("Location: ./allContests.php");
if( isset($_POST['delete']) ){
	Question::delete_question_io($_POST['qioid']);
}

$io = Question::get_question_ios($_POST['qid']);

?>
<html>
<head>
<script>

</script>
</head>
<body>
<h1>Test Cases</h1>
<h2>Test Cases: <?php echo 'Contest '.$_POST['contestID'].' Question '.$_POST['qid'];?></h2>
<table border="1">
<tr><th>Inputs</th><th>Outputs</th><th>Notes</th><th colspan="2">Operations</th></tr>
<?php 
	$x = 0;
	foreach ($io as $i => $value) {
		$x++;
		echo '<tr>
			   	  <td>'.$value['input'].'</td> <td>'.$value['output'].'</td><td>'.$value['notes'].'</td>
				  <td><form method="post" action="../_contestManager_modify_etc" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="hidden" name="qioid" value="'.$value['qioid'].'"><input type="submit" value="Edit" name="Edit"></form></td>
				  <td><form method="post" action="../_contestManager_modify_qtc" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="hidden" name="qioid" value="'.$value['qioid'].'"><input type="submit" value="X" name="delete"></form></td></tr>';
	}
	if ($x==0) echo '<tr><td colspan="3">There are no test cases!</td></tr>'
?>
<tr><td colspan="5"><form method="post" action="../_contestManager_modify_atc" style="display:inline;"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="hidden" name="qid" value=<?php echo '"'.$_POST['qid'].'"'; ?>><input type="submit" value="Add Test Case"></form></td></tr>
</table>
</body>
<form method="post" action="../_contestManager_modify"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="submit" value="Back to Contest Page"></form>
</html>