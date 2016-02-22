<?php
//if (!defined('ROOT')) exit('无访问权限');
	
	class wordSplit{
		
		static $dicfile = NULL;
				
		static $fp = NULL;
		
		public $words = NULL;
		
		private $db = NULL;
		
		private $realtSplitWords = '';
		
		public $isPasstive = 0;
		
		private $wordTypeNum = NULL;
		
		
		function __construct(){
		
			if(!class_exists('db',false)){
				include(dirname(__FILE__).'/class.db.php');
			}
			include(ROOT."/config/config.php");	
			$this->db = new db();
			$this->db->connect($db_host, $db_user, $db_password, $db_name) or die(__FILE__.'数据库连接出错');
			
			mb_internal_encoding('gb2312'); 
			mb_regex_encoding('gb2312'); 
			
			$this->wordTypeNum = array();
			$this->wordTypeNum['num_num'] = 0;
			$this->wordTypeNum['neg_num'] = 0;
			$this->wordTypeNum['nounverb_num'] = 0;
			$this->wordTypeNum['others_num'] = 0;
			$this->wordTypeNum['total'] = 0;
		}
		
		static function getDic(){
			self::$dicfile = ROOT.'/upload/SDIC.txt';
			self::$word = array();
			if (file_exists(self::$dicfile)) {
				self::$fp = fopen(self::$dicfile,'r');
				while(!feof(self::$fp)){
					$line = fgets(self::$fp);
					$splitword = explode('	', $line);
					$key = trim($splitword[0]);
					$val = trim($splitword[1]);
					if($key != '') self::$word["$key"] = $val;
				}
				fclose(self::$fp);
			}
			return self::$word;
		}
		
		function splitWords($paragragh){//分词算法:逆向最大匹配
 
			$sentence = mb_split('[\。\？\！]',$paragragh);//分句
			$spec_words = array('被','把','的','非','来','是','至','从','后','为','将');
			$words = array();
			$key = 0;
			//echo count($dic_arr);
			foreach($sentence as $sen){
				$sen = trim($sen);
				if($sen != ''){
					$str_len = strlen($sen);
					$i = 0;
					$j = 0;
					$maxlen = 12;
					$isfind = false;
					$splitword = array();
					
					while($i < $str_len){
						$n = $i+$maxlen<$str_len ? $i+$maxlen : $str_len+1;
			
						$isfind = false;
						for($j=$n-1;$j>$i;$j--){
							$word = mb_substr($sen,$i,$j-$i,'gb2312');
							//echo "$i#$j,";
							$can_split = false;			
							if($this->isfind($word) || in_array($word,$spec_words) || preg_match('/^[A-Za-z0-9\-\.\/]+$/',$word)){
								/*if(strlen($word)>3){//检查是否能够再分词
									
									$n2 = strlen($word);
									for($i2=0;$i2<$n2;$i2++){
										$w1 = mb_substr($word,0,$i2+1,'gb2312');
										$w2 = mb_substr($word,$i2+1,$n2-$i2-1,'gb2312');
										//echo "$i2#$n2,";
										if($this->isfind($w1) && $this->isfind($w2)){
											$can_split = true;
											break;
										}
									}
									
								}
								if($can_split){
									//echo $w1.'|'.$w2.'|';//能分解
									array_push($splitword,$w1,$w2);
								}else{*/
									//echo $word.'|';
									array_push($splitword,$word);
								//}
								$isfind = true;
								$i = $j;
								break;
							}
											
						}// end for
						if(!$isfind){	
							//echo "$i#$n,";	
							$i = $j+1;
						}
					}//end while
					$words[$key]=$splitword;
					
					$key++;
				}//end if($sen != '')
			}//end foreach
			return $words;
			//$this->closedb();
		}
		
		function splitAnswer($answer){//分词算法:逆向最大匹配
 
			$sentence = mb_split('[\。\？\！]',$answer);//分句
			//$spec_words = array('被','把','的','非','来','是','至','从','后','为','将');
			$words = array();
			$key = 0;
			//echo count($dic_arr);
			foreach($sentence as $sen){
				$sen = trim($sen);
				if($sen != ''){
					$str_len = strlen($sen);
					$i = 0;
					$j = 0;
					$maxlen = 12;
					$isfind = false;
					$splitword = array();
					
					while($i < $str_len){
						$n = $i+$maxlen<$str_len ? $i+$maxlen : $str_len+1;
			
						$isfind = false;
						for($j=$n-1;$j>$i;$j--){
							$word = mb_substr($sen,$i,$j-$i,'gb2312');
							//echo "$i#$j,";
							$can_split = false;	
							$search_word = $this->getWords($word);	
							//print($word);	
							if($search_word){	
								if($search_word['noun'] || $search_word['verb'] || $search_word['adj'] || $search_word['adv'] || $search_word['idom']){
									array_push($splitword,$word);
								}
								
								$isfind = true;
								$i = $j;
								break;
							}
											
						}// end for
						if(!$isfind){	
							
							$i = $j+1;
						}
					}//end while
					if(count($splitword)){
						$words[$key]=$splitword;
						$key++;
					}
				}//end if($sen != '')					
				
			}//end foreach
			
			return $words;
			//$this->closedb();
		}
		
		function reverseSplitAnswer($answer){//分词算法:逆向最大匹配
		
 			$this->wordTypeNum['num_num'] = 0;
			$this->wordTypeNum['neg_num'] = 0;
			$this->wordTypeNum['nounverb_num'] = 0;
			$this->wordTypeNum['others_num'] = 0;
			$this->wordTypeNum['total'] = 0;
			
			$sentence = mb_split('[\。\？\！]',$answer);//分句
			//$spec_words = array('被','把','的','非','来','是','至','从','后','为','将');
			$words = array();
			$key = 0;
			//print_r($sentence);
			$this->realtSplitWords = '';
			foreach($sentence as $sen){
				$sen = trim($sen);
				
				if($sen != ''){
					$str_len = mb_strlen($sen);
					$maxlen = 15;
					//echo $str_len;
					$i = $str_len-$maxlen;
					$j = $str_len;
					
					$isfind = false;
					$splitword = array();
					
					while($i < $j){
						$i = $j-$maxlen>0 ? $j-$maxlen : 0;
						//echo "$i#$j,";
						$isfind = false;
						for(;$j>$i;$i++){
							$word = mb_substr($sen,$i,$j-$i,'gb2312');
							//echo "[$i#$j,".($word)."]<br/>";
							$can_split = false;	
							$search_word = $this->getWords($word);	
							
							if(!empty($search_word)){	
								//print_r($search_word);
								$this->realtSplitWords .= $word."|";
								if( $this->is_keyword($search_word) && !$this->is_in_array($splitword,$word)){
									//echo "[".$word."]";
									array_push($splitword,array('word'=>$word,'type'=>$this->getWordType($search_word)));
									//print_r($splitword);
								}
								if($search_word['passtive']) $this->isPasstive = 1;
								$isfind = true;
								$j = $i;
								break;
							}
											
						}// end for
						
						if(!$isfind){	
							//echo 'no';
							$j--;
						}
						$i = $j-$maxlen>0 ? $j-$maxlen : 0;
					}//end while
					if(count($splitword)){
						$words[$key]=array_reverse($splitword);
						$key++;
					}
					
					$this->realtSplitWords .= '#';			
				}//end if($sen != '')	
				
				
				
			}//end foreach
			
			return $words;
			//$this->closedb();
		}
		
		function is_in_array($arr,$v){
			foreach($arr as $a){
				if($a['word'] == $v) return true;
			}
			return false;
		}
		
		function getIsPasstive(){
			return $this->isPasstive;
		}
		
		function is_keyword($search_word){
			if($search_word['noun'] || $search_word['verb'] || $search_word['adj'] || $search_word['adv'] || $search_word['idom'] || $search_word['neg'] || $search_word['c_num'] || $search_word['num'] || $search_word['digit'] || $search_word['percent']|| $search_word['c_percent'] || $search_word['passtive']) return true;
			else return false;
		}
		
		function getRealtSplitWords(){
			$sen = explode('#', $this->realtSplitWords);
			$r_sen;
			foreach($sen as $s){
				if($s != ''){
					$words = explode('|',$s);
					$words = implode('|',array_reverse($words));
					$r_sen .= $words.'#';
				}
			}
			return $r_sen;
		}
		
		function getWordType($word){
			
			if(!$word['passtive']) $this->wordTypeNum['total']++;
			if($word['neg'])   {$this->wordTypeNum['neg_num']++;return 'neg';}
			if($word['digit']) {$this->wordTypeNum['num_num']++;return 'digit';}
			if($word['c_num']) {$this->wordTypeNum['num_num']++;return 'c_num';}
			if($word['num'])   {$this->wordTypeNum['num_num']++;return 'num';}
			if($word['percent']) {$this->wordTypeNum['num_num']++;return 'percent';}
			if($word['c_percent']) {$this->wordTypeNum['num_num']++;return 'c_percent';}
			//if($word['passtive']) $this->isPasstive = true;
			if($word['noun'])  {$this->wordTypeNum['nounverb_num']++;return 'noun';}
			if($word['verb'])  {$this->wordTypeNum['nounverb_num']++;return 'verb';}
			if($word['adj'])   {$this->wordTypeNum['others_num']++;return 'adj';}
			if($word['adv'])   {$this->wordTypeNum['others_num']++;return 'adv';}
			if($word['idom'])  {$this->wordTypeNum['others_num']++;return 'idom';}
			if($word['quan'])  {$this->wordTypeNum['others_num']++;return 'quan';}
			if($word['passtive'])  {return 'passtive';}
			$this->wordTypeNum['others_num']++;
			return 'others';
		}
		
		function getWordTypeNum(){
			return $this->wordTypeNum;
		}
		
		function postSplitwords($a_id,$q_id,$order,$paragragh,$word,$type,$weight=10){
			
			$data = array('answer_id'=>$a_id,'question_id'=>$q_id,'order'=>$order,'word'=>$word,'paragragh'=>$paragragh,'wordtype'=>$type,'weight'=>$weight);
			//print_r($data);
			$this->db->postRow(TB_SW,$data);
		}
		
		function postQuestion($data){
			$this->db->postRow(TB_Q,$data);
		}
		
		function postAnswer($data){
			return $this->db->postRow(TB_ANS,$data);
		}
		
		function getAnswer($q_id,$aid){
			$where = array('question_id'=>$q_id,'aid'=>$aid);
			return $this->db->fetchRow(TB_ANS,$where);
		}
		
		function getAnswers($q_id){
			$where = array('question_id'=>$q_id);
			return $this->db->fetchRows(TB_ANS,$where);
		}
		
		function updateQ($q_id,$data){
			$where = array('question_id'=>$q_id);
			$this->db->updateRow(TB_Q,$data,$where);
		}
		
		function deleteSplitword($q_id){
			$where = array('question_id'=>$q_id);
			$this->db->deleteRows(TB_SW,$where);
		}
		
		function getSplitWords($a_id,$q_id){
			$where = array('question_id'=>$q_id,'answer_id'=>$a_id);
			return $this->db->fetchRows(TB_SW,$where,'`paragragh` ASC,`order`');
		}
		
		function deleteAnswers($question_id){
			$this->db->deleteRows(TB_ANS,array('question_id'=>$question_id));
		}
		
		function isfind($word){
			$where = array('word'=>$word);
			return $this->db->getNum(TB_WORD,$where);
		}
		
		function getWords($word){
			$where = array('word'=>$word);
			$getword = $this->db->fetchRow(TB_WORD,$where);
			if(preg_match('/^[\-0-9A-Za-z]+$/',$word)) $getword['noun'] = 1;
			if(preg_match('/^\d+\%$/',$word)) $getword['percent'] = 1;
			if(is_numeric($word)) $getword['digit'] = 1;
			if($this->isChinesePercent($word)) $getword['c_percent'] = 1;
			if($this->isChineseNum($word)) $getword['c_num'] = 1;
			if($word == '被') $getword['passtive'] = 1;
			$neg_arr = $this->getNegWords($word);
				//print_r($neg_arr);
			if(!empty($neg_arr)) $getword['neg'] = 1;
			return $getword;
		}
		
		function getNegWords($word){
			//取得同义否定词
			if(!preg_match('/^\s+$/',$word) && $word !=''){
				$sql = "SELECT * FROM `".TB_NEG."` WHERE `word1`='$word' OR `word2`='$word' OR `word3`='$word' OR `word4`='$word' OR `word5`='$word'";
				if($this->db->excute($sql)){
					//print_r($this->db->getRows());
					return $this->db->getRows();
				}
			}
			return false;
		}
		
		function isChinesePercent($word){
			if(strpos($word,'百分之') == 0){
				$c_word = str_replace('百分之','',$word);
				$len = mb_strlen($c_word);
				$c_num = array("零","一","二","三","四","五","六","七","八","九","十","百");
				$len = mb_strlen($c_word);
	
				for($k=0;$k<$len;$k++){
					$subword = mb_substr($c_word,$k,1);
	
					if(!in_array($subword,$c_num)) return false;
				}
				return true;
			}
			return false;
		}
		
		function isChineseNum($word){
			mb_internal_encoding('gb2312'); 
			mb_regex_encoding('gb2312'); 
			$c_word = preg_replace('/\d+\.?\d+/','',$word);

			$c_num = array("零","一","二","三","四","五","六","七","八","九","十","百","千","万","亿","兆");
			$len = mb_strlen($c_word);

			for($k=0;$k<$len;$k++){
				$subword = mb_substr($c_word,$k,1);

				if(!in_array($subword,$c_num)) return false;
			}
			return true;
		}
		
		function closedb(){
			$this->db->close();
		}
		
	}

?>