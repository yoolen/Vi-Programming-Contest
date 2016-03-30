<?php // This function breaks; look at this - terry
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\contest.php'); 

if(isset($_POST['name'], $_POST['month'],$_POST['day'],$_POST['year'], $_POST['dur-hour'], $_POST['dur-min'])){
	$date = date("Y-m-d", mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']));
	$duration = new DateInterval("P0Y0M0DT".$_POST['dur-hour']."H".$_POST['dur-min']."M0S");
	
	$r = Contest::insert_contest($date, $_POST['hour'], $_POST['minute'], 0, $duration ->format('%H:%I:%S'), 1, $_POST['name']);
	if($r==0){
	}
	else{
		header("Location: ../_contestManager");
	}
}
?>
<html>
<head>
</head>
<body>
<h1>New Contest</h1>
<form method="POST" action="../_contestManager_create">
<table>
<tr><td><b>Name</b></td><td><input type="text" name="name"></td></tr>
<tr><td><b>Date</b></td><td><b>M</b><input type="text" name="month"></td><td><b>D</b><input type="text" name="day"></td><td><b>Y</b><input type="text" name="year"></td></tr>
<tr><td><b>Time</b></td><td><b>H</b><input type="text" name="hour"></td><td><b>M</b><input type="text" name="minute"></td></tr>
<tr><td><b>Duration</b></td><td><b>H</b><input type="text" name="dur-hour"></td><td><b>M</b><input type="text" name="dur-min"></td></tr>
</table>
<input type="submit" value="Create Contest">
</form>
</body>
</html>