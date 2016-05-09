<?php

//Start Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Check if logged in.
if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
    header("Location: /login.php");
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/submission.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/question.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/contest.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/data/team.php";

if (!isset($_GET['contest'])) {
    echo "Invalid Page. Returning you to the dashboard.";
    header("refresh:5; url=http://njit1.initiateid.com/");
    return;
}
$contestId = $_GET['contest'];
$contestQuestions = Contest::get_contest_questions($contestId);
$teamId = User::get_teamid($_SESSION['uid']);

$question1 = $contestQuestions[0];

$folder = File_Functions::get_folder_for_question_team($teamId, $question1['qid']);

if (is_null($folder)) {
    $teamInfo = Team::get_team_info($teamId);
    $coach = $teamInfo['coach_FK'];
    $i = 1;
    foreach ($contestQuestions as $question) {
        $folder = File_Functions::create_folder($coach, 1, 1, "Question ".$i);
        $i++;
        File_Functions::new_folder_contest_association($teamId, $contestId, $question['qid'], $folder);
        $file = File_Functions::create_file("Main", "java", $folder);
        File_Functions::save_file($file, "public class Main {\r\n\tpublic static void main(String[] args) {\r\n\t\tSystem.out.println(\"Hello World!\");\r\n\t}\r\n}");
    }
}

$folder = File_Functions::get_folder_for_question_team($teamId, $question1['qid']);
$file = File_Functions::first_file($folder);

header("Location: ../imaginarium/next.php?contest=".$contestId);

?>