<?php

require_once 'page.php';
$page = null;

function getPage() {
    global $page;
    if (is_null($page)) {
        if (empty($_GET['page']) or strcmp($_GET["page"], "home") == 0) {
            require_once './page/home.php';
            $page = new Home();
            return;
        }
        switch ($_GET['page']) {
            case "complete":
                require_once './page/complete.php';
                $page = new Complete();
                return;
            case "prepost":
                require_once './page/prepost-contest.php';
                $page = new Pre_Post_Contest();
                return;
            case "current":
                require_once './page/current-contests.php';
                $page = new Current_Contests();
                return;
            case "userManager":
                require_once './page/user-manager.php';
                $page = new User_Manager();
                return;
            case "teamManager":
                require_once './page/team-manager.php';
                $page = new Team_Manager();
                return;
            case "results":
                require_once './page/results.php';
                $page = new Results();
                return;
            case "affManager":
                require_once './page/affiliation.php';
                $page = new Affiliation();
                return;
            case "contestManager":
                require_once './page/contest-manager.php';
                $page = new contestManager();
                return;
            case "imaginarium":
                require_once './page/imaginarium.php';
                $page = new Imaginarium();
                return;
            case "scoreManager":
                require_once './page/score-manager.php';
                $page = new scoreManager();
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
