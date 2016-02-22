<?php 
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>导出学生成绩</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/exportstuscore.js"></script>
<link rel="stylesheet" type="text/css" href="../css/exportstuscore.css"></link>
</head>
<?php 
  
	include'../functionfile/config.inc.php';
	include '../functionfile/mysql.func.php';
	include '../functionfile/teacher.func.php';
	include '../functionfile/globle.func.php';
?>
<?php 
	if(isset($_SESSION['selectedPapersForScore'])){
		unset($_SESSION['selectedPapersForScore']);
	}
	if(isset($_SESSION['teacherID'])){
		$teacherId=$_SESSION['teacherID'];
	}else{
		_location("您尚未登录，请先登录", "../index.php");
	}
	$classes=t_GetClassListByTid($teacherId);
	$classesNumber=$classes[0];//获取该教师班级数目		39
	$questions=t_GetQuestionList();//获取全部试卷的信息
	//echo  $questions[0];
	$questionsNumber=$questions[0];//获取所有试卷总数		   820
	$pageSize=5;
	$totalPageNumber=floor(($questionsNumber+$pageSize-1)/$pageSize);
	$currentPage=0;
	if($totalPageNumber>0){
		$currentPage=1;
	}
?>
<body>
<div id="main">
	<div id="classes">
 	<select id="class" name="class">
        	<?php 
			
        		for($i=1;$i<=$classesNumber;$i++){
        			if($i==1){
        				echo "<option selected='selected' value='".$classes[$i][0]."'>".$classes[$i][1]."</option>";
        			}else{
        				echo "<option value='".$classes[$i][0]."'>".$classes[$i][1]."</option>";
        			}
        		}
				
        	?>
        </select>	  
    </div>
    <div id="papers">
    	<table id="exportPapers" border=1>
        	<tr>
            	<th width="10%">题目号</th>
                <th width="65%">题目名</th>
                <th width="15%">创建时间</th>
                <th width="10%">
                	<input type="checkbox" id="selectAll" onclick="selectAll()"/>&nbsp;全选
                </th>
            </tr>
            <?php 
            	if($totalPageNumber==1){
            		for($i=1;$i<=$questionsNumber;$i++){
	            		echo "<tr>";
						echo "<td>".$questions[$i][0]."</td>";
	            		echo "<td><textarea name='timu' id='timu' style='width:100%;height:70px'>".$questions[$i][1]."</textarea></td>";
	            		echo "<td>".$questions[$i][3]."</td>";
	            		echo "<td><input type='checkbox' id='selectPaper' name='selectPaper' value='".$questions[$i][0]."' /></td>";
	            		echo "</tr>";
	            	}
	           		for($i=$questionsNumber+1;$i<=$pageSize;$i++){
	           			echo "<tr>";
	           			echo "<td>&nbsp;</td>";
	           			echo "<td>&nbsp;</td>";
	           			echo "<td>&nbsp;</td>";
	           			echo "<td>&nbsp;</td>";
	           			echo "</tr>";
	           		}
            	}
            	if($totalPageNumber>1){
            		for($i=1;$i<=$pageSize;$i++){
	            		echo "<tr>";
	            		echo "<td>".$questions[$i][0]."</td>";
	            		echo "<td><textarea name='timu' id='timu' style='width:100%;height:70px'>".$questions[$i][1]."</textarea></td>";
	            		echo "<td>".$questions[$i][3]."</td>";
	            		echo "<td><input type='checkbox' id='selectPaper' name='selectPaper' value='".$questions[$i][0]."' /></td>";
	            		echo "</tr>";
	            	}
            	}
            ?>
            <tr>
            	<td colspan="4">
            		<a href="javascript:saveSelectedPapers(<?php echo $currentPage;?>)">保存本页选择</a>
            		<a class="marginLeftStyle" href="javascript:firstPage(<?php echo $currentPage;?>,<?php echo $pageSize;?>,<?php echo $totalPageNumber?>)">首页</a>
                	<a class="marginLeftStyle" href="javascript:prePage(<?php echo $currentPage;?>,<?php echo $pageSize;?>,<?php echo $totalPageNumber?>)">上一页</a>
                	<a class="marginLeftStyle" href="javascript:nextPage(<?php echo $currentPage;?>,<?php echo $pageSize;?>,<?php echo $totalPageNumber?>)">下一页</a>
                	<a class="marginLeftStyle" href="javascript:lastPage(<?php echo $currentPage;?>,<?php echo $pageSize;?>,<?php echo $totalPageNumber?>)">尾页</a>
                	<label class="marginLeftStyle" style="margin-top:0px">转至第<input type="text" id="moveTo" style="width:30px" onblur="moveToPage(<?php echo $totalPageNumber;?>,<?php echo $pageSize;?>)"/>页</label>
                	<label class="marginLeftStyle">当前第<span style="color:red"><?php echo $currentPage;?></span>页</label>
                	<label class="marginLeftStyle">共<span style="color:red"><?php echo $totalPageNumber;?></span>页</label>
                </td>
            </tr>
        </table>
    </div>
    <div id="button">
    	<input type="button" id="testExport" class="button_style" name="testExport" value="导出学生成绩" onclick="exportStuScore()"/>
    </div>
    <div id="hiddenArea">
    	<input type="hidden" id="hideSelectedPapers" name="hideSelectedPapers"/>
    </div>
</div>
</body>
</html>