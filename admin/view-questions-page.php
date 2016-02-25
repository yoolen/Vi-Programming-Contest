<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/24/2016
 * Time: 7:17 PM
 */
//require_once ($_SERVER['DOCUMENT_ROOT'].'/admin/question-functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');

$q = new Question();
$questions = $q->get_all_questions();
var_dump($questions);
//echo "\n";
//$q1 = get_question('1');
//var_dump($q1);
//echo "\n";
//$q1d = get_question_io('1');
//var_dump($q1d);