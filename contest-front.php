<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
    header("Location: login.php");
}

require_once ($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
	$cID = $_GET['unit'];
	$tID_int = User::get_teamid($_SESSION['uid']);
	$teamID = "team".(string) $tID_int;
	
	
	if (!isset($_SESSION[$teamID])){
		$code = <<<EOF
/***************************************
* NJIT High School Programming Contest *
* Java - Code Imaginarium.			   *
****************************************/

public static void main(String[] args) {
	System.out.println("Hello World!");
}
EOF;
		$contestqs = Contest::get_contest_questions($cID);
		$_SESSION['viewStatus'] = array('Viewing','Not viewed yet', 'Viewed but not started yet', 'Viewed and Started', 'In Progress');
		$user_answers = array();
		for($i = 0; $i < count($contestqs); $i++){
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
				'qtext' => $contestqs[$i]['qtext']
			);
			$user_answers[] = $arr;
		}
		$_SESSION[$teamID] = $user_answers;
	
	} else {
		if(isset($_POST['answer_id_prev']))
			$prev = $_POST['answer_id_prev'];
		if(isset($_POST['answer_id_next'])){
			$next = $_POST['answer_id_next'];
			$_SESSION[$teamID][$next]['viewStatus'] = $_SESSION['viewStatus'][0];
		}
		
		if(isset($_POST['viewStat_next']))
			$_SESSION[$teamID][$prev]['viewStatus'] = $_POST['viewStat_next'];
		if(isset($_POST['codeAns']))
			$_SESSION[$teamID][$prev]['code'] = $_POST['codeAns'];
		if(isset($_POST['languageAns']))
			$_SESSION[$teamID][$prev]['language'] =  $_POST['languageAns'];
		if(isset($_POST['questionStart']) && $_POST['questionStart'] == 'true')
			$_SESSION[$teamID][$prev]['started'] = $_POST['questionStart'];
		
		
		
		//print_r($_SESSION[$teamID]);
	}
	
require_once "compilation/classes.php";
require_once "compilation/helper.php";
if (isset($_POST['code'])) {

    $code = $_POST['code'];
    $lang = $_POST['language'];
    $inputs = $_POST['inputs'];
		$qidIndex = $_POST['qid'] - 1;
		$_SESSION[$teamID][$qidIndex]['code'] = $_POST['code'];
		$_SESSION[$teamID][$qidIndex]['language'] = $_POST['language'];
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
                width: 40%;
            }
            #editor {
                width:60%;                
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
                bottom: -1vh;
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
                bottom: -1vh;
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
				width: 40%;
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
				<div id="fin_bar" style="width: 60%; float: left; margin-left: 15%; height: 10%; overflow: hidden">
					<div id="submit" type = "button" onclick="submitAnswers()" style="border:solid; width: 12%; height: 56px; text-align: center; font-size: 20px; margin-top:5px">Submit</div>
					<div id="clear" type = "button" onclick="clearEditor()" style="float: left; margin-left: 35%; border:solid; width: 12%; height: 56px; text-align: center; font-size: 20px; margin-top: -56px">Clear Editor</div>
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
                            <input onclick="clearEditor()" type="button" value="Clear" class="bigbuttons btn-default" >

                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script type="text/javascript">
			var contestQuests = <?php echo json_encode($_SESSION[$teamID]); ?>;
			
			if(typeof contestQuests === 'object'){
				var arr = [];
				//contestQuests = JSON.parse(contestQuests);
				for(var key in contestQuests){
					if(key === "")
						continue;
					arr.push(contestQuests[key]);
				}
				contestQuests = arr;
			}

			var contest_length = contestQuests.length;
			var viewStatus = <?php echo json_encode($_SESSION['viewStatus']); ?>;
			
            var i = 0;
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
            //editor.getSession().setMode("ace/mode/java");
            editor.setFontSize(16);
			editor.$blockScrolling = Infinity;
			
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
				//hourCheck(<?php echo $cID; ?>, 'on-time');
				
				var questionsDiv = document.getElementById("questions");

				for(var j = 0; j < contest_length; j++){
					var question = document.createElement('li');
					var title = document.createElement('span');
					var br = document.createElement('br');
					var viewStat = document.createElement('span');
					var viewQuest = document.createElement('span');
					var questionText = document.createElement('span');
					
						question.id = contestQuests[j]['qid'];
						question.setAttribute("type", "button");
						question.onclick = function (){changeQuestion(this)};
						
						question.style = "margin-bottom: 15px";
							
						question.setAttribute("value", contestQuests[j]['sequencenum']);
							title.style = "font-weight:bold; float: left; margin-left: 2px";
							title.innerHTML = contestQuests[j]['sequencenum'] + ". " + contestQuests[j]['title'];

							viewStat.id = "viewStat" + contestQuests[j]['sequencenum'];
							viewStat.innerHTML = contestQuests[j]['viewStatus'];
							viewStat.style = "float: left; margin-left: 15px";
							
							if(contestQuests[j]['viewStatus'] == viewStatus[0] || contestQuests[j]['viewStatus'] == viewStatus[4]){
								var controls = document.getElementsByClassName("controls")[0].childNodes;
								var languageSelector = document.getElementById('languageSelector');
								var language = contestQuests[j]['language'].replace("/output","");
								
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
							}	
							
							questionText.id = "question" + contestQuests[j]['sequencenum'];
							questionText.hidden = true;
							questionText.innerHTML = contestQuests[j]['qtext'];
							questionText.style = "float: left; margin: 20px";
							
							viewQuest.id = contestQuests[j]['sequencenum'];
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
								/*if (!e) var e = window.event;
									e.cancelBubble = true;
								if (e.stopPropagation) e.stopPropagation();*/
							};
							viewQuest.innerHTML = "See full question";
							
							
						question.appendChild(title);
						question.appendChild(viewStat);
						question.appendChild(viewQuest);
						question.appendChild(br);
						question.appendChild(questionText);

					questionsDiv.appendChild(question);
					/*questionsDiv.appendChild(br);
					questionsDiv.appendChild(br);*/
				}

				
			}
			
			function changeQuestion(div){
				var questions = document.getElementsByClassName("questions")[0].childNodes;
				var editorVal = editor.getSession().getValue(); //Current editor value
				var io = document.getElementsByClassName("io")[0].childNodes;
				var controls = document.getElementsByClassName("controls")[0].childNodes;
				var viewStat, viewStatusString;

				//
				// Get the div with a viewing or in-progess status
				for (var i = 5; i < questions.length; i++){
					
					viewStat = questions[i].childNodes;
					if(viewStat[1].innerHTML == viewStatus[0] || viewStat[1].innerHTML == viewStatus[4])
						break;	
				}
				
				//Viewing page
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
					}
				
				$.ajax({
					url: "contest-front.php?unit=" + <?php echo $cID?>,
					method: "POST",
					global: false,
					data: {
						codeAns: editorVal,
						languageAns: controls[3].getAttribute("value"),
						//input: io[1].innerHTML,
						//output: io[3].innerHTML,
						answer_id_prev: viewStat[2].id - 1,
						viewStat_next: viewStatusString,
						answer_id_next: div.getAttribute("value") - 1,
						questionStart : started
					},
					success: function(data){
						window.location = "http://njit1.initiateid.com/contest-front.php?unit=" + <?php echo $cID?>;			
					}
				});
				}
			}

			function submitAnswers(){
				var sendArr = {};
				var QA_array = [];
				
				sendArr['contestID'] = <?php echo $cID; ?>;
				sendArr['teamID'] = <?php echo $tID_int; ?>;
				
				for(var i = 0; i < contest_length; i++){
					var answer = {};
					answer['qid'] = contestQuests[i]['qid'];
					if(contestQuests[i]['viewStatus'] == viewStatus[0] || contestQuests[i]['viewStatus'] == viewStatus[4]){
						var controls = document.getElementsByClassName("controls")[0].childNodes;
						answer['code'] = editor.getSession().getValue();
						answer['language'] = controls[3].getAttribute("value");
					} else {
						answer['language'] = contestQuests[i]['language'];
						answer['code'] = contestQuests[i]['code'];
					}
					QA_array[i] = answer;
				}
				
				sendArr["answers"] = QA_array;
				//console.log(sendArr);
				$.ajax({
					url: "http://njit1.initiateid.com/middleware/contest-grading.php",
					method: "POST",
					data: sendArr,
					success: function(data){
						console.log(data);
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
				//var select = document.getElementById("languageSelector");
				var controls = document.getElementsByClassName("controls")[0].childNodes;
				editor.getSession().setMode("ace/mode/"+el.value);
				controls[3].setAttribute("value", el.value+"/output");
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
