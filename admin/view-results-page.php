<?php

require_once ($_SERVER['DOCUMENT_ROOT'].'/data/grade.php');

$grade = new Grade();

$results = $grade->get_results('1');

?>

<div id="results">
	<h2>1st Place</h2>
	<?php
		echo "Team " . $results[0]['team_FK'];
	?>
	<h2>2nd Place</h2>
	<?php
		echo "Team " . $results[1]['team_FK'];
	?>
	<h2>3rd Place</h2>
	<?php
		echo "Team " . $results[2]['team_FK'];
	?>
	<h2>Honorable Mentions</h3>
	<?php
		for($i = 3; $i < count($results); $i++){
			echo nl2br("Team " . $results[$i]['team_FK'] . "\n");
		}
	?>
</div>