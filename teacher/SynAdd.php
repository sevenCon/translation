<?php 
session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>增加同义词</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/SynAdd.js"></script>
</head>
<body>
<center>
<div id="Main">
	<div id="SynWord" style="background-color:#337AB7;border-radius: 4px">
		<p style="color:#fff;font-size:30px">增加同义词</p>
	</div>
	<div id="SynWord">
		<table>
			<tr>
				<td style="text-align:right">
					<font>同义词1：</font>
				</td>
				<td>
					<input type="text" id="synWord1">
				</td>
			</tr>		
			<tr>
				<td style="text-align:right">
					<font>同义词2：</font>
				</td>
				<td>
					<input type="text" id="synWord2">
				</td>
			</tr>		
			<tr>
				<td style="text-align:right">
					<font>同义词3：</font>
				</td>
				<td>
					<input type="text" id="synWord3">
				</td>
			</tr>		
			<tr>
				<td style="text-align:right">
					<font>同义词4：</font>
				</td>
				<td>
					<input type="text" id="synWord4">
				</td>
			</tr>		
			<tr>
				<td style="text-align:right">
					<font>同义词5：</font>
				</td>
				<td>
					<input type="text" id="synWord5">
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