<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '\data\user.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '\utility\front-utilities.php');
$user = User::get_user($_SESSION['uid']);
$affiliation = User::get_affiliation_name($_SESSION['uid']);
echo "<div class='avatar'><img src='".get_gravatar($user['email'])."' src='".$user['fname'].' '.$user['lname']."' /></div>";
echo "Welcome Back ".$user['fname'].' '.$user['lname']."!";
echo "<br>Affiliation: ".$affiliation."<br>";
//echo "<br>Credentials: ".$_SESSION['creds'];
//echo "<a href='../_settings/'>Settings</a> | ";
echo "<a href='../logout.php'>Logout</a>";
?>
