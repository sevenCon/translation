<?php 
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	include '../functionfile/config.inc.php';

/*	function GetHomeworkList()
{
	$sql = "select test_id, test_title, test_desc, limit_time, is_closed, is_published from tests";
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
	}
	$ret[0] = $cnt;
	return $ret;
}  */

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>英译中词语翻译</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/addetoc.js"></script>
</head>
<body>
<center>
<div id="Main">
	<div id="etoc" style="background-color:#337AB7;border-radius: 4px">
		<p style="color:#fff;font-size:30px">英译中题</p>
	</div>
	<div id="etocData">
		<table>
			<tr height="30px">
				<td style="text-align:right">
					<font>题目难度:</font>
				</td>
				<td>
					<select name="hard" id="hard" style="text-align:center;width:50px">
						<option selected="selected">1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>题目单元:</font>
				</td>
				<td>
					<select id="unit" name="unit" style="text-align:center;width:50px">
						<option selected="selected">1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
						<option>6</option>
						<option>7</option>
						<option>8</option>
						<option>9</option>
						<option>10</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>新增题目内容:</font>
				</td>
				<td>
					<textarea cols="55" rows="3" style="resize:none" id="etocSubject"></textarea>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>题目答案1:</font>
				</td>
				<td>
					<textarea cols="55" rows="3" style="resize:none" id="etocAnswer1"></textarea>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>题目答案2:</font>
				</td>
				<td>
					<textarea cols="55" rows="3" style="resize:none" id="etocAnswer2"></textarea>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>题目答案3:</font>
				</td>
				<td>
					<textarea cols="55" rows="3" style="resize:none" id="etocAnswer3"></textarea>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>分值:</font>
				</td>
				<td>
					<input type="text" style="resize:none" id="etocScore"></input>
				</td>
			</tr>
		</table>
	</div>
	<div id="showfeedback" style="text-align:center">
		<font style="font-size:12px" color="#FF0000" id="fdback"></font>
	</div>
	<div  id="Buttons">
		<p>
			<button id="submit" name="submit">确定</button>
			<button id="reset" name="reset">重置</button>
		</p>
	</div>
</div>

</center>
</body>
</html>
