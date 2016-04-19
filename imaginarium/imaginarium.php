<?php
header("Cache-Control: private, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

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
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/question.php";
$file = File_Functions::retrieve_file($fileId);

if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
    header("Location: /login.php");
}
$folderId = File_Functions::get_folder_from_file($_GET['file']);
$folderData = File_Functions::get_folder_data_from_fileId($_GET['file']);
$contestMode = false;
if ($folderData['contestRelated'] > 0) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/data/contest.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/data/question.php";
    $contestMode = true;
    $contest_associations = File_Functions::get_contest_data_from_folder($fileId);
    $question = Question::get_Question($contest_associations['questionId']);
    $questionTitle = $question['title'];
    $contestQuestions = Contest::get_contest_questions($contest_associations['contestId']);
}
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
        <link  rel='stylesheet' href='../style/imaginarium.css' type='text/css'>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="http://njit1.initiateid.com/library/contestTimer.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <script>
            function loadContest() {
                hourCheck(<?php echo $contest_associations['contestId']; ?>, 'on-time');
            }
        </script>
    </head>
    <body onload="loadContest()">

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
                    <div id="alert" class="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong><span id="alertTitle"></span></strong> <span id="alertMessage">Your file has been successfully saved!</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="center">
            <div id="editorPane">
                <div id="toolbar">
                    <?php
                    if ($contestMode) {                        
                        echo '<div id="problem">  | Working on Problem:';
                        echo ' <b>' . $questionTitle . "</b> | </div>";
                    }
                    echo '<div id="timerContainer">Time Remaining: <div id="timer"></div></div>';
                    ?>
                    <div id="file">
                       Editing: <b><?php echo $file['name'] . '.' . $file['extension']; ?></b> |
                    </div>
                    <div id="icons">
                        <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#newFile"> <span class="glyphicon glyphicon-open-file"></span> New File </a>
                        <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#saveFile"> <span class="glyphicon glyphicon-floppy-disk"></span> Save </a>
                        <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#discard"> <span class="glyphicon glyphicon-transfer"></span> Revert Changes </a>
                        <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteFile"> <span class="glyphicon glyphicon-floppy-remove"></span> Delete </a>
                        <a href="http://njit1.initiateid.com/=download_<?php echo $fileId; ?>" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-save"></span> Download </a> |
                        <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#execute"> <span class="glyphicon glyphicon-play-circle"></span> Run </a>
                        <!--a onclick="fileSettings()" href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-cog"></span> File Settings </a-->
                    </div>
                </div>
                <div id="editor"><?php echo $file['content']; ?></div>
            </div>
            <div id="hints" style="overflow: scroll;">
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
                                echo '<td style="text-align: center;"><a href="http://njit1.initiateid.com/imagine_' . $ffile['fileId'] . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-open"></span></a>' . '</td>';
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
                            <h5>Currently Working on: 
                                <?php
                                foreach ($contestQuestions as $questionIteration) {
                                    if ($questionIteration['qid'] == $question['qid']) {
                                        echo "<b>Question " . $questionIteration['sequencenum'] . '</b>';
                                    }
                                }
                                ?>
                            </h5>
                            <?php
                            foreach ($contestQuestions as $questionIteration) {
                                $sequence = $questionIteration['sequencenum'];
                                $inProgress = true; //Temporary
                                echo '<div class="questionGroup">';
                                if ($inProgress) {
                                    echo '<a class="btn btn-warning" href="#q' . $sequence . '" data-toggle="collapse" title="Question ' . $sequence . ' in Progress">Question ' . $sequence . '</a> ';
                                    if ($questionIteration['qid'] == $question['qid']) {
                                        echo '<a href="#" class="btn btn-default btn-sm active"> <span class="glyphicon glyphicon-arrow-right"></span> Go To </a> ';
                                    } else {
                                        echo '<a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-arrow-right"></span> Go To </a> ';
                                    }
                                    echo '<a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-play-circle"></span> Submit </a> ';
                                    echo '<div id="q' . $sequence . '" class="collapse">';
                                    $currQuestion = Question::get_question($questionIteration['qid']);
                                    echo "<h3>" . $currQuestion['title'] . "</h3>";
                                    echo "<p>" . $currQuestion['qtext'] . "</p>";
                                    echo '</div><br><br>';
                                }
                            }
                            ?>
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
                        <h4>Folder: <?php echo $folderData['name'] ?></h4>

                        <input style="display:none;" type="hidden" name="fileId" value="<?php echo $fileId; ?>">
                        <input style="display:none;" type="hidden" id="codeContent" name="content" value="">
                        <input style="display:none;" type="hidden" id="codeContent" name="action" value="save">
                        <br>
                        <script>
                            function save() {
                                $.ajax({
                                    url: "http://njit1.initiateid.com/imaginarium/post.php",
                                    method: "POST",
                                    data: {
                                        fileId: <?php echo $fileId; ?>,
                                        content: editor.getSession().getValue(),
                                        action: "save"
                                    },
                                    success: function (data) {
                                        $('#saveFile').modal('hide');
                                        if (data.valueOf() == "1") {
                                            $('#alert').removeClass();
                                            $('#alert').addClass("alert")
                                            $('#alert').addClass("alert-success");
                                            $('#alertTitle').html("Saved!");
                                            $('#alertMessage').html("Your file has been save successfully!");
                                            $('#alert').show().stop(true, true);
                                            $("#alert").fadeTo(2000, 500).slideUp(500, function () {
                                                $("#alert").hide();
                                            });
                                        }
                                    }
                                });
                            }
                            $(window).bind('keydown', function (event) {
                                if (event.ctrlKey || event.metaKey) {
                                    switch (String.fromCharCode(event.which).toLowerCase()) {
                                        case 's':
                                            event.preventDefault();
                                            save();
                                            break;
                                    }
                                }
                            });
                        </script>
                        <a onclick="save()" href="#" class="btn btn-default">Save</a>
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
                        <a class="btn btn-default" href="http://njit1.initiateid.com/imagine_<?php echo $fileId; ?>">Discard</a>
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
        foreach ($files as $fc) {
            echo "<option value=\"" . $fc['fileId'] . "\">" . $fc['name'] . "." . $fc['ext'] . "</option>";
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
