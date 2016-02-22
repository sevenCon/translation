<?php 
        session_start();
         header('Content-Type: text/html; charset=utf-8');

		 require('../functionfile/config.inc.php');
		 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<HTML>
<HEAD>
  <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <TITLE>作业管理</TITLE>
  <link rel="stylesheet" type="text/css" href="../css/table.css"/>
</HEAD>
<BODY>
  <DIV id="main">

    <DIV id="classInfo" class="divStyle">
      <TABLE class="classes" border="0">

		  <tr>
                	<th width="15%">作业名称</th>
                    <th width="5%">题量 </th>
                    <th width="10%">满分</th>
					<th width="10%">创建时间</th>
					<th width="60%">操作</th>
          </tr> 
	<?php 
         
       $query="select * from tests ;";
       $result=mysql_query($query);
       $cnt=0;
      while ($re=mysql_fetch_array($result)){

			    echo "\n<tr>";
                echo "\n<td ><p><span> ", $re['test_title'], "</span></p></td>";
                echo "\n<td > ", $re['question_num'], "</td>";
                echo "\n<td > ", $re['total_mark'], "</td>";
				echo "\n<td > ", $re['create_time'], "</td>";
			    echo "\n<td> 详细 完成情况 评分统计 查看题目 添加题目 编辑 导出各班成绩 关闭 删除</td>";
                echo "\n</tr>" ;
	}


	 ?>
	 
  </DIV>
</BODY>
</HTML>
