<?php
if( isset($_POST['competitionID']) == false )
	header("Location: ./allCompetitions.php");
echo 'Editing Competition '.$_POST['competitionID'];
?>

<html>
<head>
</head>
<body>
<br>
Edit Question
</body>
</html>