<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/28/2016
 * Time: 10:27 PM
 */
//require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/user.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/question.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/contest.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/team.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/user.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/admin.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/submission.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/admin/dbtools/backend.php');
//var_dump(User::get_user(2));
//echo $email;
//$users = User::get_all_users();
//var_dump($users);
//Competition::get_contest_questions(1);
//echo(Contest::get_checkin_status(1,2));
//var_dump(Question::get_question_io(16));
//var_dump(Question::get_all_question_io(1, 1));
//var_dump(User::get_user_by_creds(1));
//var_dump(Admin::get_all_affiliates());
//var_dump(User::get_users_by_aff(1));
//echo Admin::set_affiliate(array("affname"=> "Test1", "email"=> "Test", "phone"=>"Test", "street1"=>"Test", "street2"=>"Test", "city"=>"Test","state"=>"KS","zip"=>"Test"));
//var_dump($_POST);
//var_dump(User::get_affiliation_name(5));
//var_dump(Team::get_all_teams());
//Submission::add_submission(10, 1, 'test3', 7);
//var_dump(Submission::get_submissions_by_team(1));
//var_dump(Question::get_question_io(52));
//var_dump(Question::get_question_ios(50));
//var_dump(Team::get_team_info(1));
//Team::remove_team_member(14,26);
var_dump(Submission::get_answer(1,70));
?>