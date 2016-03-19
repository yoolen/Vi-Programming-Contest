<?php
/**
 * CS490 Project Fall 2015
 *
 * Code Evaluation PHP File
 * Determines what operations needs to be done using a language compiler/interpreter and 
 * returns a result accordingly
 *
 * @author Jan Chris Tacbianan
 */

include 'classes.php';
 
function run() {
	$data = file_get_contents('php://input');
	$request = json_decode($data);
	switch($request->type) {
		case "java/output":
			$result = javaSnippet($request);
			responseJava($result);
			return;
		case "java/file":
			//TODO: Evaluation
			return;
		case "python/output":
			pythonSnippet($request);
			return;
		case "python/test":
			pythonInputSnippet($request);
			return;
		case "python/file":
			//TODO: Evaluation
			return;
		case "python/turtle":
			pythonTurtle($request);
			return;
		default: 
			ResponseFactory::resultFactory(true, 0, true, 0, "Invalid Request");
			//Invalid Request. Should not happen if they send the right stuff!
	}
}

function pythonTurtle() {

}

function pipe_exec($cmd, &$stdout=null, &$stderr=null) {
    $proc = proc_open($cmd,[
        1 => ['pipe','w'],
        2 => ['pipe','w'],
    ],$pipes);
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    return proc_close($proc);
}

function pythonSnippet($request) {
	$snippet = $request->code;
	$writeQueue = $snippet;
	$testFile = fopen("testFile.py", "w");
	$writeResult = fwrite($testFile, $writeQueue);
	fclose($testFile);
	$time_start = microtime(true);
	exec("C:\Python34\python.exe testFile.py", $output, $status);
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	$outCont = "";
	foreach($output as $s) {
		$outCont = $outCont.$s."\r\n";
	}
	if($status == 1) {
		ResponseFactory::resultFactory(true, 0, false, $time, $outCont);
	} else {
		ResponseFactory::resultFactory(true, 0, true, $time, $outCont);
	}
}

function pythonInputSnippet($request) {
	$snippet = $request->code;
	$inputs = $request->inputs;
	$execCode = $request->executionCode;
	$writeQueue = $execCode . "\r\n" . $snippet . "\r\n" . $inputs;
	$testFile = fopen("testFile.py", "w");
	$writeResult = fwrite($testFile, $writeQueue);
	fclose($testFile);
	$time_start = microtime(true);
	$stdout;
	$stderr;
	pipe_exec("C:\Python34\python.exe testFile.py", $stdout, $stderr);
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	$outCont = $stderr.$stdout;
	if(!empty($stderr)) {
		ResponseFactory::resultFactory(true, 0, false, $time, $outCont);
	} else {
		ResponseFactory::resultFactory(true, 0, true, $time, $outCont);
	}
}

function javaSnippet($request) {
	$snippet = $request->code;
	$snippet = str_replace('"', '\"', $snippet);
	$snippet = str_replace("\r\n", '', $snippet);
	$inputs = $request->inputs;
	$inputs = preg_replace('!\s+!', ' ', $inputs);
	$com = "\"C:\Program Files\Java\jdk1.8.0_45\bin\java.exe\" -jar javat.jar -s \"".$snippet.'" -i "'.$inputs.'"'." 2>&1";
	$output = exec($com);
	sleep(5);
	$file_handle = fopen("output.txt", "r");
	$values = array();
	$i = 0;
	while (!feof($file_handle)) {
	   $line = fgets($file_handle);
	   $values[$i] = $line;
	   $i = $i + 1;
	}
	fclose($file_handle);
	return $values;
}

function responseJava($output) {
	$compResultStr = substr($output[0], 0, 6);
	$compResult = false;
	if($compResultStr === 'COMPOK') {
		$compResult = true;
	} else {
		$compResult = false;
	}
	$compTimeInd = strpos($output[0], '- Time: ') + 8;
	$compTimeStr = substr($output[0], $compTimeInd, strlen($output[0]));
	$compTime = intval($compTimeStr);
	if($compResult == false) {
		$compOut = stringBuilder($output, 1);
		ResponseFactory::resultFactory(false, $compTime, false, 0, $compOut);
		return;
	}
	$runResultStr = substr($output[1], 0, 5);
	$runResult = false;
	if($runResultStr === 'RUNOK') {
		$runResult = true;
	} else {
		$runResult = false;
	}
	$runTimeInd = strpos($output[1], '- Time: ') + 8;
	$runTimeStr = substr($output[1], $compTimeInd, strlen($output[1]));
	$runTime = intval($runTimeStr);
	$runOut = stringBuilder($output, ($runResult)?2:-1);
	ResponseFactory::resultFactory(true, $compTime, $runResult, $runTime, $runOut);
}



function stringBuilder($arr, $start) {
	$result = "";
	$capt = false;
	foreach($arr as $k => $v) {
		if($k < $start) {
			continue;
		}
		if($start == -1 and !$capt) {
			if(substr($v,0, 10) === "Caused by:") {
				$capt = true;
			} else {
				continue;
			}
		}
		$result = $result.$v;
	}
	return $result;
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	run();
}
?>