<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/24/2016
 * Time: 7:17 PM
 */
<<<<<<< HEAD
<<<<<<< HEAD
require_once ($_SERVER['DOCUMENT_ROOT'].'/admin/question-functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');

$q = new Question();
//$questions = $q->get_all_questions();
//var_dump($questions);

$contestqs = $q->get_contest_questions('1');
var_dump($contestqs);
=======
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
//require_once ($_SERVER['DOCUMENT_ROOT'].'/admin/question-functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/data/question.php');

$q = new Question();
$questions = $q->get_all_questions();
var_dump($questions);
<<<<<<< HEAD
>>>>>>> 6debfc5a8f50037a0747a830f3a06a1c6bab8adb
=======
>>>>>>> 6c9f1e765a211ce35fea541301543a51570207f0
//echo "\n";
//$q1 = get_question('1');
//var_dump($q1);
//echo "\n";
//$q1d = get_question_io('1');
//var_dump($q1d);