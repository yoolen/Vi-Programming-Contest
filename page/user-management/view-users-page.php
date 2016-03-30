<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 3/19/2016
 * Time: 10:00 PM
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
$users = USER::get_all_users();
?>

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
<script src="../../library/sorttable.js"></script>
<br/>
<table class="sortable table-hover"  border="2">
    <thead>
        <tr id="tableheaders"><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Edit</th><th>Delete</th></tr>
    </thead>
<?php
foreach ($users as $user){
    echo '<tr>';
        echo '<td hidden>'.$user['uid'].'</td>';
        echo '<td>'.$user['usr'].'</td>';
        echo '<td>'.$user['fname'].'</td>';
        echo '<td>'.$user['lname'].'</td>';
        echo '<td>'.$user['email'].'</td>';
        echo '<td><a class="btn btn-default" href="_userManager_modify_'.$user['uid'].'">Edit</a></td>';
        echo '<td><button>Delete</button></td>';
    echo '</tr>';
}
?>
</table>
<br/>
