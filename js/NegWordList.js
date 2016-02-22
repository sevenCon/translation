//删除同义否定词组
function deleteNeg(wordId){
    var notice=confirm("您是否真的要删除该词组?");
    if(notice==true){
        $.post(
            'ManageWordLibProcess.php',
            {
                requesttype : 'deleteNegWordList',
                wordid : wordId
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

//关闭相应的设置试卷框
function closeEditWordWindow(editId,negWord1,negWord2,negWord3,negWord4,negWord5){
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
//更新同义词题库
function updateNegWord(editId,wordId,negWord1,negWord2,negWord3,negWord4,negWord5){
    var jqueryEditId="#edit"+editId;
    var negword_1=$(jqueryEditId).find("#negWord1").val();
    var negword_2=$(jqueryEditId).find("#negWord2").val();
    var negword_3=$(jqueryEditId).find("#negWord3").val();
    var negword_4=$(jqueryEditId).find("#negWord4").val();
    var negword_5=$(jqueryEditId).find("#negWord5").val();
	var word_id=$(jqueryEditId).find("#wordid").val();
    $(jqueryEditId).find("#errorInfo").text("updating ... wait a minute");
    $.post(
        'ManageWordLibProcess.php',
        {
            requesttype :"updateNegWordList",
            wordid : word_id,
            word1 : negword_1,
            word2 : negword_2,
            word3 : negword_3,
            word4 : negword_4,
            word5 : negword_5
        },
        function(data,textStatus){
            var feedback=data.split(">");
            var length=feedback.length;
			//alert(feedback[length-1]);
            if(feedback[length-1]==2){
                location.reload();
            }else{
                closeEditWordWindow(editId,negword_1,negword_2,negword_3,negword_4,negword_5);
                alert("更新失败");
            }
        }
    );
}

 //取得上一页
function prePage(currentPage,totalPageNumber,pageSize){
	if(currentPage<=1){
		alert("当前已经是第一页");
		return;
	}
	getNegRequestContent(currentPage-1,totalPageNumber,pageSize);
}
//取得下一页
function nextPage(currentPage,totalPageNumber,pageSize){
	if(currentPage==totalPageNumber){
		alert("当前已经是最后一页了");
		return;
	}
	getNegRequestContent(parseInt(currentPage)+1,totalPageNumber,pageSize);
}
//取得首页
function firstPage(currentPage,totalPageNumber,pageSize){
	if(currentPage<=1){
		return;
	}
	getNegRequestContent(1,totalPageNumber,pageSize);
}
//取得尾页
function lastPage(currentPage,totalPageNumber,pageSize){
	if(currentPage==totalPageNumber){
		return;
	}
	getNegRequestContent(totalPageNumber,totalPageNumber,pageSize);
}
//显示词组
function setEditWordContent(editId,wordId,word1,word2,word3,word4,word5){
	var jqueryBoxId="#edit"+editId;
	$(jqueryBoxId).find("#negWord1").val(word1);
	$(jqueryBoxId).find("#negWord2").val(word2);
	$(jqueryBoxId).find("#negWord3").val(word3);
	$(jqueryBoxId).find("#negWord4").val(word4);
	$(jqueryBoxId).find("#negWord5").val(word5);
	$(jqueryBoxId).find("#wordid").val(wordId);
}
//清空所有答案框里的信息
function clearAnswerBoxContent(totalBoxNumber){
	for(var i=1;i<=totalBoxNumber;i++){
		var jqueryBoxId="#edit"+i;
	//	$(jqueryBoxId).find("#wordid").text("");
		$(jqueryBoxId).find("#negWord1").val("");
		$(jqueryBoxId).find("#negWord2").val("");
		$(jqueryBoxId).find("#negWord3").val("");
		$(jqueryBoxId).find("#negWord4").val("");
		$(jqueryBoxId).find("#negWord5").val("");
	}
}
//获取页面需求信息
function getNegRequestContent(requestPage,totalPageNumber,pageSize){
	var beginIndex=(requestPage-1)*pageSize;//页面数据的开始项
	//alert(beginIndex);
	// var markEditId=1;
	//向服务器获取需要的信息
	$.post(
		'ManageWordLibProcess.php',
		{
			requesttype : "getNegRequestContent",//请求类型为获取要显示的数据信息
			pagesize : pageSize,//传入页面大小
			beginindex : beginIndex//传入数据的开始项
		},
		function(data,textStatus){//处理传过来的数据对页面进行更新
			var feedback=data.split(">");
			var length=feedback.length;
			var feedbackContent=eval("("+$.trim(feedback[length-1])+")");
			var contentNumber=feedbackContent[0];
			clearAnswerBoxContent(pageSize);
			var newHtml="<table class='classes' width='800px' border='0' table-layout: fixed;>";
			newHtml+="<tr><th width='10%'>题目ID</th><th width='70%' colspan='5'>同义词</th>";
			newHtml+="<th width='20%'>操作</th></tr>";
			for(var i=1;i<=contentNumber;i++){
				newHtml+="<tr style='height:30px'>";
				newHtml+="<td name='wordid' style='text-align:center'><p>"+feedbackContent[i].wordId+"</p></td>";		
				if(feedbackContent[i].word1==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='negWord1'>"+feedbackContent[i].word1+"</td>";
				}
				if(feedbackContent[i].word2==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='negWord2'>"+feedbackContent[i].word2+"</td>";
				}
				if(feedbackContent[i].word3==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='negWord3'>"+feedbackContent[i].word3+"</td>";
				}
				if(feedbackContent[i].word4==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='negWord4'>"+feedbackContent[i].word4+"</td>";
				}
				if(feedbackContent[i].word5==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='negWord5'>"+feedbackContent[i].word5+"</td>";
				}

				newHtml+="<td><a href='javascript:void(0)' onclick=\"showEditWordWindow("+i+")\">编辑</a>";
				newHtml+="<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteNeg("+feedbackContent[i].wordId+")\">删除</a>";
				newHtml+="</td></tr>";
				setEditWordContent(i,feedbackContent[i].wordId,feedbackContent[i].word1,feedbackContent[i].word2,feedbackContent[i].word3,feedbackContent[i].word4,feedbackContent[i].word5);				
				//++markEditId;			
			}
			for(var i=contentNumber+1;i<=pageSize;i++){
				newHtml+="<tr style='height:30px'>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="</tr>";
			}
			newHtml+="<tr><td colspan='7'>";
			newHtml+="<p style='margin-left: 0px;'>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:60px;float:left;margin-top:3px' onclick=\"firstPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"')\">首页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"prePage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"')\">上一页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"nextPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"')\">下一页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"lastPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"')\">尾页</a>";
			newHtml+="<font style='margin-left:50px' >共 "+totalPageNumber+" 页</font>";
			newHtml+="<font style='margin-left:50px'>当前第 "+requestPage+" 页</font>";
			newHtml+="</p></td></tr></table>";
			document.getElementById("NegListTable").innerHTML=newHtml;
		}
	);
}