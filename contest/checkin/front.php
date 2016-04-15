<html lang="en">
	<head>
		<!--These should be the session variables for this page-->
		<?php $contestID = 2; $teamID = 112?>
	</head>
	<script src="http://njit1.initiateid.com/library/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css"></script>
	<script src="http://njit1.initiateid.com/library/vex-master/js/vex.combined.min.js"></script>
	<script>vex.defaultOptions.className = 'vex-theme-os';</script>
	<link rel="stylesheet" href="http://njit1.initiateid.com/library/vex-master/css/vex.css" />
	<link rel="stylesheet" href="http://njit1.initiateid.com/library/vex-master/css/vex-theme-os.css" />
	<body>
		<!--<p><a class="demo-confirm hs-brand-button">Destroy the planet</a></p>
		<div class="demo-result-confirm hs-doc-callout hs-doc-callout-info" style="">
		</div>-->
		<div id="dialog" style="display:none;">
		</div>
	</body>
	<script>
		/*$('.demo-confirm').click(function(){
			vex.dialog.confirm({
				message: 'Are you absolutely sure you want to destroy the alien planet?',
				overlayClosesOnClick: false,
				callback: function(value) {
					$('.demo-result-confirm').show().html('<h4>Result</h4><p>' + (value ? 'Successfully destroyed the planet.' : 'Chicken.') + '</p>');
				}
			});
		});*/
		$('#dialog').html('some message');

$('#dialog').dialog({
    autoOpen: true,
    show: "blind",
    hide: "explode",
    modal: true,
    open: function(event, ui) {
        setTimeout(function(){
            $('#dialog').dialog('close');                
        }, 3000);
    }
});
	</script>
	
	
</html>