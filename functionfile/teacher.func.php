<?php

 //根据班级ID更新班级人数
function t_UpdateClassSize($cid)
{
	$sql = "select count(*) from student where c_id=".$cid;
	if (!($result = sys_query_db_return($sql))) return false;
	$row = mysql_fetch_row($result);
	$csize = $row[0];
	$sql = "update classes set c_size=".$csize." where class_id=".$cid;
	return sys_query_db($sql);
}

//导入学生信息
function t_ImportStudentInfo($cid, $filepath)
{
	require_once dirname(dirname(__FILE__))."/excelreader/Excel/reader.php";
	$data = new Spreadsheet_Excel_Reader();	
	$data->setOutputEncoding('UTF-8');
	$data->read($filepath);
	error_reporting(E_ALL ^ E_NOTICE);
	$res = true;
	for ($i = 1; $i <= $data->sheets[0]['numRows']; ++$i) 
	{
		$sid = $data->sheets[0]['cells'][$i][1];
		$sname = $data->sheets[0]['cells'][$i][2];
		$ssex = $data->sheets[0]['cells'][$i][3];
		$sql = "insert into student(s_id, s_name, s_sex, c_id, s_pwd, s_islogin) values ('".$sid."','".$sname."','".$ssex."',".$cid.",'".md5(md5('888888'))."', 'n')";
		if (!sys_query_db($sql))  $res = false;
	}
	t_UpdateClassSize($cid);
	return $res;
}



function t_GetClassListByTid($teacherId){
	$sql="select * from classes where t_id='".$teacherId."'";
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2]);
	}
	$ret[0] = $cnt;
	return $ret;
}

//增加中译英题
function t_AddCtoETranslation($sentence, $unit, $hard, $answer1, $answer2 = "", $answer3 = "",$score)
{
	$sentence = mysql_real_escape_string($sentence);
	$answer3 = mysql_real_escape_string($answer3);
	$answer2 = mysql_real_escape_string($answer2);
	$answer1 = mysql_real_escape_string($answer1);
	$sql = "insert into qctoetranslation(q_sentence, q_answer1, q_answer2, q_answer3, q_unit, q_hard, q_count, q_score, q_isopen,test_id) values ('".$sentence."','".$answer1."','".$answer2."','".$answer3."',".$unit.",".$hard.",0,".$score.",'Y',0)";	
	return sys_query_db($sql);
}
//增加英译中题
function t_AddEtoCTranslation($sentence, $unit, $hard, $answer1, $answer2 = "", $answer3 = "", $score)
{
	$sentence = mysql_real_escape_string($sentence);
	$answer3 = mysql_real_escape_string($answer3);
	$answer2 = mysql_real_escape_string($answer2);
	$answer1 = mysql_real_escape_string($answer1);
	$sql = "insert into qetoctranslation(q_sentence, q_answer1, q_answer2, q_answer3, q_unit, q_hard, q_count, q_score, q_isopen,test_id) values ('".$sentence."','".$answer1."','".$answer2."','".$answer3."',".$unit.",".$hard.",0,".$score.",'Y',0)";	
	return sys_query_db($sql);
}
//获取同义词
function t_getSynList($beg,$end) {
	$sql="select id,word1,word2,word3,word4,word5 from synonyms limit ".$beg.",".$end;
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2],$row[3],$row[4],$row[5]);
	}
	$ret[0] = $cnt;
	return $ret;
}
//获取各类型题目
function t_getStyleList($style,$beg,$end) {
	if($style==1){	//英译汉

	   $sql="select q_sentence, q_answer1, q_answer2, q_answer3, q_score, q_id from qetoctranslation limit ".$beg.",".$end; 
	   if (!($result = sys_query_db_return($sql))) return false;
	   $ret = array();
	   $cnt = 0;
	   while ($row = @mysql_fetch_array($result))
	   {
		  ++$cnt;
		  $ret[$cnt] = array($row[0], $row[1], $row[2], $row[3],$row[4],$row[5]);
	   }
	   $ret[0] = $cnt;
	   return $ret;
		
	}else if($style==2){//汉译英

		$sql="select q_sentence, q_answer1, q_answer2, q_answer3, q_score, q_id from qctoetranslation limit ".$beg.",".$end; 
	   if (!($result = sys_query_db_return($sql))) return false;
	   $ret = array();
	   $cnt = 0;
	   while ($row = @mysql_fetch_array($result))
	   {
		  ++$cnt;
		  $ret[$cnt] = array($row[0], $row[1], $row[2], $row[3],$row[4],$row[5]);
	   }
	   $ret[0] = $cnt;
	   return $ret;
	}else if($style==3 || $style==6){ //句子翻译
		if($style==3){
			$sql="select question_text, answer, mark, question_id from questions limit ".$beg.",".$end;  //as Q,tests as T where Q.test_id = T.test_id
		}else{
			$sql="select question_text, answer, mark, question_hanyue_id from questions_hanyue limit ".$beg.",".$end;  //as Q,tests as T where Q.test_id = T.test_id
		}
		if (!($result = sys_query_db_return($sql))) return false;
		$ret = array();
		$cnt = 0;
		while ($row = @mysql_fetch_array($result))
		{
		  ++$cnt;
		  $ret[$cnt] = array($row[0], $row[1], $row[2], $row[3]);	  //加了存储id的[3]
		}
		$ret[0] = $cnt;
		return $ret;
		
	}else if($style==4){
		 //段落翻译
	}else if($style==5){
		//篇章翻译

	}else{

	}
	
}
//获取同义词数量
function t_getSynListNumber() {
	$sql="select id,word1,word2,word3,word4,word5 from synonyms";
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2],$row[3],$row[4],$row[5]);
	}
	$ret[0] = $cnt;
	return $ret;
}
//获取做题情况
function t_getScoreList($q_id,$beg,$end) {
	$sql="select s_name,score, real_score ,S.s_id,question_id from student_answers as S, student as T where S.question_id=".$q_id." and S.s_id = T.s_id limit ".$beg.",".$end;
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2],$row[3],$row[4]);
	}
	$ret[0] = $cnt;
	return $ret;
}
//获取题目-根据类型
function t_getStyleNumber($style) {
	if($style==1){ //英译汉
		$sql="select q_sentence, q_answer1, q_answer2, q_answer3, q_score,q_id from qetoctranslation ;"; 
		if (!($result = sys_query_db_return($sql))) return false;
	    $ret = array();
	    $cnt = 0;
		while ($row = @mysql_fetch_array($result))
	    {
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2], $row[3],$row[4],$row[5]);
	    }
	    $ret[0] = $cnt;
	    return $ret;
		
	}else if($style==2){//汉译英
		$sql="select q_sentence, q_answer1, q_answer2, q_answer3, q_score,q_id from qctoetranslation ;"; 
		if (!($result = sys_query_db_return($sql))) return false;
	    $ret = array();
	    $cnt = 0;
		while ($row = @mysql_fetch_array($result))
	    {
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2], $row[3],$row[4],$row[5]);
	    }
	    $ret[0] = $cnt;
	    return $ret;
	}else if($style==3 || $style==6){ //句子翻译
		if($style==3){
			$sql="select question_text, answer, mark, question_id from questions ;"; //as Q,tests as T where Q.test_id = T.test_id
		}else{
			$sql="select question_text, answer, mark, question_hanyue_id from questions_hanyue ;"; //as Q,tests as T where Q.test_id = T.test_id
		}
		if (!($result = sys_query_db_return($sql))) return false;
	    $ret = array();
	    $cnt = 0;
		while ($row = @mysql_fetch_array($result))
	    {
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2], $row[3]);
	    }
	    $ret[0] = $cnt;
	    return $ret;
	}else if($style==4){
		 //段落翻译
	}else {
		//篇章翻译
	}

	
}
//获取学生做题数量
function t_getScoreNumber($q_id) {
	$sql="select s_name, score, real_score,S.s_id,question_id from student_answers as S, student as T where S.question_id=".$q_id." and S.s_id = T.s_id";
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2],$row[3],$row[4]);
	}
	$ret[0] = $cnt;
	return $ret;
}

//根据id删除同义词
function t_DeleteSynWord($wid) {
	$sql="delete from synonyms where id=".$wid;
	return sys_query_db($sql);
}
//根据id删除句子翻译题目
function t_DeleteQuestion($wid,$getname) {
	if($getname==3){
	  $sql="delete from questions where question_id=".$wid;
	}else if($getname==1){
	   $sql="delete from qetoctranslation where q_id=".$wid;
	}else if($getname==2){
	   $sql="delete from qctoetranslation where q_id=".$wid;
	}else if($getname==4){
		
	}else{
	}  
	return sys_query_db($sql);
	
}
//更新同义词
function t_UpdateSynWord($wid,$word1,$word2,$word3,$word4,$word5) {
	$sql="update synonyms set word1='".$word1."',word2='".$word2."',word3='".$word3."',word4='".$word4."',word5='".$word5."' where id=".$wid;
	return sys_query_db($sql);
}
//更新juzifanyi
function t_UpdateQuestion($wid,$word1,$word2,$word3) {
	$sql="update questions set question_text='".$word1."',answer='".$word2."',mark=".$word3." where question_id=".$wid;
	return sys_query_db($sql);
}
//更新Etoc
function t_UpdateQuestionEtoc($wid,$word1,$word2,$word3,$word4,$word5,$getname) {
	if($getname==1){
		$sql="update qetoctranslation set q_sentence='".$word1."',q_answer1='".trim($word2)."',q_answer2='".trim($word3)."',q_answer3='".trim($word4)."',q_score=".$word5." where q_id=".$wid;
	}else if($getname==2){
		$sql="update qctoetranslation set q_sentence='".$word1."',q_answer1='".trim($word2)."',q_answer2='".trim($word3)."',q_answer3='".trim($word4)."',q_score=".$word5." where q_id=".$wid;
	}
	
	return sys_query_db($sql);
}
//增加同义词
function t_AddSynWord($w1,$w2,$w3,$w4,$w5) {
	$sql="insert into synonyms(word1,word2,word3,word4,word5) values ('".$w1."','".$w2."','".$w3."','".$w4."','".$w5."')";
	 return sys_query_db($sql);
}
//增加同义否定词
function t_AddSynNegWord($w1,$w2,$w3,$w4,$w5) {
	$sql="insert into negative(word1,word2,word3,word4,word5) values ('".$w1."','".$w2."','".$w3."','".$w4."','".$w5."')";
	 return sys_query_db($sql);
}

//获取同义否定词
function t_getNegList($beg,$end) {
	$sql="select id,word1,word2,word3,word4,word5 from negative limit ".$beg.",".$end;
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2],$row[3],$row[4],$row[5]);
	}
	$ret[0] = $cnt;
	return $ret;
}
//获取同义否定词数量
function t_getNegListNumber() {
	$sql="select id,word1,word2,word3,word4,word5 from negative";
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2],$row[3],$row[4],$row[5]);
	}
	$ret[0] = $cnt;
	return $ret;
}
//根据id删除同义否定词
function t_DeleteNegWord($wid) {
	$sql="delete from negative where id=".$wid;
	return sys_query_db($sql);
}
//更新同义否定词
function t_UpdateNegWord($wid,$word1,$word2,$word3,$word4,$word5) {
	$sql="update negative set word1='".$word1."',word2='".$word2."',word3='".$word3."',word4='".$word4."',word5='".$word5."' where id=".$wid;
	return sys_query_db($sql);
}
//增加词语
//增加词语
function t_AddWord($word,$wordtype) {
	$sql="insert into words(word,".$wordtype.") values('".$word."','1')";
	 return sys_query_db($sql);
}
//根据学生id和问题id删除学生成绩
function t_DeleteStudentScore($sid,$qid){
    $sql="delete from student_answers where s_id='".$sid."'and question_id=".$qid;
    return sys_query_db($sql);
}
//根据学生id和问题id更新学生分数
function t_UpdateStudentScore($score,$realscore,$studentId,$questionId){
       $sql="update student_answers set score=".$score.",real_score=".$realscore." where s_id='".$studentId."'and question_id=".$questionId;
       return sys_query_db($sql);
}

//获取试卷列表
function t_GetQuestionList()
{
	$sql = "select q.question_id, question_text,mark,publish_date,s.s_id,s.s_name,a.answer,real_score from questions q,student_answers a,student s where q.question_id=a.question_id and a.s_id=s.s_id";
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5],$row[6],$row[7]);
	}
	$ret[0] = $cnt;
	return $ret;
}

//导出学生成绩
function t_OutportStudentScore($classid, $paperid_arr, $output_path)
{
	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	/** Include PHPExcel */
	require_once 'excelwriter/PHPExcel.php';

	// Create new PHPExcel object
	// echo date('H:i:s') , " Create new PHPExcel object" , EOL;
	$objPHPExcel = new PHPExcel();

	// Set document properties
	// echo date('H:i:s') , " Set document properties" , EOL;
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("PHPExcel Test Document")
								 ->setSubject("PHPExcel Test Document")
								 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
								 ->setKeywords("office PHPExcel php")
								 ->setCategory("Test result file");
	
	/*$sql="select question_text,real_score,student.s_id, s_name from questions,student,student_answers where student_answers.s_id= student.s_id and questions.question_id=student_answers.question_id and questions.question_id=".$question_id." and c_id=".$classid;
	 */

	//$sql = "select s_id, s_name from student where c_id = ".$classid;
	//if (!($result = sys_query_db_return($sql))) return false;
	//$student_arr = array();
	//$cnt = 0;
	//while ($row = mysql_fetch_array($result))
	//{
	//	++$cnt;
	//	$student_arr[$cnt] = array($row[0], $row[1]);
	//}
	//$student_arr[0] = $cnt;
	$excelhead = array("题目","学号","姓名","分数");


	/*for ($i = 1; $i <= $paperid_arr[0]; ++$i)	//遍历试卷
	{
		$sql = "select p_name from paper where p_id = ".$paperid_arr[$i];
		if (!($result = sys_query_db_return($sql))) $excelhead = "未命名考试";
		else
		{
			$row = mysql_fetch_row($result);
			$excelhead[$i + 1] = $row[0];	 //	第三列开始存试卷名
		}
		for ($j = 1; $j <= $student_arr[0]; ++$j)	//遍历学生
		{
			$table = "paperanswer_".$paperid_arr[$i];
			$sql = "select s_total from ".$table." where s_id = '".$student_arr[$j][0]."' limit 1";
			if (!($result = sys_query_db_return($sql))) 
			{
				$student_arr[$j][1 + $i] = "";
				continue;
			}
			$row = mysql_fetch_row($result);
			$student_arr[$j][1 + $i] = $row[0];	//存放成绩
		}
	}	*/
	$student_arr = array();
	$student_arr[0] = $paperid_arr[0];
	for($i=1;$i<=$paperid_arr[0]; ++$i)
	{
		$sql="select question_text,student.s_id, s_name,real_score from questions,student,student_answers where student_answers.s_id= student.s_id and questions.question_id=student_answers.question_id and questions.question_id=".$paperid_arr[$i]." and c_id=".$classid;
		if (!($result = sys_query_db_return($sql))) $excelhead = "没有题目";
		else
		{
			//$excelhead[$i + 1] = $row[0];	 //	第三列开始存试卷名
			//$cnt = 0;
			while ($row = mysql_fetch_array($result))
			{
				//++$cnt;
				$student_arr[$i] = array($row[0], $row[1],$row[2],$row[3]);
			}
		}
	}
	for ($i = 0; $i < 4; ++$i)
	{
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue(chr(65 + $i)."1", $excelhead[$i]);	 //the first line 
	}
	for ($i = 1; $i <= $student_arr[0]; ++$i)
	{
		for ($j = 0; $j < 4; ++$j)
		{
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(chr(65 + $j).($i + 1), $student_arr[$i][$j]); //!!A.2 start	
		}
	}
	$objPHPExcel->getActiveSheet()->setTitle("学生成绩");
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(str_replace('.php', '.xls', $output_path));

	return true;
}


function a_SetTeacherPwd($tid, $oldpwd, $newpwd)
{
	$sql = "select count(*) from teacher where t_id='".$tid."' and t_pwd='".md5(md5($oldpwd))."'";
	if (!($result = sys_query_db_return($sql))) return false;
	$row = mysql_fetch_row($result);
	$count = $row[0];
	if ($count != 1) return false;
	$sql = "update teacher set t_pwd='".md5(md5($newpwd))."' where t_id='".$tid."'";
	return sys_query_db($sql);
}
//删除现有答案
function t_deleteExitingAnswer($qid) {
	$sql2="delete from answers where question_id=".$qid;
	$sql3="delete from split_words where question_id=".$qid;
	if(sys_query_db($sql2)&&sys_query_db($sql3)) Return true;
	else Return false;
}
//根据题目id把现有学生的答案拿出来
function t_getExitingStudentAnswers($qid) {
	$sql="select question_id,s_id,answer from student_answers where question_id=".$qid;
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2]);
	}
	$ret[0] = $cnt;
	$sql2="delete from student_answers where question_id=".$qid;
	if(!sys_query_db($sql2)) Return false;
	return $ret;
}

?>