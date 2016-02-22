<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
//include('../header.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title>句子翻译</title>
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
 $flag = $_GET['flag'];
 if($flag=='hanyue'){
	 $sql = "select *  from questions_hanyue as Q, new_ctf_answer as S where Q.question_hanyue_id = S.question_id and S.question_id = ".$id." and S.answer_stu_id ='".$_SESSION['stuID']."'" ;   // modify
 }else{
	 $sql = "select *  from questions as Q, student_answers as S where Q.question_id = S.question_id and S.question_id = ".$id." and S.s_id='".$_SESSION['stuID']."'" ;   // modify
 }
  $result=mysql_query($sql);
 //echo  "sql:".$sql;
  while($re=mysql_fetch_array($result))
  {
	 echo  "题目：".$re['question_text']."<br />";
	 echo  "分值：".$re['mark']."分<br />" ;
	  if($flag=='hanyue')
		  $re['answer']=$re['answer_question'];
	 echo  "您的答案：".$re['answer']."<br />";
//	 echo  "标准答案：".$re[3]."<br />";
//	 echo  "您的分数是：".$re['real_score']."分<br />";
//	 echo  "<br/><br/>学生答案分析：<br/><br/>".$re['fb_hard']."<br/><br/>".$re['fb_analyse']."<br/><br/>".$re['fb_convert']."<br/><br/>".$re['fb_broke']."<br/><br/>".$re['fb_switch']."<br/><br/>";

	 echo  "<br/><br/><br/>";
	 $hard=$re['q_unit'];
	 $qid=$re['question_id'];
	// echo  $hard."<br/>";
//	 if($re['fb_hard']!="词汇过关"){
//		 $type="word";
//		 echo  "<a href=pushword.php?type=".$type."&hard=".$hard."&qid=".$qid.">词汇推送</a>";
//		 echo  "<br/>";
//		 echo  "<br/>";
//	 }else
//	 if($re['fb_analyse']!="单词结构正常"){
//		 $type="analyse"; echo  "<a href=pushword.php?type=".$type."&hard=".$hard."&qid=".$qid.">单词结构句子推送</a>";
//		echo  "<br/>";
//		echo  "<br/>";
//	}
	/*if($re['fb_convert']!="句子没有发生倒装"){ 
		 $type="convert"; echo  "<a href=pushword.php?type=".$type."&hard=".$hard."&qid=".$qid.">倒装句子推送</a>";
		echo  "<br/>";
		echo  "<br/>";
	}	*/ 
//	if($re['fb_broke']!="句子拆分正常"){
//		 $type="broke"; echo  "<a href=pushword.php?type=".$type."&hard=".$hard."&qid=".$qid.">拆分句子推送</a>";
//		echo  "<br/>";
//		echo  "<br/>";
//	}
	/*if($re['fb_switch']!="句子没有主被动转换"){ 
		 $type="switch"; echo  "<a href=pushword.php?type=".$type."&hard=".$hard."&qid=".$qid.">主被动句子推送</a>";
		echo  "<br/>";
		echo  "<br/>";
	}	*/ 
  }

?>
</div>
<br/><br/>
<a class="btn btn-success btn-return" href="sentencetranslation.php" >返回	</a>

</body>


</html>