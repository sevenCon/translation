<?php 
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	include '../functionfile/config.inc.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>增加题目</title>
</head>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/addctoe.js"></script>
<body>
<center>
<div id="Main">
	<div id="ctoe" style="background-color:#337AB7;border-radius: 4px">
		<p style="color:#fff;font-size:30px">中译英题</p>
	</div>
	<div id="ctoeData">
		<table>	<!-- 
		   <tr height="30px">
			    <td style="text-align:right">
					<font>所属作业:</font>
				</td>
				<td>
				  <select name="homeworkList" id="homeworkList" style="width:100px"></select>
				</td>
			</tr>	  -->
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
					<textarea cols="55" rows="3" style="resize:none" id="ctoeSubject"></textarea>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>题目答案1:</font>
				</td>
				<td>
					<textarea cols="55" rows="3" style="resize:none" id="ctoeAnswer1"></textarea>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>题目答案2:</font>
				</td>
				<td>
					<textarea cols="55" rows="3" style="resize:none" id="ctoeAnswer2"></textarea>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>题目答案3:</font>
				</td>
				<td>
					<textarea cols="55" rows="3" style="resize:none" id="ctoeAnswer3"></textarea>
				</td>
			</tr>
			<tr>
				<td style="text-align:right">
					<font>分值:</font>
				</td>
				<td>
					<input type="text" style="resize:none" id="ctoeScore"></input>
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