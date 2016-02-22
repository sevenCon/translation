<?php
    session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}
	//include('../header.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>
<HEAD>
  <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/NegWordList.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/WordList.css"/>
    <link rel="stylesheet" type="text/css" href="../css/table.css"/>
  <TITLE>同义否定词列表</TITLE>
    <style type="text/css">
        td {
            overflow:hidden;
            text-overflow:ellipsis;
            text-align:center;
            height:42px;
        }
    </style>
</HEAD>
<BODY>
<?php
include"../functionfile/config.inc.php";
include"../functionfile/teacher.func.php";
?>
<center>
<DIV id="main">
<?php
  $pageSize=10;
?>
	 <h1>同义否定词列表</h1>
     <hr>
      <div id="NegListTable">
          <table  style="margin-top:20px;width:800px;table-layout: fixed;" border="0" class="classes">
              <tr>
                  <th style="width:10%">题目ID</th>
                  <th style="width:70%;" colspan="5">同义否定词</th>
                  <th style="width:20%">操作</th>
              </tr>
              <?php
              //定义当前页面的记录数              
              $negData=t_getNegListNumber();
              $totalNegNumber=$negData[0];
			//  echo $totalNegNumber; 
              //计算总共分多少页
              $totalPage=floor(($totalNegNumber+$pageSize-1)/$pageSize);
			  $beginIndex=0;
			   //获取请求的页面，并求得该页的第一条记录索引
			  if(isset($_GET['currentpage'])){
                  $beginIndex=($_GET['currentpage']-1)*$pageSize+1;
              }else{
                  $beginIndex=0;
                  $_GET['currentpage']=1;
              }
			  $requestPageContent=t_getNegList($beginIndex,$pageSize);
              $requestPageContentNumber=$requestPageContent[0];
			//	 echo   $requestPageContentNumber;
              $currentPage=$_GET['currentpage'];
                 for($i=1;$i<=$requestPageContentNumber;$i++){
                      echo "<tr style='height:30px'>";
                      if($negData[$i][0]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='wordid'><p>".$negData[$i][0]."</p></td>";
                      };
                      if($negData[$i][1]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='negWord1'>".$negData[$i][1]."</td>";
                      };
                      if($negData[$i][2]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='negWord2'>".$negData[$i][2]."</td>";
                      }
                      if($negData[$i][3]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='negWord3'>".$negData[$i][3]."</td>";
                      }
                      if($negData[$i][4]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='negWord4'>".$negData[$i][4]."</td>";
                      }
                      if($negData[$i][5]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='negWord5'>".$negData[$i][5]."</td>";
                      }
                      echo "<td>";
                      echo "<a href='javascript:void(0)' onclick=\"showEditWordWindow(".$i.")\">编辑</a>";
                      echo "<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteNeg(".$negData[$i][0].")\">删除</a>";
                      echo "</td>";
                      echo"</tr>";
                 }
                  for($i=$requestPageContentNumber+1;$i<=$pageSize;$i++){
                      echo "<tr style='height:30px'>";
                      echo "<td>&nbsp;</td>";
                      echo "<td>&nbsp;</td>";
                      echo "<td>&nbsp;</td>";
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
                          <a href="javascript:void(0)" onclick="firstPage('<?php echo $currentPage;?>','<?php echo $totalPage;?>','<?php echo $pageSize;?>')">首页</a>
                          <a href="javascript:void(0)" style="margin-left:10px;" onclick="prePage('<?php echo $currentPage;?>','<?php echo $totalPage;?>','<?php echo $pageSize;?>')">上一页</a>
                          <a href="javascript:void(0)" style="margin-left:10px" onclick="nextPage(<?php echo $currentPage;?>,<?php echo $totalPage;?>,<?php echo $pageSize?>)">下一页</a>
                          <a href="javascript:void(0)" style="margin-left:10px" onclick="lastPage('<?php echo $currentPage;?>','<?php echo $totalPage;?>','<?php echo $pageSize?>')">尾页</a>
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
             echo "<div id='closed' onclick=\"closeEditWordWindow(".$i.",'".$negData[$i][1]."','".$negData[$i][2]."','".$negData[$i][3]."','".$negData[$i][4]."','".$negData[$i][5]."')\">X</div>";
             echo "<table>";
             echo "<tr>";
             echo "<td><label>同义词1:</label></td>";
             echo "<td><input type='text' id='negWord1' style='width:100px'  value='".$negData[$i][1]."'/></td>";
			 echo "<td><input type='hidden' id='wordid'   value='".$negData[$i][0]."' /></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词2:</label></td>";
             echo "<td><input type='text' id='negWord2' style='width:100px'  value='".$negData[$i][2]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词3:</label></td>";
             echo "<td><input type='text' id='negWord3' style='width:100px'  value='".$negData[$i][3]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词4:</label></td>";
             echo "<td><input type='text' id='negWord4' style='width:100px'  value='".$negData[$i][4]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词5:</label></td>";
             echo "<td><input type='text' id='negWord5' style='width:100px'  value='".$negData[$i][5]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td colspan='2' style='height:15px'><label id='errorInfo' style='color:red;font-size:12px'></label></td>";
             echo "</tr>";
             echo "</table>";
             echo "<p style='text-align:center'>";
             echo "<input type='button' value='更新' onclick=\"updateNegWord(".$i.",".$negData[$i][0].",'".$negData[$i][1]."','".$negData[$i][2]."','".$negData[$i][3]."','".$negData[$i][4]."','".$negData[$i][5]."')\"/>";
             echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditWordWindow(".$i.",'".$negData[$i][1]."','".$negData[$i][2]."','".$negData[$i][3]."','".$negData[$i][4]."','".$negData[$i][5]."')\"/>";
             echo "</p></div>";
            // ++$editDivID;
         }
         for($i=$requestPageContentNumber+1;$i<=$pageSize;$i++){
             echo "<div id='edit".$i."' class='alertWindow'>";
             echo "<div id='closed' onclick=\"closeEditWordWindow(".$i.",'".$negData[$i][1]."','".$negData[$i][2]."','".$negData[$i][3]."','".$negData[$i][4]."','".$negData[$i][5]."')\">X</div>";
             echo "<table>";
             echo "<tr>";
             echo "<td><label>同义词1:</label></td>";
             echo "<td><input type='text' id='negWord1' style='width:100px'  value='".$negData[$i][1]."'/></td>";
			 echo "<td><input type='hidden' id='wordid'   value='".$negData[$i][0]."' /></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词2:</label></td>";
             echo "<td><input type='text' id='negWord2' style='width:100px'  value='".$negData[$i][2]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词3:</label></td>";
             echo "<td><input type='text' id='negWord3' style='width:100px'  value='".$negData[$i][3]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词4:</label></td>";
             echo "<td><input type='text' id='negWord4' style='width:100px'  value='".$negData[$i][4]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词5:</label></td>";
             echo "<td><input type='text' id='negWord5' style='width:100px'  value='".$negData[$i][5]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td colspan='2' style='height:15px'><label id='errorInfo' style='color:red;font-size:12px'></label></td>";
             echo "</tr>";
             echo "</table>";
             echo "<p style='text-align:center'>";
             echo "<input type='button' value='更新' onclick=\"updateNegWord(".$i.",".$negData[$i][0].",'".$negData[$i][1]."','".$negData[$i][2]."','".$negData[$i][3]."','".$negData[$i][4]."','".$negData[$i][5]."')\"/>";
             echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditWordWindow(".$i.",'".$negData[$i][1]."','".$negData[$i][2]."','".$negData[$i][3]."','".$negData[$i][4]."','".$negData[$i][5]."')\"/>";
             echo "</p></div>";
            // ++$editDivID;
         }
     ?>
  </DIV>
</center>
</BODY>
</HTML>
