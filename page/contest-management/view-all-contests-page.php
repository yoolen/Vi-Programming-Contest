<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\contest.php');
require_once($_SERVER['DOCUMENT_ROOT'].'\data\question.php');
?>
<html>
<head>
</head>
<body>
<h1>Contest Dashboard</h1>
<table>
<?php
    $contest = Contest::get_all_contests();
    $x = 0;
    foreach ($contest as $key => $value) {
    $x++;
    echo '<tr><td><b>Contest '.$value['cid'].': '.$value['name'].'</b></td><td><form method="post" action="../_contestManager_modify" style="display:inline;"><input type="hidden" name="contestID" value="'.$value['cid'].'"><input type="submit" value="Edit Contest"></form></td></tr>';
    }
    if ($x==0) echo "<tr><td>There are no contests!</td></tr>";
?>
    <tr>
        <td></td>
        <td>
            <form method="POST" action="../_contestManager_create" style="display:inline">
                <input type="submit" value="New Contest">
            </form>
        </td>
    <tr>
</table>
</body>
</html>