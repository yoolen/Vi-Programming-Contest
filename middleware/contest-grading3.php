<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/imaginarium/execution/imaginarium-collection.php";
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/grade.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/submission.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/imaginarium/execution/imaginarium-collection.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

switch($_POST['type']){
	case 'sub one':
		$info = File_Functions :: retrieve_file($_POST['fileID']);
		submitOne($_POST['qid'], $_POST['teamID'], $info);
		break;
		
	case 'sub multi':
		submitMulti($_POST['contestID'], $_POST['teamID']);
		break;
		
	case 'grade one':
		$contestID = $_POST['contestID'];
		$teamID = $_POST['teamID'];
		$sub = $_POST['sub'];
		$answer_array = array(
			'folder' => $_POST['info']['folder'],
			'file' => $_POST['info']['name'].'.'.$_POST['info']['extension'],
			'watch' => '',
			'qid' => $_POST['qid']
		);
		
		gradeOneQuest($teamID, $contestID, $answer_array, $sub);
		
		//echo json_encode($_POST);
		break;
		
	case 'grade multi':
		preGradingSetup($_POST['contestID'], $_POST['teamID'], $_POST['subs']);
		break;
};

function submitOne($qid, $tID, $info){
	$submission_stat = Submission::add_submission($qid, $tID, $info['content']);
	
	if(!is_numeric($submission_stat)){
		echo json_encode(array('qid' => $qid, 'stat' => $submission_stat));
	
	} else {
		echo json_encode(array('qid' => $qid, 'stat' => $submission_stat, 'info' => $info));
	}
}

function submitMulti($cID, $tID){
	//$teams = Contest :: get_contest_teams($cID);
	$questions = Contest :: get_contest_questions($cID);
	$sub_array = array();

	// Get an array of unsubmitted questions 
	$newQuests = filter_questions($questions, Submission :: get_submissions_by_team_and_contest($tID, $cID));

	foreach($newQuests as $quest){				
		$folderID = File_Functions :: get_folder_for_question_team($tID, $quest);
		$fileID = File_Functions :: first_file($folderID);
		$info = File_Functions :: retrieve_file($fileID);
		
		$submission_stat = Submission::add_submission($quest, $tID, $info['content']);
		
		if(!is_numeric($submission_stat))
			return json_encode(array('stat' => $submission_stat));
		
		$sub_array[] = $submission_stat;
	}
	
	echo json_encode(array('stat' => '', 'subs' => $sub_array));
}

function preGradingSetup($cID, $tID, $subs){
	//$teams = Contest :: get_contest_teams($cID);
	$questions = Contest :: get_contest_questions($cID);
	
	// Get an array of unsubmitted questions 
	$newQuests = filter_questions($questions, Submission :: get_submissions_by_team_and_contest($tID, $cID));
	
	$answer_array = array(); // Array of answers for each question
	foreach($newQuests as $quest){
		$quest_answer = array(
			'folder' => '',
			'file' => '',
			'watch' => '',
			'qid' => ''
		);
		
		$folderID = File_Functions :: get_folder_for_question_team($tID, $quest);
		$fileID = File_Functions :: first_file($folderID);
		$info = File_Functions :: retrieve_file($fileID);
		
		$quest_answer['qid'] = $quest;
		$quest_answer['folder'] = $info['folder'];
		$quest_answer['file'] = $info['name'].'.'.$info['extension'];
		
		$answer_array[] = $quest_answer;
	}
		
		gradeMultipleQuest($tID, $cID, $answer_array, $subs);
		
	
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

function gradeOneQuest($teamID, $contestID, $answer_array, $submission_stat){

	$actual_answer_list = Question :: get_answers($answer_array['qid']);
	
	foreach($actual_answer_list as $actual_answer){
		$input = $actual_answer['input'];
		
		$compiler_result = sendToCompiler($answer_array['folder'], $answer_array['file'], $answer_array['watch'], $input);
		$compiler_output = trim($compiler_result['output']);

		if( equal($compiler_output, $actual_answer['output']) == 0)
			$grade = Grade :: set_grade($submission_stat, $actual_answer['qio_PK'], 1, $compiler_output);
		else
			$grade = Grade :: set_grade($submission_stat, $actual_answer['qio_PK'], 0, $compiler_output);
		/*
		while($grade != true)
			$grade = Grade :: set_grade($submission_stat, $actual_answer['qio_PK'], $score, $compiler_output);
		*/
	}
}

function gradeMultipleQuest($teamID, $contestID, $answer_array, $submission_array){
	$score = 0;
	$total_score = 0;
	
	// This is for initializing the team score from all the submitted codes from before.
	$team_subs = Submission :: get_submissions_by_team_and_contest($teamID, $contestID);
		
		foreach($team_subs as $team_sub){
			$contest_ans = Question :: get_answers($team_sub['question_FK']);
			$tally_score = 0;
			
			foreach($contest_ans as $ans){
				$team_ans = Submission :: get_answer($teamID, $ans['qio_PK']);
				if($team_ans['grade'] == 0)
					break;
				++$tally_score;
			}
			
			if($tally_score == count($contest_ans))
				++$total_score;
		}
		$stat = Grade :: add_score($contestID, $teamID, $total_score);
	
	// This loop grades the codes that were not previously graded.
	for($i = 0; $i < count($submission_array); $i++){
		
		$actual_answer_list = Question :: get_answers($answer_array[$i]['qid']);
	
		foreach($actual_answer_list as $actual_answer){
			$input = $actual_answer['input'];
			
			$compiler_result = sendToCompiler($answer_array[$i]['folder'], $answer_array[$i]['file'], $answer_array[$i]['watch'], $input);
			
			$compiler_output = trim( $compiler_result['output'] );

			if( equal($compiler_output, $actual_answer['output']) == 0){
				$grade = Grade :: set_grade($submission_array[$i], $actual_answer['qio_PK'], 1, $compiler_output);
				++$score;
			}
			else
				$grade = Grade :: set_grade($submission_array[$i], $actual_answer['qio_PK'], 0, $compiler_output);
		}
		
		if($score == count($actual_answer_list)){
			$stored_score = Grade :: get_score($teamID, $contestID);
			$stat = Grade :: update_score($contestID, $teamID, $stored_score + 1);
		}
		
		$score = 0;
	}
	
}

function sendToCompiler($folder, $runnable, $watch, $arguments){
	$files = array();
	$folderEntries = File_Functions::retrieve_folder_files($folder);
	$folderData = "";
	foreach ($folderEntries as $f) {
		$folderData = File_Functions::get_folder_data_from_fileId($folderEntries[0]['fileId']);
		$fileContent = File_Functions::retrieve_file($f['fileId'])['content'];
		$fileToAdd = new File($f['name'], $f['ext'], $fileContent);
		$files[] = $fileToAdd;
	}
	$folderObject = new Folder($folderData['name'], $files);

	$request = new Request($folderObject, $runnable, $watch, $arguments);

	$requestJSON = json_encode($request);

	$curlRequest = curl_init('http://cs490.iidcct.com/491exec/execute.php');
	curl_setopt($curlRequest, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $requestJSON);
	curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curlRequest, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($requestJSON))
	);

	$result = json_decode(curl_exec($curlRequest), true);
	
	curl_close($curlRequest);
	
	return $result;
}

function equal($team_answer, $actual_answer){
	return(strcmp($team_answer, $actual_answer));
}
?>