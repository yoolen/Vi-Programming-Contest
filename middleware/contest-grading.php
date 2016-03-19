<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/compilation/classes.php');
/*require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/grade.php');

$answer_array = $_POST['answers'];
$contestID = $_POST['contest_PK'];
$userID = $_POST['userID'];
	$teamID = User :: get_teamid($userID);

$result_array = array('contest_PK' => $contestID, 'team_PK' => $teamID, 'score' => 0);
	
foreach($answer_array as $team_answer){
	//$actual_answer_list = Grade :: get_answers($team_answer['question_PK']);
	
	foreach($actual_answer_list as $actual_answer){
		$compiler_result = sendToCompiler($team_answer['language'], $team_answer['code'], $actual_answer['input']);
		
		$compiler_output = trim( $compiler_result['output'] );
		
		if( equal($compiler_output, $actual_answer['answer']) == 0)
			++$result_array['score'];
			
	}
	
}


function sendToCompiler($language, $code, $input){
	$request = json_encode( new Request($language, $code, $input, '') );

	$ch = curl_init('http://cs490.iidcct.com/comp/evaluate.php');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($request))
				);
				
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	
	return $result;
}
*/
function equal($team_answer, $actual_answer){
	return(strcmp($team_answer, $actual_answer));
}

$ans = 'print("Hello")';

//$editans = preg_replace('~[\t\n]+~', '', $ans);

$request = json_encode( new Request('python/output', $ans,'','') );

$ch = curl_init('http://cs490.iidcct.com/comp/evaluate.php');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($request))
				);
$result = json_decode(curl_exec($ch), true);
	curl_close($ch);

var_dump($result['output']);
//print_r(equal(trim($result['output']), 'java'));

/*
function grade($infoArray){
	$studentAnswers = json_decode($infoArray['data'], true);
	$timeout = 0;
	$a = array();
	foreach($studentAnswers as $sanswer){

		#This sends the question and get the actual answer for the question.
		$actualAns = getanswer($sanswer['qid']); 
		
		// If there are any inputs, then the question is a programming question
		if ($actualAns['input'] != ''){
			// If 'public' is in the answer, then its a Java question
			if ( preg_match('/public/', $sanswer['answer']) == true ){
			
				$sanswer['answer'] = preg_replace('~[\t\n]+~', '', $sanswer['answer']);
				$request = json_encode( new Request('java/output', $sanswer['answer'], $actualAns['input']) );
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
			
			} else if ( preg_match('/def/', $sanswer['answer']) == true ){ // Python question
			
				$request = json_encode( new Request('python/test', $sanswer['answer'], $actualAns['input']) );
				$ch = curl_init('http://njit1.initiateid.com/compilation/evaluate.php');
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($request))
				);
				$result = json_decode(curl_exec($ch));
				curl_close($ch);
			}
			$correct = (strcmp($result->output,$actualAns['answer']))==0 ? 1 : 0;
			
		} else { //Multiple Choice, fill-in or True/False
			$correct = (strcmp($sanswer['answer'],$actualAns['answer']))==0 ? 1 : 0;
		}

		$status = $actualAns['pointval'] * $correct;
		$response = getfeedback($sanswer['qid'], $sanswer['answer'], $correct);

		// Array to send to the back-end
		$sendArray = array(
			'sid' => $infoArray['sid'],
			'qid' => $sanswer['qid'],
			'eid' => $infoArray['eid'],
			'sans' => $sanswer['answer'],
			'feedback' => $response, // added this
			'pointval' => $status
		);

		// Send sendArray to the back-end
		while( !setgrade($sendArray) ){
			$timeout++;
				if($timeout == 5) return 0;
		} 
	}
	
	$sendFinal = array(
		'eid' => $sendArray['eid'],
		'sid' => $sendArray['sid'],
		'starttime' => $infoArray['timestarted'],
		'endtime' => $infoArray['timesubmitted']
	);
	
	setfinalgrade($sendFinal);
	return 1;
}
*/
?>