<?php
/**
 * theme-header.php
 * Primary Front-End Header
 */
?>
<header>
    <div class="primaryhead">
        <div class="logobox">
            <a href="http://njit1.initiateid.com/_home/"><img src="/images/logo.png" class="logo" alt=""/></a>
        </div>
        <div id="userbox">
            <div  class="container-fluid text-center">
                <?php
                if (isset($_SESSION['creds']) and $_SESSION['creds'] > 0) {
                    include_once '/page/user-box.php';
                } else {
                    include_once("/login.php");
                }
                ?>
            </div>
        </div>
    </div>
</header>
<nav class="navbar navbar-default">
    <div class="navi container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="<?php
                if (!isset($_GET["page"]) or (strcmp($_GET["page"], "home") == 0 or empty($_GET['page']))) {
                    echo "active";
                }
                ?>"><a href="../_home/">Home</a></li>
                    <?php include_once '/page/navigation.php' ?>
                <li class="pull-right"><a href="http://ccs.njit.edu">NJIT CCS</a></li>
                <li class="pull-right<?php
                if (isset($_GET["page"]) and strcmp($_GET["page"], "previous") == 0) {
                    echo " active";
                }
                ?>"><a href="../_previous/">Previous Contests</a></li>
                <li class="pull-right<?php
                    if (isset($_GET["page"]) and strcmp($_GET["page"], "about") == 0) {
                        echo " active";
                    }
                    ?>"><a href="../_about">About Contest</a></li>
            </ul>
        </div>
    </div>
</nav>
