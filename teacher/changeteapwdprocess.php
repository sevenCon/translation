<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php 
	include "../functionfile/config.inc.php";
	include "../functionfile/teacher.func.php";
?>
<?php 
	$teacherId=$_POST['teacherid'];
	$originalPwd=$_POST['originalpwd'];
	$newPassword=$_POST['newpwd'];
	if(a_SetTeacherPwd($teacherId, $originalPwd, $newPassword)){
		echo "true";
	}else{
		echo "false";
	}
?>