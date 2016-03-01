<?php
/**
 * theme-header.php
 * Primary Front-End Header
 */
?>
<header>
    <div class="primaryhead">
        <div class="logobox">
            <a href="#"><img src="/images/logo.png" class="logo" alt=""/></a>
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
                <?php include_once 'navigation.php' ?>
                <li class="pull-right"><a href="#">NJIT CCS</a></li>
                <li class="pull-right"><a href="#">Previous Competitions</a></li>
                <li class="pull-right"><a href="#">About Contest</a></li>
            </ul>
        </div>
    </div>
</nav>
