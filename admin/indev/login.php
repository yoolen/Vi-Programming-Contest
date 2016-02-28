<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/24/2016
 * Time: 2:27 AM
 */
//require_once ('dbtools/db-info.php');
require_once ('/dbtools/backend.php');

var_dump($_POST);
if(isset($_POST['usrname'], $_POST['passwd'])){
    $result = verify($_POST['usrname'], $_POST['passwd']);
    var_dump($result);
}

?>

<html>
<form action="login.php" method="POST">
    <label>User name:</label>
    <input type="text" name="usrname"><br/>
    <label>Password:</label>
    <input type="password" name="passwd"><br/>
    <input type="submit" name="submit" value="Log in">
</form>
</html>
