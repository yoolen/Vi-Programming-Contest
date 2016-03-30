<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 3/19/2016
 * Time: 10:00 PM
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/data/team.php');
$teams = Team::get_all_teams();
?>

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
<script src="../../library/sorttable.js"></script>
<h3>Modify Team</h3>
<table class="sortable table-hover"  border="2">
    <thead>
        <tr id="tableheaders"><th>Team Name</th><th>Affiliation</th><th>Contact</th><th>Coach</th><th>Edit</th><th>Members</th><th>Delete</th></tr>
    </thead>
<?php
foreach ($teams as $team){
    echo '<tr>';
        //echo '<td hidden>'.$team['team_PK'].'</td>';
        echo '<td>'.$team['teamname'].'</td>';
        echo '<td>'.$team['aff'].'</td>';
        echo '<td>'.$team['contact'].'</td>';
        echo '<td>'.$team['coach'].'</td>';
        echo '<td><a class="btn btn-default" href="_teamManager_editinfo_'.$team['team_PK'].'">Edit</a></td>';
        echo '<td><a class="btn btn-default" href="_teamManager_editusers_'.$team['team_PK'].'">Add/Remove</a></td>';
        echo '<td><button>Disable</button></td>';
    echo '</tr>';
}
?>

</table>

