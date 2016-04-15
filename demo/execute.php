<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function execute($folder, $file, $ext, $watch='') {
    $files = File_Functions::retrieve_folder_files($folder);
    rmdir(''.$folder);
    mkdir(''.$folder);
    foreach($files as $file) {
      $myfile = fopen(''.$folder.'/'.$file['name'].'.'.$file['ext'], "w");
      $content = File_Functions::retrieve_file($file['fileId'])['content'];
      $txt = $content;
      fwrite($myfile, $txt);
      fclose($myfile);
    }
    switch($ext) {
      case 'java':

      break;
      case 'python':
      
      break;

      case 'cpp':
      break;

    }

  }
  $output = shell_exec('dir');
  echo "$output";
//execute(1, '');
?>
