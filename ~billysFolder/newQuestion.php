<?php
if( isset($_POST['contestID']) == false )
	header("Location: ./allContests.php");
?>
<html>
<head>
</head>
<body>
<h1>New Question</h1>
<h2><?php echo 'Contest '.$_POST['contestID'];?></h2>
<table> <form action="addQuestion.php" method="POST">
		<input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>>
        <tr>
            <td>
                <label>Question Title:</label>
            </td>
            <td>
                <input type="text" name="title" id="title"><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Question:</label>
                </td>
            <td>
                <textarea name="qtext" id="qtext" rows="4" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Answer (optional):</label>
            </td>
            <td>
                <textarea name="ans" id="ans" rows="2" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="create" value="Create Question">

            </td>
        </tr>
    </form></table>
<form method="post" action="contestQuestions.php"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="submit" value="Back to Contest Page"></form>
	</body>
</html>