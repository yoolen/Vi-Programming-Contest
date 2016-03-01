<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/24/2016
 * Time: 7:03 PM
 */
require_once('question-functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');

if(isset($_POST['qtext'])){
    $result = update_question($_POST['qtext'], $_POST['answer']);
    //var_dump($result);
}
?>

<html>
<table>
    <form action="update-question-page.php" method="POST">
        <tr>
            <td>
            <label>Test Number:</label>
            </td>
            <td>
            <select name = "contestnum" id="contestnum">
                <option value = ""></option>
                <?php
                $competition = new Competition();
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
                <label>Question Sequence #:</label>
            </td>
            <td>
                <input type="text" name="qnum" id="qnum"><br/>
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
                <textarea name="ans" id="answer" rows="2" cols="40"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Notes:</label>
            </td>
            <td>
                <textarea name="notes" id="notes" rows="4" cols="40"></textarea><br/>
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
                <input type="submit" name="update" value="Modify Question">

            </td>
        </tr>
    </form>



</table>
</html>
