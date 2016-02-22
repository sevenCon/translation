<?php
session_start();
$submitsuccess=1;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<title>提交自主词语翻译</title>
</head>

<body>
<?php
require('../functionfile/config.inc.php');
require('../functionfile/student.func.php');

/* get etoc result  取值*/
	$etoc_count=$_POST['etoc_count'];
	$etoc_result=array();
	$etoc_result[0]=$etoc_count;
	$etoc_list=array();
	$etoc_list[0]=$etoc_count;
	for($i=1;$i<=$etoc_count;$i++){
		$etoc_name="etoc".$i;
		if(isset($_POST[$etoc_name])){
			$etoc_result[$i]=trim($_POST[$etoc_name]);
		}
		else{
			$etoc_result[$i]='';
		}
		$etoc_id_name="etoc_id".$i;
		if(isset($_POST[$etoc_id_name])){
			$etoc_list[$i][0]=$_POST[$etoc_id_name];
		}
		else{
			$etoc_list[$i][0]='';
		}
		$etoc_ques_name="etoc_ques".$i;
		if(isset($_POST[$etoc_ques_name])){
			$etoc_list[$i][1]=$_POST[$etoc_ques_name];
		}
		else{
			$etoc_list[$i][1]='';
		}
		$etoc_true_name="etoc_true".$i;
		if(isset($_POST[$etoc_true_name])){
			$etoc_list[$i][2]=$_POST[$etoc_true_name];
		}
		else{
			$etoc_list[$i][2]='';
		}
		$etoc_score_name="etoc_score".$i;	   //////
        if(isset($_POST[$etoc_score_name])){
			$etoc_list[$i][3]=$_POST[$etoc_score_name];
		}
		else{
			$etoc_list[$i][3]='';
		}
		//评出分数
	    $sql="select q_answer1,q_answer2,q_answer3 from qetoctranslation where q_id=".$etoc_list[$i][0]."";
		$result=mysql_query($sql);
		$re=mysql_fetch_array($result);
		if($re[1]=="") $re[1]=$re[0];
		if($re[2]=="") $re[2]=$re[0];

		   if($etoc_result[$i]==$re[0]||$etoc_result[$i]==$re[1]||$etoc_result[$i]==$re[2])
			   $etoc_list[$i][4]=$etoc_list[$i][3];
		   else {
		       $etoc_list[$i][4]=0;
		   }
	}


	/* get ctoe result  取值*/
	$ctoe_count=$_POST['ctoe_count'];
	$ctoe_result=array();
	$ctoe_result[0]=$ctoe_count;
	$ctoe_list=array();
	$ctoe_list[0]=$ctoe_count;
	for($i=1;$i<=$ctoe_count;$i++){
		$ctoe_name="ctoe".$i;
		if(isset($_POST[$ctoe_name])){
			$ctoe_result[$i]=trim($_POST[$ctoe_name]);
		}
		else{
			$ctoe_result[$i]='';
		}
		$ctoe_id_name="ctoe_id".$i;
		if(isset($_POST[$ctoe_id_name])){
			$ctoe_list[$i][0]=$_POST[$ctoe_id_name];
		}
		else{
			$ctoe_list[$i][0]='';
		}
		$ctoe_ques_name="ctoe_ques".$i;
		if(isset($_POST[$ctoe_ques_name])){
			$ctoe_list[$i][1]=$_POST[$ctoe_ques_name];
		}
		else{
			$ctoe_list[$i][1]='';
		}
		$ctoe_true_name="ctoe_true".$i;
		if(isset($_POST[$ctoe_true_name])){
			$ctoe_list[$i][2]=$_POST[$ctoe_true_name];
		}
		else{
			$ctoe_list[$i][2]='';
		}
		$ctoe_score_name="ctoe_score".$i;	   //////
        if(isset($_POST[$ctoe_score_name])){
			$ctoe_list[$i][3]=$_POST[$ctoe_score_name];
		}
		else{
			$ctoe_list[$i][3]='';
		}
		//评出分数
	    $sql="select q_answer1,q_answer2,q_answer3 from qctoetranslation where q_id=".$ctoe_list[$i][0]."";
		$result=mysql_query($sql);
		$re=mysql_fetch_array($result);
		if($re[1]=="") $re[1]=$re[0];
		if($re[2]=="") $re[2]=$re[0];

		   if($ctoe_result[$i]==$re[0]||$ctoe_result[$i]==$re[1]||$ctoe_result[$i]==$re[2])
			   $ctoe_list[$i][4]=$ctoe_list[$i][3];
		   else {
		       $ctoe_list[$i][4]=0;
		   }
	}

	echo "<div class='branch'style=\"font-size:26px;color:rgb(255, 0, 0);\">&nbsp;etoc-translation:(英译中)</div>";
    echo "<div style=\"border-bottom:1px dashed #cccccc;\"></div><br/>";
	if($etoc_count>=1)
        for($i=1;$i<=$etoc_count;$i++){
		echo $i.")&nbsp;";
		echo $etoc_list[$i][1];
		echo "<br />";
		echo "正确答案是: ".$etoc_list[$i][2];
		echo "<br />";
		echo "你的答案是: ".$etoc_result[$i];
		echo "<br />";							/////
		echo "总分是: ".$etoc_list[$i][3];	 //////
		echo "<br />";							/////
		echo "您的分数是: ".$etoc_list[$i][4];	 //////
		echo "<br />";
		echo "<br />";
	}
	else  echo "<div style=\"font-size:26px;color:rgb(99, 99, 99);\">No Answer!</div>"; 

	echo "<div class='branch'style=\"font-size:26px;color:rgb(255, 0, 0);\">&nbsp;ctoe-translation:(中译英)</div>";
    echo "<div style=\"border-bottom:1px dashed #cccccc;\"></div><br/>";
	if($ctoe_count>=1)
        for($i=1;$i<=$ctoe_count;$i++){
		echo $i.")&nbsp;";
		echo $ctoe_list[$i][1];
		echo "<br />";
		echo "正确答案是：".$ctoe_list[$i][2];
		echo "<br />";
		echo "你的答案是 ".$ctoe_result[$i];
		echo "<br />";
		echo "总分是: ".$ctoe_list[$i][3];	 //////
		echo "<br />";							/////
		echo "您的分数是: ".$ctoe_list[$i][4];	 //////
		echo "<br />";
	}
	else  echo "<div style=\"font-size:26px;color:rgb(99, 99, 99);\">No Answer!</div>"; 
	
?>
<br /><br />

</body>
</html>