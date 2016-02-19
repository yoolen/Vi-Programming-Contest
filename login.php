<?php
if (!isset($_SESSION['perms'])) {
    if (count(get_included_files()) === 1) {
        echo <<<LOGIN
        
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>NJIT Login Service</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="all" /><!-- tell visiting robots its OK to index this page -->  
        <meta name="description" content="" /> 
        <meta name="keywords" content="" />

        <link rel="stylesheet" type="text/css" href="https://www.njit.edu/corporate/uicomponents/styles/njit.css" /> 
        <link rel="stylesheet" type="text/css" href="https://www.njit.edu/corporate/uicomponents/styles/print.css" media="print" />

        <link rel="stylesheet" href="/idp/css/styles.css" type="text/css" />

        <!--[if lt IE 9]><script type="text/javascript" src="https://www.njit.edu/corporate/uicomponents/scripts/html5shim-1.6.2.min.js"></script><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="https://www.njit.edu/corporate/uicomponents/styles/ie/ie7.css" /><![endif]-->
        <script type="text/javascript" src="https://use.typekit.com/khd0xmd.js"></script>
        <script type="text/javascript">try {
                Typekit.load();
            } catch (e) {
            }</script> 

    </head>
    <body class="office office-homepage subpage fullwidth">
        <div id="maincontainer">

            <header class="container" id="header">
                <h1><a href="http://www.njit.edu"><img src="https://www.njit.edu/corporate/uicomponents/images/logo.png" alt="New Jersey Institute of Technology" width="300" height="100" id="njitlogo"/>
                        <img src="https://www.njit.edu/corporate/uicomponents/images/logoprint.png" alt="New Jersey Institute of Technology" width="300" height="100" id="printlogo"/></a></h1>


            </header>

            <header id="sectionheader">
                <div id="titlebar">
                    <h1 style="visibility: visible"><a href="#">NJIT Computer Programming Contest for High School Students Log In Service</a></h1>
                    <h5 style="visibility: visible">Servicing the best High School Programming Platform Ever!</h5>
                </div>
            </header>

            <div id="wrapper" style="height: 500px;">

                <div id="content" class="container" style="height: 500px;">
                    <section style="height: 500px; color: black; background-color: white; padding: 10px;">

                        <h2>Please Login Below:</h2>
                        <form action="login.php" method="POST">
                            <label for="username">Username:&nbsp;</label>
                            <input type="text" name="username" onfocus='darken(this)' onblur='lighten(this)'><br /><br />
                            <label for="password">Password:&nbsp;</label> 
                            <input type="password" name="password" onfocus='darken(this)' onblur='lighten(this)'><br /><br />
                            <input class="submit" type="submit" value="Log In" style="">			
                        </form> 

                    </section>	
                </div>
                <footer id="footer">
                    <div class="container">
                        <div id="address"><a href="http://www.njit.edu">New Jersey Institute of Technology</a><br/>
                            <span>University Heights</span> <span>Newark, New Jersey 07102</span>
                        </div>
                        <ul id="footer_links">
                            <li><a href="http://www.njit.edu/about/key-contacts.php">Contact Us</a></li>
                            <!--<li><a href="#">Link in the footer</a></li>-->
                            <li><a href="http://www.njit.edu/about/visit/gettingtonjit.php">Maps &amp; Directions</a></li>
                            <!--<li><a href="#">Footer link</a></li>-->
                        </ul>
                        <ul id="footer_social">
                            <li><a href="https://www.facebook.com/pages/Newark-NJ/NJIT/7185471825" title="NJIT on Facebook"><img src="https://www.njit.edu/corporate/uicomponents/images/social/facebook.gif" width="16" height="16" alt="Facebook"/></a></li>
                            <li><a href="https://twitter.com/njit" title="NJIT on Twitter"><img src="https://www.njit.edu/corporate/uicomponents/images/social/twitter.gif" width="16" height="16" alt="Twitter"/></a></li>
                            <li><a href="https://youtube.com/njit" title="NJIT on YouTube"><img src="https://www.njit.edu/corporate/uicomponents/images/social/youtube.gif" width="16" height="16" alt="YouTube"/></a></li>
                            <li><a href="https://www.flickr.com/photos/njit" title="NJIT on Flickr"><img src="https://www.njit.edu/corporate/uicomponents/images/social/flickr.gif" width="16" height="16" alt="Flickr"/></a></li>
                        </ul>
                    </div>
                </footer>
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
} else {
    
}
?>