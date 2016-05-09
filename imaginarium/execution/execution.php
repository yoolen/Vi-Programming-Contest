<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/imaginarium/execution/imaginarium-collection.php";

$folder = $_POST['folder'];
$runnable = $_POST['runnable'];
$arguments = $_POST['arguments'];
$watch = $_POST['watch'];

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

$result = json_decode(curl_exec($curlRequest));
if($result->compileResult) {
    echo "<h4>Compile Succeeded</h4>";
    echo "<h5>Compile Time: ".$result->compileTime."ms</h5>";
    if($result->runResult) {
        echo "<h4>Run Succeeded</h4>";
        echo "<h5>Run Time: ".$result->runTime."ms</h5>";
    } else {
        echo "<h4>Run Failed</h4>";
        echo "<h5>Run Time: ".$result->runTime."ms</h5>";
    }
} else {
    echo "<h4>Compile Succeeded</h4>";
    echo "<h5>Compile Time: ".$result->compileTime."ms</h5>";
}
echo "<textarea style=\"height: 400px; width: 400px;\">".$result->output."</textarea>";
?>
