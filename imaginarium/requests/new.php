<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";

$name = $_POST['filename'];
$ext = $_POST['extension'];
$folder = $_POST['folder'];

$userFolders = File_Functions::retrieve_all_user_folders($_SESSION['uid']);
$ownership = false;

foreach ($userFolders as $i) {
    if (intval($i['folderId']) === intval($folder)) {
        $ownership = true;
    }
}

if (!$ownership) {
    echo "0";
    return;
}


$result = File_Functions::create_file($name, $ext, $folder);

if ($result) {
    echo $result;
} else {
    echo "0";
}
?>