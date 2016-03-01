<?php
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

class Home extends Page {
  public function getPageTitle() {
    return "NJIT Programming Contest for High School Students!";
  }

  public function getPageContent() {
    return <<<ERR
<h2>Home</h2>
<p>Home Text</p>
ERR;
  }
}
?>
