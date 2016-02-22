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
<script type="text/javascript" >
function checkForm()
{
	
	
	if(!confirm("提交后将不能更正，提交请点击确定，否则点击取消"))
		{
			return false;
		}
	return true;
}

</script>
<?php 
?>
</head>

<body style="background-image:none;">
<div class="panelHeader">
	<h2>句子翻译</h2>
	</div>
<?php 

require "../functionfile/config.inc.php";

 $id=$_GET["id"];  //title

 $sql= "select * from questions where question_id='". $_SESSION['str'.$id]."'";//title-id
  $result=mysql_query($sql);
  while($result_set=mysql_fetch_array($result))
  {
	$re=$result_set['question_text']  ;
	$qid=$result_set['question_id'];

	//
	$total_score = $result_set['mark'];
	echo "<div class='feedbackPanel'>";
	echo  "分值：".$total_score."<br />" ;
	echo "<br/>";
	echo  "题目：<br />";
	echo  $re."<br/>";
	echo   "<FORM METHOD=POST ACTION='score.php' onSubmit='return checkForm()'> " ;
	echo "<br/>";
	echo  "答案：<br/><br/><textarea name='daan' cols='50' rows='14' value='' class='notdone_textarea'> </textarea><br /><br />";
	echo  "<input type='hidden' name='qid' value='$qid'>";
	echo "<input type='submit' class='button_style' value='提交' ;/>";
	 echo  "</FORM>" ;
	 echo "</div>";
  }

?>

<br/><br/>
<a class="btn btn-success btn-return" href="sentencetranslation_hanyue.php" >返回	</a>

</body>


</html>