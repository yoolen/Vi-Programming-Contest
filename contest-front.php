<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
    header("Location: login.php");
}

require_once ($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');

	$cID = $_GET['unit']; // Contest ID
	$tID_int = User::get_teamid($_SESSION['uid']); // Team ID
	$teamID = "team".(string) $tID_int; // Convert the Team ID to an arbitrary id, e.g. "team 1"
	
	/* If the team is not already set in this session, create a new session. Otherwise,
	   edit the session that is already available.
	*/
	if (!isset($_SESSION[$teamID])){
		// Generic Code
		$code = <<<EOF
/***************************************
* NJIT High School Programming Contest *
****************************************/

public static void main(String[] args) {
	System.out.println("Hello World!");
}
EOF;
		$contestqs = Contest::get_contest_questions($cID); // All the contest questions for a particular contest
		
		// An array with different status based on the editor and actions of the team
		$_SESSION['viewStatus'] = array('Viewing unsubmitted code','Not viewed yet', 'Viewed but not started yet', 'Viewed and Started', 'In Progress', 'Submitted', 'Viewing submitted code', 'Submission Error!');
		
		$user_answers = array(); // Stores the information for each questions
		for($i = 0; $i < count($contestqs); $i++){
			// The first question will always be viewed first, all other questions will not be viewed yet.
			$viewStat = ($i == 0 ? $_SESSION['viewStatus'][0] : $_SESSION['viewStatus'][1]); 
			$arr = array(
				'code' => $code,
				'language' => 'java/output',
				'input' => '',
				'output' => '',
				'viewStatus' => $viewStat,
				'started' => 'false',
				'qid' => $contestqs[$i]['qid'],
				'sequencenum' => $contestqs[$i]['sequencenum'],
				'title' => $contestqs[$i]['title'],
				'qtext' => $contestqs[$i]['qtext'],
				'error' => ''
			);
			$user_answers[] = $arr;
		}
		$_SESSION[$teamID] = $user_answers; // Set the team's information into their session
	
	} else {
		// This code is for the previous question (or question that was viewed before going to the next chosen one)
		if(isset($_POST['answer_id_prev'])){
			$prev = $_POST['answer_id_prev'];
			
			// Set the type of submission (single submission or multiple submission)
			if(isset($_POST['sent_code'])){
				$_SESSION[$teamID][$prev]['answer_type'] = $_POST['sent_code'];
			} else {
				$_SESSION[$teamID][$prev]['answer_type'] = '';
			}
		}
		
		// This code is for the question that the team choose to go to
		if(isset($_POST['answer_id_next'])){
			$next = $_POST['answer_id_next'];
			
			// Based on it's view status that was stored before selection, if the question
			// was submitted, then it should display "Viewing submitted question" and vice versa.
			if($_SESSION[$teamID][$next]['viewStatus'] != $_SESSION['viewStatus'][5])
				$_SESSION[$teamID][$next]['viewStatus'] = $_SESSION['viewStatus'][0];
			else
				$_SESSION[$teamID][$next]['viewStatus'] = $_SESSION['viewStatus'][6];
		}
		
		// Set the view status of the previous question
		if(isset($_POST['viewStat_next']))
			$_SESSION[$teamID][$prev]['viewStatus'] = $_POST['viewStat_next'];
		
		if(isset($_POST['codeAns'])) // Set the code of the previous question
			$_SESSION[$teamID][$prev]['code'] = $_POST['codeAns'];
			
		if(isset($_POST['languageAns'])) // Set the language of the code of the previous question
			$_SESSION[$teamID][$prev]['language'] =  $_POST['languageAns'];
		
		// Set the flag of whether a question has been started or not
		if(isset($_POST['questionStart']) && $_POST['questionStart'] == 'true')
			$_SESSION[$teamID][$prev]['started'] = $_POST['questionStart'];
		
		// If there was an error in the submission, set that parameter. Otherwise, set it to ''
		if(isset($_POST['error'])){
			$sq = $_POST['seq'];
			$_SESSION[$teamID][$sq]['answer_type'] = ''; // Prevents questions from resubmitting
			if($_POST['error'] == 'none')
				$_SESSION[$teamID][$sq]['error'] = '';
			else {
				$_SESSION[$teamID][$sq]['error'] = $_POST['error'];
				$_SESSION[$teamID][$sq]['viewStatus'] = $_SESSION['viewStatus'][0];
			}
		}
	}

require_once "compilation/classes.php";
require_once "compilation/helper.php";
if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $lang = $_POST['language'];
    $inputs = $_POST['inputs'];
		$qidIndex = $_POST['qid'] - 1; // Decrease the value of the qid sent to match the index value in the session array
		$_SESSION[$teamID][$qidIndex]['code'] = $_POST['code'];
		$_SESSION[$teamID][$qidIndex]['language'] = $_POST['language'];
		
		// The question should be denoted as in-progress
		if($_SESSION[$teamID][$qidIndex]['viewStatus'] != $_SESSION['viewStatus'][6])
			$_SESSION[$teamID][$qidIndex]['viewStatus'] = $_SESSION['viewStatus'][4];
		$_SESSION[$teamID][$qidIndex]['started'] = 'true';
		
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
}
?>

<html>
    <head>
        <title>NJIT High School Programming Contest</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="library/ace/ace.js" type="text/javascript" charset="utf-8"></script>
		<script src="http://njit1.initiateid.com/library/contestTimer.js"></script>
        <style>
            body {
                width:100%;
                height:100%;
                margin: 0;
                padding: 0;
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: 'Open Sans', sans-serif;
            }
            p, div {
                font-family: 'Open Sans', sans-serif;
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
                font: inherit !important;
                background-color: lightgray;
                float: right;
                height:70vh;
                width: 45%;
            }
            #editor {
                width:55%;                
                height:70vh;
                float: left;
                overflow-y: hidden;
                font-style: normal;
                font-variant: normal;
                font-weight: normal;
                font-stretch: normal;
                font-size: 12px;
                line-height: normal;
                font-family: Monaco, Menlo, 'Ubuntu Mono', Consolas, source-code-pro, monospace;
            }
            #editor div {
                font: inherit !important;
            }

            .dragbar{
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
                height: 8vh;
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

            #inputs{
                width:100%;
                height: 100%;
            }
            .inputbox {
                padding-left: 5px;
                position: absolute;
                bottom: -20vh;
                left: 0;
                width: 35vw;
                height: 12vh;
                float: left;
            }
            #bottom-main {
                display: table-row;
            }
            .outputbox {
                width: 35vw;
                height: 12vh;
                position: absolute;
                bottom: -20vh;
                left: 37vw;
            }
            .iolabel {
                font-size: .85vw;
            }
            textarea {
                resize: none;
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
            .table {
                display: table;
            }
			#questions{
				width: 45%;
				font: inherit !important;
				padding-left: 0px;
				margin-bottom: 0px;
				list-style-type: none;
                background-color: lightgray;
                float: right;
                height:70vh;
			}
        </style>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>
    <body onload="loadContest()">
		
            <div class="userbox">
                <div class="gravatar">
                    <?php
                    //require_once ($_SERVER['DOCUMENT_ROOT'] . '\data\user.php');
                    require_once ($_SERVER['DOCUMENT_ROOT'] . '\utility\front-utilities.php');
                    $user = User::get_user($_SESSION['uid']);
                    $affiliation = User::get_affiliation_name($_SESSION['uid']);
                    echo "<div class='avatar'><img style='width: 4vw;' src='" . get_gravatar($user['email']) . "' src='" . $user['fname'] . ' ' . $user['lname'] . "' /></div>";
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
            <div class="header" >
                <div class="top">
                    <div class="head">
                        <div id="timer" style = "float: right; border: 2px solid; min-width:30px; padding-right: 20px; font-size: 34px; text-align: center;margin-right:290px; padding-left:20px; background:white; overflow:hidden"></div>
				<form action="#" id="form" method="POST">
				<a href="index.php"><img src="images/logo.png" style="height: 8vh;" alt=""/></a>
                </div>
					
                </div>
            </div><!--onmouseover="showFin()"--> 
            <div class="center" id="center">
				<div id="fin_bar" style="width: 60%; float: left; margin-left: 9%; height: 20%; overflow: hidden">
					<div id="submitOneAnswer" type = "button" onclick="submitOneAnswer()" style="border:solid; width: 15%; height: 90px; text-align: center; font-size: 20px; margin-top:5px">Submit This Answer</div>
					<div id="clear" type = "button" onclick="clearEditor()" style="float: left; margin-left: 25%; border:solid; width: 12%; height: 56px; text-align: center; font-size: 20px; margin-top: -56px">Clear Editor</div>
					<div id="submitAllAnswers" type = "button" data-toggle="modal" data-target="#myModal" data-backdrop="static" onclick="submitAllAnswers()" style="border:solid; margin-left: 45%; width: 16%; height: 90px; text-align: center; font-size: 20px; margin-top:-90px">Submit All Answers</div>
				</div>
                <div id="editor" class='editor'></div>
				<div id="showHints" onclick="showHints(this)" type="button" style="width:5%; float: right; border: solid; text-align: center; margin-top:-20px">Help</div>
				<select id="languageSelector" onchange="changeEditor(this)" style="float:left; margin-top: -20px">
					<option value="java">Java</option>
					<option value="python">Python</option>
				</select>
				
                <div id="hints" hidden>
                    <span class="position"></span>
                    <div class="dragbar"></div>
                    <h3>Welcome to the Code Imaginarium.</h3>
                    <p>You are using the Java Code Imaginarium</p>
                    <h3>Useful Java References</h3>
                    -<a href="http://java.com/en/">Java.com</a><br>
                    -<a href="https://docs.oracle.com/javase/8/docs/api/">Java 8 API Documentation</a><br>
                    -<a href="http://introcs.cs.princeton.edu/java/11cheatsheet/">Quick Java Cheat Sheet</a><br>
                </div>
				<ul class="questions" id="questions">
					<span class="position"></span>
					<div class="dragbar"></div>
				</ul> 
			</div>
            <div class="bottom">
                <ul class="nav nav-tabs">
                    <li><a href="#">Files</a></li>
                    <li class="active"><a href="#">Console</a></li>
                    <li><a href="#">Question</a></li>
					<li><a style="margin-left:600px;"></a></li>
                </ul>  
                <div class='table'>
                    <div id='bottom-main'>
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
							<input type='hidden' name="qid" id="qid" value="">
							
                            <input onclick="submitForm()" type="button" value="Execute" class="bigbuttons btn-default" >
                            <!--<input onclick="clearEditor()" type="button" value="Clear" class="bigbuttons btn-default" >-->

                        </div>
                    </div>
                </div>
            </div>
        </form>
		<!--This is the modal when the submit all button is clicked or when all questions where submitted -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog modal-sm">

				<!-- Modal content-->
				<div class="modal-content" id="modal-content">
				
					<div class="modal-body" id ="modal-body" >
					  <p style="text-align:center">Submissions in Progress</p>
					  <img src="http://njit1.initiateid.com/images/loading.gif" loop=false style="width:100px;height:170px;margin-left:33%;margin-top:-10%">
					</div>
					<div class="modal-footer" id="modal-footer" hidden>
					  <button type="button" class="btn btn-primary" onclick="reSubmitAll()" >Retry</button>
					</div>
				</div>

			</div>
		</div>
        <script type="text/javascript">
			// Get the array of information for each question from a team
			var contestQuests = <?php echo json_encode($_SESSION[$teamID]); ?>;
			
			// Remove the extra object that is concatenated to the contestQuests
			if(typeof contestQuests === 'object'){
				var arr = [];
				for(var key in contestQuests){
					if(key === "")
						continue;
					arr.push(contestQuests[key]);
				}
				contestQuests = arr;
			}

			var contest_length = contestQuests.length; // Length of the array
			var viewStatus = <?php echo json_encode($_SESSION['viewStatus']); ?>; // Get the array of view status
			
            var dragging = false;
            $('.dragbar').mousedown(function (e) {
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
                    $('#editor').css("width", percentage + "%");
                    $('#hints').css("width", mainPercentage + "%");
					$('#questions').css("width", mainPercentage + "%");
                    $('#ghostbar').remove();
                    $(document).unbind('mousemove');
                    dragging = false;
                }
            });
			
            var editor = ace.edit("editor");
			var change = 0;
            editor.setTheme("ace/theme/chrome");
            editor.setFontSize(16);
			editor.$blockScrolling = Infinity;
			
			// Detect if there are any changes in the editor for a question. If true,
			// change the view status to 'in-progress'
			editor.on('change', function() {
				change = change + 1;
				if(change >= 2){
					var editorVal = editor.getSession().getValue();
					var questions = document.getElementsByClassName("questions")[0].childNodes;
					for (var i = 5; i < questions.length; i++){
						var viewStat = questions[i].childNodes;
						if(viewStat[1].innerHTML == viewStatus[0] || viewStat[1].innerHTML == viewStatus[4])
							break;	
					}
					if( editorVal != contestQuests[viewStat[2].id - 1]['code'] )
						viewStat[1].innerHTML = viewStatus[4];
				}
			});
			
            function clearEditor() {
                editor.setValue("", 0);
            }
			
            function submitForm() {
                var questions = document.getElementById("questions").childNodes;
				
				for(var i = 0; i < questions.length; i++){
					if(questions[i].className == "active"){
						document.getElementById('qid').value = questions[i].getAttribute("value");
						break;
					}
				}
				document.getElementById('code').value = editor.getSession().getValue();
                //document.getElementById('inputs').value = editor.getSession().getValue();
                document.getElementById("form").submit();
            }
				
			function loadContest(){
				// Loads the timer
				hourCheck(<?php echo $cID; ?>, 'on-time');
			
				var questionsDiv = document.getElementById("questions");
				
				for(var j = 0; j < contest_length; j++){
					var question = document.createElement('li');
					var title = document.createElement('span');
					var br = document.createElement('br');
					var viewStat = document.createElement('span');
					var viewQuest = document.createElement('span');
					var viewError = document.createElement('span');
					var errorText = document.createElement('span');
					var questionText = document.createElement('p');
					
						question.id = contestQuests[j]['qid'];
						question.setAttribute("type", "button");
						question.onclick = function (){changeQuestion(this)};
						
						question.style = "margin-bottom: 15px";
							
						question.setAttribute("value", j+1);
							title.style = "font-weight:bold; float: left; margin-left: 2px";
							title.innerHTML = j+1 + ". " + contestQuests[j]['title'];

							viewStat.id = "viewStat" + (j+1);
							viewStat.innerHTML = contestQuests[j]['viewStatus'];
							viewStat.style = "float: left; margin-left: 15px";
							
							// Set the language and code when they execute their code.
							if(contestQuests[j]['viewStatus'] == viewStatus[0] || contestQuests[j]['viewStatus'] == viewStatus[4] || contestQuests[j]['viewStatus'] == viewStatus[6]){
								var controls = document.getElementsByClassName("controls")[0].childNodes;
								var languageSelector = document.getElementById('languageSelector');
								var language = contestQuests[j]['language'].replace(/\/output|\/test/,"");
								
								var opts = languageSelector.options.length;
								editor.getSession().setMode("ace/mode/" + language);
								editor.getSession().setValue(contestQuests[j]['code']);
								question.className = "active";
								controls[3].setAttribute("value", contestQuests[j]['language']);
								
								for(var k = 0; k < opts; k++){
									if (languageSelector.options[k].value == language){
										languageSelector.options[k].selected = true;
										break;
									}
								}
								
								// This disables some parts of the view (editor etc.). Only when there is an error (a question is
								// submitted) or is already submitted will thsi be executed.
								if(contestQuests[j]['viewStatus'] == viewStatus[6] || contestQuests[j]['error'] != ''){
									editor.setReadOnly(true);
									//document.getElementById("fin_bar").hidden = true;
									document.getElementById("languageSelector").hidden = true;
									document.getElementById("showHints").hidden = true;
								}
							}	
							
							// If they submitted a code, this will execute
							if(contestQuests[j]['viewStatus'] == viewStatus[5]){
								
								// This code is for a single submission.
								if(contestQuests[j]['answer_type'] == 'single'){
									viewStat.innerHTML = 'Submission in progress';
									$.ajax({
										url: "http://njit1.initiateid.com/middleware/contest-grading.php",
										method: "POST",
										data: {
											contestID: <?php echo $cID; ?>,
											teamID: <?php echo $tID_int; ?>,
											codeAns: contestQuests[j]['code'],
											sequencenum: j,
											language: contestQuests[j]['language'],
											qid: contestQuests[j]['qid'],
											sent_code: 'single'
											
										},
										success: function(data){
											
											var obj = JSON.parse(data);
											console.log(obj);
											var questions = document.getElementsByClassName("questions")[0].childNodes;
											for(var i = 5; i < questions.length; i++){
												
												// Look for the question with the matching qid
												if(questions[i].id == obj['qid']){
													var viewStat = questions[i].childNodes[1];
													
													// If we get an error, display an error and set the error for that 
													// question in the session variable, else display submitted and set the error to ''
													if(isNaN(obj['stat'])){
														viewStat.innerHTML = viewStatus[7];
														$(viewStat).css("color", "red");
														
														$.ajax({
															url: "contest-front.php?unit=" + <?php echo $cID?>,
															method: "POST",
															data:{
																seq: obj['seq'],
																error: obj['stat']
															}

														});
													} else {
														viewStat.innerHTML = viewStatus[5];
														$(viewStat).css("color", "green");
														setTimeout(function(){$(viewStat).css("color", "black");}, 1000);
														$.ajax({
															url: "contest-front.php?unit=" + <?php echo $cID?>,
															method: "POST",
															data:{
																seq: obj['seq'],
																error: 'none'
															}
														});
														if(allQuestSubmitted())
															allSubmitModal();
													
													}
													break;
												}
											}
										}
									}); 
								}
							}
							
							questionText.id = "question" + (j+1);
							questionText.hidden = true;
							questionText.innerHTML = contestQuests[j]['qtext'];
							questionText.style = "margin: 20px";

							viewQuest.id = j+1;
							viewQuest.setAttribute("type", "button");
							viewQuest.style="border:solid; float: right; margin-right: 15px";
							
							viewQuest.onclick = function(){
								var qText = document.getElementById("question"+ this.id);
								var questions = document.getElementsByClassName("questions")[0].childNodes;

								if(qText.hidden){
									qText.hidden = false;
									this.innerHTML = "See other questions";
									
									for(var i = 5; i < questions.length; i++){
										if (this.id != questions[i].getAttribute("value"))
											questions[i].hidden = true;
									}
									
								} else {
									qText.hidden = true;
									this.innerHTML = "See full question";
									for(var i = 5; i < questions.length; i++){
										if (this.id != questions[i].getAttribute("value"))
											questions[i].hidden = false;
									}
								}
							};
							viewQuest.innerHTML = "See full question";
							
							// If an error message is stored for a question, display a "See Error" button
							// instead of a "See question" button
							if(contestQuests[j]['error'] != ''){
								errorText.id = "question" + (j+1);
								errorText.hidden = true;
								errorText.innerHTML = contestQuests[j]['error'];
								errorText.style = "float: left; margin: 20px";
								
								viewError.id = j+1;
								viewError.setAttribute("type", "button");
								viewError.style="border:solid; float: right; margin-right: 15px";
								
								viewError.onclick = function(){
									var eText = document.getElementById("question"+ this.id);
									var questions = document.getElementsByClassName("questions")[0].childNodes;

									if(eText.hidden){
										eText.hidden = false;
										this.innerHTML = "See other questions";
										
										for(var i = 5; i < questions.length; i++){
											if (this.id != questions[i].getAttribute("value"))
												questions[i].hidden = true;
										}
										
									} else {
										eText.hidden = true;
										this.innerHTML = "See Error";
										for(var i = 5; i < questions.length; i++){
											if (this.id != questions[i].getAttribute("value"))
												questions[i].hidden = false;
										}
									}
								};
								viewError.innerHTML = "See Error";
							}
							
						question.appendChild(title);
						question.appendChild(viewStat);
						question.appendChild(br);
						if(contestQuests[j]['error'] == ''){
							question.appendChild(viewQuest);
							question.appendChild(br);
							question.appendChild(questionText);
						} else {
							question.appendChild(viewError);
							question.appendChild(br);
							question.appendChild(errorText);
						}
						if(contestQuests[j]['viewStatus'] == viewStatus[6] || contestQuests[j]['viewStatus'] == viewStatus[5]){
							viewQuest.hidden = true;
						}
					questionsDiv.appendChild(question);
				}
				
				if(allQuestSubmitted())
					allSubmitModal();
				
			}
			
			// Check if all questions are submitted
			function allQuestSubmitted(){
				var questions = document.getElementsByClassName("questions")[0].childNodes;
				for (var i = 5; i < questions.length; i++){
					var viewStat = questions[i].childNodes;
					if(viewStat[1].innerHTML != viewStatus[5] && viewStat[1].innerHTML != viewStatus[6])
						return false;
				}
				return true;
			}
			
			// Display an all questions submitted modal
			function allSubmitModal(){
				$('#myModal').modal('toggle');
				var modal = document.getElementsByClassName("modal-body")[0].childNodes;
				var p = document.createElement("p");
				var i = 5;
				modal[1].innerHTML = "All Questions Submitted Successfully!";
				modal[3].src = "http://njit1.initiateid.com/images/green.png";
				modal[3].style = "width:100px;height:100px;margin-left:33%;";
				setInterval(function(){
					if(i == 0)
						location.replace("http://njit1.initiateid.com");
					
					p.innerHTML = "You wil be redirected to the home-page in " + i;
					modal[1].appendChild(p);
					i--;
				}, 1000);
			}
			
			function changeQuestion(div){
				var questions = document.getElementsByClassName("questions")[0].childNodes;
				var editorVal = editor.getSession().getValue(); //Current editor value
				var io = document.getElementsByClassName("io")[0].childNodes;
				var controls = document.getElementsByClassName("controls")[0].childNodes;
				var viewStat, viewStatusString;
				
				// Get the div with a viewing or in-progess status
				for (var i = 5; i < questions.length; i++){
					
					viewStat = questions[i].childNodes;
					if(viewStat[1].innerHTML == viewStatus[0] || viewStat[1].innerHTML == viewStatus[4] || viewStat[1].innerHTML == viewStatus[6])
						break;	
				}
				
				//Viewing page (unsubmitted or submitted)
				if(div.childNodes != viewStat){
					switch(viewStat[1].innerHTML){
						case viewStatus[0]:
						case viewStatus[4]:
							var started = contestQuests[viewStat[2].id - 1]['started'];
							
							if(editorVal == contestQuests[viewStat[2].id - 1]['code'] && started == 'false')
								viewStatusString = viewStatus[2];
							else {
								viewStatusString = viewStatus[3];
								started = 'true';
							}
							break;
							
						case viewStatus[6]:
							viewStatusString = viewStatus[5];
							break;
					}
				
				$.ajax({
					url: "contest-front.php?unit=" + <?php echo $cID?>,
					method: "POST",
					data: {
						codeAns: editorVal,
						languageAns: controls[3].getAttribute("value"),
						answer_id_prev: viewStat[2].id - 1,
						viewStat_next: viewStatusString,
						answer_id_next: div.getAttribute("value") - 1,
						questionStart : started
					},
					success: function(data){
						location.replace("http://njit1.initiateid.com/contest-front.php?unit=" + <?php echo $cID?>);			
					}
				});
				}
			}

			function submitOneAnswer(){
				var submit_id, get_next_id;
				var answer = {};
				var controls = document.getElementsByClassName("controls")[0].childNodes;

				for(var i = 0; i < contest_length; i++){
					
					if(contestQuests[i]['viewStatus'] == viewStatus[0] || contestQuests[i]['viewStatus'] == viewStatus[4]){
						submit_id = i;
						answer['qid'] = contestQuests[i]['qid'];
						answer['code'] = editor.getSession().getValue();
						answer['language'] = controls[3].getAttribute("value");
						break;
					}
				}

				get_next_id = get_next_unsubmit(submit_id);

				$.ajax({
					url: "contest-front.php?unit=" + <?php echo $cID?>,
					method: "POST",
					data: {
						codeAns: answer['code'],
						languageAns: answer['language'],
						qidAns: answer['qid'],
						answer_id_prev: submit_id,
						viewStat_next: viewStatus[5],
						answer_id_next: get_next_id,
						questionStart : 'true',
						sent_code: 'single' // Signifies a single submission
						
					},
					success: function(data){
						location.replace("http://njit1.initiateid.com/contest-front.php?unit=" + <?php echo $cID?>);
					}
				});
			}
			
			function showHints(el){
				var hints = document.getElementById("hints");
				var questions = document.getElementById("questions");
				if(hints.hidden){
					hints.hidden = false;
					el.innerHTML = "Return to Questions";
					$(el).css("width", "15%");
					questions.hidden = true;
				} else {
					hints.hidden = true;
					el.innerHTML = "Help";
					$(el).css("width", "5%");
					questions.hidden = false;
				}
			}
			
			function changeEditor(el){
				var controls = document.getElementsByClassName("controls")[0].childNodes;
				switch(el.value){
					case 'java':
						editor.getSession().setMode("ace/mode/"+el.value);
						controls[3].setAttribute("value", el.value+"/output");
						break;
					
					case 'python':
						editor.getSession().setMode("ace/mode/"+el.value);
						controls[3].setAttribute("value", el.value+"/test");
						break;
				}
				
			}
			
			// Get the next unsubmitted question. Return the closest submission if all are submitted
			function get_next_unsubmit(id){
				if(id + 1 == contest_length){
					for(var i = 0; i < contest_length - 1; i++){ 
						if(contestQuests[i]['viewStatus'] != viewStatus[5])
							return i;
					}
					return 0;
				}
				
				for(var i = 0; i < contest_length; i++){
					if(id == i)
						continue;
						
					if(contestQuests[i]['viewStatus'] != viewStatus[5])
						return i;
				}
				return id + 1;
			}
			
			function submitAllAnswers(){
				var sendArr = {};
				var QA_array = [];
				var controls = document.getElementsByClassName("controls")[0].childNodes;
				sendArr['contestID'] = <?php echo $cID; ?>;
				sendArr['teamID'] = <?php echo $tID_int; ?>;
				
				for(var i = 0; i < contest_length; i++){
					var answer = {};
					// Get the information from the viewing page. Otherwise get all unsubmitted codes.
					if(contestQuests[i]['viewStatus'] == viewStatus[0] || contestQuests[i]['viewStatus'] == viewStatus[4]){
						answer['qid'] = contestQuests[i]['qid'];
						answer['code'] = editor.getSession().getValue();
						answer['language'] = controls[3].getAttribute("value");
						QA_array.push(answer);
						
					} else if (contestQuests[i]['viewStatus'] != viewStatus[5] && contestQuests[i]['viewStatus'] != viewStatus[6]) {
						answer['qid'] = contestQuests[i]['qid'];
						answer['language'] = contestQuests[i]['language'];
						answer['code'] = contestQuests[i]['code'];
						QA_array.push(answer);
					}
				}
				sendArr["answers"] = QA_array;
				
				// If there are no unsubmitted code, display a message to redirect to the home-page.
				// Otherwise, send the codes to the grader.
				if(sendArr['answers'].length == 0)
					allSubmitModal();
				else {
					$.ajax({
						url: "http://njit1.initiateid.com/middleware/contest-grading.php",
						method: "POST",
						data: sendArr,
						success: function(data){
							setTimeout(function(){
							var obj = JSON.parse(data);
							var modal = document.getElementsByClassName("modal-body")[0].childNodes;
							
							// Display an error message if one or more submission fails. Otherwise,
							// display a success message
							if(obj['stat'] != ''){
								var modal_foot = document.getElementById("modal-footer");
								modal[1].innerHTML = "Submissions Failed";
								modal[3].src = "http://njit1.initiateid.com/images/redX.png";
								modal[3].style = "width:100px;height:100px;margin-left:33%;";
								modal_foot.hidden = false;
							} else {
								var p = document.createElement("p");
								var i = 5;
								modal[1].innerHTML = "Submissions Success";
								modal[3].src = "http://njit1.initiateid.com/images/green.png";
								modal[3].style = "width:100px;height:100px;margin-left:33%;";
								setInterval(function(){
									if(i == 0)
										location.replace("http://njit1.initiateid.com");
									
									p.innerHTML = "You wil be redirected to the home-page in " + i;
									modal[1].appendChild(p);
									i--;
								}, 1000);
							}
							}, 1000);
						}
					});
				}
			}
			
			// Function that should resubmit the codes when retry is clicked.
			function reSubmitAll(){
				var modal = document.getElementsByClassName("modal-body")[0].childNodes;
				var modal_foot = document.getElementById("modal-footer");
					modal[1].innerHTML = "Submissions in Prograss";
					modal[3].src = "http://njit1.initiateid.com/images/loading.gif";
					modal[3].style = "width:100px;height:170px;margin-left:33%;top:-20%";
					modal_foot.hidden = true;
				submitAllAnswers();
			}
			/*function showFin(){
				var fin = document.getElementById("fin_bar");
				if(fin.hidden)
					fin.hidden = false;
				else
					fin.hidden = true;
			}*/
		
		
		</script>
    </body>
</html>
