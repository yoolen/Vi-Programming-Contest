<?php

//Session Start Check. This condition is used for the standalone login page.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '\admin\user-functions.php');

//Check for login post.
if (isset($_POST['username'], $_POST['password'])) {
    $result = verify($_POST['username'], $_POST['password']);
    $_SESSION['uid'] = $result['usrID'];
    $_SESSION['creds'] = $result['usrlvl'];
    if($_SESSION['creds'] > 0) {
        header("Location: index.php");
    }
}

//Place holder for login error messages.
$err = '';


if (isset($_SESSION['creds']) and $_SESSION['creds'] < 0) {
    $err = '<h3 class="err">Invalid Username or Password.</h3>';
}
if (isset($_SESSION['creds']) and $_SESSION['creds'] >= 0) {
    header("Location: index.php");
}

if (count(get_included_files()) === 3) {
    echo <<<LOGIN

<!DOCTYPE html>
<html>
    <head>
        <title>NJIT High School Programming Contest - Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
        <link href='../style/login.css' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div id="main">
            <div class="main">
                <div class="sig"> <a href="index.php"><img src="images/logo.png" class="logo" style="max-height:90px;" alt=""/></a><br><br><br> </div>
                <div class="loginBox">
                <h2>Please Login Below:</h2>
                <h4 style="color:red;">{$err}</h4>
                <form action="login.php" method="POST">
                    <label for="username">Username:&nbsp;</label>
                    <input type="text" name="username" onfocus='darken(this)' onblur='lighten(this)'><br /><br />
                    <label for="password">Password:&nbsp;</label>
                    <input type="password" name="password" onfocus='darken(this)' onblur='lighten(this)'><br /><br />
                    <input class="submit" type="submit" value="Log In" style="color:#000; background-color:#EEE; border-color:#000; border: solid 2px; border-radius:5px; padding:5px;">
                </form>
            </div>
                <br><br>
            <footer>
                Developed by NJIT CS Capstone 2016 Entrepreneurship Team
                <br>U Chern, J Tacbianan, W Ciaurro, C Gayle & M Wolfman. <br><br>
                <span style="font-size:10pt;">&copy; 2016 - <a href="http://www.njit.edu">New Jersey Institute of Technology</a> - <a href="http://ccs.njit.edu">College of Computing Sciences</a> - All Rights Reserved</span>
            </footer>
            </div>
        </div>
    </body>
</html>


LOGIN;
} else {
    echo <<<LOG
        <form action="login.php" method="POST">
            <label for="username">Username:&nbsp;</label>
            <input style="width: 120px;" type="text" name="username"><br>
            <label for="password">Password:&nbsp;</label>
            <input style="width: 120px;" type="password" name="password"> <br>
            <input class="submit" type="submit" value="Log In">
        </form>
        <!--a href="login.php">Alternate Login</a-->
LOG;
}

?>
