<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/24/2016
 * Time: 7:03 PM
 */
require_once('question-functions.php');
<<<<<<< HEAD
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/competition.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');

$competition = new Competition();
$question = new Question();
var_dump($_POST);
if(isset($_POST['contestnum'], $_POST['seqnum'], $_POST['qtext'], $_POST['ans'],$_POST['notes1'], $_POST['input1'], $_POST['output1'] )){
    $result = $question->insert_question($_POST['title'],$_POST['qtext'], $_POST['ans']);
    //var_dump($result);
    $question->insert_question_io($result,$_POST['input1'],$_POST['output1'],$_POST['notes1']);
    if(!($_POST['input2']=='') && !($_POST['output2']=='')){
        $question->insert_question_io($result,$_POST['input2'],$_POST['output2'],$_POST['notes2']);
    }
    if(!($_POST['input3']=='') && !($_POST['output3']=='')){
        $question->insert_question_io($result,$_POST['input3'],$_POST['output3'],$_POST['notes3']);
    }
    $competition->add_question_to_contest($_POST['contestnum'],$result,$_POST['seqnum']);
=======

if(isset($_POST['qtext'])){
    $result = add_question($_POST['qtext'], $_POST['answer']);
    //var_dump($result);
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
}
?>

<html>
<<<<<<< HEAD
<table>
    <form action="create-question-page.php" method="POST">
        <tr>
            <td>
            <label>Test Number:</label>
            </td>
            <td>
            <select name = "contestnum" id="contestnum">
                <option value = ""></option>
                <?php
                $comps = $competition->get_all_competitions();
                foreach($comps as $comp):
                    echo '<option value="'.$comp["contest_PK"].'">'.$comp["contest_PK"].'</option>';
                endforeach;
                ?>
            </select><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Question #:</label>
            </td>
            <td>
                <input type="text" name="seqnum" id="seqnum"><br/>
            </td>
        </tr>
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
                <label>Input 2:</label>
            </td>
            <td>
                <textarea name="input2" id="input2" rows="4" cols="40"></textarea><br/>
            </td>
            <td>
                <label>Input 3:</label>
            </td>
            <td>
                <textarea name="input3" id="input3" rows="4" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Output 1:</label>
            </td>
            <td>
                <textarea name="output1" id="output1" rows="4" cols="40"></textarea><br/>
            </td>
            <td>
                <label>Output 2:</label>
            </td>
            <td>
                <textarea name="output2" id="output2" rows="4" cols="40"></textarea><br/>
            </td>
            <td>
                <label>Output 3:</label>
            </td>
            <td>
                <textarea name="output3" id="output3" rows="4" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Notes 1(optional):</label>
            </td>
            <td>
                <textarea name="notes1" id="notes1" rows="4" cols="40"></textarea><br/>
            </td>
            <td>
                <label>Notes 2(optional):</label>
            </td>
            <td>
                <textarea name="notes2" id="notes2" rows="4" cols="40"></textarea><br/>
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
    </form>



</table>
=======
<form action="create-question-page.php" method="POST">
    <label>Question:</label>
    <input type="text" name="qtext"><br/>
    <label>Answer (optional):</label>
    <input type="text" name="answer"><br/>
    <input type="submit" name="submit" value="Add Question">
</form>
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
</html>
