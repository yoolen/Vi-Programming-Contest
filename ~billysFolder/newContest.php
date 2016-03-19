<?php 

if(isset($_POST['name'], $_POST['start'], $_POST['duration'])){
	//To be implemented.
	echo "To be implemented.";
}

?>
<html>
<head>
</head>
<body>
<h1>New Contest</h1>
<form method="POST" action="newContest.php">
<table>
<tr><td><b>Name</b></td><td><input type="text" name="name"></td></tr>
<tr><td><b>Date</b></td><td><input type="date" name="start"></td></tr>
<tr><td><b>Duration</b></td><td><input type="text" name="duration"></td></tr>
</table>
<input type="submit" value="Create Contest">
</form>
</body>
</html>