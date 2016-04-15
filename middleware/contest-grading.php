<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/compilation/classes.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/grade.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/submission.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');

$contestID = $_POST['contestID'];
$teamID = $_POST['teamID'];

//echo json_encode($_POST['answers']);

if($_POST['sent_code'] == 'single'){
	$answer_array = array(
		'code' => $_POST['codeAns'],
		'language' => $_POST['language'],
		'qid' => $_POST['qid'],
		'seq' => $_POST['sequencenum']
	);
	gradeOneQuest($teamID, $contestID, $answer_array);
	//echo $result;
} else {

	gradeMultipleQuest($teamID, $contestID, $_POST['answers']);
	//echo $result;
}
	
function gradeOneQuest($teamID, $contestID, $answer_array){
	$submission_stat = Submission::add_submission($answer_array['qid'], $teamID, $answer_array['code']);
	if($submission_stat == 0)
		return $submission_stat;
	
	echo $submission_stat;
	
	$score = 0;
	$actual_answer_list = Question :: get_answers($answer_array['qid']);
	$front_array = array('qid' => $answer_array['qid'], 'seq' => $answer_array['seq'], 'test_cases' => array());
	
	foreach($actual_answer_list as $actual_answer){
		if($actual_answer['input'] == 0)
			$input = '';
		else
			$input = $actual_answer['input'];
			
		$test_case = array('expected_answer' => '', 'user_answer' => '', 'status' => '');
		
		$compiler_result = sendToCompiler($answer_array['language'], $answer_array['code'], $input);
		$compiler_output = trim( $compiler_result['output'] );
		
		$test_case['expected_answer'] = $actual_answer['output'];
		$test_case['user_answer'] = $compiler_output;
		
		if( equal($compiler_output, $actual_answer['output']) == 0){
			++$score;
			$test_case['status'] = 'Correct';
		} else {
			$test_case['status'] = 'Incorrect';
		}
		
		$front_array['test_cases'][] = $test_case;
		
		$grade = Grade :: set_grade($submission_stat, $actual_answer['qio_PK'], $score);
		
		$score = 0;
	}

	echo json_encode($front_array);
}

function gradeMultipleQuest($teamID, $contestID, $answer_array){
	$score = 0;
	$front_array = array();
	
	$submission_array = array();
	foreach($answer_array as $team_answer){
		$submission_stat = Submission::add_submission($team_answer['qid'], $teamID, $team_answer['code']);
		if($submission_stat == 0)
			return $submission_stat;
		$submission_array[] = $submission_stat;
	}
	
	for($i = 0; $i < count($submission_array); $i++){
		$actual_answer_list = Question :: get_answers($answer_array[$i]['qid']);
	
		foreach($actual_answer_list as $actual_answer){
			if($actual_answer['input'] == 0)
				$input = '';
			else
				$input = $actual_answer['input'];
				
			$test_case = array('expected_answer' => '', 'user_answer' => '', 'status' => '');
			$compiler_result = sendToCompiler($answer_array[$i]['language'], $answer_array[$i]['code'], $input);
			
			$compiler_output = trim( $compiler_result['output'] );
			
			$test_case['expected_answer'] = $actual_answer['output'];
			$test_case['user_answer'] = $compiler_output;
			
			if( equal($compiler_output, $actual_answer['output']) == 0){
				++$score;
				$test_case['status'] = 'Correct';
			} else {
				$test_case['status'] = 'Incorrect';
			}
			
			$FA_main['test_cases'][] = $test_case;
			$front_array[] = $FA_main;
			
			$grade = Grade :: set_grade($submission_stat, $actual_answer['qio_PK'], $score);
			$score = 0;
		}
	}		
	echo json_encode($front_array);
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

function equal($team_answer, $actual_answer){
	return(strcmp($team_answer, $actual_answer));
}
?>