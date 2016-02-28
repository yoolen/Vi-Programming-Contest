<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>


<body>
<div id="banner">
    <button type="button" onclick="user_opt()">User Options</button>
    <button type="button" onclick="con_opt()">Contest Options</button>
</div>
<div id ="display">
</div>

<script>
    function user_opt(){
        document.getElementById("banner").innerHTML =
            '<button type="button" id="createbutton" onclick="create_user()">Create User</button>' +
            '<button type="button" onclick="edit_user()">Edit User</button>' +
            '<button type="button" onclick="back()">Back</button>';
    }

    function con_opt(){
        document.getElementById("banner").innerHTML =
            '<button type="button" onclick="create_contest()">Create Contest</button>' +
            '<button type="button" onclick="edit_contest()">Edit Contest</button>' +
            '<button type="button" onclick="back()">Back</button>';
    }

    function create_user(){
        $(document).ready(function(){
           $("#createbutton").click(function(){
               $("#display").load("create-user-page.php");
           });
        });
    }

    function edit_user(){
        var xhttp;
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.onreadystatechange = function(){
            if(xhttp.readyState == 4 && xhttp.status == 200){
                document.getElementById("display").innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("get","edit-user.php", true);
        xhttp.send();
    }


    function back(){
        document.getElementById("banner").innerHTML =
            '<button type="button" onclick="user_opt()">User Options</button>' +
            '<button type="button" onclick="con_opt()">Contest Options</button>';
        document.getElementById("display").innerHTML = "";
    }
</script>



</body>
</html>