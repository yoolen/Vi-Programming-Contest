<?php

require_once 'page.php';
$page = null;

function getPage() {
    global $page;
    if (is_null($page)) {
        if (empty($_GET['page']) or strcmp($_GET["page"], "home") == 0) {
            $page = new Home();
            return;
        }
        switch ($_GET['page']) {
            case "current":
                require_once './page/current-contests.php';
                $page = new Current_Contests();
                return;
            case "userManager":
                require_once './page/user-manager.php';
                $page = new User_Manager();
                return;
            default:
                $page = new Page();
        }
        $page = new Page();
    }
}

function onLoad() {
  global $page;
  getPage();
  echo $page->onLoad();
}

function getPageImports() {
    global $page;
    getPage();
    echo $page->getPageImports();
    return;
}

function getAdditionalInitialization() {
    global $page;
    getPage();
    echo $page->getInitialization();
}

function getPageContent() {
    global $page;
    getPage();
    echo $page->getPageContent();
    return;
}

function getWarnData() {

}

function getPageTitle() {
    global $page;
    getPage();
    echo $page->getPageTitle();
    return;
}

?>
