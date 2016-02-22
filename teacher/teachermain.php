<?php 
	session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教师页面</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/teachermain.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<!--For Menu-->

</head>
<body>
<!--Top menu-->
<div id="top">
  <div class="top-inner">
    <div id="logo"> <a href="#"><img src="../image/logo.png" alt="" border="0" /></a> </div>
    <div id="primary-nav">
      <ul id="menu-primary-menu" class="sf-menu">
        <li>
		<a href="#"><span><font size="2.5" color="">作业管理</font></span>
		</a>
		<ul class="secondmenu">
			<li>
				<a onclick="set_iframe('homeworklist')">作业列表</a>
			</li>
			<li>
				<a onclick="set_iframe('homeworkadd');">增加作业</a>
			</li>
			<li>
				<a onclick="set_iframe('exportstuscore')">导出学生成绩</a>
			</li>
		</ul>
		</li>

        <li>
		<a href="#" class="sf-with-ul"><font size="2.5" color="">题库管理</font>
		</a>
			<ul class="secondmenu">
				<li><a onclick="set_iframe('questionlist');">题目列表</a></li>
				<li><a onclick="set_iframe('etocadd');">英译中词语翻译</a></li>
				<li><a onclick="set_iframe('ctoeadd');">中译英词语翻译</a></li>
				<li><a onclick="set_iframe('sentadd');">句子翻译</a></li>
				<li><a onclick="set_iframe('paraadd');">段落翻译</a></li>
				<li><a onclick="set_iframe('artiadd');">文章翻译</a></li>
			</ul>
        </li>
        <li>
		<a href="#"><span><font size="2.5" color="">词库管理</font></span>
		</a>
			 <ul class="secondmenu">
				<li><a onclick="set_iframe('synlist');">同义词列表</a></li>
				<li><a onclick="set_iframe('synadd');">增加同义词</a></li>
				<li><a onclick="set_iframe('neglist');">同义否定词列表</a></li>
				<li><a onclick="set_iframe('negadd');">增加同义否定词</a></li>
				<li><a onclick="set_iframe('wordlistadd');">增加词库分词</a></li>
			</ul>
		</li>
		<li>
		<a href="#"><span><font size="2.5" color="">学生管理</font></span>
		</a>
			<ul class="secondmenu">
				<li><a onclick="set_iframe('studentscore');">查看学生成绩</a></li>
				<li><a onclick="set_iframe('inputstudent');">导入学生</a></li>
			</ul>
		</li>
		<li>
		<a href="#"><span><font size="2.5" color="">自我管理</font></span>
		</a>
		    <ul class="secondmenu">
				<li><a onclick="set_iframe('modifyinfo');">修改个人密码</a></li>
			</ul>
		</li>
		<li><a href="../exitsession.php"><span><font size="2.5" color="">退出登陆</font></span></a></li>
      </ul>
      <!-- END #primary-nav -->
    </div>
  </div>
</div>


<!--Middle section    scrolling="no"  -->
<div id="middle-sdo1">
  <div id="sdo2">
  
    <div id="middleTeacher">
      <iframe id="iframe" name="content"width="100%"height="630px;"frameborder="0"
		src="info.php" style="overflow:auto;"></iframe>
      		  
        		
      <div class="clearfix"> </div>
    </div>
  </div>
</div>
<!--Middle section End -->

<!--Footer -->
<div id="footer">
  <div id="footer-inner">
    <div class="client-say">
      <h3>翻译系统服务</h3>
      <img src="../image/client.jpg" alt="" class="flleft grayborR" />
      <font size="2" color="">
	  <br>
	  <p>敬请期待更多翻译系统服务</p>
	  <p>我们的服务，你值得拥有！</p>
	  </font>
    </div>
    <div class="footer-details">
      <h3>联系我们</h3>
	  <br>
      <div class="address"><img alt="" src="../image/address.png"> <strong>Address:</strong> 广外13栋 </div>
      <div class="address"><img height="18" width="14" alt="" src="../image/phone.png"> <strong>Phone:</strong> 110</div>
      <div class="address"><img height="12" width="15" alt="" src="../image/mail.png"> <strong>Email:</strong> <a href="#">gdufs@163.com</a></div>
    </div>
    <div class="footer-details no-margin">
      <h3>友情链接</h3>
	 
      <p> <a href="http://fanyi.youdao.com"><font size="2" color="white">有道翻译</font></a></p>
	  <br>
	  <p> <a href="http://202.116.196.4/ees"><font size="2" color="white">英语系统</font></a></p>
	  <br>
	  <p> <a href="#"><font size="2" color="white">EES</font></a></p>
    </div>
  </div>
  <!--Footer Info-->
  <div class="Finfo">
    <div class="copyright"> &copy; Copyright 2013 by <a href="#">gdufs.com</a></div>
  </div>
</div>


</body>
</html>
