<?php
function getNavigationLinks() {
    if (!isset($_SESSION['creds'])) {
        return;
    }
    switch ($_SESSION['creds']) {
        case 1:
            echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Administration<span class="caret"></span></a>';
            echo '<ul class="dropdown-menu">';
            if (isset($_GET["page"]) and strcmp($_GET["page"], "contestManager") == 0) {
                echo '<li class="active"><a href="../_contestManager/">Contest Manager</a></li>';
            } else {
                echo '<li ><a href="../_contestManager/">Contest Manager</a></li>';
            }
            if (isset($_GET["page"]) and strcmp($_GET["page"], "userManager") == 0) {
                echo '<li class="active"><a href="../_userManager/">User Manager</a></li>';
            } else {
                echo '<li ><a href="../_userManager/">User Manager</a></li>';
            }
            if (isset($_GET["page"]) and strcmp($_GET["page"], "teamManager") == 0) {
                echo '<li class="active"><a href="../_teamManager/">Team Manager</a></li>';
            } else {
                echo '<li ><a href="../_teamManager/">Team Manager</a></li>';
            }
            if (isset($_GET["page"]) and strcmp($_GET["page"], "affManager") == 0) {
                echo '<li class="active"><a href="../_affManager/">Affiliation Manager</a></li>';
            } else {
                echo '<li ><a href="../_affManager/">Affiliation Manager</a></li>';
            }
            if (isset($_GET["page"]) and strcmp($_GET["page"], "scoreManager") == 0) {
                echo '<li class="active"><a href="../_scoreManager/">Score Manager</a></li>';
            } else {
                echo '<li ><a href="../_scoreManager/">Score Manager</a></li>';
            }
            echo '</ul>';
        case 2:
        case 3:
        case 4:
            if (isset($_GET["page"]) and strcmp($_GET["page"], "current") == 0) {
                echo '<li class="active"><a href="../_current/">Current Contests</a></li>';
            } else {
                echo '<li ><a href="../_current/">Current Contests</a></li>';
            }
            if (isset($_GET["page"]) and strcmp($_GET["page"], "results") == 0) {
                echo '<li class="active"><a href="../_results/">Results</a></li>';
            } else {
                echo '<li ><a href="../_results/">Results</a></li>';
            }
            if (isset($_GET["page"]) and strcmp($_GET["page"], "imagine") == 0) {
                echo '<li class="active"><a href="../_imaginarium/">Code Imaginarium</a></li>';
            } else {
                echo '<li ><a href="../_imaginarium/">Code Imaginarium</a></li>';
            }
			
            /*
            if (isset($_GET["page"]) and strcmp($_GET["page"], "settings") == 0) {
                echo '<li class="active"><a href="../_settings/">Account Settings</a></li>';
            } else {
                echo '<li ><a href="../_settings/">Account Settings</a></li>';
            }*/
            return;
    }
}

getNavigationLinks();
?>
