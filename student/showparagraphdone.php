<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title>段落翻译</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body style="background-image:none;">

<div class="panelHeader">
	<h2>翻译答案反馈</h2>
	</div>
<div class="feedbackPanel">
<?php 
require "../functionfile/config.inc.php";

 $id=$_GET["id"];
 $sql = "select *  from paragraph as P, student_answers as S where P.p_id = S.p_id and S.p_id = ".$id." and S.s_id='".$_SESSION['stuID']."'" ; 
  $result=mysql_query($sql);

  while($re=mysql_fetch_array($result))
  {
	 echo  "题目：".$re['text']."<br />";
	 echo  "分值：".$re['mark']."分<br />" ;
	 echo  "您的答案：".$re['answer']."<br />";
	 echo  "标准答案：".$re[3]."<br />";
	 echo  "您的分数是：".$re['real_score']."分<br />";	 
	 echo  "<br/><br/>学生答案分析：<br/><br/>".$re['fb_hard']."<br/><br/>".$re['fb_analyse']."<br/><br/>".$re['fb_convert']."<br/><br/>".$re['fb_broke']."<br/><br/>".$re['fb_switch']."<br/><br/>";
	 
	  
  }	  

?>
</div>
<br/><br/>
<a class="btn btn-success btn-return" href="paragraphtranslation.php" >返回	</a>

</body>


</html>