<?php

//教师登录
function log_Teacher($tid, $pwd)
{
	$sql = "select count(*) from teacher where t_id='".$tid."' and t_pwd='".md5(md5($pwd))."' ";  //and t_isadmin='n'
	if (!($result = sys_query_db_return($sql))) return false;	
	$row = mysql_fetch_row($result);
	$count = $row[0];
	if ($count != 1) return false;
	//$sql = "update teacher set t_islogin='y' where t_id = '".$tid."'";
	//sys_query_db($sql);
	return true;
}
//学生登录
function log_Student($sid, $pwd)
{
	$sql = "select count(*) from student where s_id='".$sid."' and s_pwd='".md5(md5($pwd))."'";
	if (!($result = sys_query_db_return($sql))) return false;
	$row = mysql_fetch_row($result);
	$count = $row[0];
	if ($count != 1) return false;
	//$sql = "update student set s_islogin='y' where s_id = '".$sid."'";
	//sys_query_db($sql);
	return true;
}
//查看教师或管理员是否已登录
function log_TeacherIsLogin($tid)
{
	$sql="select t_islogin from teacher where t_id = '".$tid."'";
	if (!($result = sys_query_db_return($sql))) return false;
	$row = mysql_fetch_row($result);
	if($row[0]=='y') return true;
	return false;
}
//查看学生是否已登录
function log_StudentIsLogin($sid)
{
	$sql="select s_islogin from student where s_id = '".$sid."'";
	if (!($result = sys_query_db_return($sql))) return false;
	$row = mysql_fetch_row($result);
	if($row[0]=='y') return true;
	return false;
}
//教师或管理员退出登录
function log_TeacherLogout($tid)
{
	$sql="update teacher set t_islogin='n' where t_id = '".$tid."'";
	return sys_query_db($sql);
}
//学生退出登录
function log_StudentLogout($sid)
{
	$sql="update student set s_islogin='n' where s_id = '".$sid."'";
	return sys_query_db($sql);
}
?>