<h3>Affiliation Management</h3>
<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 3/19/2016
 * Time: 10:00 PM
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/data/admin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/admin/dbtools/lists.php');
$affiliates = Admin::get_all_affiliates();
?>

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
<script src="../../library/sorttable.js"></script>

<table class="sortable table-hover"  border="2">
    <thead>
    <tr id="tableheaders"><th>Affiliation</th><th>Email</th><th>Phone</th><th>Street 1</th><th>Street 2</th><th>City</th><th>State</th><th>Zip</th></tr>
    </thead>
    <?php
    foreach ($affiliates as $affiliate){
        echo '<tr>';
            echo '<td hidden>'.$affiliate['aff_PK'].'</td>';
            echo '<td>'.$affiliate['affname'].'</td>';
            echo '<td>'.$affiliate['email'].'</td>';
            echo '<td>'.$affiliate['phone'].'</td>';
            echo '<td>'.$affiliate['street1'].'</td>';
            echo '<td>'.$affiliate['street2'].'</td>';
            echo '<td>'.$affiliate['city'].'</td>';
            echo '<td>'.$affiliate['state'].'</td>';
            echo '<td>'.$affiliate['zip'].'</td>';
        echo '</tr>';
    }
    ?>
</table>
<br>
<?php
if(isset($_POST['affname'], $_POST['email'], $_POST['phone'], $_POST['street1'], $_POST['city'], $_POST['state'], $_POST['zip'])){
    $affinfo = array('affname'=>$_POST['affname'],'email'=>$_POST['email'],'phone'=>$_POST['phone'],'street1'=>$_POST['street1'],
                'street2'=>$_POST['street2'],'city'=>$_POST['city'],'state'=>$_POST['state'],'zip'=>$_POST['zip']);
//    var_dump($_POST);
    if(Admin::set_affiliate($affinfo)){
        echo 'Added';
    } else {
        echo '<br>Affiliate already exists';
    }
}
?>

<div id="affinfo">
    <form action="" method="POST">
        <label for="affname">Affiliate Name:&nbsp;</label>
        <input type="text" name="affname" id="affname"><br/>

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
                echo '<option value="' . $state . '">' . $state . '</option>';
            endforeach;
            ?>
        </select><br/>

        <label for="zip">Zip:&nbsp;</label>
        <input type="text" name="zip" id="zip"><br/>

        <input type="submit" value="Add New Affiliate" id="submitbutton">
    </form>
</div>


<script type="text/javascript" id="buttonscript">
    $(document).ready(function (){
        validate();
        $(document).change(validate);
    });

    function validate(){
        if ($('#affname').val() && $('#email').val() && $('#phone').val() && $('#street1').val() && $('#city').val() &&
            $('#state').val() && $('#zip').val()) {
            $("#submitbutton").prop("disabled", false);
        } else {
            $("#submitbutton").prop("disabled", true);
        }
    }
</script>