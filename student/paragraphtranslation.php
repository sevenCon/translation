<?php
session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>段落翻译页面</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body style="background-image:none;">
<div class="panelHeader">
	<h2>段落翻译</h2>
	</div>
<div>
<?php

require "../functionfile/config.inc.php";

$branch = 1; //   用于题号
echo "<div class='questionLeftTop'></div>"; //增加观看做过的题目
echo  "<div class='questionRightTop'></div>";
echo  "<div class='questionBottom'><h1>已做的题目<br /></h1>";
echo  "<div class='questionInner'>";
$query_st = "select *  from paragraph as P, student_answers as S where P.p_id = S.p_id and S.s_id ='".$_SESSION['stuID']."';";	

$result_st = mysql_query($query_st);

while ($re = mysql_fetch_array($result_st))	  
{  
	echo "<a class='btn btn-success' href =showparagraphdone.php?id=".$re[p_id].">" . $re[p_id] . "</A>" ;	//title-id
	$cnt++;

	$branch++;
}	
echo  "</div>";
echo  "</div>";
echo  "<br /><br />" ;

$branch = 1;
echo "<div class='questionLeftTop'></div>";
echo  "<div class='questionRightTop'></div>";
echo  "<div class='questionBottom'><h1>可做的题目<br /></h1>";
echo  "<div class='questionInner'>";
$query = "select distinct p_id  from paragraph where p_id not in( select p_id  from student_answers where s_id = '" . $_SESSION['stuID'] . "') and p_id != 0;";	//要注意p_id ！==0 否则会取出句子翻译题目

$result = mysql_query($query);
$cnt = 0;
while ($re = mysql_fetch_array($result))
{
	$_SESSION['str' . $cnt] = $re[0];
	echo "<a class='btn btn-success'href ='showparagraph.php?id=".$cnt."'>"  . $re[0] . "</A>" ;
	$cnt++;
	$branch++;
}
echo  "</div>";
echo  "</div>";
echo  "<div class='pageBottom'></div>";

?>


</body>
</html>