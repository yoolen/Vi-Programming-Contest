<?php
function getAuxiliaryPageData() {

}

function getPageContent() {

}

function getWarnData() {

}

function getPageTitle() {
  if(isset($_SESSION['page'])) {
    return "Hello!";
  }
  return "NJIT Programming Contest for High School Students!";
}


 ?>
