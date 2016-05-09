<?php

class Pre_Post_Contest extends Page {

    public function getPageTitle() {
        return "Waiting Page";
    }

    public function getPageImports() {
		return <<<SCRIPT
		<script src="http://njit1.initiateid.com/library/contestTimer.js"></script>
SCRIPT;
    }

    public function onLoad() {
		$unit = $_GET['unit'];
		return 'onload="hourCheck('.$unit.', \'pre\')"';
    }

    public function getPageContent() {
        return <<<ERR
		<div style="text-align:center; margin-top:200px; font-size:20px">
			<div id="message">
			</div>
		</div>
ERR;
    }

}
?>
