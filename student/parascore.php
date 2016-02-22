<?php
   SESSION_START();
   header('Content-Type: text/html; charset=utf-8');
   require("../functionfile/config.inc.php");
   include('../header.php');
  define('TB_ANS','answers');// 标准答案表
  define('TB_SA','student_answers');// 学生答案表
  define('TB_SW','split_words');//分词结果表
  define('TB_SYN','synonyms');//同义词表
  define('TB_NEG','negative');//同义否定词表
  define('TB_WORD','words');//词典表
  define('TB_P','paragraph');//段落表

   $answer=$_POST['daan'];
   $pid=$_POST['pid'];
   $s_id=$_SESSION['stuID'];
	

    echo  $s_id.":";
    echo  "<br />您的答案是:".$answer;


    //标准答案
	 $query_st="select * from paragraph where p_id='". $pid."'";
     $result_st=mysql_query($query_st);
     while($re=mysql_fetch_array($result_st) )
	 {
	     $std_answers = $re['answer'];
	 }
	echo  "<br />标准答案是:".$std_answers."<br/><br/>";
  
    echo  "<a href = 'paragraphtranslation.php'>返回</a>"	;

  $results;
  $sql11="select * from paragraph where p_id='".$pid."'";
  $re=mysql_query($sql11);
  $qlist=mysql_fetch_array($re);
  function getQList($p_id=''){
		if($p_id != '') $where = array('p_id'=>$p_id);
		return fetchRows(TB_P, $where, '', '');
  }
    $anomaly_count = 0;

   $insert_data = array(
	'answer'=>$answer,
	'p_id'=>$pid,
	's_id'=>$s_id
	);
   $scoredata = countScore($pid,$answer);	//!!!!

   $insert_data = array_merge($insert_data,$scoredata);	//合并一个或多个数组
   	
	$single_score = $insert_data['score']?round($qlist['mark']-((10-$insert_data['score'])/10)*$qlist['mark']*0.8):0;
	//round - 对浮点数进行四舍五入
	$insert_data['real_score'] = $single_score;
	if($single_score<($qlist['mark']/2)){
		
		$anomaly_count++;  //anomaly 异常
		$insert_data['is_anomaly']=1;
	}
	

	$stdata = array(
	'is_scored'=>1,
	'finished_time'=>date('Y-m-d H:i:s'),
	'anomaly_count'=>$anomaly_count
	);

	 $insert_data=array_merge($insert_data,$stdata);

   postAnswer($insert_data);	//把所有信息写进数据库

	function postAnswer($data){//插入学生答案
			if(is_array($data)){
				//如果已有记录，则更新，无则插入
				$where = array('p_id'=>$data['p_id'],'s_id'=>$data['s_id']);
				if(getNum(TB_SA,$where))   updateRow(TB_SA,$data,$where);
				else postRow(TB_SA,$data);
			}
	}
  function getNum($table, $where=array()){
			if($table == '') return false;
			$where_sql = '';
			if(is_array($where) && count($where)){
				foreach($where as $key=>$val){
					$where_sql .= "and `$key`='$val'";
				}
				$where_sql = strlen($where_sql)==0?'':"where ".substr($where_sql,3);
			}
			$sql = "select * from `$table` $where_sql;";
			
			$result = mysql_query($sql) or die(mysql_error().$sql);
			if(!$result) return false;
			$num = mysql_num_rows($result);
			return $num;
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


	function countScore($c_question,$stu_answer){
		return countQuizScore($c_question,$stu_answer);
	}

	function countQuizScore($p_id,$stu_answer){
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
		$answers = getAnswersByQid($p_id);
		$maxscore = array('score'=>0);
		foreach($answers as $answer){
			$score = getQuizScore($answer,$stu_answer);
			
			if($maxscore['score'] <= $score['score']) $maxscore = $score;
		}
		
		return $maxscore;
	}

	function getAnswersByQid($pid){
			$where = array('p_id'=>$pid);
			return fetchRows(TB_ANS,$where,'','');
	}

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
			$rows = array();
			if(mysql_num_rows($results)){	 //函数返回结果集中行的数目
				while($row = mysql_fetch_array($results)){
					array_push($rows,$row);
				}
			}
			
			return $rows;
			
		}
	  /////////统计逗号的个数//////////////////
	   function countAnswerdh($answer) {
		   $count=substr_count($answer,'，'); //函数计算子串在字符串中出现的次数
		   Return $count;
	   }

	   function countStuAnserdh($stu_answer){
		   $count=substr_count($stu_answer,'，');
		   Return $count;
	   }
	 ///////////////////////////
   	function getQuizScore($a,$stu_answer){
		//评分:总分=（完全匹配得到的分数+不完全匹配得到的分数（完全匹配分数的80%））/关键字总个数*70%+10（通顺分）*30%
	    //*	mb_internal_encoding('gb2312'); 
	    //*	mb_regex_encoding('gb2312'); 

			$paragragh = $stu_answer;
			$splistwords = getSplitWordsOrder($a['answer_id']);
			$total_splitword = count($splistwords);
			if(!$total_splitword) return array(
			      		  'match_result'=>'无分词',
							'score'=>0,
							'match_score'=>0,
							'smooth_score'=>0,
							'smooth_result'=>'',
							'used_answer_id'=>NULL
							);
			$score = 0;
			$match_num = 0;
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
			
			$fb_hard="词汇反馈：";
			$fb_construct="单词结构反馈：";
			$fb_convert="句子发生了动名词倒装：";
			$fb_broke="句子发生了拆分：";
			$fb_switch="句子发生了主被动转换：";
			
			//用于判读断句是否有问题
			$andhcount=countAnswerdh($a['answer']);	  //标准答案逗号的个数
			$stu_andhcount=countStuAnserdh($stu_answer);  ////学生答案逗号的个数
			
			if(abs($stu_andhcount-$andhcount)>=2){	//绝对值
				$fb_broke.="请注意断句<br/>";
			}
			$stu_pass_pos = find_passtive($paragragh);	//passtive_position
			
			if($stu_pass_pos !== false){
				$stu_passtive = 1;			//$stu_passtive代表学生答案里是否有被字
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
					$passtive_pos = $sw['order'];	//如果分词里有被动词，则记下他的位置
					continue;
					
				}
				
				//判断是否主被动转换、语序是否正常
				if($total_splitword==$o){
					if(($has_passtive&&!$stu_passtive)){
						$fb_switch.="最好请用被动句<br/>";
					}
					else if((!$has_passtive&&$stu_passtive)){
						$fb_switch.="最好请用主动句<br/>";
					}
				}

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
								// echo "关键字【".$sw_word."】位置异常<br />";
							 }
						}else{	 
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
					
					$is_match = true;
                    $is_neg_match = false;

					if($neg_words){	
					  foreach($neg_words as $key=>$neg_word){
						foreach($neg_word as $negw){
							if($negw!=''){
							$neg_pos = mb_strpos($paragragh,$negw);
							if($neg_pos !== false){
								//$score += $sw['weight']*0.8;
								$negw_gb = iconv('utf-8','utf-8',$negw);
								$match_result .= "匹配到关键词【".$sw_word."】的同义否定词【".$negw_gb."】 score += ".$sw['weight']."*0.8<br />";	//第一个原本是gd2312
								$score += $sw['weight']*0.8;
								//echo "score += ".$sw['weight'];
								//echo "匹配到关键词【".$sw_word."】的同义否定词【".iconv('gb2312','utf-8',$negw)."】<br />";
								$is_neg_match = true;
								break;
							}
							}
						}
						}
					}
					
					if(!$is_neg_match){ $match_result .= "未匹配到关键词【".$sw_word."】的同义否定词 score += 0<br />"; 
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
										//echo "匹配到关键词【".$sw_word."】的同义词【".iconv('gb2312','utf-8',$synw)."】<br />";
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
													// echo "关键字【".$sw_word."】位置异常<br />";
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
					//echo '#'.$sw['weight']."未匹配到关键词【".$sw_word."】<br />";
					$unmatch_num++;
				}
			}  //全部分词寻找遍历结束

			$match_score = ($score/$total_splitword)*0.6;
			$match_result .= "match_score = ($score/$total_splitword)*0.6<br />";

			$smooth_score = ($unmatch_num == $total_splitword) ? 0:($total_splitword-$unsmooth_num)/$total_splitword*4;//关键字匹配为0，通顺度分数为0
			$smooth_score = $smooth_score>2?4:$smooth_score;
			$smooth_score = $unmatch_num*2>$total_splitword ? $smooth_score*0.8:$smooth_score;//关键字匹配低于一半扣20%通顺分
			$sum = $smooth_score?$match_score+$smooth_score:0;//通顺度为零的话,得分为0
			///////以上遍历过程中都没变化的话说明过关
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
	
	function getSplitWordsOrder($a_id){
			$where = array('answer_id'=>$a_id);
			return fetchRows(TB_SW,$where,'`paragragh` ASC,`order`');
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
				$num = sprintf('%.0f',ChineseNum2digit($word['word']));	//显示0位浮点数
				
				if(mb_strpos($p,$num) !== false) return true;//匹配阿拉伯数字
				if(mb_strpos($p,Chinese_Num_Conv($num)) !== false) return true;//匹配中文数词
				if(mb_strpos($p,ChineseNum2point($num)) !== false) return true;//匹配阿拉伯数字加中文数词（1.2亿）
			}
			return false;
		}

		function find_passtive($p){

			$bew = iconv('utf-8','utf-8',"被");   
			$pos = mb_strpos($p,$bew);	//查找字符串在另一个字符串中首次出现的位置
			$is_af = false;	

			if($pos !== false){	//存在被动词
				for($j=2;$j<5;$j++){
					$word = mb_substr($p,$pos+3,$j);	//原本是$pos+1
					if(isWord($word)){	   //words表中存在该词
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
   function getSynWords($word){
			//取得同义词
			$sql = "SELECT word1,word2,word3,word4,word5 FROM `".TB_SYN."` WHERE `word1`='$word' OR `word2`='$word' OR `word3`='$word' OR `word4`='$word' OR `word5`='$word'";
			if(excute($sql)){
				return getRows();
			}
			return false;
		}

	 function isWord($word){
	  $where = array('word'=>$word);
	  return getNum(TB_WORD,$where);
  }


?>