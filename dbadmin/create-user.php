<html>
<head>
    <?php
        include_once ('/dbtools/lists.php');
        include_once ('/dbtools/db-info.php');
        include_once ('/dbtools/backend.php');
    ?>
    <script type="text/javascript">
        var xhttp;
        if(window.XMLHttpRequest){
            xhttp = new XMLHttpRequest();
        } else {
            // code for IE5/6
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    </script>

</head>

<body>
<div id="logon">
    <form action="adduser.php" method="POST">
        <ul class="errorMsg"></ul>

        <label for="usr">Username:&nbsp;</label>
        <input type="text" name="usr"><br/>

        <label for="fname">First Name:&nbsp;</label>
        <input type="text" name="fname"><br/>

        <label for="lname">Last Name:&nbsp;</label>
        <input type="text" name="lname"><br/>

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
        <input type="text" name="email"><br/>

        <label for="phone">Phone:&nbsp;</label>
        <input type="text" name="phone"><br/>

        <label for="street1">Street 1:&nbsp;</label>
        <input type="text" name="street1"><br/>

        <label for="street2">Street 2:&nbsp;</label>
        <input type="text" name="street2"><br/>

        <label for="city">City:&nbsp;</label>
        <input type="text" name="city"><br/>

        <label for="state">State:&nbsp;</label>
        <select name="state">
            <option value=""></option>
            <?php
            foreach($states as $state):
                echo '<option value="'.$state.'">'.$state.'</option>';
            endforeach;
            ?>
        </select><br/>

        <label for="zip">Zip:&nbsp;</label>
        <input type="text" name="zip"><br/>

        <label for="pwd">Password:&nbsp;</label>
        <input type="password" name="pwd"><br/>

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
    </form>
    <button type="button" onclick="submit()">Submit</button>
</div>
<br>
<script type="text/javascript">
    function submit(){
        var ulevel = document.getElementById('ulevel').value;
        alert(ulevel);
    }
</script>

</body>
