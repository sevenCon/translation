<?php 
	session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}
	include('../header.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>教师管理页面</title>
</head>
<body>
	
	1、作业管理	   <br /> 
	<a href="HomeworkList.php">作业列表</a>	   <br />
	<a href="HomeworkAdd.php">增加作业</a>	    <br />  <br />

	2、题库管理		  <br />   
	<a href="QuestionList.php">题目列表</a>	  <br />
	添加题目 <br />
	 <a href="etocAdd.php">英译中词语翻译</a>	 <br />
	 <a href="ctoeAdd.php">中译英词语翻译</a>	 <br />
	 <a href="sentAdd.php">句子翻译</a>	 <br />
	 <a href="paraAdd.php">段落翻译</a>	 <br />
	 <a href="artiAdd.php">文章翻译</a>	   <br /><br />
	<br />	 <br />

	3、词库管理		<br />		   
	<a href="SynList.php">同义词列表</a>	 <br />
	 <a href="SynAdd.php">增加同义词</a>	 <br />
	 <a href="NegList.php">同义否定词列表</a>	 <br />
	 <a href="NegAdd.php">增加同义否定词</a>	 <br />
	 <a href="WordListAdd.php">增加词库分词</a>	   <br />
     <br />	 <br />

	4、学生管理	<br />		   
	<a href="StudentScore.php">查看学生成绩</a>	 <br />
</body>
</html>