<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
	
	require_once dirname(dirname(__FILE__))."/excelreader/Excel/reader.php";
	include "../functionfile/config.inc.php";
	include"../functionfile/mysql.func.php";
	include '../functionfile/teacher.func.php';
	include '../functionfile/globle.func.php';
	//include '../functionfile/common.func.php';
	
	//判断是否选择了文件
	if($_FILES["upload"]["name"]==""){
	    _alert_back("请选择您要导入的文件");
	}
	$filename=$_FILES["upload"]["name"];//获取要导入的文件的名字
	//print_r($filename);
	$fileSuffix=_get_file_suffix($filename);//获取文件后缀名
	//判断文件是否Excel文件
	if($fileSuffix=="xls"||$fileSuffix=="xlsx"){
		$classID=$_POST['classID'];
	    $serverFilePath="../tmp/".$filename;//服务器的文件路径
	    if(file_exists($serverFilePath)){//判断服务器是否存在此文件，存在则先删除
	    	unlink($serverFilePath);
	    }
	    move_uploaded_file($_FILES["upload"]["tmp_name"],$serverFilePath);//将临时文件复制到服务器
	    //读取excel文件的学生信息并写入数据库
	    if(t_ImportStudentInfo($classID, $serverFilePath)){
	        unlink($serverFilePath);
	        _location("成功导入学生信息到班级", "InputStudent.php");
	    }else{
	        unlink($serverFilePath);
	        _location("部分学生信息未导入到班级", "InputStudent.php");
		} 
	}else{
	    _alert_back("文件必须为Excel工作簿");
	}
?>