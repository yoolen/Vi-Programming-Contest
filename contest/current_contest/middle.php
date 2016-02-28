<?php
	include 'back.php';
	
	$checked = getcheckstatus();
	
	echo json_encode($checked);
?>