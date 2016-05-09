<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";
$file = File_Functions::retrieve_file($_GET['fileId']);
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
			
			function renameFile() {
                $.ajax({
                    url: "http://njit1.initiateid.com/imaginarium/requests/rename.php",
                    method: "POST",
                    data: {
                        filename: document.getElementById("newFileName").value,
                        extension: document.getElementById("newFileExt").value,
                        fileId: <?php echo $_GET['fileId']; ?>
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
					<h4 class="modal-title">Rename File</h4>
				</div>
				<div class="modal-body">
					<h2>Rename File</h2>
                                        <h4>The page will reload on creating new file. Please save all changes before proceeding.</h4>
					<form id="newForm">                                            
						New File Name:<br>
						<input id="newFileName" type="text" name="filename" value="<?php echo $file['name']; ?>"><br><br>
						File Extension:<br>
						<select id="newFileExt" name="extension">
							<option value="java">*.java</option>
							<option value="cpp">*.cpp</option>
							<option value="py">*.py</option>
							<option value="txt">*.txt</option>
						</select><br>
						<input id="newFolder" style="display:none;" type="hidden" name="folder" value="<?php echo $_GET['folder']; ?>"><br>
						<a onclick="renameFile()" href="#" class="btn btn-default">Rename File</a><br>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
