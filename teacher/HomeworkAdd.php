<?php 
	session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}

	require "../functionfile/config.inc.php";

$test_title = $_POST['test_title'];
$test_desc = $_POST['test_desc'];
$limit_time = $_POST['limit_time'];

if (isset($_POST['sub_delete']))
{ if ($_POST['sub_delete']=="确认")
	{
		if($test_title==''){
			echo  "<script>alert('作业名称不能为空！');location.href='HomeworkAdd.php';</script>";
		}else{

			$query="insert into tests set test_title='".$test_title."',test_desc='".$test_desc."',limit_time=".$limit_time.";";	
		    $result=mysql_query($query);

			if($result=='true')
			{ echo"<script>alert('增加作业成功！');location.href='HomeworkList.php';</script>";
			}else{
			  echo"<script>alert('增加作业失败！');location.href='HomeworkList.php';</script>";
			}
		}
	}else if ($_POST['sub_delete']=="取消")
	{
		echo"<script>location.href='HomeworkAdd.php';</script>";
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>教师管理页面</title>
</head>
<body>

<FORM METHOD=POST ACTION="">
		 &nbsp&nbsp&nbsp作业名称：<input type="text" name="test_title" > </input><br /><br />
      	 &nbsp&nbsp&nbsp作业描述：<input type="text" name="test_desc"> </input>
      <br /><br /><br />
	   &nbsp&nbsp&nbsp限时：<input type="text" name="limit_time" value="" cols="25"> <br /><br />
      &nbsp&nbsp&nbsp<INPUT TYPE="submit" value="确认" name="sub_delete">
      &nbsp&nbsp&nbsp<INPUT TYPE="submit" value="取消" name="sub_delete">
</FORM>
<br /><br />

</body>
</html>