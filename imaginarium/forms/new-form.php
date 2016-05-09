<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>New File</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link  rel='stylesheet' href='/style/main.css' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="/library/jquery.js"></script>
    </head>
    <body>
		<script>
			function refreshParent() {
				window.opener.location.reload();
			}
		
			window.onunload = refreshParent;
			
			function newFile() {
                $.ajax({
                    url: "http://njit1.initiateid.com/imaginarium/requests/new.php",
                    method: "POST",
                    data: {
                        filename: document.getElementById("newFileName").value,
                        extension: document.getElementById("newFileExt").value,
                        folder: document.getElementById("newFolder").value
                    },
                    success: function (data) {
                        if (data.valueOf() != "0") {
                            window.close();
							close();
                        } else {
                            alert("Failed creating file.");
                        }
                    }
                });
            }
		</script>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">New File</h4>
				</div>
				<div class="modal-body">
					<h2>New File</h2>
					<form id="newForm" action="/imaginarium/new.php" method="POST">
						<h4>Folder: <?php echo $_GET['folderName']; ?></h4>
						<h4>The page will reload on creating new file. Please save all changes before proceeding.</h4>
						File Name:<br>
						<input id="newFileName" type="text" name="filename" value=""><br><br>
						File Extension:<br>
						<select id="newFileExt" name="extension">
							<option value="java">*.java</option>
							<option value="cpp">*.cpp</option>
							<option value="py">*.py</option>
							<option value="txt">*.txt</option>
						</select><br>
						<input id="newFolder" style="display:none;" type="hidden" name="folder" value="<?php echo $_GET['folder']; ?>"><br>
						<a onclick="newFile()" href="#" class="btn btn-default">New</a><br>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
