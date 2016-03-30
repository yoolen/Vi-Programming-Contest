<?php
class Team_Manager extends Page {

    public function getPageTitle() {
        return "Team Manager";
    }

    public function getInitialization() {
        return "";
    }

    public function getPageContent()
    {
        if (isset($_GET['sub'])) {
            switch ($_GET['sub']) {
                case 'create':
                    require_once '/team-management/create-team-page.php';
                    return '';
                case 'modify':
                    if (isset($_GET['unit'])) {
                        require_once '/team-management/view-teams-page.php';
                        return '';
                    }
                    require_once '/team-management/view-teams-page.php';
                    return '';
                default:

            }
        } else {

            return <<<TEAMMANAGER
            <h2>Team Manager</h2>
            <ul>
                <li><a href='../_teamManager_create'>Create Team</a></li>
                <li><a href='../_teamManager_modify'>Modify Team</a></li>
            </ul>
TEAMMANAGER;
        }
    }
}

?>