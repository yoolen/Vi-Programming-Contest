<?php
function getNavigationLinks() {
    if (!isset($_SESSION['creds'])) {
        return;
    }
    switch ($_SESSION['creds']) {
        case 1:
            if (strcmp($_GET["page"], "connect") == 0) {
                echo '<li class="active"><a href="../p_connect/">Community Connect</a></li>';
            } else {
                echo '<li ><a href="../p_connect/">Community Connect</a></li>';
            }
    }
}
?>
