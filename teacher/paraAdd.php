<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	include '../functionfile/config.inc.php';
	include '../functionfile/teacher.func.php';
	include 'fun.php';
	define('TB_P','paragraph');//问题表
	define('TB_ANS','answers');// 标准答案表
	define('TB_NEG','negative');//同义否定词表
	define('TB_WORD','words');//词典表
	define('TB_SW','split_words');//分词结果表
    define('TB_SYN','synonyms');//同义词表	

	$wenti=$_REQUEST['wenti'];
	$daan=$_REQUEST['daan'];	
	$unit=$_REQUEST['unit'];
	$type=$_REQUEST['type'];

	//echo  $type;
	$fenshu=$_REQUEST['fenshu'];
	$publish_date = date('Y-m-d H:i:s');

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

if (isset($_POST['sub'])){
	if ($_POST['sub']=="确认"){
		
		if($wenti=="" || $daan==""){ 
			echo  "<script>alert('问题或答案内容不能为空！');location.href='paraAdd.php';</script>";
		}else{

		 $query="insert into paragraph set   test_id=".$_SESSION['id'].",text='".$wenti."',answer='".$daan."',mark='".$fenshu."',publish_date='".$publish_date."',unit=".$unit.",type='".$type."'";
		 $result=mysql_query($query);
		 
		
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
			return fetchRow(TB_P, $where, '', '');
		}

	  $q_row=getQ(array('publish_date'=>$publish_date));
	 //增加函数的地方

	 function getAnswer($p_id){	  //这里修改过，少了一个参数aid
			$where = array('p_id'=>$p_id);
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
	 function postSplitwords($a_id,$p_id,$order,$paragragh,$word,$type,$weight=10){
			
			$data = array('answer_id'=>$a_id,'p_id'=>$p_id,'order'=>$order,'word'=>$word,'paragragh'=>$paragragh,'wordtype'=>$type,'weight'=>$weight);
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
			
			if(!preg_match('/^\s+$/',$word) && $word !=''){//执行一个正则表达式匹配/^\s+$/开始结束
				$sql = "SELECT * FROM `".TB_NEG."` WHERE `word1`='$word' OR `word2`='$word' OR `word3`='$word' OR `word4`='$word' OR `word5`='$word'";
				if(excute($sql)){
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
			$quiz['p_id'] = $q_row['p_id'];
			$ws = new wordSplit();
			$split_word = $ws->reverseSplitAnswer($quiz['answer']);	
			$quiz['split_words'] = $ws->getRealtSplitWords();
									

			postAnswer($quiz);
			$p_answer=getAnswer($q_row['p_id']);
			// print_r($p_answer);
			$wordtype_num=$ws->getWordTypeNum();//$wordTypeNum;
		    $words = $split_word;	 
									
			$p = '';
			$n = 1;
			if(!empty($words)){
				foreach($words as $w){
						$order = 1;
						foreach($w as $k){
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
									
							}else if(in_array($k['type'],$nounverb_arr)){	
									if($wordtype_num['others_num'] == 0){
										
										$weight = $nv_w/$wordtype_num['nounverb_num'];
									}else if($wordtype_num['others_num']>0 && $wordtype_num['nounverb_num']<$nv_w/11)
										$weight = 11;
									else $weight = 10;
							}
							postSplitwords($p_answer['answer_id'],$quiz['p_id'],$order,$n,$k['word'],$k['type'],$weight);
								
							$order++;
							}

							$n++;
					}
				}
								

		    echo"<script>alert('上传题目成功！');location.href='QuestionList.php';</script>";
         }else{

		  echo"<script>alert('存入数据库出错!');location.href='paraAdd.php';</script>";
         }

	    } 
	}
	if ($_POST['sub']=="取消")
	{
		echo"<script>location.href='paraAdd.php';</script>";
	}
  
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>增加段落翻译题目</title>
<link rel="stylesheet" type="text/css" href="../css/classinfo.css"></link>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/setpaperscore.js"></script>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body style="background-image:none;">
<div class="panelHeader">
  <h2>增加段落翻译练习</h2>
</div>

      
<div class="feedbackPanel">	   
     <form action='' method='get'>
	     &nbsp&nbsp&nbsp所属作业：<select name="homeworkList" id="homeworkList" style="width:100px">
		<?php 
			$homeworkList=GetHomeworkList();	
			for($i=1;$i<=$homeworkList[0];$i++){
			   if($i==1){	   
			     echo "<option selected='selected' value='".$homeworkList[$i][0]."'>".$homeworkList[$i][1]."</option>";
				}else{
				 echo "<option value='".$homeworkList[$i][0]."'>".$homeworkList[$i][1]."</option>";
				}	
				
			}
		?>
		<input name="btn_pro" type="submit" value="Submit"/><br /><br />
		</select>
	</form>
	 <?php 
	 if(isset($_GET['btn_pro'])){
	     $_SESSION['id']=$_GET[homeworkList];
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
		 <div style="float:left;">&nbsp&nbsp&nbsp题目：</div><div><textarea name="wenti" cols="50" rows="14"style="margin-left: 26;"></textarea><br /><br /> </div>
		 <br />
      	 <div style="float:left;">&nbsp&nbsp&nbsp答案：</div>
		 <div>
		 <textarea name="daan" cols="50" rows="14"style="margin-left: 26;"></textarea>
		 </div>
      <br /><br /><br />
	   &nbsp&nbsp&nbsp分数：<input type="text" name="fenshu" value="" cols="25"style="margin-left: 26;"> <br /><br />
	   <div style="border-top:1px solid #cccccc;height:20px;"></div>
      &nbsp&nbsp&nbsp<INPUT class="btn btn-success" TYPE="submit" value="确认" name="sub">
      &nbsp&nbsp&nbsp<INPUT class="btn btn-success"TYPE="submit" value="取消" name="sub">
      </FORM>

	  
</div>

<a class="btn btn-success btn-return" href="QuestionList.php">返回</a>
</body>
</html>


