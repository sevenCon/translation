<?php
   SESSION_START();
   header('Content-Type: text/html; charset=utf-8');
   require("../functionfile/config.inc.php");
   include('../header.php');
  define('TB_ANS','answers');// 标准答案表
  define('TB_SA','new_ctf_answer');// 学生答案表
  define('TB_SW','split_words');//分词结果表
  define('TB_SYN','synonyms');//同义词表
  define('TB_NEG','negative');//同义否定词表
  define('TB_WORD','words');//词典表
  define('TB_Q','questions');//问题表

   $answer=$_POST['daan'];
   $qid=$_POST['qid'];
   $s_id=$_SESSION['stuID'];

$insert_data = array(
	'answer_question'=>$answer,
	'question_id'=>$qid,
	'answer_stu_id'=>$s_id
);
//var_dump($insert_data);
postAnswer($insert_data);
function postAnswer($data){//插入学生答案
	if(is_array($data)){
		//如果已有记录，则更新，无则插入
		$where = array('question_id'=>$data['question_id'],'answer_stu_id'=>$data['s_id']);
		if(getNum(TB_SA,$where))   updateRow(TB_SA,$data,$where);
		else postRow(TB_SA,$data);
	}
}

function getNum($table, $where=array()){//获取记录
	if($table == '') return false;
	$where_sql = '';
	//print_r($where);
	if(is_array($where) && count($where)){
		foreach($where as $key=>$val){
			$where_sql .= "and `$key`='$val'";
		}
		$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
	}
	$sql = "select * from `$table` $where_sql;";
	//print($this->sql);
	$result = mysql_query($sql) or die(mysql_error().$sql);
	if(!$result) return false;
	$num = mysql_num_rows($result);
	return $num;
}
function postRow($table,$data){//插入一条记录
	global  $results;
	if($table == '') return false;

	$data_sql = '';
	if(is_array($data) && count($data)){
		foreach($data as $key=>$val){
			$data_sql .= ", `$key`='$val'";
		}
		$data_sql = strlen($data_sql)==0?'':"set ".substr($data_sql,1);
	}
	$sql = "insert into `$table` $data_sql;";
	$results = mysql_query($sql) or die(mysql_error().$sql);
	return true;
}
?>
<a class="btn btn-success btn-return" href="sentencetranslation_hanyue.php" >返回	</a>