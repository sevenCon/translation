<?php 
        session_start();
         header('Content-Type: text/html; charset=utf-8');

	//若该用户为学生且已登录，直接转入学生页面。
	if(isset($_SESSION['stuID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='student/studentmain.php'";
		echo "</script>";
	}
	//若该用户为教师且已登录，直接转入教师页面。
	if(isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='teacher/teachermain.php'";
		echo "</script>";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> 登陆界面 </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link rel="stylesheet" href="css/basic.css"> 
<script type="text/javascript" src="js/jquery.js"></script> 
  <script type="text/javascript" src="js/index.js"></script>
 </head>

 <body>

	 <div class="top_info">
        <font id="tips">English Translation System</font>
	<img id="arrow" src="image/arrow1.png"/>
	<a id="tips1" href="inform.php">|&nbsp登陆说明&nbsp|</a>
   </div>

  <center>
        <div class="login_blank" ></div>

	<form action="dlindex.php" id="indexform" method="post">
	<table id="loginitem">
	<tr>
		<td colspan="2" class="td"><label class="log_lab" 
id="log_lab1">Student/Teacher's ID</label><input oninput="OnInput(event)" 
onpropertychange="onPropChanged(event)" type="text" id="userid" class="logininput txt"  
maxlength="15" name="userid" /></td>
		<td></td>
		<td><div class="warning" id="idwarning"></div></td>
	</tr>
	<tr>
		<td colspan="2" class="td"><label class="log_lab" 
id="log_lab2">Password</label><input oninput="OnInput1(event)" 
onpropertychange="onPropChanged1(event)" type="password" id="password" class="logininput 
txt"  maxlength="20" name="userpwd" /></td>
		<td></td>
		<td><div class="warning" id="pwdwarning"></div></td>
	</tr>
	<tr>
		
		<td><input type="radio" name="usertype" class="radio_style" value="0" 
checked="checked" /><label class="radio">student</label>
		<input type="radio" name="usertype" class="radio_style" value="1" 
/><label class="radio">teacher</label></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2">
			<input class="button_style" id="submit1" type="submit" value="Log in" />
             
		</td>
		<td> </td> 
		<td> </td> 
	   </tr>
	   <tr style="text-align:center;">
		 <?php 
		//检验是否登录成功。
			if(isset($_SESSION['loginfail'])){
				if ($_SESSION['loginfail']==1)
				{
					echo "<td><div class='warning'>学号/工号或密码错误，请重新输入</div></td>";
					unset($_SESSION['loginfail']);
				}
				/*else if ($_SESSION['loginfail']==2)
				{
					echo "<td><div class='warning'>该用户已登录</div></td>";
					unset($_SESSION['loginfail']);
				}	 */
			}	
		?>
		 <td> </td> 
		<td> </td> 
		 </tr>
	</table>
	</form>
	<div class="copyright1"> &copy; Copyright 2013 by <a href="#">Ees Team</a></div>
  
 </body>
</html >
