
//删除同义词组
function deleteStu(studentId,questionId){
    var notice=confirm("您是否真的要删除该学生成绩?");
    if(notice==true){
        $.post(
            'ManageStudentScoreProcess.php',
            {
                requesttype : 'deleteStudentScore',
                studentid : studentId,
                questionid : questionId
            },
            function(data,textStatus){
                var feedback=data.split(">");
                var length=feedback.length;
                if(feedback[length-1]==2){
                    location.reload();
                }else{
                    alert("删除失败");
                }
            }
        );
    }else{
        return;
    }
}


//显示词组
function setEditWordContent(editId,studentname,score,realscore){
    var jqueryBoxId="#edit"+editId;
    $(jqueryBoxId).find("#studentName").val(studentname);
    $(jqueryBoxId).find("#Score").val(score);
    $(jqueryBoxId).find("#realScore").val(realscore);
}

//关闭相应的设置试卷框
function closeEditWordWindow(editId,stuName,score,realScore){
    var jqueryEditId="#edit"+editId;
    $(jqueryEditId).css("display","none");
   
    $("#floatLayout").css("display","none");
}
//显示相应设置试卷的对话框
function showEditWordWindow(editId){
    $("#floatLayout").css("display","block");
    var jqueryEditId="#edit"+editId;
    $(jqueryEditId).css("display","block");
}
//更新学生成绩
function updateSynWord(editId,studentName,Score,realScore,studentId,questionId){
    var jqueryEditId="#edit"+editId;
    var studentname= $(jqueryEditId).find("#studentName").val();
    var score= $(jqueryEditId).find("#Score").val();
    var realscore= $(jqueryEditId).find("#realScore").val();
    $(jqueryEditId).find("#errorInfo").text("updating ... wait a minute");
    $.post(
        'ManageStudentScoreProcess.php',
        {
            requesttype : "updateStudentScore",
            studentName : studentname,
            Score : score,
            realScore : realscore,
            studentID : studentId,
            questionID : questionId

        },
        function(data,textStatus){
            var feedback=data.split(">");
            var length=feedback.length;
			//alert(feedback[length-1]);
            if(feedback[length-1]==2){
                location.reload();
            }else{
                closeEditWordWindow(editId,studentname,score,realscore);
                alert("更新失败");
            }
        }
    );

}

 //取得上一页
function prePage(currentPage,totalPageNumber,pageSize,questionId){
	if(currentPage<=1){
		alert("当前已经是第一页");
		return;
	}
	getRequestContent(currentPage-1,totalPageNumber,pageSize,questionId);
}
//取得下一页
function nextPage(currentPage,totalPageNumber,pageSize,questionId){
	if(currentPage==totalPageNumber){
		alert("当前已经是最后一页了");
		return;
	}
	getRequestContent(parseInt(currentPage)+1,totalPageNumber,pageSize,questionId);
}
//取得首页
function firstPage(currentPage,totalPageNumber,pageSize,questionId){
	if(currentPage<=1){
		return;
	}
	getRequestContent(1,totalPageNumber,pageSize,questionId);
}
//取得尾页
function lastPage(currentPage,totalPageNumber,pageSize,questionId){
	if(currentPage==totalPageNumber){
		return;
	}
	getRequestContent(totalPageNumber,totalPageNumber,pageSize,questionId);
}
//获取页面需求信息
function getRequestContent(requestPage,totalPageNumber,pageSize,questionId){
	var beginIndex=(requestPage-1)*pageSize;//页面数据的开始项
	//向服务器获取需要的信息
	$.post(
		'ManageStudentScoreProcess.php',
		{
			requesttype : "getRequestContent",//请求类型为获取要显示的数据信息
			pagesize : pageSize,//传入页面大小
			beginindex : beginIndex,//传入数据的开始项
            questionid :questionId  //传入问题ID
		},
		function(data,textStatus){//处理传过来的数据对页面进行更新
			var feedback=data.split(">");
			var length=feedback.length;
			var feedbackContent=eval("("+$.trim(feedback[length-1])+")");
			var contentNumber=feedbackContent[0];
			clearAnswerBoxContent(pageSize);
			var newHtml="<table class='classes' width='800px' border='0' table-layout: fixed>";
			newHtml+="<tr><th width='25%'>姓名</th><th width='20%'>满分为10分的得分</th>";
			newHtml+="<th width='20%'>实际分数</th><th width='35%'>操作</th></tr>";
			for(var i=1;i<=contentNumber;i++){
				newHtml+="<tr style='height:30px'>";
				newHtml+="<td name='studentName' style='text-align:center'><p>"+feedbackContent[i].studentname+"</p></td>";
				if(feedbackContent[i].score==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='Score'>"+feedbackContent[i].score+"</td>";
				}
				if(feedbackContent[i].realscore==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='realScore'>"+feedbackContent[i].realscore+"</td>";
				}

				newHtml+="<td><a href='javascript:void(0)' onclick=\"showEditWordWindow("+i+")\">编辑</a>";
				newHtml+="<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteStu('"+feedbackContent[i].studentID+"','"+feedbackContent[i].questionID+"')\">删除</a>";
				newHtml+="</td></tr>";
				setEditWordContent(i,feedbackContent[i].studentname,feedbackContent[i].score,feedbackContent[i].realscore);
				//++markEditId;			
			}
			for(var i=contentNumber+1;i<=pageSize;i++){
				newHtml+="<tr style='height:30px'>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="</tr>";
			}
			newHtml+="<tr><td colspan='7'>";
			newHtml+="<p style='margin-left: 0px;'>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:60px;float:left;margin-top:3px' onclick=\"firstPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"','"+questionId+"')\">首页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"prePage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"','"+questionId+"')\">上一页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"nextPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"','"+questionId+"')\">下一页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"lastPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"','"+questionId+"')\">尾页</a>";
			newHtml+="<font style='margin-left:50px' >共 "+totalPageNumber+" 页</font>";
			newHtml+="<font style='margin-left:50px'>当前第 "+requestPage+" 页</font>";
			newHtml+="</p></td></tr></table>";
			document.getElementById("SynListTable").innerHTML=newHtml;
		}
	);
}
//清空所有答案框里的信息
function clearAnswerBoxContent(totalBoxNumber){
	for(var i=1;i<=totalBoxNumber;i++){
		var jqueryBoxId="#edit"+i;
	//	$(jqueryBoxId).find("#wordid").text("");
		$(jqueryBoxId).find("#studentName").val("");
		$(jqueryBoxId).find("#Score").val("");
		$(jqueryBoxId).find("#realScore").val("");
	}
}