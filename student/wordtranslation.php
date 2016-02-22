<?php
session_start();

include('../functionfile/config.inc.php');
include('../functionfile/student.func.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>词语翻译页面</title>

<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body style="background-image:none;" id='inner_body'>
<div class="panelHeader">
<h2 class='exe-title'> 
字词翻译练习
</h2>
</div>
<div id='form_container'>
<br><br>
<form style="margin-left:35px;margin-top:5px;" name="showexercise" id="showexercise" action="submitwordtranslation.php" method="post">

<?php
$branch=1;
/**
 * display etoc
*/ 
$etoc_list = s_GetNeededProblemSet(0); //单元，题型，难度
echo "<br/><br/><div class='branch'style=\"font-size:23px;color:rgb(100, 98, 98);\">".$branch.".&nbsp;英译中</div>";
echo "<div class='dash_line'></div><br/>";
if ($etoc_list[0] != 0)
{
	echo "<input type='hidden' name='etoc_count' value='" . $etoc_list[0] . "'>"; 
	// 传递翻译题数量
	echo "<div style='margin-left:30px;'>";
	for($i = 1;$i <= $etoc_list[0];$i++)
	{
		echo "<div class='etoc_q'>" . $i . ")&nbsp;" . $etoc_list[$i][1] . "</div>";
		echo "<input type='hidden' name='etoc_id" . $i . "' value='" . $etoc_list[$i][0] . "'>"; 
		// 传递翻译题原始题目id
		echo "<input type='hidden' name='etoc_ques" . $i . "' value='" . $etoc_list[$i][1] . "'>"; 
		// 传递翻译题题目
		echo "<input type='hidden' name='etoc_true" . $i . "' value='" . $etoc_list[$i][2] . "'>"; 
		// 传递翻译题正确答案
		echo "<input type='hidden' name='etoc_score" . $i . "' value='" . $etoc_list[$i][3] . "'>"; 
		// 传递翻译题正确分数
		echo "<div class='etoc_a'>" . "<input type='text' class='input-text' name='etoc" . $i . "'  />" . "</div>";
		echo "<br />";  
	}
	echo "</div>";
	echo "<br />";
}
else
{
	echo "<div style=\"font-size:16px;margin-left:30px;color:rgb(255, 0, 0);\">Sorry ,the subjects you want are not exist!</div>";
}
$branch++;	

/**
 * display ctoe 
*/ 
$ctoe_list = s_GetNeededProblemSet(1); 
echo "<br/><br/><div class='branch'style=\"font-size:23px;color:rgb(100, 98, 98);\">".$branch.".&nbsp;中译英</div>";
echo "<div class='dash_line'></div><br/>";
if ($ctoe_list[0] != 0)
{
	echo "<input type='hidden' name='ctoe_count' value='" . $ctoe_list[0] . "'>"; 
	// 传递翻译题数量
	echo "<div style='margin-left:30px;'>";
	for($i = 1;$i <= $ctoe_list[0];$i++)
	{
		echo "<div class='ctoe_q'>" . $i . ")&nbsp;" . $ctoe_list[$i][1] . "</div>";
		echo "<input type='hidden' name='ctoe_id" . $i . "' value='" . $ctoe_list[$i][0] . "'>"; 
		// 传递翻译题原始题目id
		echo "<input type='hidden' name='ctoe_ques" . $i . "' value='" . $ctoe_list[$i][1] . "'>"; 
		// 传递翻译题题目
		echo "<input type='hidden' name='ctoe_true" . $i . "' value='" . $ctoe_list[$i][2] . "'>"; 
		// 传递翻译题正确答案
		echo "<input type='hidden' name='ctoe_score" . $i . "' value='" . $etoc_list[$i][3] . "'>"; 
		// 传递翻译题正确分数
		echo "<div class='ctoe_a'>" . "<input type='text' class='input-text' name='ctoe" . $i . "'  />" . "</div>";
		echo "<br />";
	}
	echo "</div>";
	echo "<br />";
}
else
{
	echo "<div style=\"font-size:16px;margin-left:30px;color:rgb(255, 0, 0);\">Sorry ,the subjects you want are not exist!</div>";
}
$branch++;	 

echo "<div style=\"border-bottom:1px solid #cccccc;width:650px;height:30px;float:left;\"></div>";
echo "<div style='float:left;margin-left:10px;'><input type='submit' class='button_style' value='提交' /></div>";
echo "<div style=\"border-bottom:1px solid #cccccc;width:50px;height:30px;float:left;margin-left:10px;\"></div>";

?>
</form>
</div>
</body>

</html>