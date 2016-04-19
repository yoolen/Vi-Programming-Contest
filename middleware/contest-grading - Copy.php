<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/compilation/classes.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/grade.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
/*
$answer_array = $_POST['answers'];
$contestID = $_POST['contestID'];
$teamID = $_POST['teamID'];
$scores = 0;

$result_array = array('contest_PK' => $contestID, 'team_PK' => $teamID, 'score' => 0);
	
foreach($answer_array as $team_answer){
	//$actual_answer_list = Grade :: get_answers($team_answer['qid']);
	
	foreach($actual_answer_list as $actual_answer){
		$compiler_result = sendToCompiler($team_answer['language'], $team_answer['code'], $actual_answer['input']);
		
		$compiler_output = trim( $compiler_result['output'] );
		
		if( equal($compiler_output, $actual_answer['output']) == 0)
			++$scores;
			
	}
	if($scores === count($actual_answer_list))
		++$result_array['score'];
	$scores = 0;
}
*/
/*
$answer_array = array(
	array(
		'code' => <<<EOF
/***************************************
* NJIT High School Programming Contest *
* Java - Code Imaginarium.			   *
****************************************

public static void main(String[] args) {
	int x = 0;
	for(String i : args){
		x += Integer.parseInt(i);
	}
	System.out.println(x);
}
EOF
,
		'language' => "java/output",
		'qid' => "48"
	)
);
$contestID = 1;
$teamID = 1;
$scores = 0;
$result_array = array('contest_PK' => $contestID, 'team_PK' => $teamID, 'score' => 0);

foreach($answer_array as $team_answer){
	$actual_answer_list = Grade :: get_answers($team_answer['qid']);
	
	foreach($actual_answer_list as $actual_answer){
		$compiler_result = sendToCompiler($team_answer['language'], $team_answer['code'], $actual_answer['input']);
		
		$compiler_output = trim( $compiler_result['output'] );
		
		if( equal($compiler_output, $actual_answer['output']) == 0)
			++$scores;
			
	}
	if($scores === count($actual_answer_list))
		++$result_array['score'];
	
	$scores = 0;
}


echo json_encode($result_array);
*/
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
/*
$answer_array = $_POST['answers'];
$contestID = $_POST['contestID'];
$teamID = $_POST['teamID'];
$scores = 0;
$result_array = array('contest_PK' => $contestID, 'team_PK' => $teamID, 'score' => 0);
//print_r($_POST);
foreach($answer_array as $team_answer){
	$actual_answer_list = Grade :: get_answers(48);
	foreach($actual_answer_list as $actual_answer){
		$compiler_result = sendToCompiler($team_answer['language'], $team_answer['code'], $actual_answer['input']);
		
		$compiler_output = trim( $compiler_result['output'] );

		if( equal($compiler_output, $actual_answer['output']) == 0)
			++$scores;		
	}
	if($scores === count($actual_answer_list))
		++$result_array['score'];
	
	$scores = 0;
}

echo json_encode($result_array);*/
/*$actual_answer_list = Grade :: get_answers(50);
print_r($actual_answer_list);*/

$q = Question :: get_answers(61);
print_r($q);
?>