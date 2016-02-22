<?php
session_start();
header("Content-type:text/html;charset=UTF-8;");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>句子翻译·汉译越</title>
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body style="background-image:none;">
<div class="panelHeader">
    <h2>句子翻译·汉译越</h2>
</div>
<div>
    <?php

    require "../functionfile/config.inc.php";

    $branch = 1; //   用于题号
    echo "<div class='questionLeftTop'></div>"; //增加观看做过的题目
    echo  "<div class='questionRightTop'></div>";
    echo  "<div class='questionBottom'><h1>已做的题目
<input type='button' value='导出已完成题目Excel' class='btn btn-success' onclick=\"location.href="."'outputExcel.php?flag=hanyue'"."\"/>
<br /></h1>";
    echo  "<div class='questionInner'>";
    $query_st = "select *  from questions_hanyue as Q, new_ctf_answer as S where Q.question_hanyue_id = S.question_id and S.answer_stu_id  ='".$_SESSION['stuID']."';";		 //quote?  there changed!!
//    echo  "seee".$query_st;
    $result_st = mysql_query($query_st);
//    echo  "1:".$re[0];exit;

    while ($re = mysql_fetch_array($result_st))
    {
        echo "<a class='btn btn-success' href =showsentencedone.php?flag=hanyue&id=".$re[question_id].">" . $re[question_id] . "</A>" ;	//title-id
        $cnt++;

        $branch++;
    }
    echo  "</div>";
    echo  "</div>";
    echo  "<br /><br />" ;
    ///////////////////////
    $branch = 1;
    echo "<div class='questionLeftTop'></div>";
    echo  "<div class='questionRightTop'>

    </div>";

    echo  "<div class='questionBottom'><h1>可做的题目<br /></h1>";
    echo  "<div class='questionInner'>";
    $query = "select distinct question_hanyue_id  from questions_hanyue where question_hanyue_id not in( select question_id  from  new_ctf_answer where answer_stu_id = '" . $_SESSION['stuID'] . "');";	  //修改了关于做题的定义? title-id
    $result = mysql_query($query);
    //echo  "$result::".$result;
    $cnt = 0;
    while ($re = mysql_fetch_array($result))
    {
        $_SESSION['str' . $cnt] = $re[0];
        //echo "<h2><a href ='showsentence.php?title=".$cnt."'>" . $branch . "、" . $re[0] . "</A><br /></h2>" ;
        echo "<a class='btn btn-success' href ='showsentence_hanyue.php?id=".$cnt."'>"  . $re[0] . "</A>" ;
        $cnt++;

        $branch++;
    }

    echo  "</div>";
    echo  "</div>";
    echo  "<div class='pageBottom'></div>";

    ?>
</body>
</html>