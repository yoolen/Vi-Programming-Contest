<?php
if( isset($_POST['competitionID']) == false )
	header("Location: ./allCompetitions.php");
echo 'Editing Competition '.$_POST['competitionID'];
?>
<html>
<head>
</head>
<body>
<br>
Add New Question
<table> <form action="addQuestion.php" method="POST">
		<input type="hidden" name="competitionID" value=<?php echo '"'.$_POST['competitionID'].'"'; ?>>
        <tr>
            <td>
                <label>Question Title:</label>
            </td>
            <td>
                <input type="text" name="title" id="title"><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Question:</label>
                </td>
            <td>
                <textarea name="qtext" id="qtext" rows="4" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Answer (optional):</label>
            </td>
            <td>
                <textarea name="ans" id="ans" rows="2" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
           <td>
                <label>Input 1:</label>
           </td>
           <td>
                <textarea name="input1" id="input1" rows="4" cols="40"></textarea><br/>
           </td>
           <td>
                <label>Output 1:</label>
            </td>
            <td>
                <textarea name="output1" id="output1" rows="4" cols="40"></textarea><br/>
            </td> 
            <td>
                <label>Notes 1(optional):</label>
            </td>
            <td>
                <textarea name="notes1" id="notes1" rows="4" cols="40"></textarea><br/>
            </td>
            
        </tr>
        <tr>
            
            <td>
                <label>Input 2:</label>
            </td>
            <td>
                <textarea name="input2" id="input2" rows="4" cols="40"></textarea><br/>
            </td>
            <td>
                <label>Output 2:</label>
            </td>
            <td>
                <textarea name="output2" id="output2" rows="4" cols="40"></textarea><br/>
            </td>
            <td>
                <label>Notes 2(optional):</label>
            </td>
            <td>
                <textarea name="notes2" id="notes2" rows="4" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Input 3:</label>
            </td>
            <td>
                <textarea name="input3" id="input3" rows="4" cols="40"></textarea><br/>
            </td>
            <td>
                <label>Output 3:</label>
            </td>
            <td>
                <textarea name="output3" id="output3" rows="4" cols="40"></textarea><br/>
            </td>
            <td>
                <label>Notes 3(optional):</label>
            </td>
            <td>
                <textarea name="notes3" id="notes3" rows="4" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="create" value="Create Question">

            </td>
        </tr>
    </form></table>
</body>
</html>