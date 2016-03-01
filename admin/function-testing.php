<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/28/2016
 * Time: 10:27 PM
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/user.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
//var_dump(User::get_user(2));
//echo $email;
//$users = User::get_all_users();
//var_dump($users);
//Competition::get_contest_questions(1);
echo(Competition::get_checkin_status(1,2));