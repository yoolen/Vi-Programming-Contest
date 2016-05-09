<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";

$name = $_POST['filename'];
$ext = $_POST['extension'];
$fileId = $_POST['fileId'];

$result = File_Functions::rename_file($fileId, $name, $ext);

if ($result) {
    echo $result;
} else {
    echo "0";
}
?>