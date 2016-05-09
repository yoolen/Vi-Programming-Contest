<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";

$folder = $_POST['folder'];


$result = File_Functions::create_folder($_SESSION['uid'], 0, 0, $folder);

if ($result) {
    echo $result;
} else {
    echo "0";
}
?>