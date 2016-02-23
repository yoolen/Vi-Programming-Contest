<html>
<head>
    <?php
        include_once ('/dbtools/lists.php');
        include_once ('/dbtools/db-info.php');
        include_once ('/dbtools/backend.php');
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>

<body>

<div id="logon">
    <form action="create-user.php" method="POST">
        <label for="usr">Username:&nbsp;</label>
        <input type="text" name="usr" id="usr"><br/>

        <label for="fname">First Name:&nbsp;</label>
        <input type="text" name="fname" id="fname"><br/>

        <label for="lname">Last Name:&nbsp;</label>
        <input type="text" name="lname" id="lname"><br/>

        <label for="aff">Affiliation:&nbsp;</label>
        <select name = "affiliation" id="aff">
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

        <label for="pwd">Password:&nbsp;</label>
        <input type="password" name="pwd" id="pwd"><br/>

        <label for="ul">User Level:&nbsp;</label>
        <select name="userlevel" id="ulevel">
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


<script>
    $(document).ready(function (){
        validate();
        $(document).change(validate);
    });

    function validate(){
        if ($('#usr').val().length   >   0   &&
            $('#lname').val().length  >   0   &&
            $('#street1').val().length    >   0) {
            $("#submitbutton").prop("disabled", false);
        } else {
            $("#submitbutton").prop("disabled", true);
        }
    }
</script>

</body>