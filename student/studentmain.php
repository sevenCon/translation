<?php 
	session_start();
	if(!isset($_SESSION['stuID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}

	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>学生页面</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/studentmain.js"></script>
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
        <li class="nav_topic" >
		<a onclick="set_iframe('modifyinfo')"><span><font size="2.5" color="">个人信息</font></span>
		</a>
		</li>

<!--        <li class="nav_topic">-->
<!--		<a onclick="set_iframe('wordtranslation')" class="sf-with-ul"><font size="2.5" color="">字词翻译</font>-->
<!--		</a>-->
<!--			-->
<!--        </li>-->

        <li class="nav_topic">
		<a onclick="set_iframe
('sentencetranslation')"><span><font size="2.5" color="">句译·越译汉</font></span>
		</a>
			 
		</li>
		<li class="nav_topic">
		<a onclick="set_iframe
('sentencetranslation_hanyue')"><span><font size="2.5" color="">句译·汉译越</font></span>
		</a>

<!--		</li>-->
<!--		<li class="nav_topic"><a onclick="set_iframe-->
<!--('articletranslation')"><span><font size="2.5" color="">篇章翻译</font></span></a>-->
<!--        </li>-->
		
		<li><a href="../exitsession.php"><span><font size="2.5" color="">退出登陆</font></span></a></li>
      </ul>
      <!-- END #primary-nav -->
    </div>
  </div>
</div>


<!--Middle section-->
<div id="middle-sdo1">
  	<!-- <div id="leftPanel">
	<div class="panelHeader">
	<h2>题目列表</h2>
	</div>
	<div id="leftPanelContent">
	
	<li id="nav1"><a id="a1" class="leftPanelList" onclick="set_iframe('wordtranslation')">字词翻译</a></li>
	<li id="nav2"><a id="a2"class="leftPanelList"onclick="set_iframe
('sentencetranslation')">句子翻译</a></li>
	<li id="nav3"><a id="a3"class="leftPanelList"onclick="set_iframe
('paragraphtranslation')">段落翻译</a></li>
	<li id="nav4"><a id="a4"class="leftPanelList"onclick="set_iframe
('articletranslation')">篇章翻译</a></li>
    <li id="nav5"><a id="a5"class="leftPanelList" onclick="set_iframe('modifyinfo')">个人信息</a></li>

	</div>
	</div> -->
  
    <div id="middle">
      <iframe id="iframe" name="content"width="100%"height="100%"frameborder="0"
		src="sentencetranslation.php" style="overflow:auto;"></iframe>
      		  
        		
      <div class="clearfix"> </div>
    </div>
  
</div>
<!--Middle section End -->

<!--Footer -->
<div id="footer">
<img src="../image/hidden.png" id="footer-hidden" alt="" />
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
      <div class="address"><img height="18" width="14" alt="" src="../image/phone.png"> <strong>Phone:</strong>13580348209</div>
      <div class="address"><img height="12" width="15" alt="" src="../image/mail.png"> <strong>Email:</strong>kevin@xuuue.cn</div>
    </div>
    <div class="footer-details no-margin">
      <h3>友情链接</h3>
	 
      <!-- <p> <a href="http://fanyi.youdao.com"><font size="2" color="white">有道翻译</font></a></p> -->
	  <br>
	  <!-- <p> <a href="http://202.116.196.4/ees"><font size="2" color="white">英语系统</font></a></p> -->
	  <br>
	  <!-- <p> <a href="#"><font size="2" color="white">EES</font></a></p> -->
    </div>
  </div>
  <!--Footer Info-->
  <div class="Finfo">
    <div class="copyright"> &copy; Copyright 2013 by <a href="#">gdufs.com</a></div>
  </div>
</div>


</body>
</html>
