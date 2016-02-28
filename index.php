<?php
/*
NJIT Computer Programming Contest for High School Students

NJIT Spring 2016 Computer Science Capstone
Entrepreneurship Team:
Jan Chris Tacbianan
Terry Chern
Billy Ciaurro
Cheulando Gayle
Matt Wolfman
*/

/**
 * index.php
 * Primary Front-End Page
 */

//Start Session
session_start();
include_once('page/page-data.php'); //Retrieve Page Data (Styling, Scripts, Data)
require_once('webhead.php'); //HTML Head Information
require_once('theme/theme-auxiliary.php'); //Auxiliary Page Data
require_once('theme/theme-header.php'); //Contains Display Header (Navigation, Log On Controls, etc.)
require_once('theme/theme-warn.php'); //Contains any messages related to maintenance.
if( isset($_SESSION['perms']) ) { //Are we logged In?
  require_once('front-page.php');  //If not, display front page.
} else {
  require_once('theme/theme-content.php'); //Main Page Content
}
require_once('theme/theme-footer.php'); //Contains Display Footer
?>
<html><body>
</body>
</html>
