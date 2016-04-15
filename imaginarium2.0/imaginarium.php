<?php

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// If the user is on a mobile device, redirect them
if (isMobile()) {
    echo "The Code Imaginarium is Currently Unavailable for Mobile Devices. Redirecting you to the Dashboard.";
    header("refresh:5; url=http://njit1.initiateid.com/");
    return;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$fileId = $_GET['file'];
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";
$file = File_Functions::retrieve_file($fileId);

if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
    header("Location: /login.php");
}

$contestMode = false;
/*
  if (isset($_POST['code'])) {
  require_once "../compilation/classes.php";
  require_once "../compilation/helper.php";
  $code = $_POST['code'];
  $lang = $_POST['language'];
  $inputs = $_POST['inputs'];
  $req = new Request($lang, $code, $inputs);
  $request = json_encode($req);
  $ch = curl_init('http://cs490.iidcct.com/comp/evaluate.php');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json',
  'Content-Length: ' . strlen($request))
  );

  $result = json_decode(curl_exec($ch));
  curl_close($ch);
  $outBox = $result->output;
  //print_r($result);
  }
 */
?>
<!DOCTYPE html>

<html>
    <head>
        <title>NJIT High School Programming Contest</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="/library/ace/ace.js" type="text/javascript" charset="utf-8"></script>
        <style>
            body {
                width:100%;
                height:100%;
                margin: 0;
                padding: 0;
                font-family: 'Open Sans', sans-serif !important;
                font-size: 14pt;
            }

            .hide {
              display: none;
            }

            div {
                overflow: no-content;
            }

            .center {
                border: 1px solid black;
            }

            .center:after {
                content: '';
                display: table;
                clear: both;
            }
            #hints {
                font-family: 'Open Sans', sans-serif;
                background-color: white;
                float: right;
                height:80vh;
                width: 20%;
                min-width: 20%;
            }
            #editorPane {
                width:80%;
                max-width: 80%;
                height:80vh;
                float: left;
                overflow-y: hidden;
            }

            #editor {
                height: 77vh;
            }

            #problem {
                float: right;
                padding: 5px;
            }

            #file {
                float: right;
                padding: 5px;
            }

            #icons {
                padding: 2px;
            }

            #toolbar {
                padding: 2px;
                height: 5vh;
                min-height: 30px;
                max-height: 50px;
                border: black 1px solid;
            }

            #dragbar{
                background-color:black;
                height:100%;
                float: left;
                width: 3px;
                cursor: col-resize;
            }
            #ghostbar{
                width:3px;
                background-color:#000;
                opacity:0.5;
                position:absolute;
                cursor: col-resize;
                z-index:999;
            }

            .head {
                padding: 0.5vw;
                height: 10vh;
                background: linear-gradient(to bottom, rgba(178,3,12,1) 1%,rgba(143,2,34,1) 91%,rgba(109,0,25,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b2030c', endColorstr='#6d0019',GradientType=0 );
                background-size: cover;
            }
            .header {
                width: 100%;
                display: table;
            }
            .userbox {
                background-color: #f8f8f8;
                position:absolute;
                top:0;
                right:0;
                width: 20vw;
                height: 10vh;
                padding: 5px;
            }
            .gravatar {
                position: absolute;
                top:0;
                right:20vw;
                width: 4vw;
                height: 10vh;
                background-color: black;
            }
            .table {
                display: table;
            }
            .hints {
                width: 98%;
                float: right;
            }
            #alert {
                display: none;
                float: right;
                width: 500px;
                height: 6.25vh;
                margin-right: 25vw;
            }
        </style>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    </head>
    <body>

        <div class="userbox">
            <div class="gravatar">
                <?php
                require_once ($_SERVER['DOCUMENT_ROOT'] . '\data\user.php');
                require_once ($_SERVER['DOCUMENT_ROOT'] . '\utility\front-utilities.php');
                $user = User::get_user($_SESSION['uid']);
                $affiliation = User::get_affiliation_name($_SESSION['uid']);
                echo "<div class='avatar'><img style='width:4vw ;' src='" . get_gravatar($user['email']) . "' src='" . $user['fname'] . ' ' . $user['lname'] . "' /></div>";
                ?>
            </div>
            <div class="userinfo">
                <?php
                echo 'Logged in As: ' . $user['fname'] . ' ' . $user['lname'] . ' <br>';
                echo 'Affiliation:  ' . $affiliation . ' <br>';
                echo '<a href="http://njit1.initiateid.com/">Click Here to Return to Dashboard</a>';
                ?>
            </div>
        </div>
        <div class="header" >
            <div class="top">
                <div class="head">
                    <a href="../index.php"><img src="../images/logo.png" style="height: 8vh;" alt=""/></a>
                    <div id="alert" class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong>Saved!</strong> Your file has been successfully saved!
                    </div>
                </div>
            </div>
        </div>
        <div class="center">
            <div id="editorPane">
                <div id="toolbar">
                    <div id="problem">
                        | Working on Problem: <b> Prime Numbers</b>
                    </div>
                    <div id="file">
                        Editing: <b><?php echo $file['name'] . '.' . $file['extension']; ?></b>
                    </div>
                    <div id="icons">
                        <a onclick="newFile()" href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#newFile"> <span class="glyphicon glyphicon-open-file"></span> New File </a>
                        <a onclick="saveFile()" href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#saveFile"> <span class="glyphicon glyphicon-floppy-disk"></span> Save </a>
                        <a onclick="discardChanges()" href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#discard"> <span class="glyphicon glyphicon-transfer"></span> Revert Changes </a>
                        <a onclick="deleteFile()" href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteFile"> <span class="glyphicon glyphicon-floppy-remove"></span> Delete </a>
                        <a onclick="downloadFile()" href="http://njit1.initiateid.com/=download_<?php echo $fileId; ?>" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-save"></span> Download </a> |
                        <a onclick="runFile()" href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#execute"> <span class="glyphicon glyphicon-play-circle"></span> Run </a>
                        <!--a onclick="fileSettings()" href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-cog"></span> File Settings </a-->
                    </div>
                    <script>
                        var savedData = "";
                        function fileChanged() {
                            var currentData = editor.getSession().getValue();
                            return currentData != savedData;
                        }

                        function discardChanges() {
                            /*var result = confirm("Are you sure you would like to discard your changes? All changes will be lost!");
                            if (result == true) {
                                location.reload();
                            }*/
                        }

                        function savePrompt() {
                            alert("Please save your work before proceeding!");
                        }

                        function newFile() {
                            /*if (fileChanged()) {
                                savePrompt();
                            }*/
                        }

                        function saveFile() {
                            /*if (fileChanged()) {
                                alert("No changes have been made!");
                            }
                            //TODO Complete Function
                            */
                        }
                        function deleteFile() {
                            /*
                            var result = confirm("Are you sure you would like to delete this function?");
                            if (result == true) {
                                editor.setValue(savedData, 0);
                            }
                            */
                        }
                        function downloadFile() {
                            //TODO Re-implement
                        }
                        function runFile() {
                            /*if (fileChanged()) {
                                savePrompt();
                            }*/
                        }
                        function fileSettings() {
                            /*if (fileChanged() == true) {
                                savePrompt();
                            }*/
                        }
                    </script>
                </div>
                <div id="editor"><?php echo $file['content']; ?></div>
            </div>
            <div id="hints">
                <span id="position"></span>
                <div id="dragbar"></div>
                <div class='hints'>
                    <ul class="nav nav-tabs">
                        <?php if ($contestMode) echo '<li class="active"><a data-toggle="tab" href="#questions">Questions</a></li>'; ?>
                        <li><a data-toggle="tab" href="#files">Files</a></li>
                        <li><a data-toggle="tab" href="#help">Help</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="help" class="tab-pane fade <?php if (!$contestMode) echo"in active" ?>">
                            <h5>Welcome to the Code Imaginarium.</h5>
                            <p>You are using the Java Code Imaginarium</p>
                            <h2>Useful Java References</h2>
                            -<a href="http://java.com/en/" target="_blank">Java.com</a><br>
                            -<a href="https://docs.oracle.com/javase/8/docs/api/" target="_blank">Java 8 API Documentation</a><br>
                            -<a href="http://introcs.cs.princeton.edu/java/11cheatsheet/" target="_blank">Quick Java Cheat Sheet</a><br>
                        </div>
                        <div id="files" class="tab-pane fade">
                            <h3>Files</h3>
                            <?php
                            $folderId = File_Functions::get_folder_from_file($_GET['file']);
                            $folderData = File_Functions::get_folder_data_from_fileId($_GET['file']);
                            echo "<b>Folder Name:</b> " . $folderData['name'] . '&ensp; <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-pencil"></span> Rename Folder </a><br>';
                            if ($folderData['teamShare'] == 0) {
                                echo '<b>Shared with Team: </b> No. <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-share"></span> Share with Team </a>';
                            } else {
                                echo '<b>Shared with Team: </b> Yes. <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-edit"></span> Unshare with Team </a>';
                            }
                            echo '<br><a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-trash"></span> Delete Folder </a><br>';
                            echo "<h4><b>Folder Files</b></h4>";
                            $files = File_Functions::retrieve_folder_files($folderId);
                            echo <<<TSET
                    <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Open</th>
                            <th>Rename</th>
                            <th>Download</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
TSET;
                            foreach ($files as $ffile) {

                                echo '<tr>';
                                echo '<td>' . $ffile['name'] . '.' . $ffile['ext'] . '</td>';
                                echo '<td style="text-align: center;"><a href="http://njit1.initiateid.com/imaginarium2.0/imaginarium.php?file=' . $ffile['fileId'] . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-open"></span></a>' . '</td>';
                                echo '<td style="text-align: center;"><a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a>' . '</td>';
                                echo '<td style="text-align: center;"><a href="../=download_' . $ffile['fileId'] . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-download-alt"></span></a>' . '</td>';
                                echo '<td style="text-align: center;"><a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></a>' . '</td>';
                                echo '</tr>';
                            }
                            echo "</tbody></table>";
                            ?>
                        </div>
                        <div id="questions" class="tab-pane fade <?php if ($contestMode) echo"in active" ?>">
                            <h3>Contest Questions</h3>
                            <h5>Currently Working on: Question 2</h5>
                            <div class="questionGroup">
                                <a class="btn btn-warning" href="#q1" data-toggle="collapse" title="Question 1 in Progress">Question 1</a>
                                <a href="#" class="btn btn-default btn-sm active"> <span class="glyphicon glyphicon-arrow-right"></span> Go To </a>
								<a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-play-circle"></span> Submit </a>
                                <div id="q1" class="collapse">
                                    <h3>Hello World!</h3>
									<p>Write an application in the programming language of your choice that prints "Hello World!" to the console.</p>
									<p>For example, when the program runs, the following should be outputted</p>
									<p><i>Hello World!</i></p>
                                </div>
                            </div>
                            <br>
                            <div class="questionGroup">
                                <a class="btn btn-warning" href="#q2" data-toggle="collapse" title="Question 2 has not been started">Question 2</a>
                                <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-arrow-right"></span> Go To </a>
                                <div id="q2" class="collapse">
                                    <h3>Prime Numbers</h3>
									<p>Write a program that takes in one additional command line argument. This number will be an integer. Your program should output as many prime numbers (ascending sorted starting with 2) as specified by the command line argument. The output should be on one line.</p>
									<p>For example, when the program runs as such:</p>
									<p><i>java Submission 10</i></p>
									<p>The following should be outputted</p>
									<p><i>2 
3 
5 
7 
11 
13 
17 
19 
23 
29 </i></p>
                                </div>
                            </div>
                            <br>
                            <div class="questionGroup">
                                <a class="btn btn-success" href="#q3" data-toggle="collapse" title="Question 3 has has been submitted">Question 3</a>
                                <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-check"></span> Submitted </a>
                                <div id="q1" class="collapse">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer massa tellus, bibendum tincidunt leo quis, hendrerit commodo justo. Vivamus facilisis dictum pharetra. Mauris ante risus, egestas eget dolor vel, vulputate euismod metus. Ut rutrum vulputate risus, ut varius risus euismod vel. Nullam vitae tempor nulla, quis placerat metus. Sed nec nisi non tortor malesuada hendrerit vel ac lectus. Sed at orci auctor, maximus urna et, fringilla urna. Fusce non viverra orci. Curabitur sit amet orci imperdiet, fringilla diam vel, ultrices purus. Etiam faucibus sem nec lectus efficitur rhoncus.
                                </div>

                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="bottom">
            <div>
            </div>
            <script type="text/javascript">
                var i = 0;
                var dragging = false;
                $('#dragbar').mousedown(function (e) {
                    e.preventDefault();

                    dragging = true;
                    var main = $('#main');
                    var ghostbar = $('<div>',
                            {id: 'ghostbar',
                                css: {
                                    height: main.outerHeight(),
                                    top: main.offset().top,
                                    left: main.offset().left
                                }
                            }).appendTo('body');

                    $(document).mousemove(function (e) {
                        ghostbar.css("left", e.pageX + 2);
                    });

                });

                $(document).mouseup(function (e) {
                    if (dragging)
                    {
                        var percentage = (e.pageX / window.innerWidth) * 100;
                        var mainPercentage = 100 - percentage;
                        $('#editorPane').css("width", percentage + "%");
                        $('#hints').css("width", mainPercentage + "%");
                        $('#ghostbar').remove();
                        $(document).unbind('mousemove');
                        dragging = false;
                    }
                });
                var editor = ace.edit("editor");
                editor.setTheme("ace/theme/chrome");
<?php
switch ($file['extension']) {
    case 'java':
        echo 'editor.getSession().setMode("ace/mode/java");';
        break;
    case 'cpp':
        echo 'editor.getSession().setMode("ace/mode/c_cpp");';
        break;
    case 'py':
        echo 'editor.getSession().setMode("ace/mode/python");';
        break;
    default:
        echo 'editor.getSession().setMode("ace/mode/txt");';
        break;
}
echo PHP_EOL;
?>
                editor.setFontSize(16);
                editor.$blockScrolling = Infinity
                function clearEditor() {
                    editor.setValue("", 0);
                }
                function submitForm() {
                    document.getElementById('code').value = editor.getSession().getValue();
                    //document.getElementById('inputs').value = editor.getSession().getValue();
                    document.getElementById("form").submit();
                }
				function savery() {
                    document.getElementById('codeContent').value = editor.getSession().getValue();
                    //document.getElementById('inputs').value = editor.getSession().getValue();
                    document.getElementById("saveForm").submit();
                }
            </script>
        </div>
        <div id="newFile" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New File</h4>
              </div>
              <div class="modal-body">
                <h2>New File</h2>
                <form action="new.php" id="newForm" method="POST">
                  <h4>Folder: <?php echo $folderData['name'] ?></h4>
                  File Name:<br>
                  <input type="text" name="newFileName" value="file.java"><br>
                  <input style="display:none;" type="hidden" name="folderId" value="<?php echo $folderData['folderId'] ?>"><br><br>
                  <input type="submit" class="btn btn-default" value="New File">
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="saveFile" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Save File</h4>
              </div>
              <div class="modal-body" style="text-align: center;">
                <h3>Save File</h3>
                <form action="post.php" id="saveForm" method="POST">
                  <h4>Folder: <?php echo $folderData['name'] ?></h4>

                  <input style="display:none;" type="hidden" name="fileId" value="<?php echo $fileId; ?>">
                  <input style="display:none;" type="hidden" id="codeContent" name="content" value="">
				  <input style="display:none;" type="hidden" id="codeContent" name="action" value="save">
                  <br>
                  <input onclick="savery()" type="button" class="btn btn-default" value="Save">
                </form>
              </div>
            </div>
          </div>
        </div>
        <!--div id="saveFile" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Save File</h4>
              </div>
              <div class="modal-body" style="text-align: center;">
                <h4>File Saved</h4>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div-->
        <div id="discard" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Discard Changes</h4>
              </div>
              <div class="modal-body" style="text-align: center;">
                <h4>Are you sure you want to discard changes?</h4>
                <h6>All changes made since the last save will be lost.</h6>
                <a class="btn btn-default" href="http://njit1.initiateid.com/imaginarium2.0/imaginarium.php?file=<?php echo $fileId; ?>">Discard</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div id="deleteFile" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete File</h4>
              </div>
              <div class="modal-body" style="text-align: center;">
                <h4>Are you sure you want to delete this file?</h4>
                <h6>This action cannot be undone.</h6>
                <a class="btn btn-default" href="http://njit1.initiateid.com/imaginarium2.0/imaginarium.php?file=<?php echo $fileId; ?>">Delete</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!--div id="execute" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Execute</h4>
              </div>
              <div class="modal-body" style="text-align: center;">
                <h2>Execute File</h2>
                <form id="executeForm">
                  <b>Runnable File: </b>
                  <select name="runnable">
                    <?php
                      foreach($files as $fc) {
                        echo "<option value=\"".$fc['fileId']."\">".$fc['name'].".".$fc['ext']."</option>";
                      }
                    ?>
                  </select><br><br>
                  <b>Command Line Arguments:</b><br>
                  <input type="text" name="args" value=""><br>
                </form>
              </div>
            </div>
          </div>
        </div-->
		<div id="execute" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Result</h4>
              </div>
              <div class="modal-body" style="text-align: center;">
                <h2>Execution</h2>
				<p><b>Ran As:</b> java PrimeNumber 15</p>
                <textarea style="text-align:left;">2 
3 
5 
7 
11 
13 
17 
19 
23 
29 
31 
37 
41 
43 
BUILD SUCCESSFUL (total time: 0 seconds)
				</textarea>
              </div>
            </div>
          </div>
        </div>
    </body>
</html>
