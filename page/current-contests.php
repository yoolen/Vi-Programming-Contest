<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/contest/current_contest/back.php');

class Current_Contests extends Page {

    public function getPageTitle() {
        return "Current Contests";
    }

    public function getPageImports() {
		$userID = $_SESSION['uid'];
		return <<<SCRIPT
		<script src="http://njit1.initiateid.com/library/checkinTimer.js"></script>
		<script src="http://njit1.initiateid.com/library/moment.js"></script>
        <script>
        var user_ID = $userID;

        function start_checkin() {
            setupCurrentContest(user_ID);
        }
        start_checkin();
    </script>
SCRIPT;
    }

    public function onLoad() {
      return 'onload="start_checkin()"';
    }

    public function getPageContent() {
        return <<<ERR
<div id="contests">
    </div>
ERR;
    }

}
?>
