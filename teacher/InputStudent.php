<?php 
	session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}
	$teacherId=$_SESSION['teacherID'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html><!---->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>导入学生信息</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/importstuinfo.js"></script>
<script type='text/javascript'>
	function updateID(obj){
		var getClassName=obj.name;
		importform.classID.value=document.getElementById(getClassName).value;
	}
</script>

</head>
<?php 
	//require_once '../functionfile/gloable.func.php';
	include "../functionfile/config.inc.php";
	include '../functionfile/teacher.func.php';
?>
<body>
<center>
<div style="margin-top:10%">
	<div style="background-color:#6699ff">
		<p>
			<font style=" font-family: 楷体" size="+7">导入学生信息</font>
		</p>
	</div>
	<div style="margin-top:30px">
		<p>
			<font style="font-family:'楷体'" size="+3" color="#FF0000">导入须知：</font>
		</p>
		<p>
			<font style="font-family:'楷体'" size="+2" color="#FF0000">			  1.在导入信息前请在Excel中将学生信息按照学号、姓名、性别（男、女）的列顺序排列好
			</font>
		</p>
		<p>
			<font style="font-family:'楷体'" size="+2" color="#FF0000">			  2.在导入学生之前请选择好相应的班级，即学生所属的班级名字
			</font>
		</p>
		<p>
			<font style="font-family:'楷体'" size="+2" color="#FF0000">			  3.请确保文件的后缀名为xls
			</font>
		</p>
		<p>
			<font style="font-family:'楷体'" size="+2" color="#FF0000">			  4.点击浏览按钮找到存有学生信息的Excel表所在路径，导入并确保路径名无误
			</font>
		</p>
	</div>

	
			
	<div style="margin-top:30px; background-color:#6699ff">
		<form name="importform" action="uploadfile.php" method="post" enctype="multipart/form-data">
			<p>
				<font size="+2" style="font-family:'楷体'">班级：</font>
				<select name="classname" id="class" style="text-align:center;width:100px">
					<?php 
						
						$classes=t_GetClassListByTid($teacherId);
						for($i=1;$i<=$classes[0];$i++){
							$classid=$classes[$i][0];//获取班级id
							$classname=$classes[$i][1];//获取班级名称
							echo "<option value='".$classid."'>".$classname."</option>";
						}
					?>
				</select>
				<input type="file" name="upload" id="upload" style="margin-left:10px"/>
				<input type="submit" name="submit" value="导入" style="margin-left:10px" />
				<?php 
					if($classes[0]>0){
						echo "<input type='hidden' name='classID' id='recordClassID' value='".$classes[1][0]."'/>";
					}else{
						echo "<input type='hidden' name='classID' id='recordClassID' value=''/>";
					}
				?>
			</p>
		</form>
	</div>
</div>	
</center>
</body>
</html>