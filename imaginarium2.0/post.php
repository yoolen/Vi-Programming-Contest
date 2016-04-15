<?php

$id = $_POST['fileId'];
$content = $_POST['content'];
$act = $_POST['action'];

print_r($_POST);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";

if (strcmp($act, 'save') == 0) {
  $result = File_Functions::save_file($id, $content);
  echo $result;
  /*if($result) {
    echo "Save Success!";
  } else {
    echo "Save Fail!";
  }*/
}

?>
