<?php
session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
include "../functionfile/teacher.func.php";
include "../functionfile/config.inc.php";
?>
<?php
$requestType=$_POST['requesttype'];
  switch($requestType){
      case deleteStudentScore:
           $studentID=$_POST['studentid'];
           $questionID=$_POST['questionid'];
          if(t_DeleteStudentScore($studentID,$questionID)){
              echo "2";
          }else{
              echo "3";
          }
          break;
      case updateStudentScore:
          $score=$_POST['Score'];
          $realscore=$_POST['realScore'];
          $studentId=$_POST['studentID'];
          $questionId=$_POST['questionID'];
          if(t_UpdateStudentScore($score,$realscore,$studentId,$questionId)){
              echo "2";
          }else{
              echo "3";
          }
          break;
      case getRequestContent:
          $beginIndex=$_POST['beginindex'];
          $pageSize=$_POST['pagesize'];
          $questionId=$_POST['questionid'];
          $requestContent=t_getScoreList($questionId,$beginIndex,$pageSize);
          $feedbackRequestContent=array();
          $feedbackRequestContent[0]=$requestContent[0];
          for($i=1;$i<=$requestContent[0];$i++){
              $feedbackRequestContent[$i]=array("studentname"=>$requestContent[$i][0],"score"=>$requestContent[$i][1],"realscore"=>$requestContent[$i][2],"studentID"=>$requestContent[$i][3],"questionID"=>$requestContent[$i][4]);
          }
          echo json_encode($feedbackRequestContent);
          break;
      default:
          break;
  }
?>