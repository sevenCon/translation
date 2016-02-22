<?php 
	session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php 
	include '../functionfile/config.inc.php';
	include '../functionfile/teacher.func.php';
?>
<?php 
	//将保存试卷号的session转为特定数组
	function changeSessionToSimpleArray($array){
	    $simpleArray=array();
		foreach($array as $key=>$value){
			for($i=0;$i<count($value);$i++){
				array_push($simpleArray, $value[$i]);
			}
		}
		sort($simpleArray);
		return $simpleArray;
	}
	//将数组用分号分隔返回字符串
	function changeArrayToString($array){
		$targetString="";
		for($i=0;$i<count($array);$i++){
		    if($i==0){
		    	$targetString=$array[$i];
			}else{
				$targetString=$targetString.",".$array[$i];
			}
		}
		return $targetString;
	}
?>
<?php 
	$requestType=$_POST['requesttype'];
	switch ($requestType){
		case "getPageContent":
			$requestPage=$_POST['requestpage'];
			$beginIndex=$_POST['beginindex'];
			$pageSize=$_POST['pagesize'];
			$totalPageNumber=$_POST['totalpage'];
			$papersInfo=t_GetQuestionList();		   //820
			$endIndex=$beginIndex;
			if($requestPage==$totalPageNumber){
				$endIndex=$papersInfo[0];
			}else{
				$endIndex=$beginIndex+$pageSize-1;
			}
			$requestPageContent=array();
			$j=0;
			for($i=$beginIndex;$i<=$endIndex;$i++){
				$requestPageContent[++$j]=array("paperId"=>$papersInfo[$i][0],"paperName"=>$papersInfo[$i][1],"createTime"=>$papersInfo[$i][3]);
			}
			$requestPageContent[0]=array("requestPaperNumber"=>$j);
			echo json_encode($requestPageContent);
			break;
		case "saveSelectedPapers":
			$currentPage=$_POST['currentpage'];
			$selectedPapersString=$_POST['selectedpapers'];
			$selectedPapers=explode(",", $selectedPapersString);
			//将选择到的题目按照选择的页数和选择的题目号作为键值对存储
			if(isset($_SESSION['selectedPapersForScore'])){
				$combine=$_SESSION['selectedPapersForScore'];
				$isKeyExists=array_key_exists($currentPage, $combine);//判断是否存在以页面号为键的数据
				if(!$isKeyExists&&$selectedPapersString==""){
					echo "false";//表示用户没有选择本页的任何一份试卷（因为设置了session但是不存在该键值以及没有选择东西）
				}else{
					if($isKeyExists&&$selectedPapersString==""){//假如原先的session中存在请求的键值而且传进来选择为空则删除原先记录
						unset($combine[$currentPage]);
						$_SESSION['selectedPapersForScore']=$combine;
						echo changeArrayToString(changeSessionToSimpleArray($_SESSION['selectedPapersForScore']));
					}else{//否则重新设置键值对
						if($isKeyExists){//先将存在的键值清除
							unset($combine[$currentPage]);
						}
						$keys=array();
						$values=array();
						//获取原有的键值对并保存到相应的数组中
						foreach($combine as $key=>$value){
							array_push($keys, $key);
							array_push($values, $value);
						}
						//增加新的键值对
						array_push($keys,$currentPage);
						array_push($values,$selectedPapers);
						$combine=array_combine($keys, $values);
						ksort($combine);
						$_SESSION['selectedPapersForScore']=$combine;
						echo changeArrayToString(changeSessionToSimpleArray($_SESSION['selectedPapersForScore']));
					}
				}
			}else{
				if($selectedPapersString==""){
					echo "false";//表示用户没有选择本页的任何一条题目
				}else{
					$keys=array($currentPage);
					$values=array($selectedPapers);
					$combine=array_combine($keys, $values);
					ksort($combine);
					$_SESSION['selectedPapersForScore']=$combine;
					echo changeArrayToString(changeSessionToSimpleArray($_SESSION['selectedPapersForScore']));
				}
			}
			break;
		case "exportData":
			$classId=$_POST['classid'];
			$selectedPapers=$_POST['selectedpapers'];
			$selectedPapers=$_SESSION['selectedPapersForScore'];
			$papersArray=array();
			$j=0;
			foreach($selectedPapers as $key=>$value){
				for($i=0;$i<count($value);$i++){
					$papersArray[++$j]=$value[$i];	
				}
			}
			$papersArray[0]=$j;
			$date=date('Ymdhis');
			$filename=$date.'.xls';
			$serverPath="../exportdatatmp/".$filename;
			if(t_OutportStudentScore($classId, $papersArray, $serverPath)){	 //656
				echo $filename;
			}else{
				echo "2";
			}
			if(isset($_SESSION['selectedPapersForScore'])){
				unset($_SESSION['selectedPapersForScore']);
			}
			break;
		default:
			break;
	}
?>