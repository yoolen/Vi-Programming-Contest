<?php
/**
 * theme-header.php
 * Primary Front-End Header
 */
?>
<header>
  <div class="row">
    <div class="col-sm-8">
      <a href="#"><img src="images/logo.png" class="logo" alt=""/></a>
    </div>
    <div class="col-sm-4">
      <div id="userbox" class="container-fluid text-center">
        <?php include_once("/login.php"); ?>
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
                        <li class="active"><a href="index.html">Home</a></li>
                        <li><a href="#">Current Contests</a></li>
                        <li><a href="#">Results</a></li>
                        <li><a href="#">Account Settings</a></li>
                        <li class="pull-right"><a href="#">NJIT CCS</a></li>
                        <li class="pull-right"><a href="#">Previous Competitions</a></li>
                        <li class="pull-right"><a href="#">About Contest</a></li>
                    </ul>
                </div>
            </div>
          </nav>
