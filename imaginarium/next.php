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
$submissions = Submission::get_all_submissions();

foreach ($contestQuestions as $question) {
    $found = false;
    foreach ($submissions as $sub) {
        if ($question['qid'] == $sub['question_FK'] && $teamId == $sub['team_FK']) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        $folder = File_Functions::get_folder_for_question_team($teamId, $question['qid']);
        $file = File_Functions::first_file($folder);
        $file = header("Location: /imagine_" . $file);
        return;
    }
}

$file = header("Location: /_complete");
?>