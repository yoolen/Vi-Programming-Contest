<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
    header("Location: login.php");
}

require_once "compilation/classes.php";
require_once "compilation/helper.php";
if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $lang = $_POST['language'];
    $inputs = $_POST['inputs'];
    $req = new Request($lang, $code, $inputs);
    $request = json_encode($req);
    $curlRequest = curl_init('http://cs490.iidcct.com/comp/evaluate.php');
    curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $request);
    curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlRequest, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($request))
    );

    $result = json_decode(curl_exec($curlRequest));
    curl_close($curlRequest);
    $outBox = $result->output;
    //print_r($result);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>NJIT High School Programming Contest</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <style>
            body {
                margin: 0 !important;
                padding: 0 !important;
                font-family: 'Open Sans', sans-serif;
            }
            .head {
                height: 8vh;
                background: linear-gradient(to bottom, rgba(178,3,12,1) 1%,rgba(143,2,34,1) 91%,rgba(109,0,25,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b2030c', endColorstr='#6d0019',GradientType=0 );
                background-size: cover;
            }
            .main {
                width: 100%;
                display: table;
            }
            .center {
                display: table-row;
            }
            .editor {
                height: 79vh;
                width: 80vw;
                display: table-cell;
                border: solid 2px black;
            }
            .hints {
                padding-left: 10px;
                display: table-cell;
                background-color: lightgray;
                border: solid 2px black;
                font-size: .75vw;
            }
            .bottom {
                display: table-row;
            }
            .io {
                display: table-cell;
                height: 11.5vh;
                width: 60vw;
            }
            .controls {
                display: table-cell;
                vertical-align: central;
                width: 20vw;
                height: 8vh;
            }
            .userbox {
                background-color: #f8f8f8;
                position:absolute;
                top:0;
                right:0;
                width: 20vw;
                height: 8vh;
            }
            .gravatar {
                position: absolute;
                top:0;
                right:16vw;;
                width: 4vw;
                height: 8vh;
                background-color: black;
            }
            .userinfo {
                position:absolute;
                top:0;
                right:0;
                width: 16vw;
                height: 8vh;
                text-align: center;
                font-size: 0.75vw;
            }
            .inputbox {
                padding-left: 5px;
                position: absolute;
                bottom: 5vh;
                left: 0;
                width: 35vw;
                height: 8vh;
                float: left;
            }
            .outputbox {
                width: 35vw;
                height: 8vh;
                position: absolute;
                bottom: 5vh;
                left: 37vw;
            }
            .iolabel {
                font-size: .85vw;
            }
            textarea {
                resize: none;
            }

            #inputs{
                width:100%;
                height: 100%;
            }
            .bigbuttons {
                font-family: 'Ubuntu', sans-serif;
                font-size: 11pt;
                border-color:#000;
                border-style:solid;
                border-radius:5px;
                margin-top:5px;
                margin-bottom:5px;
                width: 20%;
                height: 8vh;
            }
        </style>
    </head>
    <body>
        <form action="imaginarium.php" id="form" method="POST">
            <div class="main" >
                <div class="top">
                    <div class="head">
                        <a href="index.php"><img src="images/logo.png" style="height: 8vh;" alt=""/></a>
                    </div>
                </div>
                <div class="center">
                    <div class="editor" id="editor">/**
* NJIT High School Programming Contest
* Java - Code Imaginarium.
*/
public static void main(String[] args) {
    System.out.println("Hello World!");
}
                    </div>
                    <div class="hints">
                        <h3>Welcome to the Code Imaginarium.</h3>
                        <p>You are using the Java Code Imaginarium</p>
                        <h2>Useful Java References</h2>
                        -<a href="http://java.com/en/">Java.com</a><br>
                        -<a href="https://docs.oracle.com/javase/8/docs/api/">Java 8 API Documentation</a><br>
                        -<a href="http://introcs.cs.princeton.edu/java/11cheatsheet/">Quick Java Cheat Sheet</a><br>
                    </div>
                </div>
            </div>
            <div class="main" >
                <div class="bottom">
                    <div class="io">
                        <div class="inputbox">
                            <span class="iolabel">Inputs:</span> <br>
                            <textarea name='inputs' id="inputs"></textarea>
                        </div>
                        <div class="outputbox">
                            <span class="iolabel">Output:</span> <br>
                            <textarea name='outputs' id="outputs" style="width:100%; height: 100%" ><?php
                                if (isset($outBox)) {
                                    echo $outBox;
                                }
                                ?></textarea>
                        </div>
                    </div>
                    <div class="controls">
                        <input type='hidden' name="code" id="code" value="">
                        <input type='hidden' name="language" value="java/output">
                        <input onclick="submitForm()" type="button" value="Execute" class="bigbuttons" >
                        <input onclick="clearEditor()" type="button" value="Clear" class="bigbuttons" >

                    </div>
                </div>
            </div>
            <div class="userbox">
                <div class="gravatar">
                    <?php
                    require_once ($_SERVER['DOCUMENT_ROOT'] . '\data\user.php');
                    require_once ($_SERVER['DOCUMENT_ROOT'] . '\utility\front-utilities.php');
                    $user = User::get_user($_SESSION['uid']);
                    $affiliation = User::get_affiliation_name($_SESSION['uid']);
                    echo "<div class='avatar'><img style='width: 60px;' src='" . get_gravatar($user['email']) . "' src='" . $user['fname'] . ' ' . $user['lname'] . "' /></div>";
                    ?>
                </div>
                <div class="userinfo">
                    <?php
                    echo 'Logged in As: ' . $user['fname'] . ' ' . $user['lname'] . ' <br>';
                    echo 'Affiliation:  ' . $affiliation . ' <br>';
                    echo '<a href="http://njit1.initiateid.com/">Click Here to Return to the Dashboard</a>';
                    ?>
                </div>
            </div>
        </form>
        <script src="library/ace/ace.js" type="text/javascript" charset="utf-8"></script>
        <script>
                            var editor = ace.edit("editor");
                            editor.setTheme("ace/theme/chrome");
                            editor.getSession().setMode("ace/mode/java");
                            editor.setFontSize(16);
                            function clearEditor() {
                                editor.setValue("", 0);
                            }
                            function submitForm() {
                                document.getElementById('code').value = editor.getSession().getValue();
                                //document.getElementById('inputs').value = editor.getSession().getValue();
                                document.getElementById("form").submit();
                            }
        </script>
    </body>
</html>
