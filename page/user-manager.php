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
                    require_once '/user-management/create-user-page.php';
                    return '';
                case 'modify':
					if(isset($_GET['unit'])) {
						require_once '/user-management/modify-user-page.php';
						return '';
					}
                    require_once '/user-management/view-users-page.php';
                    return '';
                default:

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