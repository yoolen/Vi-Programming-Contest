<html lang="en">
	<head>
		<!--These should be the session variables for this page-->
		<?php $contestID = 2; $teamID = 112?>
	</head>
	<script src="http://njit1.initiateid.com/library/jquery.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<body>
		<div class="container">
			<h2>Modal Example</h2>
			<!-- Trigger the modal with a button -->
			<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

			<!-- Modal -->
			<div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog modal-sm">

					<!-- Modal content-->
					<div class="modal-content">
					
						<div class="modal-body" id ="modal-body" >
						  <p style="text-align:center">Submission in Progress</p>
						  <img id="loadingPic" src="http://njit1.initiateid.com/images/loading.gif" loop=false style="width:100px;height:170px;margin-left:33%;margin-top:-10%">
						</div>
						<div class="modal-footer" id="modal-footer" hidden>
						  <button type="button" class="btn btn-default" data-dismiss="modal">Retry</button>
						</div>
					</div>

				</div>
			</div>
		</div>
		
	</body>
	<script>
		//var modal no
	</script>
</html>