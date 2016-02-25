<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/24/2016
 * Time: 7:03 PM
 */
require_once('question-functions.php');

if(isset($_POST['qtext'])){
    $result = add_question($_POST['qtext'], $_POST['answer']);
    //var_dump($result);
}
?>

<html>
<form action="create-question-page.php" method="POST">
    <label>Question:</label>
    <input type="text" name="qtext"><br/>
    <label>Answer (optional):</label>
    <input type="text" name="answer"><br/>
    <input type="submit" name="submit" value="Add Question">
</form>
</html>
