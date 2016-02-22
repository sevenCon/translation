<?php
session_start();

$flag = $_GET['flag'];
//引入PHPExcel相关文件
require "../functionfile/config.inc.php";
//备注：
//$_SERVER['DOCUMENT_ROOT'] . '/dm/11/translation(beta2.0)/PHPExcel_1.8.0_doc/Classes/'
define("EXCEL_FILE", $_SERVER['DOCUMENT_ROOT'] . 'translation(beta1.0)/PHPExcel_1.8.0_doc/Classes/');
require_once EXCEL_FILE . "PHPExcel.php";
require_once EXCEL_FILE . 'PHPExcel/IOFactory.php';
require_once EXCEL_FILE . 'PHPExcel/Writer/Excel5.php';
//新建
$resultPHPExcel = new PHPExcel();
if($flag=='hanyue'){
    $query_st = "select  question_id,answer_question  from new_ctf_answer  where answer_stu_id = '".$_SESSION['stuID']."';";		 //quote?  there changed!!
}else{
    $query_st = "select question_id,answer  from student_answers  where s_id = '".$_SESSION['stuID']."';";		 //quote?  there changed!!
}
//    echo  "seee".$query_st;
$result_st = mysql_query($query_st);
//    echo  "1:".$re[0];exit;
//设置参数
//设值
$resultPHPExcel->getActiveSheet()->setCellValue('A1', '题号');
$resultPHPExcel->getActiveSheet()->setCellValue('B1', '答案');
$i = 2;
while ($row = mysql_fetch_array($result_st, MYSQL_NUM)) {
    $resultPHPExcel->getActiveSheet()->setCellValue('A'.$i,$row[0]);
    $resultPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i,$row[1],PHPExcel_Cell_DataType::TYPE_STRING);
    $resultPHPExcel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode("@");
    $i++;
}
//设置导出文件名
$outputFileName = $_SESSION['stuID'].'.xls';
$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
//ob_start(); ob_flush();
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header('Content-Disposition:inline;filename="' . $outputFileName . '"');
header("Content-Transfer-Encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
$xlsWriter->save("php://output");

$finalFileName = '/runtime/' . time() . '.xls';
$xlsWriter->save($finalFileName);
echo file_get_contents($finalFileName);