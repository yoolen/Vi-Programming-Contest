<html>
<head>
    <?php
    require_once ('/dbtools/lists.php');
    require_once ('/dbtools/backend.php');
    require_once ('user-functions.php');
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>

<body>

<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/24/2016
 * Time: 7:03 PM
 */

//var_dump($_POST);

if(isset(   $_POST['usr'], $_POST['fname'], $_POST['lname'], $_POST['aff'],$_POST['email'], $_POST['phone'],
            $_POST['street1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['passwd'], $_POST['cred'])){
    $usrinfo = array('usr'=>strtolower($_POST['usr']),'fname'=>$_POST['fname'],'lname'=>$_POST['lname'],'aff'=>$_POST['aff'],
        'email'=>$_POST['email'],'phone'=>$_POST['phone'],'street1'=>$_POST['street1'],'street2'=>$_POST['street2'],'city'=>$_POST['city'],
        'state'=>$_POST['state'],'zip'=>$_POST['zip'],'passwd'=>$_POST['passwd'],'cred'=>$_POST['cred']);
    var_dump($usrinfo);
    create_user($usrinfo);
}
?>

<div id="logon">
    <form action="create-user-page.php" method="POST">
        <label for="usr">Username:&nbsp;</label>
        <input type="text" name="usr" id="usr"><br/>

        <label for="fname">First Name:&nbsp;</label>
        <input type="text" name="fname" id="fname"><br/>

        <label for="lname">Last Name:&nbsp;</label>
        <input type="text" name="lname" id="lname"><br/>

        <label for="aff">Affiliation:&nbsp;</label>
        <select name = "aff" id="aff">
            <option value = ""></option>
            <?php
            $affils = getaffs();
            foreach($affils as $affil):
                echo '<option value="'.$affil[0].'">'.$affil.'</option>';
            endforeach;
            ?>
        </select><br/>

        <label for="email">Email:&nbsp;</label>
        <input type="text" name="email" id="email"><br/>

        <label for="phone">Phone:&nbsp;</label>
        <input type="text" name="phone" id="phone"><br/>

        <label for="street1">Street 1:&nbsp;</label>
        <input type="text" name="street1" id="street1"><br/>

        <label for="street2">Street 2:&nbsp;</label>
        <input type="text" name="street2" id="street2"><br/>

        <label for="city">City:&nbsp;</label>
        <input type="text" name="city" id="city"><br/>

        <label for="state">State:&nbsp;</label>
        <select name="state" id="state">
            <option value=""></option>
            <?php
            foreach($states as $state):
                echo '<option value="'.$state.'">'.$state.'</option>';
            endforeach;
            ?>
        </select><br/>

        <label for="zip">Zip:&nbsp;</label>
        <input type="text" name="zip" id="zip"><br/>

        <label for="passwd">Password:&nbsp;</label>
        <input type="password" name="passwd" id="passwd"><br/>

        <label for="ul">User Level:&nbsp;</label>
        <select name="cred" id="cred">
            <option value=""></option>
            <?php
                foreach($userlevels as $userlevel):
                    echo '<option value="'.$userlevel[0].'">'.$userlevel.'</option>';
                    echo $userlevel[0];
                endforeach;
            ?>
        </select><br/>
    <input type="submit" value="Add User" id="submitbutton">
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
            $('#zip').val() && $('#passwd').val() && $('#cred').val()) {
            $("#submitbutton").prop("disabled", false);
        } else {
            $("#submitbutton").prop("disabled", true);
        }
    }
</script>

</body>
