<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
/**
 * Created by PhpStorm.
 * User: yoole
 * Date: 3/22/2016
 * Time: 6:58 PM
 */
            echo '<select name = "contact" id="contact">';
            $contacts = User::get_users_by_aff_creds($_POST['aff'],$_POST['creds']);
            if(isset($_POST['selected'])) {
                foreach($contacts as $contact) {
                    if ($contact['uid'] == $_POST['selected']) {
                        echo '<option value="' . $contact['uid'] . '" selected="selected">' . $contact['fname'] . ' ' . $contact['lname'] . '</option>';
                    } else {
                        echo '<option value="' . $contact['uid'] . '">' . $contact['fname'] . ' ' . $contact['lname'] . '</option>';
                    }
                }
            } else {
                foreach($contacts as $contact){
                    echo '<option value="' . $contact['uid'] . '">' . $contact['fname'] . ' ' . $contact['lname'] . '</option>';
                }
            }
            echo '</select>';
?>