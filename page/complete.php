<?php
class Complete {

  public function getPageTitle() {
    return "NJIT Programming Contest for High School Students!";
  }

  public function getPageImports() {
      return "";
  }

  public function getInitialization() {
      return "";
  }

  public function onLoad() {
    return "";
  }

  public function getPageContent() {
    return <<<ERR
<h2>Contest Complete</h2>
<p>Excellent Work! You have completed the contest. Please wait for futher instructions by your contest administrator.</p>
ERR;
  }
}
?>
