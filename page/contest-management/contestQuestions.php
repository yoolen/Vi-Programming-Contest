<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\data\contest.php');

if( isset($_POST['contestID']) == false )
	header("Location: ../page/contest-management/allContests.php");
if( isset($_POST['delete']) ){
	Contest::remove_contest_question($_POST['contestID'], $_POST['qid']);
}
$question = Contest::get_contest_questions($_POST['contestID']);
if( isset($_POST['up'])){
	if($_POST['pos']>0){
		echo 'q'.$_POST['contestID'].'c'.$question[$_POST['pos']]['qid'].'s'.$_POST['pos'];
		$a = Contest::get_seq($_POST['contestID'], $question[$_POST['pos']]['qid']);
		$b = Contest::get_seq($_POST['contestID'], $question[$_POST['pos']-1]['qid']);
		echo $a.'vvv';
		echo $b;
		Contest::set_seq($b, $_POST['contestID'], $question[$_POST['pos']]['qid']);
		Contest::set_seq($a, $_POST['contestID'], $question[$_POST['pos']-1]['qid']);
		$question = Contest::get_contest_questions($_POST['contestID']);
	}
}
if( isset($_POST['down']) ){
	if($_POST['pos']<(sizeof($question)-1)){
		$a = Contest::get_seq($_POST['contestID'], $question[$_POST['pos']]['qid']);
		$b = Contest::get_seq($_POST['contestID'], $question[$_POST['pos']+1]['qid']);
		Contest::set_seq($b, $_POST['contestID'], $question[$_POST['pos']]['qid']);
		Contest::set_seq($a, $_POST['contestID'], $question[$_POST['pos']+1]['qid']);
		$question = Contest::get_contest_questions($_POST['contestID']);
	}
}
if(isset($_POST['name'], $_POST['month'],$_POST['day'],$_POST['year'], $_POST['dur-hour'], $_POST['dur-min'])){
	$date = date("Y-m-d", mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']));
	$duration = new DateInterval("P0Y0M0DT".$_POST['dur-hour']."H".$_POST['dur-min']."M0S");
	
	$r = Contest::update_contest($_POST['contestID'], $date, $_POST['hour'], $_POST['minute'], 0, $duration ->format('%H:%I:%S'), 1, $_POST['name']);
}

$contest = Contest::get_contest($_POST['contestID']);
?>
<html>
<head>
</head>
<body>
<h1>Contest Options</h1>
<h2>Contest: <?php echo $contest['name']; ?></h2>
<table border="1">
<tr><th>Question</th><th>Operations</th></tr>
<?php 
	$x = 0;
	foreach ($question as $q => $value) {
		echo '<tr>
			   	  <td><form method="post" action="../_contestManager_modify" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'">
				      <input type="submit" value="&#x25B2;" name="up">
			   	      <input type="submit" value="&#x25BC;" name="down"><input type="hidden" value="'.$x.'" name="pos"></form> 
			   	      Question: '.$value['title'].'</td>
				  <td><form method="post" action="../_contestManager_modify_editQ" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="Edit" name="Edit"></form>
				  <form method="post" action="../_contestManager_modify_qtc" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="Test Cases"></form>
				  <form method="post" action="../_contestManager_modify" style="display:inline;"><input type="hidden" name="contestID" value="'.$_POST['contestID'].'"><input type="hidden" name="qid" value="'.$value['qid'].'"><input type="submit" value="X" name="delete"></form></td></tr>';
				$x+=1;
	}
	if($x == 0) echo "<tr><td>There are no questions.</td></tr>";
?>
</table>
<br>
<form method="post" action="../_contestManager_modify_newQ"><input type="hidden" name="contestID" value=<?php echo '"'.$_POST['contestID'].'"'; ?>><input type="submit" value="Add Question"></form>
<br>
<form method="post" action="../_contestManager_modify">
<table>
<tr><td><b>Name</b></td><td><input type="text" name="name" value="<?php echo $contest["name"]; ?>"></td></tr>
<tr><td><b>Date</b></td><td><b>M</b><select name="month">
<option value="<?php echo $contest["month"]; ?>"><?php echo $contest["month"]; ?></option>
<option value="01">January</option>
<option value="02">February</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select></td>
<td><b>D</b><select name="day">
<option value="<?php echo $contest["day"]; ?>"><?php echo $contest["day"]; ?></option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select></td>
<td><b>Y</b><select id="year" name="year">
<option value="<?php echo $contest["year"]; ?>"><?php echo $contest["year"]; ?></option>
  <script>
  var myDate = new Date();
  var year = myDate.getFullYear();
  for(var i = year; i < year+5; i++){
	  document.write('<option value="'+i+'">'+i+'</option>');
  }
  </script>
</select></td></tr>
<tr><td><b>Time</b></td><td><b>H</b>
<select name="hour">
<option value="<?php echo $contest["hours"]; ?>"><?php echo $contest["hours"]; ?></option>
<option value="00">12</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">01</option>
<option value="14">02</option>
<option value="15">03</option>
<option value="16">04</option>
<option value="17">05</option>
<option value="18">06</option>
<option value="19">07</option>
<option value="20">08</option>
<option value="21">09</option>
<option value="22">10</option>
<option value="23">11</option>
</select></td>
<td><b>M</b><select name="minute">
<option value="<?php echo $contest["minutes"]; ?>"><?php echo $contest["minutes"]; ?></option>
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
</select>
</td></tr>
<tr><td><b>Duration</b></td><td><b>H</b><select name="dur-hour">
<option value="<?php echo $contest["dhours"]; ?>"><?php echo $contest["dhours"]; ?></option>
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
</select></td>
<td><b>M</b><select name="dur-min">
<option value="<?php echo $contest["dminutes"]; ?>"><?php echo $contest["dminutes"]; ?></option>
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
</select></td></tr>
</table>
<input type="hidden" name="contestID" value="<?php echo $_POST["contestID"]; ?>"><br>
<input type="submit" value="Update Contest">
</form>
<br/>
<a href="../_contestManager">Back to Contest Dashboard</a>

</body>
</html>