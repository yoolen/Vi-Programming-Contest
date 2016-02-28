<?php
echo "Logged in as USER ID ".$_SESSION['uid'];
echo "<br>Credentials: ".$_SESSION['creds'];
echo "<br><a href='../logout.php'>Logout</a>";
?>
