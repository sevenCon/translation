<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title>段落翻译</title>
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
</head>

<body style="background-image:none;">
<div class="panelHeader">
    <h2>段落翻译</h2>
    </div>
<div class="feedbackPanel">
<?php 

require "../functionfile/config.inc.php";

 $id=$_GET["id"];  //title
// echo  "id=".$id."str=".$_SESSION['str'.$id]."	";
  $sql= "select * from paragraph where p_id='". $_SESSION['str'.$id]."'";//title-id
  $result=mysql_query($sql);
  while($result_set=mysql_fetch_array($result))
  {
	$re=$result_set['text']  ;
	$pid=$result_set['p_id'];

	$total_score = $result_set['mark'];
	echo  "分值：".$total_score."<br />" ;
    echo "<br/>";
	echo  "题目：<br />";
	echo  $re."<br/>";
	echo   "<FORM METHOD=POST ACTION='parascore.php' onSubmit='return checkForm()'> " ;
    echo "<br/>";
	echo  "答案：<br/><br/><textarea name='daan' cols='40' rows='15' value='' class='notdone_textarea'> </textarea><br /><br />";
	echo  "<input type='hidden' name='pid' value='$pid'>";
	echo "<input type='submit' class='button_style' value='提交' ;/>";
	 echo  "</FORM>" ;
  }

?>
</div>
<br/><br/>
<a class="btn btn-success btn-return" href="paragraphtranslation.php" >返回   </a>

</body>


</html>