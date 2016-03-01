<?php
function getAuxiliaryPageData() {

}

function getPageContent() {
  if (empty($_GET['page']) or strcmp($_GET["page"], "home") == 0) {
          $page = new Home();
          $page->getPageContent();
          return;
      }
      switch ($_GET['page']) {
        default:
          $page = new Page();
          $page->getPageContent();
          return;
      }
    $page = new Page();
    $page->getPageContent();
    return;
}

function getWarnData() {

}


function getPageTitle() {
    require_once 'page.php';
    if (isset($_GET['page'])) {
        switch ($_GET['page']) {
          case 'Home':
            $home = new Home();
            $home = getPageTitle();
            return;
          }
        }
  return "NJIT Programming Contest for High School Students!";
}
?>
