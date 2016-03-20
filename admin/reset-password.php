<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 3/20/2016
 * Time: 1:52 AM
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/data/user.php');

if(isset($_POST['usr'], $_POST['passwd'])){
    var_dump($_POST);
    $usr_PK = User::get_user_PK($_POST['usr']);
    echo $usr_PK;
    echo $_POST['passwd'];
    User::set_user_password($usr_PK,$_POST['passwd']);
}

?>

<div id="logon">
    <form action="reset-password.php" method="POST">
        <label for="usr">Username:&nbsp;</label>
        <input type="text" name="usr" id="usr"><br/>
        <label for="passwd">Password:&nbsp;</label>
        <input type="password" name="passwd" id="passwd"><br/>
        <input type="submit" value="Set Password" id="submitbutton">
    </form>
</div>