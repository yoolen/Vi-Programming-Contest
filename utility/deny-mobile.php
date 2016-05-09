<?php

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// If the user is on a mobile device, redirect them
if (isMobile()) {
    echo "This Page is Currently Unavailable for Mobile Devices. Redirecting you to the Dashboard.";
    header("refresh:5; url=http://njit1.initiateid.com/");
    return;
}

?>