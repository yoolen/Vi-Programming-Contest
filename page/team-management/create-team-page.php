<h3>Create New Team</h3>
<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/admin/dbtools/lists.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/admin/dbtools/backend.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/data/user.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/data/team.php');
?>

<?php
/**
// * Created by PhpStorm.
// * User: yoolen
// * Date: 2/24/2016
// * Time: 7:03 PM
// */

//var_dump($_POST);

if(isset($_POST['teamname'], $_POST['aff'],$_POST['coach'], $_POST['contact'])){
    if(Team::create_team($_POST['aff'],$_POST['contact'],$_POST['coach'],$_POST['teamname'])){
        echo 'Successful team creation!';
    } else {
        echo 'Failed team creation!';
    }
}
?>

<div id="userinfo">
    <form action="_teamManager_create" method="POST">
        <label for="teamname">Team Name:&nbsp;</label>
        <input type="text" name="teamname" id="teamname"><br/>

        <label for="aff">Affiliation:&nbsp;</label>
        <select name="aff" id="aff"">
            <option value = ""></option>
            <?php
            $affils = getaffs();
            foreach($affils as $affil):
                echo '<option value="' . explode(' - ', $affil)[0] . '">' . explode(' - ', $affil)[1] . '</option>';
            endforeach;
            ?>
        </select><br/>
        <label for="contact">Contact:&nbsp;</label>
            <select name = "contact" id = 'contact'>';
                <?php
                $contacts = User::get_all_users();
                foreach($contacts as $contact):
                    echo '<option value="' . $contact['uid'] . '">' . $contact['fname'] . '</option>';
                endforeach;
                echo '</select>';
                ?>

        <br/>
        <label for="coach">Coach:&nbsp;</label>

            <select name = "coach" id = 'coach'>';
                <?php
                $contacts = User::get_all_users();
                foreach($contacts as $contact):
                    echo '<option value="' . $contact['uid'] . '">' . $contact['fname'] . '</option>';
                endforeach;
                echo '</select>';
                ?>

        <br/>
        <input type="submit" value="Create Team" id="submitbutton">
    </form>
</div>


<script type="text/javascript" id="buttonscript">
    $(document).ready(function(){
        validate();

//        $(document).change(function(){
//            validate();
//            alert($('#teamname').val() + $('#contact').val() + $('#coach').val() + $('#aff').val());
//        });

        $(document).bind('DOMSubtreeModified',function () {
            validate();
            //alert($('#teamname').val() + $('#contact').val() + $('#coach').val() + $('#aff').val());
        })

        $('#aff').on('change',function(){
            var affID = $(this).val();
            if(affID){
                $.post( '/page/team-management/get-contacts-by-aff.php',
                    {
                        aff: affID,
                        creds: 4
                    },
                    function(result){
                        $('#contact').html(result)
                    } );
                $.post( '/page/team-management/get-coaches-by-aff.php',
                    {
                        aff: affID,
                        creds: 5
                    },
                    function(result){
                        $('#coach').html(result)
                    } );
            }
        })
    });


    function validate(){
        if ($('#teamname').val() && $('#contact').val() && $('#coach').val() && $('#aff').val()){
            $("#submitbutton").prop("disabled", false);
        } else {
            $("#submitbutton").prop("disabled", true);
        }
    }
</script>