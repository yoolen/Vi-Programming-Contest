<?php


class User_Manager extends Page
{

    public function getPageTitle()
    {
        return "User Manager";
    }

    public function getInitialization()
    {

        return "";
    }

    public function getPageContent()
    {
        if (isset($_GET['sub'])) {
            //  if (strcmp($_GET['sub'], "create") == 0) {
            switch ($_GET['sub']) {
                case 'create':
                    include '/user-management/create-user-page.html';
                case 'modify':
                    require '/user-management/modify-user-page.html';
            }
        }
        return
<<<USERMANAGER
                    <h2>User Manager</h2>
                    <ul>
                        <li><a href='../_userManager_create'>Create User</a></li>
                        <li><a href='../_userManager_modify'>Modify User</a></li>
                    </ul>
USERMANAGER;
    }
}

?>
