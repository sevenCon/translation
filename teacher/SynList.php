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
    <script type="text/javascript" src="../js/SynWordList.js" ></script>
    <link rel="stylesheet" type="text/css" href="../css/WordList.css"/>
    <link rel="stylesheet" type="text/css" href="../css/table.css"/>
  <TITLE>同义词列表</TITLE>
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
	 <h1>同义词列表</h1>
     <hr>
      <div id="SynListTable">
          <table  style="margin-top:20px;width:800px;table-layout: fixed;" border="0" class="classes">
              <tr>
                  <th style="width:10%">题目ID</th>
                  <th style="width:70%;" colspan="5">同义词</th>
                  <th style="width:20%">操作</th>
              </tr>
              <?php
              //定义当前页面的记录数
              
              $synData=t_getSynListNumber();
              $totalSynNumber=$synData[0];
			//  echo $totalSynNumber;
              //计算总共分多少页
              $totalPage=floor(($totalSynNumber+$pageSize-1)/$pageSize);
			  //echo  $totalPage;
			  $beginIndex=0;
			   //获取请求的页面，并求得该页的第一条记录索引
			  if(isset($_GET['currentpage'])){
                  $beginIndex=($_GET['currentpage']-1)*$pageSize+1;
              }else{
                  $beginIndex=0;
                  $_GET['currentpage']=1;
              }
			  $requestPageContent=t_getSynList($beginIndex,$pageSize);
              $requestPageContentNumber=$requestPageContent[0];
             
              
              //计算该页最后一条记录的索引
             // $endIndex=$beginIndex+$pageSize-1;
              $currentPage=$_GET['currentpage'];
              //$restDataNumber=0;
             // $markEditId=1;
                 for($i=1;$i<=$requestPageContentNumber;$i++){
                      echo "<tr style='height:30px'>";
                      if($synData[$i][0]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='wordid'><p>".$synData[$i][0]."</p></td>";
                      };
                      if($synData[$i][1]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='synWord1'>".$synData[$i][1]."</td>";
                      };
                      if($synData[$i][2]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='synWord2'>".$synData[$i][2]."</td>";
                      }
                      if($synData[$i][3]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='synWord3'>".$synData[$i][3]."</td>";
                      }
                      if($synData[$i][4]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='synWord4'>".$synData[$i][4]."</td>";
                      }
                      if($synData[$i][5]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='synWord5'>".$synData[$i][5]."</td>";
                      }
                      echo "<td>";
                      echo "<a href='javascript:void(0)' onclick=\"showEditWordWindow(".$i.")\">编辑</a>";
                      echo "<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteSyn(".$synData[$i][0].")\">删除</a>";
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
                          <a href="javascript:void(0)" style="margin-left:10px" onclick="lastPage(<?php echo $currentPage;?>,<?php echo $totalPage;?>,<?php echo $pageSize?>)">尾页</a>
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
             echo "<div id='closed' onclick=\"closeEditWordWindow(".$i.",'".$synData[$i][1]."','".$synData[$i][2]."','".$synData[$i][3]."','".$synData[$i][4]."','".$synData[$i][5]."')\">X</div>";
             echo "<table>";
             echo "<tr>";
             echo "<td><label>同义词1:</label></td>";
             echo "<td><input type='text' id='synWord1' style='width:100px'  value='".$synData[$i][1]."'/></td>";
			  echo "<td><input type='hidden' id='wordid'   value='".$synData[$i][0]."' /></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词2:</label></td>";
             echo "<td><input type='text' id='synWord2' style='width:100px'  value='".$synData[$i][2]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词3:</label></td>";
             echo "<td><input type='text' id='synWord3' style='width:100px'  value='".$synData[$i][3]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词4:</label></td>";
             echo "<td><input type='text' id='synWord4' style='width:100px'  value='".$synData[$i][4]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词5:</label></td>";
             echo "<td><input type='text' id='synWord5' style='width:100px'  value='".$synData[$i][5]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td colspan='2' style='height:15px'><label id='errorInfo' style='color:red;font-size:12px'></label></td>";
             echo "</tr>";
             echo "</table>";
             echo "<p style='text-align:center'>";
             echo "<input type='button' value='更新' onclick=\"updateSynWord(".$i.",".$synData[$i][0].",'".$synData[$i][1]."','".$synData[$i][2]."','".$synData[$i][3]."','".$synData[$i][4]."','".$synData[$i][5]."')\"/>";
             echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditWordWindow(".$i.",'".$synData[$i][1]."','".$synData[$i][2]."','".$synData[$i][3]."','".$synData[$i][4]."','".$synData[$i][5]."')\">";
             echo "</p></div>";
            // ++$editDivID;
         }
         for($i=$requestPageContentNumber+1;$i<=$pageSize;$i++){
             echo "<div id='edit".$i."' class='alertWindow'>";
             echo "<div id='closed' onclick=\"closeEditWordWindow(".$i.",'".$synData[$i][1]."','".$synData[$i][2]."','".$synData[$i][3]."','".$synData[$i][4]."','".$synData[$i][5]."')\">X</div>";
             echo "<table>";
             echo "<tr>";
             echo "<td><label>同义词1:</label></td>";
             echo "<td><input type='text' id='synWord1' style='width:100px'  value='".$synData[$i][1]."'/></td>";
			 echo "<td><input type='hidden' id='wordid'   value='".$synData[$i][0]."' /></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词2:</label></td>";
             echo "<td><input type='text' id='synWord2' style='width:100px'  value='".$synData[$i][2]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词3:</label></td>";
             echo "<td><input type='text' id='synWord3' style='width:100px'  value='".$synData[$i][3]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词4:</label></td>";
             echo "<td><input type='text' id='synWord4' style='width:100px'  value='".$synData[$i][4]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>同义词5:</label></td>";
             echo "<td><input type='text' id='synWord5' style='width:100px'  value='".$synData[$i][5]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td colspan='2' style='height:15px'><label id='errorInfo' style='color:red;font-size:12px'></label></td>";
             echo "</tr>";
             echo "</table>";
             echo "<p style='text-align:center'>";
             echo "<input type='button' value='更新' onclick=\"updateSynWord(".$i.",".$synData[$i][0].",'".$synData[$i][1]."','".$synData[$i][2]."','".$synData[$i][3]."','".$synData[$i][4]."','".$synData[$i][5]."')\"/>";
             echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditWordWindow(".$i.",'".$synData[$i][1]."','".$synData[$i][2]."','".$synData[$i][3]."','".$synData[$i][4]."','".$synData[$i][5]."')\">";
             echo "</p></div>";
            // ++$editDivID;
         }


     ?>
  </DIV>
</center>
</BODY>
</HTML>
