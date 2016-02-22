<?php session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Login...</title>
</head>

<body>
<?php 
    require "functionfile/login.func.php";
	require "functionfile/config.inc.php";
	$userid=$_POST["userid"];
	$userpwd=$_POST["userpwd"];
	$usertype=$_POST["usertype"];
	
/* 教师登录 */
if($usertype==1)
{
	if(!log_Teacher($userid,$userpwd)){
			$_SESSION['loginfail']=1;
			echo "<script language='javascript' type='text/javascript'>";
			echo "window.history.back();";
			echo "</script>";
		}else{

 		  $_SESSION['teacherID']=$userid;
		//	$_SESSION['islogin']=1;
			echo "<script language='javascript' type='text/javascript'>";
			echo "window.location.href='teacher/teachermain.php'";
			echo "</script>";
		}

}

/* 学生登录 */			 
if($usertype==0)
{
	if(!log_student($userid,$userpwd)){
		$_SESSION['loginfail']=1;
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.history.back();";
		echo "</script>";
	}
	else {	 
		$_SESSION['stuID']=$userid;
		//$_SESSION['islogin']=1;
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='student/studentmain.php'";
		echo "</script>";
	}
}
	
?>
</body>
</html>