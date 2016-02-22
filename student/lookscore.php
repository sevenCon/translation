<?php
    SESSION_START();
   header('Content-Type: text/html; charset=utf-8');
   require("../functionfile/config.inc.php");
   include('../header.php');

   //真实分数

	 $query_st="select * from student_answers where question_id='". $_GET['qid']."'";
     $result_st=mysql_query($query_st);
     while($re=mysql_fetch_array($result_st))
	 {
	     $std_score = $re['real_score'];
	 }
	 echo  "<br />分数为:".$std_score;
	 //echo  "qid====".$_GET['qid'];
	 
	
?>