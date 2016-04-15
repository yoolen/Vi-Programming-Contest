<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
    header("Location: /login.php");
}

$fileId = $_GET['file'];
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";
$file = File_Functions::retrieve_file($fileId);
header('Content-type: text/plain');
header('Content-Disposition: attachment; filename="'.$file['name'].'.'.$file['extension'].'"');
echo $file['content'];
?>