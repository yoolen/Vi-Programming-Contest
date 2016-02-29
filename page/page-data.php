<?php
$var;
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

class Page {

  public function getPageTitle() {
    return "NJIT Programming Contest for High School Students!";
  }

  public function getPageContent() {
    return <<<ERR
<h2>Page Could Not Be Found</h2>
<p>The page you are trying to access could not be found. Please go back or try again later.</p>
ERR;
  }
}


 ?>
