<?php

	function Chinese_Num_Conv($i,$s=0){
		$c_digit_min = array("零","十","百","千","万","亿","兆");
		$c_num_min = array("零","一","二","三","四","五","六","七","八","九","十");
		
		$c_digit_max = array("零","拾","佰","仟","万","亿","兆");
		$c_num_max = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖","拾");
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
			return "负".Chinese_Num_Conv(-$i,$s);
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
		$c_num = array("零","一","二","三","四","五","六","七","八","九","十");
		if($a<10)
			return $c_num[0] . "角" . $c_num[$a] . "分";
		else if($a%10 == 0)
			return $c_num[$a/10] . "角" . $c_num[0] . "分";
		else
			return $c_num[floor($a/10)] . "角" . $c_num[$a%10] ."分";
	}

	function ChineseNum2point($numstr){
		  $num_len = strlen($numstr);
		  
		  if($num_len >= 12){
				  return ($numstr/1000000000000)."兆";
		  }else if($num_len >= 9){
				  return ($numstr/100000000)."亿";
		  }else if($num_len >= 5){
				  return ($numstr/10000)."万";
		  }else{
		  	return false;
		  }
		  
	}
	
	
	function ChineseNum2digit($numstr,$is_point=0){
		  $c_num_max = array("兆"=>1000000000000,"亿"=>100000000,"万"=>10000,"千"=>1000,"百"=>100,"十"=>10,"点"=>0.1);
		  $c_num_min = array("零"=>0,"一"=>1,"二"=>2,"三"=>3,"四"=>4,"五"=>5,"六"=>6,"七"=>7,"八"=>8,"九"=>9);
		  //echo $numstr;
		  if($numstr == '') return 0;
		  
		  $numstr = strtr($numstr,$c_num_min);
		  
		  if(is_numeric($numstr)) return $numstr;
		  
		  foreach($c_num_max as $cm=>$cn){
			// echo $cm;
			  if(strpos($numstr,$cm) !== false){
				  $s = explode($cm,$numstr);
				 // print_r($s);
				 if($cm == "点") $is_point=1;
				 if($is_point) return ChineseNum2digit($s[1],$is_point)*$cn+($s[0] == ''?0:ChineseNum2digit($s[0],$is_point));
				 else return ChineseNum2digit($s[0])*$cn+($s[1] == ''?0:ChineseNum2digit($s[1]));
			  }
		  }
		  
	}
	
	function percent2Chinese($numstr){
		$w = str_replace('%','',$numstr);
		return '百分之'.Chinese_Num_Conv($w);
	}
	
	function percent2c_digit($numstr){
		$w = str_replace('%','',$numstr);
		if($w % 100 == 0 ) return Chinese_Num_Conv($w/100)."倍";
		return $numstr;
	}
	
	function percent2digit($c_numstr){
		$w = str_replace('%','',$numstr);
		if($w % 100 == 0 ) return ($w/100)."倍";
		return $c_numstr;
	}
	
	function Chinese2percent($numstr){
		$w = str_replace('百分之','',$numstr);
		return ChineseNum2digit($w)."%";
	}

	function Chinese_Money($i){
		$j=Floor($i);
		$x=($i-$j)*100;
		//return $x;
		return Chinese_Num_Conv($j)."元".Chinese_Money_Min($x)."整";
	}

?>