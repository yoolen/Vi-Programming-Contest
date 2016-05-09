<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id = $_POST['fileId'];
$content = $_POST['content'];
$act = $_POST['action'];

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";

$folder = File_Functions::get_folder_data_from_fileId($id);
$userFolders = File_Functions::retrieve_all_user_folders($_SESSION['uid']);

$ownership = false;
foreach($userFolders as $i) {
    if($i['folderId'] == $folder) {
        $ownership = true;
    }
}
//TODO: FIX THIS!
/*
if(!$ownership) {
    echo "0";
    return;
}*/

if (strcmp($act, 'save') == 0) {
  $result = File_Functions::save_file($id, $content);
  
  if($result) {
    echo "1";
  } else {
    echo "0";
  }
}

?>
