<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '\admin\user-functions.php');
if (isset($_POST['username'], $_POST['password'])) {
    $result = verify($_POST['username'], $_POST['password']);
    $_SESSION['uid'] = $result['usrID'];
    $_SESSION['creds'] = $result['usrlvl'];
    if($_SESSION['creds'] > 0) {
        header("Location: index.php");
    }
}

$err = '';
if (isset($_SESSION['creds']) and $_SESSION['creds'] < 0) {
    $err = '<h3 class="err">Invalid Username or Password.</h3>';
}
if (isset($_SESSION['creds']) and $_SESSION['creds'] >= 0) {
    $err = strval($_SESSION['creds']);
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

        <style>
            body {
                background: linear-gradient(to bottom,  rgba(214,214,214,1) 0%,rgba(183,183,183,1) 49%,rgba(201,201,201,1) 51%,rgba(229,229,229,1) 100%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d6d6d6', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
                background-repeat: no-repeat;
                background-attachment: fixed;
                font-family: 'Ubuntu', sans-serif;
            }
            #main {
                position: absolute;
                width: 700px;
                height: 550px;
                z-index: 15;
                top: 50%;
                left: 50%;
                margin: -275px 0 0 -350px;
                background: white;
                border-radius: 6px;
                padding: 5px;
                opacity: 0.5;
                text-align: center;
            }
            .err {
                color: red;
            }
            .main {
                opacity: 1;
            }

            a {
                color: inherit;
                text-decoration: inherit;
            }
            a:hover {
                color: inherit;
                text-decoration: underline;
            }
            a:visited {
                color: inherit;
                text-decoration: inherit;
            }
        </style>
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
            <input style="width: 120px;" type="text" name="username">
            <label for="password">Password:&nbsp;</label> 
            <input style="width: 120px;" type="password" name="password"> &nbsp;&nbsp;
            <input class="submit" type="submit" value="Log In">			
        </form>
        <a href="login.php">If you don't like our damn login box. Click Here!</a>
LOG;
}

?>