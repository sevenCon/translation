<?php 
        session_start();
         header('Content-Type: text/html; charset=utf-8');
	
		 require('../functionfile/config.inc.php');
		 include"../functionfile/teacher.func.php";
		 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>
<HEAD>
  <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <script type="text/javascript" src="../js/jquery.js"></script>
   <script type="text/javascript" src="../js/QuestionList.js" ></script>
   <link rel="stylesheet" type="text/css" href="../css/WordList.css"/> 
   <link rel="stylesheet" type="text/css" href="../css/table.css"/>
  <TITLE>题目列表</TITLE>
</HEAD>
<center>
<BODY>
<form action='' method='get'>
<select name='name'>			
<option value='0'>请选择需要查看的题目类型：</option> 
<?php
  for($i=1;$i<=6;$i++){
	  if($i==1)	{  echo "<option value='".$i."'>英译汉</option>";	 }
	  else if($i==2){
		  echo "<option value='".$i."'>汉译英</option>";
	  }else if($i==3){
		  echo "<option value='".$i."'>句子翻译</option>";
	  }else if($i==4){
		  echo "<option value='".$i."'>段落翻译</option>";
	  }else if($i==5){
		  echo "<option value='".$i."'>篇章翻译</option>";
	  }else{
          echo "<option value='".$i."'>句译·汉译越</option>";
      }
  }
?>
  <input name="btn_pro" type="submit" value="Submit"/>
</select>
</form><br /><br />

<?php
// 选定类型的内容
if(isset($_GET['btn_pro'])){
	   // echo  $_GET[name];
	   //先选定句子翻译的题目	$_GET[name]==3
} 
$pageSize=5;
?>
  <div id="ShowTable" >
   <table  style="margin-top:20px;width:800px;table-layout: fixed;" border="0" class="classes">
              <tr>
                  <th style="width:35%">题目</th>
                  <th style="width:40%">答案</th>
				  <th style="width:5%">分值</th>
                  <th style="width:20%">操作</th>	 
              </tr>
			  <?php

			  $Data = t_getStyleNumber($_GET[name]);  //把获取题目信息放到功能里面

			  $totalDataNumber=$Data[0];
			 // echo  $totalDataNumber;
			  $totalPage=floor(($totalDataNumber+$pageSize-1)/$pageSize);
			 
			  $beginIndex=0;
			  if(isset($_GET['currentpage'])){
                  $beginIndex=($_GET['currentpage']-1)*$pageSize+1;
              }else{
                  $beginIndex=0;
                  $_GET['currentpage']=1;
              }
			  $requestPageContent=t_getStyleList($_GET[name],$beginIndex,$pageSize);
              $requestPageContentNumber=$requestPageContent[0];

              $currentPage=$_GET['currentpage'];

			  if($_GET[name]==3 || $_GET[name]==6){//选中句子翻译
                  if($_GET[name]==6){
                      echo "<input type='hidden' name='flag' value='hanyue'/>";
                  }
				  for($i=1;$i<=$requestPageContentNumber;$i++){
                      echo "<tr style='height:30px'>";
                      if($Data[$i][0]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='question_text'>".$Data[$i][0]."</td>";
                      };
                      if($Data[$i][1]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='answer'>".$Data[$i][1]."</td>";
                      };
                      if($Data[$i][2]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='mark'>".$Data[$i][2]."</td>";
                      }
                      echo "<td>";
                      echo "<a href='javascript:void(0)' onclick=\"showEditDataWindow(".$i.")\">编辑</a>";
                      echo "<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteData(".$Data[$i][3].",".$_GET[name].")\">删除</a>";		//$Data[][3]=id
					  //echo  $Data[$i][3]."&nbsp".$_GET[name];
					  echo "<a href='javascript:void(0)' onclick=\"showReScoreDataWindow(".$i.")\">重新评分</a>";
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

              }else if($_GET[name]==1){	//英译汉

				  for($i=1;$i<=$requestPageContentNumber;$i++){	  //0-timu 1 2 3=answer 4-score 5-id
                      echo "<tr style='height:30px'>";
                      if($Data[$i][0]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='q_sentence'><p>".$Data[$i][0]."</p></td>";
                      };
                      if($Data[$i][1]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='q_answer'>".$Data[$i][1]."&nbsp".$Data[$i][2]."&nbsp".$Data[$i][3]."</td>";
                      };
                      if($Data[$i][4]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='q_score'>".$Data[$i][4]."</td>";
                      }
                      echo "<td>";
                      echo "<a href='javascript:void(0)' onclick=\"showEditDataWindow(".$i.")\">编辑</a>";
                      echo "<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteData(".$Data[$i][5].",".$_GET[name].")\">删除</a>";
					  //echo  $Data[$i][5]."&nbsp".$_GET[name];
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
				
			  }else if($_GET[name]==2){	//汉译英

				   for($i=1;$i<=$requestPageContentNumber;$i++){//0-timu 1 2 3=answer 4-score 5-id
                      echo "<tr style='height:30px'>";
                      if($Data[$i][0]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='q_sentence'><p>".$Data[$i][0]."</p></td>";
                      };
                      if($Data[$i][1]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='q_answer'>".$Data[$i][1]."&nbsp".$Data[$i][2]."&nbsp".$Data[$i][3]."</td>";
                      };
                      if($Data[$i][4]==null){
                          echo "<td>&nbsp;</td>";
                      }else{
                          echo"<td name='q_score'>".$Data[$i][4]."</td>";
                      }
                      echo "<td>";
                      echo "<a href='javascript:void(0)' onclick=\"showEditDataWindow(".$i.")\">编辑</a>";
                      echo "<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteData(".$Data[$i][5].",".$_GET[name].")\">删除</a>";
					  //echo  $Data[$i][5]."&nbsp".$_GET[name];
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

			  }else if($_GET[name]==4){	//段落翻译

			  }else if($_GET[name]==5){//篇章翻译
				
			  }else{//句子翻译：汉译越

              }
                 
			  ?>
			  <tr>
                  <td colspan="7">
                      <p style="margin-left: 0px;">
                          <a href="javascript:void(0)" onclick="firstPage('<?php echo $currentPage;?>','<?php echo $totalPage;?>','<?php echo $pageSize;?>',<?php echo $_GET[name];?>)">首页</a><!---传输style值 -->

                          <a href="javascript:void(0)" style="margin-left:10px;" onclick="prePage('<?php echo $currentPage;?>','<?php echo $totalPage;?>','<?php echo $pageSize;?>',<?php echo $_GET[name];?>)">上一页</a>

                          <a href="javascript:void(0)" style="margin-left:10px" onclick="nextPage('<?php echo $currentPage;?>','<?php echo $totalPage;?>','<?php echo $pageSize?>','<?php echo $_GET[name];?>')">下一页</a>


                          <a href="javascript:void(0)" style="margin-left:10px" onclick="lastPage(<?php echo $currentPage;?>,<?php echo $totalPage;?>,<?php echo $pageSize?>,<?php echo $_GET[name];?>)">尾页</a>

						  <font style="margin-left:50px">共&nbsp;<?php echo $totalPage;?>&nbsp;页</font>
						  <font style="margin-left:10px">当前第&nbsp;<?php echo $currentPage;?>&nbsp;页</font>
						
                      </p>
                  </td>
              </tr>

          </table>

  </div>

  <div id="floatLayout"></div>
     <?php 

	    if($_GET[name]==3 || $_GET[name]==6){//选中句子翻译

			for($i=1;$i<=$requestPageContentNumber;$i++){
             echo "<div id='edit".$i."' class='alertWindow'>";
             echo "<div id='closed' onclick=\"closeEditDataWindow(".$i.",'".$Data[$i][0]."','".$Data[$i][1]."','".$Data[$i][2]."')\">X</div>";
             echo "<table>";
             echo "<tr>";
             echo "<td><label>题目:</label></td>";
			 echo  "<td><textarea name='timu' id='timu' readonly='readonly' style='width:200px;height:100px'>".$Data[$i][0]."</textarea></td>";
			 echo "<td><input type='hidden' id='wordid1'   value='".$Data[$i][3]."' /></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>答案:</label></td>";
              echo  "<td><textarea name='daan' id='daan' style='width:200px;height:100px'>".$Data[$i][1]."</textarea></td>"  ;
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>分值:</label></td>";
             echo "<td><input type='text' id='mark' style='width:100px'  value='".$Data[$i][2]."'/></td>";
             echo "</tr>";

             echo "<tr>";
             echo "<td colspan='2' style='height:15px'><label id='errorInfo' style='color:red;font-size:12px'></label></td>";
             echo "</tr>";
             echo "</table>";
             echo "<p style='text-align:center'>";
             echo "<input type='button' value='更新' onclick=\"updateData(".$i.",".$Data[$i][3].",'".$Data[$i][0]."','".$Data[$i][1]."','".$Data[$i][2]."')\"/>";  // $i, id, timu, daan, mark
             echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditDataWindow(".$i.",'".$Data[$i][0]."','".$Data[$i][1]."','".$Data[$i][2]."')\">";
             echo "</p></div>";
         }
         for($i=$requestPageContentNumber+1;$i<=$pageSize;$i++){
             echo "<div id='edit".$i."' class='alertWindow'>";
             echo "<div id='closed' onclick=\"closeEditDataWindow(".$i.",'".$Data[$i][0]."','".$Data[$i][1]."','".$Data[$i][2]."')\">X</div>";
             echo "<table>";
             echo "<tr>";
             echo "<td><label>题目:</label></td>";
			 echo  "<td><textarea name='timu' id='timu' readonly='readonly' style='width:200px;height:100px'>".$Data[$i][0]."</textarea></td>"  ;
			 echo "<td><input type='hidden' id='wordid1'   value='".$Data[$i][3]."' /></td>";
             echo "</tr>";
             echo "<tr>";
              echo "<td><label>答案:</label></td>";
              echo  "<td><textarea name='daan' id='daan' style='width:200px;height:100px'>".$Data[$i][1]."</textarea></td>"  ;
             echo "</tr>";
             echo "<tr>";
            echo "<td><label>分值:</label></td>";
             echo "<td><input type='text' id='mark' style='width:100px'  value='".$Data[$i][2]."'/></td>";
             echo "</tr>";
             
             echo "</table>";
             echo "<p style='text-align:center'>";
             echo "<input type='button' value='更新' onclick=\"updateData(".$i.",".$Data[$i][3].",'".$Data[$i][0]."','".$Data[$i][1]."','".$Data[$i][2]."')\"/>";
             echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditDataWindow(".$i.",'".$Data[$i][0]."','".$Data[$i][1]."','".$Data[$i][2]."')\">";
             echo "</p></div>";
         }

		}else if($_GET[name]==1 || $_GET[name]==2){	//英译汉

			for($j=1;$j<=$requestPageContentNumber;$j++){
             echo "<div id='edit".$j."' class='alertWindow'>";
             echo "<div id='closed' onclick=\"closeEditDataWindow(".$j.",'".$Data[$j][0]."','".$Data[$j][1]."','".$Data[$j][2]."')\">X</div>";
             echo "<table>";
             echo "<tr>";
              echo "<td><label>题目:</label></td>";
             echo "<td><input type='text' id='timu' style='width:100px'  value='".$Data[$j][0]."'/></td>";
			 echo "<td><input type='hidden' id='wordid'   value='".$Data[$j][5]."' /></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>答案1:</label></td>";
             echo "<td><input type='text' id='daan1' style='width:100px'  value='".$Data[$j][1]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>答案2:</label></td>";
             echo "<td><input type='text' id='daan2' style='width:100px'  value='".$Data[$j][2]."'/></td>";
             echo "</tr>";
			 echo "<tr>";
             echo "<td><label>答案3:</label></td>";
             echo "<td><input type='text' id='daan3' style='width:100px'  value='".$Data[$j][3]."'/></td>";
             echo "</tr>";
			 echo "<tr>";
             echo "<td><label>分值:</label></td>";
             echo "<td><input type='text' id='mark' style='width:100px'  value='".$Data[$j][4]."'/></td>";
             echo "</tr>";

             echo "<tr>";
             echo "<td colspan='2' style='height:15px'><label id='errorInfo' style='color:red;font-size:12px'></label></td>";
             echo "</tr>";
             echo "</table>";
             echo "<p style='text-align:center'>";
             echo "<input type='button' value='更新' onclick=\"updateDataEtoc(".$j.",".$Data[$j][5].",'".$Data[$j][0]."','".$Data[$j][1]."','".$Data[$j][2]."','".$Data[$j][3]."','".$Data[$j][4]."',".$_GET[name].")\"/>";  
             echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditDataWindow(".$j.",'".$Data[$j][0]."','".$Data[$j][1]."','".$Data[$j][2]."')\">";
             echo "</p></div>";
         }
         for($j=$requestPageContentNumber+1;$j<=$pageSize;$j++){
             echo "<div id='edit".$j."' class='alertWindow'>";
             echo "<div id='closed' onclick=\"closeEditDataWindow(".$j.",'".$Data[$j][0]."','".$Data[$j][1]."','".$Data[$j][2]."')\">X</div>";
             echo "<table>";
             echo "<tr>";
             echo "<td><label>题目:</label></td>";
             echo "<td><input type='text' id='timu' style='width:100px'  value='".$Data[$j][0]."'/></td>";
			 echo "<td><input type='hidden' id='wordid'   value='".$Data[$j][5]."' /></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>答案1:</label></td>";
             echo "<td><input type='text' id='daan1' style='width:100px'  value='".$Data[$j][1]."'/></td>";
             echo "</tr>";
             echo "<tr>";
             echo "<td><label>答案2:</label></td>";
             echo "<td><input type='text' id='daan2' style='width:100px'  value='".$Data[$j][2]."'/></td>";
             echo "</tr>";
			 echo "<tr>";
             echo "<td><label>答案3:</label></td>";
             echo "<td><input type='text' id='daan3' style='width:100px'  value='".$Data[$j][3]."'/></td>";
             echo "</tr>";
			 echo "<tr>";
             echo "<td><label>分值:</label></td>";
             echo "<td><input type='text' id='mark' style='width:100px'  value='".$Data[$j][4]."'/></td>";
             echo "</tr>";

             echo "</table>";
             echo "<p style='text-align:center'>";
             echo "<input type='button' value='更新' onclick=\"updateDataEtoc(".$j.",".$Data[$j][5].",'".$Data[$j][0]."','".$Data[$j][1]."','".$Data[$j][2]."','".$Data[$j][3]."','".$Data[$j][4]."',".$_GET[name].")\"/>";
             echo "<input type='button' style='margin-left:10px' value='取消'  onclick=\"closeEditDataWindow(".$j.",'".$Data[$j][0]."','".$Data[$j][1]."','".$Data[$j][2]."')\">";
             echo "</p></div>";
         }

		}
		else if($_GET[name]==4){	//段落翻译

		}
        else{//篇章翻译

        }
         


     ?>
  </DIV>


</BODY>
</center>


 

</HTML>
