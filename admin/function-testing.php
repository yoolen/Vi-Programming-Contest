<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/28/2016
 * Time: 10:27 PM
 */
//require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/user.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/team.php');
//var_dump(User::get_user(2));
//echo $email;
//$users = User::get_all_users();
//var_dump($users);
//Competition::get_contest_questions(1);
//echo(Contest::get_checkin_status(1,2));
var_dump(Question::get_question_io(16));
//var_dump(Question::get_all_question_io(1, 1));