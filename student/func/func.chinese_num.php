<?php

	function Chinese_Num_Conv($i,$s=0){
		$c_digit_min = array("��","ʮ","��","ǧ","��","��","��");
		$c_num_min = array("��","һ","��","��","��","��","��","��","��","��","ʮ");
		
		$c_digit_max = array("��","ʰ","��","Ǫ","��","��","��");
		$c_num_max = array("��","Ҽ","��","��","��","��","½","��","��","��","ʰ");
		//echo $s;
		if($s==1){
			$c_digit = $c_digit_max;
			$c_num = $c_num_max;
		}
		else{
			$c_digit = $c_digit_min;
			$c_num = $c_num_min;
		}
		
		if($i<0)
			return "��".Chinese_Num_Conv(-$i,$s);
		if ($i < 11)
			return $c_num[$i];
		if ($i < 20)
			return $c_num[1].$c_digit[1] . $c_num[$i - 10];
		if ($i < 100) {
			if ($i % 10)
				return $c_num[$i / 10] . $c_digit[1] . $c_num[$i % 10];
			else
				return $c_num[$i / 10] . $c_digit[1];
		}
		if ($i < 1000) {
			if ($i % 100 == 0)
				return $c_num[$i / 100] . $c_digit[2];
			else if ($i % 100 < 10)
				return $c_num[$i / 100] . $c_digit[2] . $c_num[0] . Chinese_Num_Conv($i % 100,$s);
			else if ($i % 100 < 10)
				return $c_num[$i / 100] . $c_digit[2] . $c_num[1] . Chinese_Num_Conv($i % 100,$s);
			else
				return $c_num[$i / 100] . $c_digit[2] . Chinese_Num_Conv($i % 100,$s);
		}
		if ($i < 10000) {
			if ($i % 1000 == 0)
				return $c_num[$i / 1000] . $c_digit[3];
			else if ($i % 1000 < 100)
				return $c_num[$i / 1000] . $c_digit[3] . $c_num[0] . Chinese_Num_Conv($i % 1000,$s);
			else 
				return $c_num[$i / 1000] . $c_digit[3] . Chinese_Num_Conv($i % 1000,$s);
		}
		if ($i < 100000000) {
			if ($i % 10000 == 0)
				return Chinese_Num_Conv($i / 10000,$s) . $c_digit[4];
			else if ($i % 10000 < 1000)
				return Chinese_Num_Conv($i / 10000,$s) . $c_digit[4] . $c_num[0] . Chinese_Num_Conv($i % 10000,$s);
			else
				return Chinese_Num_Conv($i / 10000,$s) . $c_digit[4] . Chinese_Num_Conv($i % 10000,$s);
		}
		if ($i < 1000000000000) {
			//echo ($i % 100000000).'!!!';
			if ($i % 100000000 == 0)
				return Chinese_Num_Conv($i / 100000000,$s) . $c_digit[5];
			else if ($i % 100000000 < 1000000)
				return Chinese_Num_Conv($i / 100000000,$s) . $c_digit[5] . $c_num[0] . Chinese_Num_Conv($i % 100000000,$s);
			else 
				return Chinese_Num_Conv($i / 100000000,$s) . $c_digit[5] . Chinese_Num_Conv($i % 100000000,$s);
		}
		if ($i % 1000000000000 == 0)
			return Chinese_Num_Conv($i / 1000000000000,$s) . $c_digit[6];
		else if ($i % 1000000000000 < 100000000)
			return Chinese_Num_Conv($i / 1000000000000,$s) . $c_digit[6] . $c_num[0] . Chinese_Num_Conv($i % 1000000000000,$s);
		else
			return Chinese_Num_Conv($i / 1000000000000,$s) . $c_digit[6] . Chinese_Num_Conv($i % 1000000000000,$s);
	}
	
	function Chinese_Money_Min($a){
		$c_num = array("��","һ","��","��","��","��","��","��","��","��","ʮ");
		if($a<10)
			return $c_num[0] . "��" . $c_num[$a] . "��";
		else if($a%10 == 0)
			return $c_num[$a/10] . "��" . $c_num[0] . "��";
		else
			return $c_num[floor($a/10)] . "��" . $c_num[$a%10] ."��";
	}

	function ChineseNum2point($numstr){
		  $num_len = strlen($numstr);
		  
		  if($num_len >= 12){
				  return ($numstr/1000000000000)."��";
		  }else if($num_len >= 9){
				  return ($numstr/100000000)."��";
		  }else if($num_len >= 5){
				  return ($numstr/10000)."��";
		  }else{
		  	return false;
		  }
		  
	}
	
	
	function ChineseNum2digit($numstr,$is_point=0){
		  $c_num_max = array("��"=>1000000000000,"��"=>100000000,"��"=>10000,"ǧ"=>1000,"��"=>100,"ʮ"=>10,"��"=>0.1);
		  $c_num_min = array("��"=>0,"һ"=>1,"��"=>2,"��"=>3,"��"=>4,"��"=>5,"��"=>6,"��"=>7,"��"=>8,"��"=>9);
		  //echo $numstr;
		  if($numstr == '') return 0;
		  
		  $numstr = strtr($numstr,$c_num_min);
		  
		  if(is_numeric($numstr)) return $numstr;
		  
		  foreach($c_num_max as $cm=>$cn){
			// echo $cm;
			  if(strpos($numstr,$cm) !== false){
				  $s = explode($cm,$numstr);
				 // print_r($s);
				 if($cm == "��") $is_point=1;
				 if($is_point) return ChineseNum2digit($s[1],$is_point)*$cn+($s[0] == ''?0:ChineseNum2digit($s[0],$is_point));
				 else return ChineseNum2digit($s[0])*$cn+($s[1] == ''?0:ChineseNum2digit($s[1]));
			  }
		  }
		  
	}
	
	function percent2Chinese($numstr){
		$w = str_replace('%','',$numstr);
		return '�ٷ�֮'.Chinese_Num_Conv($w);
	}
	
	function percent2c_digit($numstr){
		$w = str_replace('%','',$numstr);
		if($w % 100 == 0 ) return Chinese_Num_Conv($w/100)."��";
		return $numstr;
	}
	
	function percent2digit($c_numstr){
		$w = str_replace('%','',$numstr);
		if($w % 100 == 0 ) return ($w/100)."��";
		return $c_numstr;
	}
	
	function Chinese2percent($numstr){
		$w = str_replace('�ٷ�֮','',$numstr);
		return ChineseNum2digit($w)."%";
	}

	function Chinese_Money($i){
		$j=Floor($i);
		$x=($i-$j)*100;
		//return $x;
		return Chinese_Num_Conv($j)."Ԫ".Chinese_Money_Min($x)."��";
	}

?>