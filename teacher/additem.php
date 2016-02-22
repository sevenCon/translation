<?php
session_start();
//$_SESSION['admin']='false';	   

?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
	require_once '../functionfile/teacher.func.php';
	//require_once "../functionfile/globle.func.php";
	require_once '../functionfile/config.inc.php';
	
	$action=$_POST['action'];
	//注意，1表示增加失败（数据库方面问题），2表示成功，3表示题目内容过长，4表示答案或者选项字段过长
	switch($action){
		
		//增加汉译英
		case "addctoe":
		    $hard=$_POST["hard"];
		    $unit=$_POST["unit"];
		    $ctoeSubject=$_POST["ctoesubject"];
		    $ctoeAnswer1=$_POST["ctoeanswer1"];
		    $ctoeAnswer2=$_POST["ctoeanswer2"];
		    $ctoeAnswer3=$_POST["ctoeanswer3"];
			$ctoeScore=$_POST["ctoeScore"];
		    if(mb_strlen($ctoeSubject,'UTF8')>5000){
		        echo "3";
		    }else if(mb_strlen($ctoeAnswer1,'UTF8')>5000||mb_strlen($ctoeAnswer2,'UTF8')>5000
		            ||mb_strlen($ctoeAnswer3,'UTF8')>5000)
		    {
		        echo "4";
		    }else{
		        if(t_AddCtoETranslation($ctoeSubject, $unit, $hard, $ctoeAnswer1,$ctoeAnswer2,$ctoeAnswer3,$ctoeScore)){
		            echo "2";
		        }else{
		            echo "1";
		        }
		    }
		    break;
		//增加英译汉
		case "addetoc":
		    $hard=$_POST["hard"];
		    $unit=$_POST["unit"];

			//$homeworkList=$_POST["homeworkList"];

		    $etocSubject=$_POST["etocsubject"];
		    $etocAnswer1=$_POST["etocanswer1"];
		    $etocAnswer2=$_POST["etocanswer2"];
		    $etocAnswer3=$_POST["etocanswer3"];
			$etocScore=$_POST["etocScore"];///add score
		    if(mb_strlen($etocSubject,'UTF8')>5000){
		        echo "3";
		    }else if(mb_strlen(etocAnswer1,'UTF8')>5000||mb_strlen(etocAnswer2,'UTF8')>5000
		            ||mb_strlen(etocAnswer3,'UTF8')>5000)
		    {
		        echo "4";
		    }else{
		        if(t_AddEtoCTranslation($etocSubject, $unit, $hard, $etocAnswer1,$etocAnswer2,$etocAnswer3,$etocScore)){
		            echo "2";
		        }else{
		            echo "1";
		        }
		    }
		    break;

		case "synAdd":	
		    $word1=$_POST['w1'];
			$word2=$_POST['w2'];
			$word3=$_POST['w3'];
			$word4=$_POST['w4'];
			$word5=$_POST['w5'];
			if(t_AddSynWord($word1,$word2,$word3,$word4,$word5)){
				echo "2";
			}else{
				echo "1";
			}
			
		    break;
		
	    case "synNegAdd":
			$word1=$_POST['w1'];
			$word2=$_POST['w2'];
			$word3=$_POST['w3'];
			$word4=$_POST['w4'];
			$word5=$_POST['w5'];
			
			if(t_AddSynNegWord($word1,$word2,$word3,$word4,$word5)){
				echo "2";
			}else{
				echo "1";
			}		
		    break;
		case "addWord":
			$word1=$_POST['word1'];
			$word2=$_POST['word2'];
			$word3=$_POST['word3'];
			$word4=$_POST['word4'];
			$word5=$_POST['word5'];
			$wordtype1=$_POST['wordtype1'];
			$wordtype2=$_POST['wordtype2'];
			$wordtype3=$_POST['wordtype3'];
			$wordtype4=$_POST['wordtype4'];
			$wordtype5=$_POST['wordtype5'];
			if(t_AddWord($word1,$wordtype1)&&t_AddWord($word2,$wordtype2)&&t_AddWord($word3,$wordtype3)&&t_AddWord($word4,$wordtype4)&&t_AddWord($word5,$wordtype5)){
				echo  "2";
			}else{
				echo  "1";
			}
			break;
		default:
		    break;
	}
?>