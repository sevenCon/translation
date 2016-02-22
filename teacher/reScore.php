<?php
    session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
      include "../functionfile/config.inc.php";
      include "../functionfile/teacher.func.php";
	  include 'fun.php';

    define('TB_Q','questions');//问题表
	define('TB_ANS','answers');// 标准答案表
	define('TB_NEG','negative');//同义否定词表
	define('TB_WORD','words');//词典表
	define('TB_SW','split_words');//分词结果表
    define('TB_SYN','synonyms');//同义词表	 ///
    define('TB_SA','student_answers');// 学生答案表 
	$questionId=$_POST['wordid1'];
	$answer=$_POST['daan1'];
	//$questionId=177;
	//$answer="运行时系统只初始化一次固定变量；而对于动态变量，如果用初始程序说明，那么每当进入动态变量所在的块时，就开始重新初始化。";
	$results;
    $publish_date = date('Y-m-d H:i:s'); 
	$query="update questions set answer='".$answer."' where question_id=".$questionId;
	$result=mysql_query($query);
	//echo  $result;
	if($result=='true'){
	t_deleteExitingAnswer($questionId);
	 $num_arr=array('num','c_num','digit','percent','c_percent');
	 $others_arr = array('quan','idom','adv','adj','other');
	 $nounverb_arr = array('noun','verb');	 
	 //增加题目
	 function updateRow1($table,$data,$where){
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
			updateRow1(TB_ANS,$data,$where);
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

	  $q_row=getQ(array('question_id'=>$questionId));
	 //增加函数的地方

	 function getAnswer($q_id){	  //这里修改过，少了一个参数aid
			$where = array('question_id'=>$q_id);
			return fetchRow(TB_ANS,$where);
	 }

	 function postRow1($table,$data){	//insert
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
			postRow1(TB_SW,$data);
		}	
      
         function postAnswer1($data){
			return postRow1(TB_ANS,$data);
		}

		function excute1($sql){
			 global $result1;
			
			$sql1 = $sql;
			$result1 = mysql_query($sql1) or die(mysql_error().$sql1);
			return $result1;
		}

		function getRows1(){
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

		function getNegWords1($word){
			//取得同义否定词
			if(!preg_match('/^\s+$/',$word) && $word !=''){//执行一个正则表达式匹配/^\s+$/开始结束
				$sql = "SELECT * FROM `".TB_NEG."` WHERE `word1`='$word' OR `word2`='$word' OR `word3`='$word' OR `word4`='$word' OR `word5`='$word'";
				if(excute1($sql)){
					//print_r($this->db->getRows());
					return getRows1();
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
			$neg_arr = getNegWords1($word);
				//print_r($neg_arr);
			if(!empty($neg_arr)) $getword['neg'] = 1;
			return $getword;
		}


			                        $quiz=array();
				                	$quiz['answer'] =$answer;						 
									$quiz['question_id'] = $q_row['question_id'];
									$ws = new wordSplit();
									$split_word = $ws->reverseSplitAnswer($quiz['answer']);	
									$quiz['split_words'] = $ws->getRealtSplitWords();
									//var_dump($quiz['split_words']);
									
									postAnswer1($quiz);									
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
												
												}else if(in_array($k['type'],$nounverb_arr)){	
												if($wordtype_num['others_num'] == 0){
												
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
									}  //end if
	}
	 
	//////////////////再次分词完毕/////////////////////
	function fetchRows($table, $where=array(), $orderkey='', $order='ASC'){
		     global $results;
			if($table == '') return false;
			$where_sql = '';
			
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			
			if($orderkey) $order_sql = "order by $orderkey $order";
			
			$sql = "select * from `$table` $where_sql $order_sql;";
			
			$results = mysql_query($sql) or die(mysql_error().$sql);
			//if(!$this->result) return false;
			$rows = array();
			if(mysql_num_rows($results)){
				while($row = mysql_fetch_array($results)){
					array_push($rows,$row);
				}
			}
			
			return $rows;
			
		}
       function excute($sql){
		    global $results;
		    $sql0 = $sql;
		    $results = mysql_query($sql0) or die(mysql_error().$sql0);
		    return $results;
		 }
		function getRows(){
		   global $results;
		   if($results){
			  $rows = array();
			  while($row = mysql_fetch_array($results)){
				array_push($rows,$row);
			}
			//print($this->sql."<br/>");
			//print_r($this->rows);
			return $rows;
		}
		return false;
	   }		
       function updateRow($table,$data,$where){
	         global $results;
			if($table == '') return false;
			
			$data_sql = '';
			if(is_array($data) && count($data)){
				foreach($data as $key=>$val){
					$data_sql .= ", `$key`='$val'";
				}
				$data_sql = strlen($data_sql)==0?'':"set ".substr($data_sql,1);
			}
			
			$where_sql = '';
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			$sql = "update `$table` $data_sql $where_sql;";
			$results = mysql_query($sql) or die(mysql_error().$sql);
			return true;
		}

       function postRow($table,$data){
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
       function getNum($table, $where=array()){
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
      function isWord($word){
	    $where = array('word'=>$word);
	    return getNum(TB_WORD,$where);
      }
     function getAnswersByQid($qid){
			$where = array('question_id'=>$qid);
			return fetchRows(TB_ANS,$where,'','');
	 }
     function getSplitWordsOrder($a_id){
			$where = array('answer_id'=>$a_id);
			return fetchRows(TB_SW,$where,'`paragragh` ASC,`order`');
	 }
	/////////统计逗号的个数//////////////////
	 function countAnswerdh($answer) {
		   $count=substr_count($answer,'，');
		   Return $count;
	  }
	  function countStuAnserdh($stu_answer){
		   $count=substr_count($stu_answer,'，');
		   Return $count;
	  }
	function find_passtive($p){
		//	mb_internal_encoding('gb2312'); 
		//	mb_regex_encoding('gb2312'); 
			
			$bew = iconv('utf-8','utf-8',"被");   //第二个原本为gd2312
			
			$pos = mb_strpos($p,$bew);
			 $is_af = false;	
			if($pos !== false){
				//include(dirname(__FILE__).'/class.wordsplit.php');
				//$be_p = mb_substr($p,$s,8);//对被字前后共8个字符进行分词$ws_obj = new wordsplit();
				//$is_af = false;		  //af代表after
				
				for($j=2;$j<5;$j++){
					$word = mb_substr($p,$pos+3,$j);	//原本是$pos+1
					
					if(isWord($word)){
						$is_af = true;
						break;
					}
				}
				
				$is_be = false;			  //be代表before

				for($n=2;$n<5;$n++){
					$k = $pos-$n;
					$word = mb_substr($p,$k,$pos-$k);
					if(isWord($word)){
						$is_be = true;
						break;
					}
				}
				
				 
				if($is_af && $is_be) return $pos;
				if($is_be){
					for($j=2;$j<5;$j++){
						$word = mb_substr($p,$pos,$j);
						if(isWord($word)) return false;
					}
					return $pos;
				}
				
				if($is_af){
					for($n=2;$n<5;$n++){
						$k = $pos-$n;
						$word = mb_substr($p,$k+1,$pos-$k);
						if(isWord($word)) return false;
					}
					return $pos;
				}
				
			}
			return false;
		}
	    function getNegWords($word){
			//取得同义否定词
			$sql = "SELECT word1,word2,word3,word4,word5 FROM `".TB_NEG."` WHERE `word1`='$word' OR `word2`='$word' OR `word3`='$word' OR `word4`='$word' OR `word5`='$word'";
			if(excute($sql)){
				return getRows();
			}
			return false;
		}
	    function is_find_num($word,$p){
			require_once('./func/func.chinese_num.php');
			if($word['wordtype'] == 'c_percent'){//中文百分数
				$percent = Chinese2percent($word['word']);
				if(mb_strpos($p,$percent)) return true;
				if(mb_strpos($p,percent2digit($percent))) return true;
				if(mb_strpos($p,percent2c_digit($percent))) return true;
			}else if($word['wordtype'] == 'percent'){//百分数
				$c_percent = percent2Chinese($word['word']);
				if(mb_strpos($p,$c_percent)) return true;
				if(mb_strpos($p,percent2digit($word['word']))) return true;
				if(mb_strpos($p,percent2c_digit($word['word']))) return true;
			}else{
				$num = sprintf('%.0f',ChineseNum2digit($word['word']));
				
				if(mb_strpos($p,$num) !== false) return true;//匹配阿拉伯数字
				if(mb_strpos($p,Chinese_Num_Conv($num)) !== false) return true;//匹配中文数词
				if(mb_strpos($p,ChineseNum2point($num)) !== false) return true;//匹配阿拉伯数字加中文数词（1.2亿）
			}
			return false;
		 }
	     function getSynWords($word){
			//取得同义词
			$sql = "SELECT word1,word2,word3,word4,word5 FROM `".TB_SYN."` WHERE `word1`='$word' OR `word2`='$word' OR `word3`='$word' OR `word4`='$word' OR `word5`='$word'";
			if(excute($sql)){
				return getRows();
			}
			return false;
		 }
		
	///////////////////////////
   	    function getQuizScore($a,$stu_answer){
		//评分:总分=（完全匹配得到的分数+不完全匹配得到的分数（完全匹配分数的80%））/关键字总个数*70%+10（通顺分）*30%
	    //*	mb_internal_encoding('gb2312'); 
	//*		mb_regex_encoding('gb2312'); 
			$paragragh = $stu_answer;
			$splistwords = getSplitWordsOrder($a['answer_id']);
			//print_r($splistwords);
			$total_splitword = count($splistwords);
			if(!$total_splitword) return array(
		//		'match_result'=>iconv('utf-8','gb2312','无分词'),
			      		  'match_result'=>'无分词',
							'score'=>0,
							'match_score'=>0,
							'smooth_score'=>0,
							'smooth_result'=>'',
							'used_answer_id'=>NULL
							);
			$score = 0;
			$match_num = 0;
			//$syn_num;
			$match_result = '';
			$unsmooth_num = 0;
			$unmatch_num = 0;
			$smooth_result = '';
			$prev_pos = 0;
			$p_order  = 1;
			$nounverb = array('noun','verb');
			$num_arr = array('num','c_num','digit','percent','c_percent');
			$has_passtive = 0;
			$passtive_pos = 0;
			$stu_passtive = 0;
			/**/
			$fb_hard="词汇反馈：";
			$fb_construct="单词结构反馈：";
			$fb_convert="句子发生了动名词倒装：";
			$fb_broke="句子发生了拆分：";
			$fb_switch="句子发生了主被动转换：";
			/**/
			//用于判读断句是否有问题
			$andhcount=countAnswerdh($a['answer']);
			$stu_andhcount=countStuAnserdh($stu_answer);
			if(abs($stu_andhcount-$andhcount)>=2){
				$fb_broke.="请注意断句<br/>";
			}
			$stu_pass_pos = find_passtive($paragragh);	
			
			if($stu_pass_pos !== false){
				$stu_passtive = 1;				  //$stu_passtive代表学生答案里是否有被字
			}
			
			$o=0;
			foreach($splistwords as $sw){
				 $o++;
				if($p_order != $sw['paragragh']){
					$p_order = $sw['paragragh'];
					$prev_order = 0;
				}//句子结束重置词的位置
				
				
				if($sw['wordtype'] == 'passtive') {
					$total_splitword--;//被动词不纳入计分
					$has_passtive = 1;
					$passtive_pos = $sw['order'];		//如果分词里有被动词，则记下他的位置
					continue;
					
				}
				
				//判断是否主被动转换  //////////////////////////
				if($total_splitword==$o){
					if(($has_passtive&&!$stu_passtive)){
						$fb_switch.="最好请用被动句<br/>";
					}
					else if((!$has_passtive&&$stu_passtive)){
						$fb_switch.="最好请用主动句<br/>";
					}
				}
				//////////////////////////////

				
			    $sw_word=$sw['word'];
				$is_match = false;
				$next_pos = false;
				
				$sw_pos =  mb_strpos($paragragh,$sw['word']);
				
				
				if($sw_pos !== false){
					$score += $sw['weight'];
					
					$match_num++;
					$match_result .= "匹配到关键字【".$sw_word."】 score += ".$sw['weight']."<br />";
				
					if(in_array($sw['wordtype'],$nounverb)){
						//if(($has_passtive&&$stu_passtive)||(!$has_passtive&&!$stu_passtive)){//标答和学生答案都有或都没有“被”字：正常语序
						$be_pos = $has_passtive?$passtive_pos:$stu_pass_pos;  //被字的位置
						
						
						
						//如果还有一个相同的词在后面
						$next_word_pos = mb_strpos($paragragh,$sw['word'],$sw_pos+1);
						
						$sw_pos = ($next_word_pos !== false&&$next_word_pos>$sw_pos)? $next_word_pos:$sw_pos;
				
						if($prev_pos > $sw_pos){	//位置异常的条件
							if(($has_passtive||$stu_passtive)&&(!$has_passtive||!$stu_passtive)){//标答或学生答有“被”字：调转语序
								//进来的条件是，发现一方有被字，另一方没有被字	 
								if(!($prev_pos>$be_pos && $be_pos>$sw_pos)){//如果被字不是在两个词中间，语序有错
								
									//判断是否倒装////////////////////////////////
								  if($sw['wordtype']=='verb'){
							         $fb_convert.="关键字【".$sw_word."】跟关键字【".$splistwords[($sw['order']-1)-1]['word']."】发生了倒装<br/>";
							      }
								  //////////////////////////////////////////////////
									$unsmooth_num++;
									$smooth_result .= "关键字【".$sw_word."】位置异常<br />";
									$fb_construct.="关键字【".$sw_word."】位置异常<br />";
								}
							}else{
					
								 $unsmooth_num++;
								 $smooth_result .= "关键字【".$sw_word."】位置异常<br />";
								 $fb_construct.="关键字【".$sw_word."】位置异常<br />";
								  //判断是否倒装////////////////////////////////
								  if($sw['wordtype']=='verb'){
							         $fb_convert.="关键字【".$sw_word."】跟关键字【".$splistwords[($sw['order']-1)-1]['word']."】发生了倒装<br/>";
							      }
							//////////////////////////////////////////////////
							
							 }
						}else{	  //测不到这里
							if(($has_passtive||$stu_passtive)&&(!$has_passtive||!$stu_passtive)&&($prev_pos>$be_pos && $be_pos>$sw_pos)){
								//如果是被动语态，就判断两个词之间是不是有被字
							
								$unsmooth_num++;
								$smooth_result .= "关键字【".$sw_word."】位置异常（被动语态）<br />";
								$fb_construct.="关键字【".$sw_word."】位置异常（被动语态）<br />";
							}
						}
						$prev_pos = $sw_pos;
					}
					
					$is_match = true;
				}else if(in_array($sw['wordtype'],$num_arr)){//如果是数词
					$is_match = true;
					if(is_find_num($sw,$paragragh)){
						$match_result .= "匹配到数词【".$sw_word."】 score += ".$sw['weight']."<br />";
						
						$score += $sw['weight'];
					}else{
						$match_result .= "未匹配到数词【".$sw_word."】 score += 0<br />";
						$fb_hard.="未匹配到数词【".$sw_word."】<br />";
						
					}
				}else if($sw['wordtype'] == 'neg'){
					//如果是否定词
					$neg_words = getNegWords($sw['word']);//搜索同义否定词表
					//print_r($neg_words);
					
					$is_match = true;
                    $is_neg_match = false;
					if($neg_words){	  //&& !empty($neg_words)
					  foreach($neg_words as $key=>$neg_word){
						//  print_r($neg_word);
						
						foreach($neg_word as $negw){
						
							if($negw!=''){
							$neg_pos = mb_strpos($paragragh,$negw);
						
							if($neg_pos !== false){
								//$score += $sw['weight']*0.8;
								$negw_gb = iconv('utf-8','utf-8',$negw);
								$match_result .= "匹配到关键词【".$sw_word."】的同义否定词【".$negw_gb."】 score += ".$sw['weight']."*0.8<br />";	//第一个原本是gd2312
								$score += $sw['weight']*0.8;
								
								$is_neg_match = true;
								break;
							}
							}
						}
						}
					}
					
					if(!$is_neg_match){ $match_result .= "未匹配到关键词【".$sw_word."】的同义否定词 score += 0<br />"; //第一个原本是gd2312
					
					$fb_hard.="未匹配到关键词【".$sw_word."】的同义否定词<br />";
					}
				}else{
					//搜索关键词同义词表
					$syn_words = getSynWords($sw['word']);
					if($syn_words){
						foreach($syn_words as $key=>$syn_word){
                        
							foreach($syn_word as $synw){
                            
								if($synw != ''){
									$syn_pos = mb_strpos($paragragh,$synw);
									if($syn_pos !== false){
										$score += $sw['weight']*0.8;
										$synw_gb = iconv('utf-8','utf-8',$synw);	//改过
										
										$match_result .= "匹配到关键词【".$sw_word."】的同义词【".$synw_gb."】 score += ".$sw['weight']."*0.8<br />";
										
										if(in_array($sw['wordtype'],$nounverb)){
											$next_word_pos = mb_strpos($paragragh,$sw['word'],$syn_pos);
											$syn_pos = $next_word_pos !== false ? $next_word_pos:$syn_pos;
											if($prev_pos > $syn_pos){
												if(($has_passtive||$stu_passtive)&&(!$has_passtive||!$stu_passtive)){//标答或学生答有“被”字：调转语序
													
													if(!($prev_pos>$be_pos && $be_pos>$syn_pos)){//如果被字不是在两个词中间，语序有错
														$unsmooth_num++;
														$smooth_result .= "关键字【".$synw_gb."】位置异常<br />";
														$fb_construct.="关键字【".$sw_word."】位置异常<br />";
													}
												}else{
													 $unsmooth_num++;
													 $smooth_result .= "关键字【".$synw_gb."】位置异常<br />";
													 $fb_construct.="关键字【".$sw_word."】位置异常<br />";
													
												 }
											}else{
												if(($has_passtive||$stu_passtive)&&(!$has_passtive||!$stu_passtive)&&($prev_pos>$be_pos && $be_pos>$syn_pos)){
													//如果是被动语态，就判断两个词之间是不是有被字
													$unsmooth_num++;
													$smooth_result .= "关键字【".$synw_gb."】位置异常（被动语态）<br />";
													$fb_construct.="关键字【".$sw_word."】位置异常（被动语态）<br />";
												}
											}
											$prev_pos = $syn_pos;
										}
										$is_match = true;
										break;
									}
								}
							}
						}
					}
					
				}
				
				if(!$is_match){
					$match_result .= "未匹配到关键词【".$sw_word."】 score += 0<br />";
					$fb_hard.="未匹配到关键词【".$sw_word."】<br />";
					
					$unmatch_num++;
				}
			}
			$match_score = ($score/$total_splitword)*0.6;
			$match_result .= "match_score = ($score/$total_splitword)*0.6<br />";
			
			$smooth_score = ($unmatch_num == $total_splitword) ? 0:($total_splitword-$unsmooth_num)/$total_splitword*4;//关键字匹配为0，通顺度分数为0
			
			$smooth_score = $smooth_score>2?4:$smooth_score;
			$smooth_score = $unmatch_num*2>$total_splitword ? $smooth_score*0.8:$smooth_score;//关键字匹配低于一半扣20%通顺分
			$sum = $smooth_score?$match_score+$smooth_score:0;//通顺度为零的话,得分为0
			///////////////////////////////////
			if($fb_hard=="词汇反馈："){ 
				$fb_hard="词汇过关";	
			}
			if($fb_construct=="单词结构反馈："){ 
				$fb_construct="单词结构正常";
			}
			if($fb_convert=="句子发生了动名词倒装："){
				$fb_convert="句子没有发生倒装";
			}
			if($fb_broke=="句子发生了拆分："){
				$fb_broke="句子拆分正常";
			}
			if($fb_switch=="句子发生了主被动转换："){
				$fb_switch="句子没有主被动转换";
			}
			//////////////////////////////
			$rsl_data = array(
							'match_result'=>iconv('utf-8','utf-8',$match_result),		  //第二个原本为gd2312
							'score'=>$sum,
							'match_score'=>$match_score,
							'smooth_score'=>$smooth_score,
							'smooth_result'=>iconv('utf-8','utf-8',$smooth_result),	   //改过
							'used_answer_id'=>$a['answer_id'],
							'fb_hard'=>$fb_hard,
						    'fb_analyse'=>$fb_construct,
							'fb_convert'=>$fb_convert,
							'fb_broke'=>$fb_broke,
							'fb_switch'=>$fb_switch
							);
			return $rsl_data;
		}
       function countQuizScore($q_id,$stu_answer){
		/**
		*	①赋权值：
		*	h:动词和名词的总个数
		*	g:剩余的关键词个数
		*   句子总分=10×关键词个数n
		*   否定词默认权值=12 
		*   否定词总分=12×否定词个数m
		*   数词默认权值=8 
		*   数词总分=8×数词个数k
		*   (名词+动词)的权值 g=0:(10n-12m-8k)/h g!=0:11
		*   (语句修饰成分：形容词+副词.etc)的权值=(10n-12m-8k-11h)/g
		*
		*   ②句子得分=通顺度得分×40%+(否定词得分+数词得分+剩余关键词完全匹配得分+剩余关键词不完全匹配得分)/关键词个数n×60%
		**/
		$answers = getAnswersByQid($q_id);
		$maxscore = array('score'=>0);
		foreach($answers as $answer){
			$score = getQuizScore($answer,$stu_answer);
			//print_r($score);
			if($maxscore['score'] <= $score['score']) $maxscore = $score;
		}
		
		return $maxscore;
	  }
     function countScore($c_question,$stu_answer){
		return countQuizScore($c_question,$stu_answer);
	  }
	  function postAnswer($data){//插入学生答案
			if(is_array($data)){
				//如果已有记录，则更新，无则插入
				$where = array('question_id'=>$data['question_id'],'s_id'=>$data['s_id']);
				if(getNum(TB_SA,$where))   updateRow(TB_SA,$data,$where);
				else postRow(TB_SA,$data);
			}
		}
	//$allStuAns=array();
	$allStuAns=t_getExitingStudentAnswers($questionId);
	
	//die;
	for($i=1;$i<=$allStuAns[0];$i++){
	      $answer=$allStuAns[$i][2];
          $qid=$allStuAns[$i][0];
          $s_id=$allStuAns[$i][1];
          $sql11="select * from questions where question_id=".$qid;
		  //echo  $sql11;
		//  die;
          $re=mysql_query($sql11);
          $qlist=mysql_fetch_array($re);
        $anomaly_count = 0;

	   $insert_data = array(
		'answer'=>$answer,
		'question_id'=>$qid,
		's_id'=>$s_id
		);
       

    $scoredata = countScore($qid,$answer);

    $insert_data = array_merge($insert_data,$scoredata);
   	
	$single_score = $insert_data['score']?round($qlist['mark']-((10-$insert_data['score'])/10)*$qlist['mark']*0.8):0;
	$insert_data['real_score'] = $single_score;
	if($single_score<($qlist['mark']/2)){
		//$changed_count++;
		$anomaly_count++;
		//$insert_data['is_changed']=1;
		$insert_data['is_anomaly']=1;
	}
	
	//$total_score += $insert_data['real_score'];

	$stdata = array(
	'is_scored'=>1,
	'finished_time'=>date('Y-m-d H:i:s'),
	'anomaly_count'=>$anomaly_count
	);

	 $insert_data=array_merge($insert_data,$stdata);	
      postAnswer($insert_data);

   }
	echo  "2";	
?>