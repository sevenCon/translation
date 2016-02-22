<?php 
	session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}
	$teacherId=$_SESSION['teacherID'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>教师密码修改</title>
<link href="../css/basic.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/a_changeteapwd.js"></script>
</head>
<body class="childfilebody">
<center>
<?php 
	//include "../functionfile/globle.func.php";
	//echo $teacherId;
?>
<div style="margin-top:4%;"> 
<p text-align="left" style="border-bottom:2px solid rgb(202, 214, 226);"><font size="+3">教师密码修改</font></p>
		<table border="0" width="350" height="90" style="margin-top:30px;">
			
						
			
			<tr align="center" height="40">
				<td width="45%">
					原&nbsp;密&nbsp;码：
				</td>
				<td>
					<input type=password id="originalPwd"/>
				</td>
			</tr>
			<tr align="center" height="40">
				<td>
					新&nbsp;密&nbsp;码：
				</td>
				<td>
					<input id="newPwd" type=password />
				</td>
			</tr>
			<tr align="center" height="40">
				<td>
					确认密码：
				</td>
				<td>
					<input id="repeatPwd" type=password />
				</td>
			</tr>
			<tr align="center" height="40">
				<td colspan="2">			
					<div id="showfeedback" style="text-align:center">
						<font style="font-size:12px" color="#FF0000" id="errorInfo"></font>
					</div>
					<div>		
						<input class="button_style"style="margin-left:10px;" id="submit" type="button" value="提交"  onclick="submit('<?php echo $teacherId;?>')"/>
						<input class="button_style"style="margin-left:10px;" id="reset" type="button" value="重置"/>
					</div>
				</td>
			</tr>
		</table>
</div>
</center>

</body>
</html>
