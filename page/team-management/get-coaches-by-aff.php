<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
/**
 * Created by PhpStorm.
 * User: yoole
 * Date: 3/22/2016
 * Time: 6:58 PM
 */
            echo '<select name = "coach" id="coach">';
            $coaches = User::get_users_by_aff_creds($_POST['aff'],$_POST['creds']);
            if(isset($_POST['selected'])){
                foreach($coaches as $coach){
                    if($coach['uid'] == $_POST['selected']){
                        echo '<option value="' . $coach['uid'] . '" selected="selected">' . $coach['fname'] . ' ' . $coach['lname'] . '</option>';
                    } else {
                        echo '<option value="' . $coach['uid'] . '">' . $coach['fname'] . ' ' . $coach['lname'] . '</option>';
                    }
                }
            } else {
                foreach ($coaches as $coach) {
                    echo '<option value="' . $coach['uid'] . '">' . $coach['fname'] . ' ' . $coach['lname'] . '</option>';
                }
            }
            echo '</select>';
?>