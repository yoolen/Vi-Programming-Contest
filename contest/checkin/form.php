<form action="" method="POST">
<table>
<tr>TD...</tr>
...
<input type="hidden" value="<?php echo $_GET['submission']; ?>">
<tr><td>
<select name="correct">
<option value="1">Correct</option>
<option value="0">Incorrect</option>
</select>
</td><td><input type="submit" value="Change Grade"></td></tr>
</table>
</form>