<?php
    session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
      include "../functionfile/teacher.func.php";
      include "../functionfile/config.inc.php";
?>
<?php
      $requestType=$_POST['requesttype'];
      switch($requestType){
          case "deleteSynWordList":
			  $wordId=$_POST['wordid'];
			  if(t_DeleteSynWord($wordId)){
					  echo "2";
			  }else{
				  echo "3";
			  }
             break;
          case "deleteQuestionList":	    //增加了句子翻译
			  $wordId=$_POST['wordid'];
			  $getName=$_POST['getname'];
			  if(t_DeleteQuestion($wordId,$getName)){
					  echo "2";
			  }else{
				  echo "3";
			  }
             break;
          case "updateSynWordList":
			  $wordId_1=$_POST['wordid'];
			  $sysword1=$_POST['word1'];
			  $sysword2=$_POST['word2'];
			  $sysword3=$_POST['word3'];
			  $sysword4=$_POST['word4'];
			  $sysword5=$_POST['word5'];
			  
			  if(t_UpdateSynWord($wordId_1,$sysword1,$sysword2,$sysword3,$sysword4,$sysword5)){
				  echo "2";
			  }else{
				  echo "3";
			  }
             break;
	      case "updateQuestionList":	 //juzifanyi
			  $wordId_1=$_POST['wordid'];
			  $data1=$_POST['word1'];
			  $data2=$_POST['word2'];
			  $data3=$_POST['word3'];
			  
			  if(t_UpdateQuestion($wordId_1,$data1,$data2,$data3)){
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
				$questionId=$wordId_1;
				$answer=$data2;
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
				  
				  echo "2";
			  }else{
				  echo "3";
			  }
             break;
		 case "updateQuestionListEtoc":	 //Etoc
			  $wordId_1=$_POST['wordid'];
			  $data1=$_POST['word1'];
			  $data2=$_POST['word2'];
			  $data3=$_POST['word3'];
			  $data4=$_POST['word4'];
			  $data5=$_POST['word5'];
			  $getName=$_POST['getname'];
			  
			 // echo  "<script>alert(".$wordId_1.")</script>";
			  
			  if(t_UpdateQuestionEtoc($wordId_1,$data1,$data2,$data3,$data4,$data5,$getName)){
				  echo "2";
			  }else{
				  echo "3";
			  }
             break;
	      case "getRequestContent":
		  	  $beginIndex=$_POST['beginindex'];
		      $pageSize=$_POST['pagesize'];
			  $requestContent=t_getSynList($beginIndex,$pageSize);
			  $feedbackRequestContent=array();
			  $feedbackRequestContent[0]=$requestContent[0];
			  for($i=1;$i<=$requestContent[0];$i++){
		    	$feedbackRequestContent[$i]=array("wordId"=>$requestContent[$i][0],"word1"=>$requestContent[$i][1],"word2"=>$requestContent[$i][2],"word3"=>$requestContent[$i][3],"word4"=>$requestContent[$i][4],"word5"=>$requestContent[$i][5]);
		     }
			 echo json_encode($feedbackRequestContent);
			break;
			//juzifanyi	$style==3
			case "getRequestContentStc":
		  	  $beginIndex=$_POST['beginindex'];
		      $pageSize=$_POST['pagesize'];
			  $requestContent=t_getStyleList(3,$beginIndex,$pageSize); /////3
			  $feedbackRequestContent=array();
			  $feedbackRequestContent[0]=$requestContent[0];
			  for($i=1;$i<=$requestContent[0];$i++){
		    	$feedbackRequestContent[$i]=array("wordId"=>$requestContent[$i][3],"word1"=>$requestContent[$i][0],"word2"=>$requestContent[$i][1],"word3"=>$requestContent[$i][2]); //
		     }
			 echo json_encode($feedbackRequestContent);
			break;
			//Etoc	$style==1
		  case "getRequestContentStc6":  //汉越翻译
			  $beginIndex=$_POST['beginindex'];
			  $pageSize=$_POST['pagesize'];
			  $requestContent=t_getStyleList(6,$beginIndex,$pageSize); /////6
			  $feedbackRequestContent=array();
			  $feedbackRequestContent[0]=$requestContent[0];
			  for($i=1;$i<=$requestContent[0];$i++){
				  $feedbackRequestContent[$i]=array("wordId"=>$requestContent[$i][3],"word1"=>$requestContent[$i][0],"word2"=>$requestContent[$i][1],"word3"=>$requestContent[$i][2]); //
			  }
			  echo json_encode($feedbackRequestContent);
			  break;
		  //Etoc	$style==1
			case "getRequestContentEtc":
		  	  $beginIndex=$_POST['beginindex'];
		      $pageSize=$_POST['pagesize'];
			  $getName=$_POST['getname'];
			  $requestContent=t_getStyleList($getName,$beginIndex,$pageSize); 
			  $feedbackRequestContent=array();
			  $feedbackRequestContent[0]=$requestContent[0];
			  for($i=1;$i<=$requestContent[0];$i++){
		    	$feedbackRequestContent[$i]=array("wordId"=>$requestContent[$i][5],"word1"=>$requestContent[$i][0],"word2"=>$requestContent[$i][1],"word3"=>$requestContent[$i][2],"word4"=>$requestContent[$i][3],"word5"=>$requestContent[$i][4]); // id,timu,daan123,score
		     }
			 echo json_encode($feedbackRequestContent);
			break;
			//同义否定词
			case "deleteNegWordList":
			  $wordId=$_POST['wordid'];
			  if(t_DeleteNegWord($wordId)){
					  echo "2";
			  }else{
				  echo "3";
			  }
             break;
		  
          case "updateNegWordList":
			  $wordId_1=$_POST['wordid'];
			  $sysword1=$_POST['word1'];
			  $sysword2=$_POST['word2'];
			  $sysword3=$_POST['word3'];
			  $sysword4=$_POST['word4'];
			  $sysword5=$_POST['word5'];			  
			  if(t_UpdateNegWord($wordId_1,$sysword1,$sysword2,$sysword3,$sysword4,$sysword5)){
				  echo "2";
			  }else{
				  echo "3";
			  }
             break;
	      case "getNegRequestContent":
		  	  $beginIndex=$_POST['beginindex'];
		      $pageSize=$_POST['pagesize'];
			  $requestContent=t_getNegList($beginIndex,$pageSize);
			  $feedbackRequestContent=array();
			  $feedbackRequestContent[0]=$requestContent[0];
			  for($i=1;$i<=$requestContent[0];$i++){
		    	$feedbackRequestContent[$i]=array("wordId"=>$requestContent[$i][0],"word1"=>$requestContent[$i][1],"word2"=>$requestContent[$i][2],"word3"=>$requestContent[$i][3],"word4"=>$requestContent[$i][4],"word5"=>$requestContent[$i][5]);
		     }
			 echo json_encode($feedbackRequestContent);
			break;
          default:
              break;
      }
?>