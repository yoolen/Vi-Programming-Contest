<h3>Edit Team</h3>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dbtools/lists.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/dbtools/backend.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/user.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/team.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
$teaminfo = Team::get_team_info($_GET['unit']);
$teammembers = Team::get_team_members($_GET['unit']);
$contestid = Team::get_assigned_contests($_GET['unit']);
//var_dump($team);
//var_dump($teaminfo);
echo '<br/>';
//var_dump($teammembers);
?>

<?php
/**
 * // * Created by PhpStorm.
 * // * User: yoolen
 * // * Date: 2/24/2016
 * // * Time: 7:03 PM
 * // */

if (isset($_POST['teamname'], $_POST['aff'], $_POST['coach'], $_POST['contact'])) {
    if ($teamid = Team::create_team($_POST['aff'], $_POST['contact'], $_POST['coach'], $_POST['teamname'])) {
        foreach ($_POST['contestantid'] as $contestant) {
            Team::add_team_member($teamid, $contestant);
        }
        if (isset($_POST['contest'])){
            Contest::set_contest_team($_POST['contest'],$teamid);
        }
        echo 'Successful team creation!';
    } else {
        echo 'Failed team creation!';
    }
}
?>

<div id="teaminfo">
    <form action="_teamManager_modify_<?php echo $_GET['unit']?>" method="POST">
        <label for="teamname">Team Name:&nbsp;</label>
        <input type="text" name="teamname" id="teamname" value="<?php echo $teaminfo['teamname']?>"><br/>

        <label for="aff">Affiliation:&nbsp;</label>
        <select name="aff" id="aff"">
        <option value=""></option>
        <?php
        $affils = getaffs();
        foreach ($affils as $affil) {
            if (trim(explode('-', $affil)[0]) == $teaminfo['aff_FK']) {
                echo '<option value="' . trim(explode('-', $affil)[0]) . '" selected="selected">' . trim(explode('-', $affil)[1]) . '</option>';
            } else {
                echo '<option value="' . trim(explode('-', $affil)[0]) . '">' . trim(explode('-', $affil)[1]) . '</option>';
            }
        }
        ?>
        </select><br/>
        <label for="contact">Contact:&nbsp;</label>
        <select name="contact" id='contact'>';
            <?php
            $contacts = User::get_all_users();
            foreach ($contacts as $contact) {
                echo '<option value="' . $contact['uid'] . '">' . $contact['fname'] . ' ' . $contact['lname'] . '</option>';
            }
            echo '</select>';
            ?>

            <br/>
            <label for="coach">Coach:&nbsp;</label>
            <select name="coach" id="coach">
                <?php
                $contacts = User::get_all_users();
                foreach ($contacts as $contact) {
                    echo '<option value="' . $contact['uid'] . '">' . $contact['fname'] . ' ' . $contact['lname'] . '</option>';
                }
                ?>
            </select>
            <br/>
            <label for="contest">Contest:&nbsp;</label>
            <select name="contest" id="contest">
                <?php
                $contests = Contest::get_all_contests();
                foreach ($contests as $contest){
                    if($contestid == $contest['cid']){
                        echo '<option value="' . $contest['cid'] . '" selected="selected">' . $contest['name'] . '</option>';
                    } else {
                        echo '<option value="' . $contest['cid'] . '">' . $contest['name'] . '</option>';
                    }
                }
                ?>
            </select>


            <div id="teammembers"></div>
            <br/>
            <input type="submit" value="Update Team" id="submitbutton">
    </form>
</div>


<script type="text/javascript" id="buttonscript">
    $(document).ready(function () {
        validate();
        updateValues($('#aff').val());

        $(document).bind('DOMSubtreeModified', function () {
            validate();
            //alert($('#teamname').val() + $('#contact').val() + $('#coach').val() + $('#aff').val());
        })

        $('#aff').on('change', function () {
            var affID = $(this).val();
            updateValues(affID);
        })
    });

    function updateValues(affID) {
        var contact = <?php echo $teaminfo['contact_FK']; ?>;
        var coach = <?php echo $teaminfo['coach_FK']; ?>;
        var contestants = <?php echo json_encode($teammembers); ?>;
        if (affID) {
            $.post('/page/team-management/get-contacts-by-aff.php',
                {
                    aff: affID,
                    creds: 5,
                    selected: contact
                },
                function (result) {
                    $('#contact').html(result)
                });
            $.post('/page/team-management/get-coaches-by-aff.php',
                {
                    aff: affID,
                    creds: 6,
                    selected: coach
                },
                function (result) {
                    $('#coach').html(result)
                });
            $.post('/page/team-management/get-users-by-aff.php',
                {
                    aff: affID,
                    creds: 4,
                    selected: contestants
                },
                function (result) {
                    $('#teammembers').html(result)
                });
        }
    }

    function validate() {
        if ($('#teamname').val() && $('#contact').val() && $('#coach').val() && $('#aff').val()) {
            $("#submitbutton").prop("disabled", false);
        } else {
            $("#submitbutton").prop("disabled", true);
        }
    }
</script>