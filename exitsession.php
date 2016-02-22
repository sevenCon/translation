<?php
session_start();
require "functionfile/login.func.php";
require "functionfile/config.inc.php";
/*if(isset($_SESSION['stuID'])){
	destroy($_SESSION['stuID']);
}
if(isset($_SESSION['teacherID'])){
	log_TeacherLogout($_SESSION['teacherID']);
}  */
session_destroy();
echo "<script language='javascript' type='text/javascript'>";
echo "window.location.href='index.php'";
echo "</script>";
?>