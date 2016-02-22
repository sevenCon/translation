<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php 
	include "../functionfile/config.inc.php";
	include "../functionfile/student.func.php";
?>
<?php 
	$studentId=$_POST['studentid'];
	$originalPwd=$_POST['originalpwd'];
	$newPassword=$_POST['newpwd'];
	if(s_SetStudentPwd($studentId, $originalPwd, $newPassword)){
		echo "true";
	}else{
		echo "false";
	}
?>