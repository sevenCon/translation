
//删除同义词组
function deleteSyn(wordId){
    var notice=confirm("您是否真的要删除该词组?");
    if(notice==true){
        $.post(
            'ManageWordLibProcess.php',
            {
                requesttype : 'deleteSynWordList',
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
function closeEditWordWindow(editId,synWord1,synWord2,synWord3,synWord4,synWord5){
    var jqueryEditId="#edit"+editId;
    $(jqueryEditId).css("display","none");
   
    $("#floatLayout").css("display","none");
}
//显示相应设置试卷的对话框
function showEditWordWindow(editId){
	//alert(editId);
    $("#floatLayout").css("display","block");
    var jqueryEditId="#edit"+editId;
    $(jqueryEditId).css("display","block");
}
//更新同义词题库
function updateSynWord(editId,wordId,synWord1,synWord2,synWord3,synWord4,synWord5){
    var jqueryEditId="#edit"+editId;
    var synword_1= $(jqueryEditId).find("#synWord1").val();
    var synword_2= $(jqueryEditId).find("#synWord2").val();
    var synword_3= $(jqueryEditId).find("#synWord3").val();
    var synword_4= $(jqueryEditId).find("#synWord4").val();
    var synword_5= $(jqueryEditId).find("#synWord5").val();
	var word_id= $(jqueryEditId).find("#wordid").val();
    $(jqueryEditId).find("#errorInfo").text("updating ... wait a minute");
    $.post(
        'ManageWordLibProcess.php',
        {
            requesttype : "updateSynWordList",
            wordid : word_id,
            word1 : synword_1,
            word2 : synword_2,
            word3 : synword_3,
            word4 : synword_4,
            word5 : synword_5
        },
        function(data,textStatus){
            var feedback=data.split(">");
            var length=feedback.length;
			//alert(feedback[length-1]);
            if(feedback[length-1]==2){
                location.reload();
            }else{
                closeEditWordWindow(editId,synword_1,synword_2,synword_3,synword_4,synword_5);
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
	getRequestContent(currentPage-1,totalPageNumber,pageSize);
}
//取得下一页
function nextPage(currentPage,totalPageNumber,pageSize){
	if(currentPage==totalPageNumber){
		alert("当前已经是最后一页了");
		return;
	}
	getRequestContent(parseInt(currentPage)+1,totalPageNumber,pageSize);
}
//取得首页
function firstPage(currentPage,totalPageNumber,pageSize){
	if(currentPage<=1){
		return;
	}
	getRequestContent(1,totalPageNumber,pageSize);
}
//取得尾页
function lastPage(currentPage,totalPageNumber,pageSize){
	if(currentPage==totalPageNumber){
		return;
	}
	getRequestContent(totalPageNumber,totalPageNumber,pageSize);
}
//显示词组
function setEditWordContent(editId,wordId,word1,word2,word3,word4,word5){
	var jqueryBoxId="#edit"+editId;
	$(jqueryBoxId).find("#synWord1").val(word1);
	$(jqueryBoxId).find("#synWord2").val(word2);
	$(jqueryBoxId).find("#synWord3").val(word3);
	$(jqueryBoxId).find("#synWord4").val(word4);
	$(jqueryBoxId).find("#synWord5").val(word5);
	$(jqueryBoxId).find("#wordid").val(wordId);
} 
//清空所有答案框里的信息
function clearAnswerBoxContent(totalBoxNumber){
	for(var i=1;i<=totalBoxNumber;i++){
		var jqueryBoxId="#edit"+i;
	//	$(jqueryBoxId).find("#wordid").text("");
		$(jqueryBoxId).find("#synWord1").val("");
		$(jqueryBoxId).find("#synWord2").val("");
		$(jqueryBoxId).find("#synWord3").val("");
		$(jqueryBoxId).find("#synWord4").val("");
		$(jqueryBoxId).find("#synWord5").val("");
	}
}
//获取页面需求信息
function getRequestContent(requestPage,totalPageNumber,pageSize){
	var beginIndex=(requestPage-1)*pageSize;//页面数据的开始项
	//向服务器获取需要的信息
	$.post(
		'ManageWordLibProcess.php',
		{
			requesttype : "getRequestContent",//请求类型为获取要显示的数据信息
			pagesize : pageSize,//传入页面大小
			beginindex : beginIndex//传入数据的开始项
		},
		function(data,textStatus){//处理传过来的数据对页面进行更新
			var feedback=data.split(">");
			var length=feedback.length;
			var feedbackContent=eval("("+$.trim(feedback[length-1])+")");
			var contentNumber=feedbackContent[0];
			clearAnswerBoxContent(pageSize);
			var newHtml="<table class='classes' width='800px' border='0' table-layout: fixed>";
			newHtml+="<tr><th width='10%'>题目ID</th><th width='70%' colspan='5'>同义词</th>";
			newHtml+="<th width='20%'>操作</th></tr>";
			for(var i=1;i<=contentNumber;i++){
				newHtml+="<tr style='height:30px'>";
				newHtml+="<td name='wordid' style='text-align:center'><p>"+feedbackContent[i].wordId+"</p></td>";		
				if(feedbackContent[i].word1==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='synWord1'>"+feedbackContent[i].word1+"</td>";
				}
				if(feedbackContent[i].word2==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='synWord2'>"+feedbackContent[i].word2+"</td>";
				}
				if(feedbackContent[i].word3==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='synWord3'>"+feedbackContent[i].word3+"</td>";
				}
				if(feedbackContent[i].word4==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='synWord4'>"+feedbackContent[i].word4+"</td>";
				}
				if(feedbackContent[i].word5==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='synWord5'>"+feedbackContent[i].word5+"</td>";
				}

				newHtml+="<td><a href='javascript:void(0)' onclick=\"showEditWordWindow("+i+")\">编辑</a>";
				newHtml+="<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteSyn("+feedbackContent[i].wordId+")\">删除</a>";
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
			document.getElementById("SynListTable").innerHTML=newHtml;
		}
	);
}