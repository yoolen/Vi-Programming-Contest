<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/compilation/classes.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/grade.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/submission.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/files.php');


switch($_POST['type']){
	case 'sub one':
		$info = File_Functions :: retrieve_file($_POST['fileID']);
		submitOne($_POST['qid'], $_POST['teamID'], $info['content']);
		break;
		
	case 'sub multi':
		submitMulti($_POST['contestID']);
		break;
		
	case 'grade one':
		$contestID = $_POST['contestID'];
		$teamID = $_POST['teamID'];
		$sub = $_POST['sub'];
		$answer_array = array(
			'code' => $_POST['code'],
			'language' => $_POST['language'],
			'qid' => $_POST['qid']
		);
		
		gradeOneQuest($teamID, $contestID, $answer_array, $sub);
		
		break;
		
	case 'grade multi':
		preGradingSetup($_POST['contestID'], $_POST['subs']);
		break;
};

function submitOne($qid, $tID, $code){
	$submission_stat = Submission::add_submission($qid, $tID, $code);
	
	if(!is_numeric($submission_stat)){
		echo json_encode(array('qid' => $qid, 'stat' => $submission_stat));
	
	} else {
		echo json_encode(array('qid' => $qid, 'stat' => $submission_stat, 'code' => $code));
	}
}

function submitMulti($cID){
	$teams = Contest :: get_contest_teams($cID);
	$questions = Contest :: get_contest_questions($cID);
	$sub_array = array();
	
	//echo json_encode(array('stat' => 'jhvjkvkvklvbkujhgujju', 'subs' => $sub_array));
	
	foreach($teams as $team){
		// Get an array of unsubmitted questions 
		$newQuests = filter_questions($questions, Submission :: get_submissions_by_team_and_contest($team['team_FK'],$cID));
		$sub_array[ $team['team_FK'] ] = array();
		
		foreach($newQuests as $quest){				
			$folderID = File_Functions :: get_folder_for_question_team($team['team_FK'], $quest);
			$fileID = File_Functions :: first_file($folderID);
			$info = File_Functions :: retrieve_file($fileID);
			
			$submission_stat = Submission::add_submission($quest, $team['team_FK'], $info['content']);
			
			if(!is_numeric($submission_stat))
				return json_encode(array('stat' => $submission_stat));
			
			$sub_array[ $team['team_FK'] ][] = $submission_stat;
		}
	}
	
	echo json_encode(array('stat' => '', 'subs' => $sub_array));
}

function preGradingSetup($cID, $subs){
	$teams = Contest :: get_contest_teams($cID);
	$questions = Contest :: get_contest_questions($cID);
	
	foreach($teams as $team){
		// Get an array of unsubmitted questions 
		$newQuests = filter_questions($questions, Submission :: get_submissions_by_team_and_contest($team['team_FK'],$cID));
		
		$answer_array = array(); // Array of answers for each question
		foreach($newQuests as $quest){
			$quest_answer = array('qid' => '', 'code' => '', 'language' => '');
			
			$folderID = File_Functions :: get_folder_for_question_team($team['team_FK'], $quest);
			$fileID = File_Functions :: first_file($folderID);
			$info = File_Functions :: retrieve_file($fileID);
			
			$quest_answer['qid'] = $quest;
			$quest_answer['language'] = languageType($info['extension']);
			$quest_answer['code'] = $info['content'];
			
			$answer_array[] = $quest_answer;
		}
		
		gradeMultipleQuest($team['team_FK'], $cID, $answer_array, $subs[ $team['team_FK'] ]);
		
	}
	
}

function filter_questions($quests, $teamQuests){
	$rtnArr = array();
	$flag = false;
	foreach($quests as $q){
		foreach($teamQuests as $tq){
			if($q['qid'] == $tq['question_FK']){
				$flag = true;
				break;
			}
		}
		if(!$flag)
			$rtnArr[] = $q['qid'];
		$flag = false;
	}
	return $rtnArr;
}

function languageType($ext){
	switch ($ext) {
		case 'java':
			return 'java/output';
		case 'cpp':
			return 'cpp/output';
		case 'py':
			return 'python/test';
		default:
			return 'python/test';
	}
}

	
function gradeOneQuest($teamID, $contestID, $answer_array, $submission_stat){
	$score = 0;
	$actual_answer_list = Question :: get_answers($answer_array['qid']);

	foreach($actual_answer_list as $actual_answer){
		$input = $actual_answer['input'];
		
		$compiler_result = sendToCompiler($answer_array['language'], $answer_array['code'], $input);
		$compiler_output = trim( $compiler_result['output'] );
		
		if( equal($compiler_output, $actual_answer['output']) == 0)
			++$score;

		$grade = Grade :: set_grade($submission_stat, $actual_answer['qio_PK'], $score, $compiler_output);
		/*
		while($grade != true)
			$grade = Grade :: set_grade($submission_stat, $actual_answer['qio_PK'], $score, $compiler_output);
			*/
		$score = 0;
	}
}

function gradeMultipleQuest($teamID, $contestID, $answer_array, $submission_array){
	$score = 0;

	for($i = 0; $i < count($submission_array); $i++){
		
		$actual_answer_list = Question :: get_answers($answer_array[$i]['qid']);
	
		foreach($actual_answer_list as $actual_answer){
			$input = $actual_answer['input'];
			
			$compiler_result = sendToCompiler($answer_array[$i]['language'], $answer_array[$i]['code'], $input);
			
			$compiler_output = trim( $compiler_result['output'] );

			if( equal($compiler_output, $actual_answer['output']) == 0)
				++$score;
			
			$grade = Grade :: set_grade($submission_array[$i], $actual_answer['qio_PK'], $score, $compiler_output);
			$score = 0;
		}
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

function equal($team_answer, $actual_answer){
	return(strcmp($team_answer, $actual_answer));
}
?>