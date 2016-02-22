<?php

function sys_connect()
{
	if (!($conn = mysql_connect(DB_HOST,DB_USER,DB_PWD))) exit("connect db fail!");
}

function sys_select_db()
{
	if (!mysql_select_db(DB_NAME)) exit("db select fail!");
}

function sys_set_names()
{
	if (!mysql_query("set names utf8")) exit("set names fail!");
}

function sys_query_db($sql)
{
	sys_set_names();
	//$sql = mysql_real_escape_string($sql);
	if (!mysql_query($sql)) return false;
	return true;
}

function sys_query_db_return($sql)
{
	sys_set_names();
	//$sql = mysql_real_escape_string($sql);
	if (!($result = mysql_query($sql))) return false;
	return $result;
}

?>
