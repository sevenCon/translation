<?php 
	session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}

	require "../functionfile/config.inc.php";
	include"../functionfile/teacher.func.php";

//获取questions里面的题目列表
function GetQuestionsList()
{
	$sql = "select distinct question_id from questions";
	if (!($result = sys_query_db_return($sql))) return false;
	$ret = array();
	$cnt = 0;
	while ($row = @mysql_fetch_array($result))
	{
		++$cnt;
		$ret[$cnt] = array($row[0]);
	}
	$ret[0] = $cnt;
	return $ret;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<script type="text/javascript" src="../js/jquery.js" ></script>
<script type="text/javascript" src="../js/StudentScore.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/WordList.css"/>
    <link rel="stylesheet" type="text/css" href="../css/table.css"/>
<title>查看学生成绩页面</title>
    <style type="text/css">
        td {
            overflow:hidden;
            text-overflow:ellipsis;
            text-align:center;
            height:42px;
        }
    </style>
</head>
<body>
<form action='' method='get'>
<select name='name'>			
<option value='0'>请选择需要查看的题目ID：</option>
<?php
  $questionslist = GetQuestionsList();
  for($i=1;$i<=$questionslist[0];$i++){
	echo "<option value='".$questionslist[$i][0]."'>".$questionslist[$i][0]."</option>";
  }
?>
<input name="btn_pro" type="submit" value="Submit"/>
</select>
</form><br /><br />
<?php 

/*<table id="questions" border="1">
<tr>
   <th width="15%">姓名</th>
   <th width="15%">满分为10分的得分</th>
   <th width="15%">实际分数</th>
</tr> 	  */

	   //显示题目内容
if(isset($_GET['btn_pro'])){
	   $sql="select * from student_answers as S, questions as Q where S.question_id=".$_GET[name]." and S.question_id = Q.question_id";
       $result=mysql_query($sql);
       $re=mysql_fetch_array($result);
       echo  "[ID号]：".$_GET[name]."<br/>";
	   echo  "[题目]：".$re['question_text']."<br/>";
	   echo  "[标准答案]：".$re['answer']."<br/><br /><br />";
}
	
$pageSize=10;
?>
<center> 
<div id="SynListTable">
          <table  style="margin-top:20px;width:800px;table-layout: fixed;" border="0" class="classes">
              <tr>
                  <th style="width:25%">姓名</th>
                  <th style="width:20%;">满分为10分的得分</th>
                  <th style="width:20%">实际分数</th>
				  <th style="width:35%">操作</th>
              </tr>
              <?php
              $student=t_getScoreNumber($_GET[name]);

              $totalStuNumber=$student[0];
			//  echo $totalStuNumber;
              //计算总共分多少页
              $totalPage=floor(($totalStuNumber+$pageSize-1)/$pageSize);
			  $beginIndex=0;
			   //获取请求的页面，并求得该页的第一条记录索引
			  if(isset($_GET['currentPage'])){
                  $beginIndex=($_GET['currentPage']-1)*$pageSize+1;
              }else{
                  $beginIndex=0;
                  $_GET['currentPage']=1;
              }

			  $requestPageContent=t_getScoreList($_GET[name],$beginIndex,$pageSize);
            //  print_r($requestPageContent);
              $requestPageContentNumber=$requestPageContent[0];
             
              //计算该页最后一条记录的索引
              $currentPage=$_GET['currentPage'];

                 for($i=1;$i<=$requestPageContentNumber;$i++){
                      echo "<tr style='height:30px'>";
                      if($student[$i][0]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='studentName'><p>".$student[$i][0]."</p></td>";
                      };
                      if($student[$i][1]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='Score'>".$student[$i][1]."</td>";
                      };
                      if($student[$i][2]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='realScore'>".$student[$i][2]."</td>";
                      }
                      echo "<td>";
                      echo "<a href='javascript:void(0)' onclick=\"showEditWordWindow(".$i.")\">编辑</a>";
                      echo "<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteStu('".$student[$i][3]."','".$student[$i][4]."')\">删除</a>";
                      echo "</td>";
                      echo"</tr>";
                 }
                  for($i=$requestPageContentNumber+1;$i<=$pageSize;$i++){
                      echo "<tr style='height:30px'>";
                      echo "<td>&nbsp;</td>";
                      echo "<td>&nbsp;</td>";
                      echo "<td>&nbsp;</td>";
                      echo "<td>&nbsp;</td>";
                      echo "</tr>";
                  }
              ?>
              <tr>
                  <td colspan="7">
                      <p style="margin-left: 0px;">
                          <a href="javascript:void(0)" onclick="firstPage('<?php echo $currentPage;?>','<?php echo $totalPage;?>','<?php echo $pageSize;?>','<?php echo $_GET[name]; ?>')">首页</a>
                          <a href="javascript:void(0)" style="margin-left:10px;" onclick="prePage('<?php echo $currentPage;?>','<?php echo $totalPage;?>','<?php echo $pageSize;?>','<?php echo $_GET[name]; ?>')">上一页</a>
                          <a href="javascript:void(0)" style="margin-left:10px" onclick="nextPage(<?php echo $currentPage;?>,<?php echo $totalPage;?>,<?php echo $pageSize?>,'<?php echo $_GET[name]; ?>')">下一页</a>
                          <a href="javascript:void(0)" style="margin-left:10px" onclick="lastPage(<?php echo $currentPage;?>,<?php echo $totalPage;?>,<?php echo $pageSize?>,'<?php echo $_GET[name]; ?>')">尾页</a>
						  <font style="margin-left:50px">共&nbsp;<?php echo $totalPage;?>&nbsp;页</font>
						  <font style="margin-left:10px">当前第&nbsp;<?php echo $currentPage;?>&nbsp;页</font>
						
                      </p>
                  </td>
              </tr>
          </table>
      </div>
    <div id="floatLayout"></div>
    <?php
    for($i=1;$i<=$requestPageContentNumber;$i++){
        echo "<div id='edit".$i."' class='alertWindow'>";
        echo "<div id='closed' onclick=\"closeEditWordWindow(".$i.",'".$student[$i][0]."','".$student[$i][1]."','".$student[$i][2]."')\">X</div>";
        echo "<table>";
        echo "<tr>";
        echo "<td><label>学生姓名:</label></td>";
        echo "<td><input type='text' id='studentName' style='width:100px'  value='".$student[$i][0]."'/></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><label>满分为10分的得分:</label></td>";
        echo "<td><input type='text' id='Score' style='width:100px'  value='".$student[$i][1]."'/></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><label>实际分数:</label></td>";
        echo "<td><input type='text' id='realScore' style='width:100px'  value='".$student[$i][2]."'/></td>";
        echo "</tr>";

        echo "</table>";
        echo "<p style='text-align:center'>";
        echo "<input type='button' value='更新' onclick=\"updateSynWord(".$i.",'".$student[$i][0]."',".$student[$i][1].",".$student[$i][2].",'".$student[$i][3]."',".$student[$i][4].")\"/>";
        echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditWordWindow(".$i.",'".$student[$i][0]."','".$student[$i][1]."','".$student[$i][2]."')\">";
        echo "</p></div>";
        // ++$editDivID;
    }
    for($i=$requestPageContentNumber+1;$i<=$pageSize;$i++){
        echo "<div id='edit".$i."' class='alertWindow'>";
        echo "<div id='closed' onclick=\"closeEditWordWindow(".$i.",'".$student[$i][0]."','".$student[$i][1]."','".$student[$i][2]."')\">X</div>";
        echo "<table>";
        echo "<tr>";
        echo "<td><label>学生姓名:</label></td>";
        echo "<td><input type='text' id='studentName' style='width:100px'  value='".$student[$i][0]."'/></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><label>满分为10分的得分:</label></td>";
        echo "<td><input type='text' id='Score' style='width:100px'  value='".$student[$i][1]."'/></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><label>实际分数:</label></td>";
        echo "<td><input type='text' id='realScore' style='width:100px'  value='".$student[$i][2]."'/></td>";
        echo "</tr>";

        echo "</table>";
        echo "<p style='text-align:center'>";
        echo "<input type='button' value='更新' onclick=\"updateSynWord(".$i.",'".$student[$i][0]."',".$student[$i][1].",".$student[$i][2].",'".$student[$i][3]."',".$student[$i][4].")\"/>";
        echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditWordWindow(".$i.",'".$student[$i][0]."','".$student[$i][1]."','".$student[$i][2]."')\">";
        echo "</p></div>";
        // ++$editDivID;
    }
    ?>
    </DIV>


</center>
</body>
</html>