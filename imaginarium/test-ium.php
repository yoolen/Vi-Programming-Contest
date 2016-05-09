<?php
//NJIT High School Programming Contest - Project
//Code Imaginarium. Developed by Jan Chris Tacbianan

//Forces Reload on Back Action
require_once $_SERVER['DOCUMENT_ROOT'] . "/utility/force-refresh.php";

//Deny Mobile Users:
require_once $_SERVER['DOCUMENT_ROOT'] . "/utility/deny-mobile.php";

//Start Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Check if logged in.
if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
    header("Location: /login.php");
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/submission.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/question.php";

if(!isset($_GET['file'])) {
    echo "Invalid Page. Returning you to the dashboard.";
    header("refresh:5; url=http://njit1.initiateid.com/");
    return;
}

$teamId = User::get_teamid($_SESSION['uid']);
$fileId = $_GET['file'];
$file = File_Functions::retrieve_file($fileId);
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

if(File_Functions::is_folder_viewed($folderId) == 0) {
    File_Functions::set_folder_viewed($folderId);
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Code Imaginarium - NJIT High School Programming Contest</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="/library/ace/ace.js" type="text/javascript" charset="utf-8"></script>
        <link  rel='stylesheet' href='../style/imaginarium.css' type='text/css'>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
        <script src="http://njit1.initiateid.com/library/contestTimer.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

    </head>
    <body onload="pageLoad()">

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
                        echo ' <b>' . $questionTitle . "</b> </div>";
                        echo '<div id="timerContainer">Time Remaining: <div id="timer"></div></div>';
                    }                    
                    ?>
                    <div id="file">
                        Editing: <b><?php echo $file['name'] . '.' . $file['extension']; ?></b> |
                    </div>
                    <div id="icons">
                        <a href="#" class="btn btn-default btn-sm <?php if($contestMode) { echo "disabled"; } ?>" title="File creation is disabled in contest mode." data-toggle="modal" data-target="#newFile"> <span class="glyphicon glyphicon-open-file"></span> <?php if($contestMode) { echo "Disabled"; } else { echo "New File"; } ?> </a>
                        <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#saveFile"> <span class="glyphicon glyphicon-floppy-disk"></span> Save </a>
                        <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#discard"> <span class="glyphicon glyphicon-transfer"></span> Revert Changes </a>
                        <!--a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#deleteFile"> <span class="glyphicon glyphicon-floppy-remove"></span> Delete </a-->
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
                        <li <?php if (!$contestMode) echo 'class="active"'; ?>><a data-toggle="tab" href="#files">Files</a></li>
                        <li><a data-toggle="tab" href="#help">Help</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="help" class="tab-pane fade">
                            <?php
                            switch ($file['extension']) {
                                case 'java':
                                    echo <<<JAVA
                                        <h5>Welcome to the Code Imaginarium.</h5>
                                        <p>You are using the Java Code Imaginarium</p>
                                        <h2>Useful Java References</h2>
                                        -<a href="http://java.com/en/" target="_blank">Java.com</a><br>
                                        -<a href="https://docs.oracle.com/javase/8/docs/api/" target="_blank">Java 8 API Documentation</a><br>
                                        -<a href="http://introcs.cs.princeton.edu/java/11cheatsheet/" target="_blank">Quick Java Cheat Sheet</a><br>
                                        <h6>JAVA - OPENJDK VER 1.8.0_91</h6>
JAVA;
                                    break;
                                case 'cpp':
                                    echo <<<CPP
                                        <h5>Welcome to the Code Imaginarium.</h5>
                                        <p>You are using the C++ Code Imaginarium</p>
                                        <h2>Useful C++ References</h2>
                                        -<a href="http://www.cplusplus.com/" target="_blank">CPlusPlus.com</a><br>
                                        -<a href="http://www.cplusplus.com/reference/" target="_blank">C++ Reference</a><br>
                                        <h6>GCC - GNU COMPILER COLLECTION 4.9.3</h6>
CPP;
                                    break;
                                case 'py':
                                    echo <<<PY
                                        <h5>Welcome to the Code Imaginarium.</h5>
                                        <p>You are using the Python Code Imaginarium</p>
                                        <h2>Useful Python 3 References</h2>
                                        -<a href="http://www.python.org" target="_blank">Python.org</a><br>
                                        -<a href="https://docs.python.org/3/" target="_blank">Python 3.5.1 Documentation</a><br>
                                        <h6>PYTHON - PYTHON VER 3.5.1</h6>
PY;
                                    break;
                                default:
                                    echo 'editor.getSession().setMode("ace/mode/text");';
                                    break;
                            }
                            echo "<h6>Code Imaginarium Version 2.1a</h6>";
                            echo PHP_EOL;
                            ?>
                            
                        </div>
                        <div id="files" class="tab-pane fade <?php if (!$contestMode) echo"in active" ?>">
                            <h3>Files</h3>
                            <?php
                            echo "<b>Folder Name:</b> " . $folderData['name'] . '&ensp; <br><a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-pencil"></span> Rename Folder </a><br>';
                            if(!$contestMode) { 
                                if ($folderData['teamShare'] == 0) {
                                    echo '<b>Shared with Team: </b> No. <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-share"></span> Share with Team </a>';
                                } else {
                                    echo '<b>Shared with Team: </b> Yes. <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-edit"></span> Unshare with Team </a>';
                                }
								echo '<br><a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-trash"></span> Delete Folder </a><br>';
                            }
                            
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
                                if($ffile['fileId'] == $fileId) {
                                    echo '<tr>';
                                    echo '<td><b>' . $ffile['name'] . '.' . $ffile['ext'] . '</b></td>';
                                    echo '<td style="text-align: center;"><a href="#" class="btn btn-default btn-sm active"><span class="glyphicon glyphicon-open"></span></a>' . '</td>';
                                    echo '<td style="text-align: center;"><a href="#" onclick="window.open(\'http://njit1.initiateid.com/imaginarium/forms/rename-form.php?fileId='.$file['fileId'].'\', \'windowName\',\'width=400,height=600,scrollbars=no\')" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a></td>';
                                    echo '<td style="text-align: center;"><a href="../=download_' . $ffile['fileId'] . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-download-alt"></span></a>' . '</td>';
                                    echo '<td style="text-align: center;"><a href="#" title="Open file cannot be deleted." class="btn btn-default btn-sm active"><span class="glyphicon glyphicon-trash"></span></a>' . '</td>';
                                    echo '</tr>';
                                } else {
                                    echo '<tr>';
                                    echo '<td>' . $ffile['name'] . '.' . $ffile['ext'] . '</td>';
                                    echo '<td style="text-align: center;"><a href="http://njit1.initiateid.com/imagine_' . $ffile['fileId'] . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-open"></span></a>' . '</td>';
                                    echo '<td style="text-align: center;"><a href="#" onclick="window.open(\'http://njit1.initiateid.com/imaginarium/forms/rename-form.php?fileId='.$file['fileId'].'\', \'windowName\',\'width=400,height=600,scrollbars=no\')" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a></td>';
                                    echo '<td style="text-align: center;"><a href="../=download_' . $ffile['fileId'] . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-download-alt"></span></a>' . '</td>';
                                    echo '<td style="text-align: center;"><a href="#" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span></a>' . '</td>';
                                    echo '</tr>';
                                }
                            }
                            echo "</tbody></table>";
                            ?>
                        </div>
                        <?php if ($contestMode) { ?>
                        <div id="questions" class="tab-pane fade in active">
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
                                $questionFolder = File_Functions::get_folder_for_question($_SESSION['uid'], $questionIteration['qid']);
                                $viewed = File_Functions::is_folder_viewed($questionFolder);    
                                $rFile = File_Functions::first_file($questionFolder); //TODO: Get file.
                                echo '<div class="questionGroup">';
                                if ($viewed) {
                                    $submitted = false;
                                    $submissions = Submission::get_all_submissions();
                                    foreach($submissions as $sub) {
                                        if($questionIteration['qid'] == $sub['question_FK'] && $teamId == $sub['team_FK']) {
                                            $submitted = true;
                                            break;
                                        }
                                    }
                                    if ($questionIteration['qid'] == $question['qid']) {
                                        echo '<a class="btn btn-warning active" href="#q' . $sequence . '" data-toggle="collapse" title="Question ' . $sequence . ' in Progress">Question ' . $sequence . '</a> ';
                                        echo '<a href="#" class="btn btn-default btn-sm active"> <span class="glyphicon glyphicon-arrow-right"></span> Go To </a> ';
                                        echo '<a href="#" id='.$question['qid'].' onclick="submitOneAnswer(this)" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-play-circle"></span> Submit </a> ';
                                    } else {
                                        if($submitted) {
                                            echo '<a class="btn btn-success" href="#q' . $sequence . '" data-toggle="collapse" title="Question ' . $sequence . ' in Progress">Question ' . $sequence . '</a> ';
                                            echo '<a href="#" class="btn btn-default btn-sm disabled"> <span class="glyphicon glyphicon-check"></span> Submitted </a> ';
                                        } else {
                                            echo '<a class="btn btn-warning" href="#q' . $sequence . '" data-toggle="collapse" title="Question ' . $sequence . ' in Progress">Question ' . $sequence . '</a> ';
                                            echo '<a href="http://njit1.initiateid.com/imagine_' . $rFile .'" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-arrow-right"></span> Go To </a> ';
                                        }
                                        
                                    }
                                    echo '<div id="q' . $sequence . '" class="collapse">';
                                    $currQuestion = Question::get_question($questionIteration['qid']);
                                    echo "<h3>" . $currQuestion['title'] . "</h3>";
                                    echo '<p style = "white-space: pre-wrap">' . $currQuestion['qtext'] . "</p>";
                                    echo '</div><br><br>';
                                } else {
                                    echo '<a class="btn btn-danger" href="#q' . $sequence . '" data-toggle="collapse" title="Question ' . $sequence . ' in Progress">Question ' . $sequence . '</a> ';
                                    if ($questionIteration['qid'] == $question['qid']) {
                                        echo '<a href="#" class="btn btn-default btn-sm active"> <span class="glyphicon glyphicon-arrow-right"></span> Go To </a> ';
                                        echo '<a href="#" onclick="submitOneAnswer(this)" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-play-circle"></span> Submit </a> ';
                                    } else {
                                        echo '<a href="http://njit1.initiateid.com/imagine_' . $rFile .'" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-arrow-right"></span> Go To </a> ';
                                    }                                    
                                    echo '<div id="q' . $sequence . '" class="collapse">';
                                    $currQuestion = Question::get_question($questionIteration['qid']);
                                    echo "<h3>" . $currQuestion['title'] . "</h3>";
                                    echo '<p style = "white-space: pre-wrap" >' . $currQuestion['qtext'] . "</p>";
                                    echo '</div><br><br>';
                                }
                            }
                            ?>
                            <a href="#" onclick="submitAllAnswers()" class="btn btn-default btn-sm"> Submit All Answers</a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

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
                        <form id="newForm" action="/imaginarium/new.php" method="POST">
                            <h4>Folder: <?php echo $folderData['name'] ?></h4>
                            <h4>The page will reload on creating new file. Please save all changes before proceeding.</h4>
                            File Name:<br>
                            <input id="newFileName" type="text" name="filename" value="file.java"><br><br>
                            File Extension:<br>
                            <select id="newFileExt" name="extension">
                                <option value="java">*.java</option>
                                <option value="cpp">*.cpp</option>
                                <option value="py">*.py</option>
                                <option value="txt">*.txt</option>
                            </select><br>
                            <input id="newFolder" style="display:none;" type="hidden" name="folder" value="<?php echo $folderData['folderId'] ?>"><br>
                            <a onclick="newFile()" href="#" class="btn btn-default">New</a><br>
                            <input id="newFileOpen" type="checkbox" name="openNew" value="openNew" checked> Open File After Creating<br>
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
                        
                        <a onclick="save()" href="#" class="btn btn-default">Save</a>
                    </div>
                </div>
            </div>
        </div>
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
        <div id="execute" class="modal fade" role="dialog">
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
                  <select name="runnable" id="execRunnable">
        <?php
        foreach ($files as $fc) {
            echo "<option value=\"" . $fc['fileId'] . "\">" . $fc['name'] . "." . $fc['ext'] . "</option>";
        }
        ?>
                  </select><br><br>
                  <b>Command Line Arguments:</b><br>
                  <input type="text" id="execArgs" name="args" value=""><br><br>
                  <b>Output Watch: (Leave blank for standard out)</b><br>
                  <input type="text"id="execWatch" name="watch" value=""><br><br>
                  <a href="#" onclick="execute()" class="btn btn-default" data-dismiss="modal" data-toggle="modal" data-target="#execution">Execute</a>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="execution" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Result</h4>
                    </div>
                    <div class="modal-body" style="text-align: center;">
                        <h2>Execution Result</h2>
                        <div id="execResult">
                            <i class="fa fa-gear fa-spin" style="font-size:48px"></i>
                            <i class="fa fa-gear fa-spin" style="font-size:48px"></i>
                            <i class="fa fa-gear fa-spin" style="font-size:48px"></i>
                            <h4>Executing your code. Hang tight!</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require $_SERVER['DOCUMENT_ROOT'] . "/imaginarium/imaginarium-scripts.php"; ?>
    </body>
</html>
