<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
/**
 * Created by PhpStorm.
 * User: yoole
 * Date: 3/22/2016
 * Time: 6:58 PM
 */
$contestants = User::get_users_by_aff_creds($_POST['aff'],$_POST['creds']);
?>
<script src="../../library/sorttable.js"></script>
<br/>
<table class="sortable table-hover" border="2">
    <thead>
        <tr id="tableheaders">
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Selected</th>
        </tr>
    </thead>
<?php
if(isset($_POST['selected'])){
    $conts = $_POST['selected'];
    foreach ($contestants as $contestant) {
        $match = false;
        echo '<tr>';
        echo '<td hidden>' . $contestant['uid'] . '</td>';
        echo '<td>' . $contestant['usr'] . '</td>';
        echo '<td>' . $contestant['fname'] . '</td>';
        echo '<td>' . $contestant['lname'] . '</td>';
        echo '<td>' . $contestant['email'] . '</td>';
        foreach ($conts as $cont) {
            if ($cont['uid'] == $contestant['uid']) {
                echo '<td style="text-align:center"><input type="checkbox" name="contestantid[]" value="' . $contestant['uid'] . '" checked></td>';
                $match = true;
            }
        }
        if ($match == false){
                echo '<td style="text-align:center"><input type="checkbox" name="contestantid[]" value="' . $contestant['uid'] . '"></td>';
        }
        echo '</tr>';
    }
} else {
    foreach ($contestants as $contestant) {
        echo '<tr>';
        echo '<td hidden>' . $contestant['uid'] . '</td>';
        echo '<td>' . $contestant['usr'] . '</td>';
        echo '<td>' . $contestant['fname'] . '</td>';
        echo '<td>' . $contestant['lname'] . '</td>';
        echo '<td>' . $contestant['email'] . '</td>';
        echo '<td style="text-align:center"><input type="checkbox" name="contestantid[]" value="' . $contestant['uid'] . '"></td>';
        echo '</tr>';
    }
}
?>
</table>
