<h3>Edit Existing User</h3>
<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/admin/dbtools/lists.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/admin/dbtools/backend.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
$userinfo = USER::get_user($_GET['unit']);
?>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>

<?php

/**
// * Created by PhpStorm.
// * User: yoolen
// * Date: 2/24/2016
// * Time: 7:03 PM
// */

if(isset(   $_POST['usr'], $_POST['fname'], $_POST['lname'], $_POST['aff'],$_POST['email'], $_POST['phone'],
    $_POST['street1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['creds'])){
    $usrinfo = array('usr'=>strtolower($_POST['usr']),'fname'=>$_POST['fname'],'lname'=>$_POST['lname'],'aff'=>$_POST['aff'],
        'email'=>$_POST['email'],'phone'=>$_POST['phone'],'street1'=>$_POST['street1'],'street2'=>$_POST['street2'],'city'=>$_POST['city'],
        'state'=>$_POST['state'],'zip'=>$_POST['zip'],'creds'=>$_POST['creds']);
    //echo 'ready';
    //var_dump($_POST);
    if(User::admin_modify_user($usrinfo)){
        echo 'Successfully updated!';
    } else {
        echo 'Error, not updated!';
    }

} else {
    //echo 'not ready';
}
?>
<div id="userinfo">
    <form action="_userManager_modify_<?php echo $_GET['unit']?>" method="POST">
        <label for="usr">Username:&nbsp;</label>
        <input type="text" name="usr" id="usr" value="<?php echo $userinfo['usr']?>" readonly="readonly"><br/>

        <label for="fname">First Name:&nbsp;</label>
        <input type="text" name="fname" id="fname" value="<?php echo $userinfo['fname']?>"><br/>

        <label for="lname">Last Name:&nbsp;</label>
        <input type="text" name="lname" id="lname" value="<?php echo $userinfo['lname']?>"><br/>

        <label for="aff">Affiliation:&nbsp;</label>
        <select name = "aff" id="aff">
            <option value = ""></option>
            <?php
            $affils = getaffs();
            foreach($affils as $affil):
                if(trim(explode('-', $affil)[0]) == $userinfo['aff']){
                    echo '<option value="' . trim(explode('-', $affil)[0]) . '" selected="selected">' . trim(explode('-', $affil)[1]) . '</option>';
                } else {
                    echo '<option value="' . trim(explode('-', $affil)[0]) . '">' . trim(explode('-', $affil)[1]) . '</option>';
                }
            endforeach;
            ?>
        </select><br/>

        <label for="email">Email:&nbsp;</label>
        <input type="text" name="email" id="email" value="<?php echo $userinfo['email']?>"><br/>

        <label for="phone">Phone:&nbsp;</label>
        <input type="text" name="phone" id="phone" value="<?php echo $userinfo['phone']?>"><br/>

        <label for="street1">Street 1:&nbsp;</label>
        <input type="text" name="street1" id="street1" value="<?php echo $userinfo['street1']?>"><br/>

        <label for="street2">Street 2:&nbsp;</label>
        <input type="text" name="street2" id="street2" value="<?php echo $userinfo['street2']?>"><br/>

        <label for="city">City:&nbsp;</label>
        <input type="text" name="city" id="city" value="<?php echo $userinfo['city']?>"><br/>

        <label for="state">State:&nbsp;</label>
        <select name="state" id="state">
            <option value=""></option>
            <?php
            foreach($states as $state):
                if ($state == $userinfo['state']){
                    echo '<option value="' . $state . '" selected="selected">' . $state . '</option>';
                } else {
                    echo '<option value="' . $state . '">' . $state . '</option>';
                }
            endforeach;
            ?>

        </select><br/>

        <label for="zip">Zip:&nbsp;</label>
        <input type="text" name="zip" id="zip" value="<?php echo $userinfo['zip']?>"><br/>

        <label for="passwd">Password:&nbsp;</label>
        <input type="password" name="passwd" id="passwd" value="**********" disabled><br/>

        <label for="ul">User Level:&nbsp;</label>
        <select name="creds" id="creds">
            <option value=""></option>

            <?php
            foreach($userlevels as $userlevel):
                if ($userlevel[0] == $userinfo['creds']){
                    echo '<option value="' . $userlevel[0] . '"selected="selected">' . $userlevel . '</option>';
                }else {
                    echo '<option value="' . $userlevel[0] . '">' . $userlevel . '</option>';
                }
            endforeach;
            ?>

        </select><br/>
        <input type="submit" value="Update" id="submitbutton">
    </form>
</div>


<script type="text/javascript" id="buttonscript">
    $(document).ready(function (){
        validate();
        $(document).change(validate);
    });

    function validate(){
        if ($('#usr').val() && $('#fname').val() && $('#lname').val() && $('#aff').val() && $('#email').val() &&
            $('#phone').val() && $('#street1').val() && $('#city').val() && $('#state').val() &&
            $('#zip').val() && $('#creds').val()) {
            $("#submitbutton").prop("disabled", false);
        } else {
            $("#submitbutton").prop("disabled", true);
        }
    }
</script>