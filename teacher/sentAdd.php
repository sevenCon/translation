<?php 
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	include '../functionfile/config.inc.php';
	include '../functionfile/teacher.func.php';
	//include '../functionfile/globle.func.php';
	include 'fun.php';
	//require("../functionfile/common.func.php");
	define('TB_Q','questions');//问题表
	define('TB_ANS','answers');// 标准答案表
	define('TB_NEG','negative');//同义否定词表
	define('TB_WORD','words');//词典表
	define('TB_SW','split_words');//分词结果表
    define('TB_SYN','synonyms');//同义词表	 ///
	$wenti=$_REQUEST['wenti'];
	$daan=$_REQUEST['daan'];
	$unit=$_REQUEST['unit'];
	$type=$_REQUEST['type'];
	$fy_type=$_REQUEST['fy_type'];
	$fenshu=$_REQUEST['fenshu'];
	$publish_date = date('Y-m-d H:i:s');
	// echo  "ff".$_SESSION['id'];

//获取作业列表
function GetHomeworkList()
{
	$sql = "select test_id, test_title, test_desc, limit_time, is_closed, is_published from tests";
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
	}
	$ret[0] = $cnt;
	return $ret;
}



if (isset($_POST['sub_yes']))	 {
 if ($_POST['sub_yes']=="确认")
	{
		if($wenti=="" || $daan==""){ 
			echo  "<script>alert('问题或答案内容不能为空！');location.href='sentAdd.php';</script>";
		}else{

		if($_POST['fy_type']=='hyy'){
			$table_ques='questions_hanyue';
		}else{
			$table_ques='questions';
		}
			$query="insert into {$table_ques} set   test_id=".$_SESSION['id'].",question_text='".$wenti."',answer='".$daan."',mark='".$fenshu."',publish_date='".$publish_date."',q_unit=".$unit.",type='".$type."'";				 //question-sentence,,question_title-test_id
			$result=mysql_query($query);
		 
		
	;

    if($result=='true'){
	   $result1;
   	   $rows;

	 
	   $num_arr;
	   $others_arr;
	   $nounverb_arr;
	 $num_arr=array('num','c_num','digit','percent','c_percent');
	 $others_arr = array('quan','idom','adv','adj','other');
	 $nounverb_arr = array('noun','verb');	 
	 //增加题目
	 function updateRow($table,$data,$where){
		 global $result1;
			if($table == '') return false;
			
			$data_sql = '';
			if(is_array($data) && count($data)){
				foreach($data as $key=>$val){
					$data_sql .= ", `$key`='$val'";	 //连续定义变量
				}
				$data_sql = strlen($data_sql)==0?'':"set ".substr($data_sql,1);	  //去、
			}
			
			$where_sql = '';
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3); //去and
			}
			$sql = "update `$table` $data_sql $where_sql;";
			$result1 = mysql_query($sql) or die(mysql_error().$sql);
			return true;
		}
		function updateAnswer($a_id,$data){
			$where = array('answer_id'=>$a_id);
			updateRow(TB_ANS,$data,$where);
		} 
		
		//增加题目结束
		function fetchRow($table, $where=array()){
		    global $result1;
			global $rows;
			if($table == '') return false;
			$where_sql = '';
			
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			$sql = "select * from `$table` $where_sql;";
			//return $this->sql;
			$result1 = mysql_query($sql) or die(mysql_error().$sql);
			if(!$result1) return false;
			$rows = mysql_fetch_array($result1); //结果集中取一行
			return mysql_num_rows($result1)?$rows : array(); //函数返回结果集中行的数目

		}	
		function getQ($where){
			return fetchRow(TB_Q, $where, '', '');
		}

	  $q_row=getQ(array('publish_date'=>$publish_date));
	 //增加函数的地方

	 function getAnswer($q_id){	  //这里修改过，少了一个参数aid
			$where = array('question_id'=>$q_id);
			return fetchRow(TB_ANS,$where);
	 }

	 function postRow($table,$data){	//insert
		 global $result1;
			if($table == '') return false;
			
			$data_sql = '';
			if(is_array($data) && count($data)){
				foreach($data as $key=>$val){
					$data_sql .= ", `$key`='$val'";
				}
				$data_sql = strlen($data_sql)==0?'':"set ".substr($data_sql,1);
			}
			
			$sql = "insert into `$table` $data_sql;";
			$result1 = mysql_query($sql) or die(mysql_error().$sql);
			return true;
	 }
	 function postSplitwords($a_id,$q_id,$order,$paragragh,$word,$type,$weight=10){
			
			$data = array('answer_id'=>$a_id,'question_id'=>$q_id,'order'=>$order,'word'=>$word,'paragragh'=>$paragragh,'wordtype'=>$type,'weight'=>$weight);
			//print_r($data);
			postRow(TB_SW,$data);
		}	
      
         function postAnswer($data){
			return postRow(TB_ANS,$data);
		}

		function excute($sql){
			 global $result1;
			
			$sql1 = $sql;
			$result1 = mysql_query($sql1) or die(mysql_error().$sql1);
			return $result1;
		}

		function getRows(){
			global $result1;
			global $rows;
			if($result1){
				$rows = array();
				while($row = mysql_fetch_array($result1)){
					array_push($rows,$row);	  //$rows尾部添加$row
				}
				
				return $rows;
			}
			return false;
		}

		function getNegWords($word){
			//取得同义否定词
			if(!preg_match('/^\s+$/',$word) && $word !=''){//执行一个正则表达式匹配/^\s+$/开始结束
				$sql = "SELECT * FROM `".TB_NEG."` WHERE `word1`='$word' OR `word2`='$word' OR `word3`='$word' OR `word4`='$word' OR `word5`='$word'";
				if(excute($sql)){
					//print_r($this->db->getRows());
					return getRows();
				}
			}
			return false;
		}

		
		
	 function getWords($word){
			$where = array('word'=>$word);
			$getword = fetchRow(TB_WORD,$where);
			if(preg_match('/^[\-0-9A-Za-z]+$/',$word)) $getword['noun'] = 1;
			if(preg_match('/^\d+\%$/',$word)) $getword['percent'] = 1;
			if(is_numeric($word)) $getword['digit'] = 1;
			if(isChinesePercent($word)) $getword['c_percent'] = 1;
			if(isChineseNum($word)) $getword['c_num'] = 1;
			if($word == '被') $getword['passtive'] = 1;
			$neg_arr = getNegWords($word);
				//print_r($neg_arr);
			if(!empty($neg_arr)) $getword['neg'] = 1;
			return $getword;
		}


			                        $quiz=array();
				                	$quiz['answer'] =$daan;						 
									$quiz['question_id'] = $q_row['question_id'];
									$ws = new wordSplit();
									$split_word = $ws->reverseSplitAnswer($quiz['answer']);	
									$quiz['split_words'] = $ws->getRealtSplitWords();
									//var_dump($quiz['split_words']);
									

									postAnswer($quiz);
									
									$p_answer=getAnswer($q_row['question_id']);
									
									$wordtype_num=$ws->getWordTypeNum();//$wordTypeNum;
									
								    $words = $split_word;	 
									/**
									*	①赋权值：
									*	h:动词和名词的总个数
									*	g:剩余的关键词个数
									*   句子总分=10×关键词个数n
									*   否定词默认权值=12 
									*   否定词总分=12×否定词个数m
									*   数词默认权值=8 
									*   数词总分=8×数词个数k
									*   (名词+动词)的权值=11 if h==0:(10n-12m-8k)/h
									*   (语句修饰成分：形容词+副词.etc)的权值=(10n-12m-11h-8k)/g //Old:(10n-12m-8k)×40%/h
									******************************
									*	总分=10n 否定词=12m 数词=8k 
									*	(1)h=0, 名词动词=（10n-12m-8k）/g
									*	(2)h>0且g<（10n-12m-8k）/11 ,名词动词=11,others=（10n-12m-8k-11g）/h
									*	(3)h>0且g>（10n-12m-8k）/11 ,名词动词=10,others=（10n-12m-8k-10g）/h
									*   ②句子得分=通顺度得分×40%+(否定词得分+数词得分+剩余关键词完全匹配得分+剩余关键词不完全匹配得分)/关键词个数n×60%
									**/
									$p = '';
									$n = 1;
									if(!empty($words)){
										foreach($words as $w){
											$order = 1;
											//$splitwords = '';
											
											foreach($w as $k){
												//$splitwords .= $v.'|';
												$weight = 10;
												$nv_w = 10*$wordtype_num['total']-12*$wordtype_num['neg_num']-8*$wordtype_num['num_num'];
												if($k['type'] == 'passtive'){//有被动词
												updateAnswer($p_answer['answer_id'],array('has_passtive'=>1,'passtive_pos'=>$order));
												$weight = 0;//被动词不纳入计分
												}else if(in_array($k['type'],$num_arr)){
												$weight = 8;
												}else if($k['type'] == 'neg'){
												$weight = 12;
												}else if(in_array($k['type'],$others_arr)){
												if($wordtype_num['nounverb_num']<$nv_w/11)
												$weight = ($nv_w-11*$wordtype_num['nounverb_num'])/$wordtype_num['others_num'];
												else $weight = ($nv_w-10*$wordtype_num['nounverb_num'])/$wordtype_num['others_num'];
												//echo  $wordtype_num['others_num'];//输出
												}else if(in_array($k['type'],$nounverb_arr)){	
												if($wordtype_num['others_num'] == 0){
												//echo  $wordtype_num['nounverb_num'];//输出
												$weight = $nv_w/$wordtype_num['nounverb_num'];
												}
												else if($wordtype_num['others_num']>0 && $wordtype_num['nounverb_num']<$nv_w/11)
												$weight = 11;
												else $weight = 10;
												}
												postSplitwords($p_answer['answer_id'],$quiz['question_id'],$order,$n,$k['word'],$k['type'],$weight);
								
												$order++;
											}
											//$p .= $splitwords."#";
											$n++;
										}//end foreach
									}//end if
								//}
						//	}					
	
					//	}

	   
		//需要的新加函数
		

	
	
   //这里是分词的结束
		    echo"<script>alert('上传题目成功！');location.href='QuestionList.php';</script>";
         }else{

		  echo"<script>alert('存入数据库出错!');location.href='sentAdd.php';</script>";
         }

		}
	}

	if ($_POST['sub_no']=="取消")
	{
		echo"<script>location.href='sentAdd.php';</script>";
	}
	 
}
?>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>增加句子翻译题目</title>
<link rel="stylesheet" type="text/css" href="../css/classinfo.css"></link>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/setpaperscore.js"></script>
<link href="../css/style.css" rel="stylesheet" type="text/css" />

</head>

<body style="background-image:none;">
<div class="panelHeader">
<h2>
增加句子翻译练习
</h2>
</div>

      
<div class="feedbackPanel">	   
     <form action='' method='get'>
	     &nbsp&nbsp&nbsp所属作业：
		 <select name="homeworkList" id="homeworkList" style="width:100px">
		<?php 
			$homeworkList=GetHomeworkList();	//804
			for($i=1;$i<=$homeworkList[0];$i++){
			   if($i==1){	   //?
			     echo "<option selected='selected' value='".$homeworkList[$i][0]."'>".$homeworkList[$i][1]."</option>";
				}else{
				 echo "<option value='".$homeworkList[$i][0]."'>".$homeworkList[$i][1]."</option>";
				}	
		//echo "<option value='".$homeworkList[$i][0]."'>".$homeworkList[$i][1]."</option>";
				
			}
		?>
		<input name="btn_pro" type="submit" value="Submit"/><br /><br />
		</select>
	</form>
	 <?php 
	 if(isset($_GET['btn_pro'])){
	     $_SESSION['id']=$_GET[homeworkList];
		// echo  "ff".$_SESSION['id'];
		 $sql="select test_title from tests where test_id=".$_GET[homeworkList]."";
		 $result=mysql_query($sql);
		 $re=mysql_fetch_array($result);
	     echo  "已选作业为：" .$re[0]."<br/>";
	     echo "作业ID:".$_GET[homeworkList];
		 }
	 ?>

	<FORM METHOD=POST ACTION="">
		 &nbsp&nbsp&nbsp单元：<select id="unit" name="unit" style="text-align:center;width:50px;margin-left: 28px;">
						<option selected="selected">1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
					</select><br /><br />
		 &nbsp&nbsp&nbsp技巧类型：<select id="type" name="type" style="text-align:center;width:80px">
						<option selected="selected">word</option>
						<option>analyse</option>
						<option>broke</option>
						<option>switch</option>
						<option>convert</option>
					</select><br /><br />
		&nbsp&nbsp&nbsp翻译类型：<label for="yyh">越译汉</label><input type="radio" id="yyh" checked="checked" name="fy_type" value="yyh">
		<label for="hyy">汉译越</label><input type="radio" id="hyy" name="fy_type" value="hyy">
		<br /><br />
		 <div style="float:left;">&nbsp&nbsp&nbsp题目：</div><div><textarea name="wenti" cols="50" rows="14"style="margin-left: 26;"></textarea><br /><br /> </div>
		 <br />
      	 <div style="float:left;">&nbsp&nbsp&nbsp答案：</div>
		 <div>
		 <textarea name="daan" cols="50" rows="14"style="margin-left: 26;"></textarea>
		 </div>
      <br /><br /><br />
	   &nbsp&nbsp&nbsp分数：<input type="text" name="fenshu" value="" cols="25"style="margin-left: 26;"> <br /><br />
	   <div style="border-top:1px solid #cccccc;height:20px;"></div>
      &nbsp&nbsp&nbsp<INPUT class="btn btn-success" TYPE="submit" value="确认" name="sub_yes">
      &nbsp&nbsp&nbsp<INPUT class="btn btn-success"TYPE="submit" value="取消" name="sub_no">
      </FORM>

	  
</div>

<a class="btn btn-success btn-return" href="QuestionList.php">返回</a>
</body>
</html>	
