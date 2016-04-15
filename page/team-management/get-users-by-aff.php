<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
/**
 * Created by PhpStorm.
 * User: yoole
 * Date: 3/22/2016
 * Time: 6:58 PM
 */
            echo '<select name = "contact" id="contact">';
            $contacts = User::get_users_by_aff($_POST['aff']);
            foreach($contacts as $contact):
                echo '<option value="' . $contact['uid'] . '">' . $contact['fname'] . '</option>';
            endforeach;
            echo '</select>';
?>