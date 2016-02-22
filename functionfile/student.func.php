<?php
//设置学生密码
function s_SetStudentPwd($sid, $oldpwd, $newpwd)
{
	$sql = "select count(*) from student where s_id='".$sid."' and s_pwd='".md5(md5($oldpwd))."'";
	if (!($result = sys_query_db_return($sql))) return false;
	$row = mysql_fetch_row($result);
	$count = $row[0];
	if ($count != 1) return false;
	$sql = "update student set s_pwd='".md5(md5($newpwd))."' where s_id='".$sid."'";
	return sys_query_db($sql);
}

function s_GetProblemDetail($problemId_arr,$type)
{
	$ret_arr=array();
	if ($problemId_arr[0] == 0) 
	{
		$ret_arr[0] = 0;
		return $ret_arr;
	}
	switch ($type)
	{
	case 0:
		$db = "qetoctranslation"; $field_cnt = 4; $field = "q_id, q_sentence , q_answer1, q_score";break;
    case 1:
		$db = "qctoetranslation"; $field_cnt = 4; $field = "q_id, q_sentence , q_answer1, q_score";break;
	}
	
	$sql = "select ".$field." from ".$db." where q_id=".$problemId_arr[1];
	for ($i = 2; $i <= $problemId_arr[0]; ++$i) $sql = $sql." or q_id=".$problemId_arr[$i];
	if (!($result = sys_query_db_return($sql))) return false;
	
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		$ret_arr[++$cnt] = array();
		for ($i = 0; $i < $field_cnt; ++$i) $ret_arr[$cnt][$i] = $row[$i];
	}
	$ret_arr[0] = $cnt;
	return $ret_arr;

}
//根据单元数组，题目总体难度，题型，数量生成自主练习的某种题型的所有题目
function s_GetNeededProblemSet($type)
{
	$problemId_arr = s_GetProblemArray($type);    
	return s_GetProblemDetail($problemId_arr,$type);
}
//根据单元数组和题目类型构造二维数组（ID和难度）-------这里先忽略题目的单元和难度
function s_GetProblemArray($type)
{
	$ret_arr = array();//store problems' id
	switch ($type)
	{
        case 0: $db = "qetoctranslation";break;
		case 1: $db = "qctoetranslation";break; 
	}
	$sql = "select q_id from ".$db;     //得到的只是题目的id
	if (!($result = sys_query_db_return($sql))) return false;
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret_arr[$cnt] = $row[0];
	}
	$ret_arr[0] = $cnt;
	return $ret_arr; 
	
}
//客观题提交答案时计算得分（效率低，可能要修改）
function s_calObjectiveScore($paperid, $studentid, $type, $id_arr, $answer_arr, $ref_arr)
{
	 //提取分数并逐渐叠加
	 $sql = "select * from paperanswer_".$paperid."  where s_id = '".$studentid."'";
	 $result = sys_query_db_return($sql);
	 $row = mysql_fetch_row($result);
	 $total=$row[2];	  
	 //!!
	 
	// $total=5.0;

	switch ($type)
	{
	case 3: $db = "qlistenfillblank"; $field = "q_answer1, q_answer2, q_answer3"; break;
	default: return false;
	}

	$sql = "select p_q".$type.", p_p".$type." from paper where p_id=".$paperid;
	if (!($result = sys_query_db_return($sql))) return false;
	$row = mysql_fetch_row($result);
	$qid_arr = explode(',', $row[0]);  //题号
	$point_arr = explode(',', $row[1]);	  //分数
	for ($i = 0; $i < $id_arr[0]; ++$i)
	{
		$sql = "select ".$field." from ".$db." where q_id=".$qid_arr[$i];
		if (!($result = sys_query_db_return($sql))) continue;
		$row = mysql_fetch_row($result);
		if ($type == 3)
		{
			$score = 0.0 + sc_ListenFillBlank($answer_arr[$i + 1], array(3, $row[0], $row[1], $row[2]));
			//$score = 4;
		}
	}
}
?>