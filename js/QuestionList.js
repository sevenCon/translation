//显示相应设置试卷的对话框
function showEditDataWindow(editId){
    $("#floatLayout").css("display","block");
    var jqueryEditId="#edit"+editId;
    $(jqueryEditId).css("display","block");
}
 //显示相应重新评分的对话框
function showReScoreDataWindow(editId){	  
    $("#floatLayout").css("display","block");
    var jqueryEditId="#edit"+editId;
    $(jqueryEditId).css("display","block");
	$(jqueryEditId).find("#mark").attr("readonly","readonly");
}
//关闭相应的设置试卷框
function closeEditDataWindow(editId,timu,daan,fenshu){	
	//alert(editId);
    var jqueryEditId="#edit"+editId;
    $(jqueryEditId).css("display","none");
   
    $("#floatLayout").css("display","none");
	$(jqueryEditId).find("#mark").removeAttr("readonly");
}

//更新题库	句子翻译
function updateData(editId,wordId,timu,daan,mark){	  
    var jqueryEditId="#edit"+editId;
    var data_1= $(jqueryEditId).find("#timu").val();
    var data_2= $(jqueryEditId).find("#daan").val();
    var data_3= $(jqueryEditId).find("#mark").val();
	var word_id=$(jqueryEditId).find("#wordid1").val();
	var flag=$(jqueryEditId).find("#flag_hanyue").val();
	if(flag=='hanyue'){
		reqFile = 'reScore.php'
	}else{
		reqFile = 'reScore_hanyue.php'
	}
	if($(jqueryEditId).find('#mark').attr("readonly")==true){
		$.post(
			reqFile,
			{
			    //requesttype: "reScore",
					wordid1 : word_id,
					daan1 : data_2
		    },
			function(data,textStatus){
				var feedback=data.split(">");
				var length=feedback.length;
				//alert(feedback[length-1]);
				if(feedback[length-1]==2){
					location.reload();
				}else{
					closeEditDataWindow(editId,data_1,data_2,data_3);
					alert("更新失败");
                }
            }
		);
	}else{
    $(jqueryEditId).find("#errorInfo").text("updating ... wait a minute");
    $.post(
        'ManageWordLibProcess.php',
        {
            requesttype : "updateQuestionList",
            wordid : word_id,
            word1 : data_1,
            word2 : data_2,
            word3 : data_3
        },
        function(data,textStatus){
            var feedback=data.split(">");
            var length=feedback.length;
			//alert(feedback[length-1]);
            if(feedback[length-1]==2){
                location.reload();
            }else{
                closeEditDataWindow(editId,data_1,data_2,data_3);
                alert("更新失败");
            }
        }
    );
	}
}

//更新题库	英译汉
function updateDataEtoc(editId,wordId,timu,daan1,daan2,daan3,mark,getName){
    var jqueryEditId="#edit"+editId;
    var data_1= $(jqueryEditId).find("#timu").val();
    var data_2= $(jqueryEditId).find("#daan1").val();
	var data_3= $(jqueryEditId).find("#daan2").val();
	var data_4= $(jqueryEditId).find("#daan3").val();
    var data_5= $(jqueryEditId).find("#mark").val();
	var WordId= $(jqueryEditId).find("#wordid").val();
    $(jqueryEditId).find("#errorInfo").text("updating ... wait a minute");
    $.post(
        'ManageWordLibProcess.php',
        {
            requesttype : "updateQuestionListEtoc",
            wordid : WordId,
            word1 : data_1,
            word2 : data_2,
            word3 : data_3,
            word4 : data_4,
            word5 : data_5,
			getname : getName
        },
        function(data,textStatus){
            var feedback=data.split(">");
            var length=feedback.length;
			//alert(feedback[length-1]);
            if(feedback[length-1]==2){
                location.reload();
            }else{
                closeEditDataWindow(editId,data_1,data_2,data_5);	//data_3/4==null?
                alert("更新失败");
            }
        }
    );

}

//删除
function deleteData(wordId,getName){	 //style
    var notice=confirm("您是否真的要删除该词组?");
    if(notice==true){
        $.post(
            'ManageWordLibProcess.php',
            {
                requesttype : 'deleteQuestionList',
			    getname : getName,
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

 //取得上一页
function prePage(currentPage,totalPageNumber,pageSize,getname){
	if(currentPage<=1){
		alert("当前已经是第一页");
		return;
	}
	if(getname==3 || getname==6){	//句子翻译
	   getRequestContentStc(currentPage-1,totalPageNumber,pageSize,getname);
	}else if(getname==1){ //英译汉
		getRequestContentEtc(currentPage-1,totalPageNumber,pageSize,getname);
	}else if(getname==2){ //汉译英
		getRequestContentEtc(currentPage-1,totalPageNumber,pageSize,getname);//共用
	}else if(getname==4){
	}else{
	}
	
}
//取得下一页
function nextPage(currentPage,totalPageNumber,pageSize,getname){
	if(currentPage==totalPageNumber){
		alert("当前已经是最后一页了");
		return;
	}
	
	//alert("getname==3!!huanyedeshihou");
	if(getname==3 || getname==6){	//句子翻译
	   getRequestContentStc(parseInt(currentPage)+1,totalPageNumber,pageSize,getname);
	  //alert(getname);	 // 运行了！
	}else if(getname==1){ //英译汉
		getRequestContentEtc(parseInt(currentPage)+1,totalPageNumber,pageSize,getname);
	}else if(getname==2){ //汉译英
		getRequestContentEtc(parseInt(currentPage)+1,totalPageNumber,pageSize,getname);
	}else if(getname==4){
	}else{
	}
	
}
//取得首页
function firstPage(currentPage,totalPageNumber,pageSize,getname){
	if(currentPage<=1){
		return;
	}
	if(getname==3 || getname==6){	//句子翻译
	   getRequestContentStc(1,totalPageNumber,pageSize,getname);
	}else if(getname==1){  //英译汉
		getRequestContentEtc(1,totalPageNumber,pageSize,getname);
	}else if(getname==2){  //汉译英
		getRequestContentEtc(1,totalPageNumber,pageSize,getname);
	}else if(getname==4){
	}else{
	}
	
}
//取得尾页
function lastPage(currentPage,totalPageNumber,pageSize,getname){
	if(currentPage==totalPageNumber){
		return;
	}
	if(getname==3 || getname==6){	//句子翻译
	   getRequestContentStc(totalPageNumber,totalPageNumber,pageSize,getname);
	}else if(getname==1){ //英译汉
	   getRequestContentEtc(totalPageNumber,totalPageNumber,pageSize,getname);
	}else if(getname==2){  //汉译英
		getRequestContentEtc(totalPageNumber,totalPageNumber,pageSize,getname);
	}else if(getname==4){
	}
	
}

//显示句子翻译	  
function setEditDataContent(editId,wordId,word1,word2,word3){
	var jqueryBoxId="#edit"+editId
	$(jqueryBoxId).find("#wordid1").val(wordId);
	$(jqueryBoxId).find("#timu").val(word1);
	$(jqueryBoxId).find("#daan").val(word2);
	$(jqueryBoxId).find("#mark").val(word3);
}
//显示英译汉

function setEditDataContentEtoc(editId,wordId,word1,word2,word3,word4,word5){
		var jqueryBoxId="#edit"+editId;
		$(jqueryBoxId).find("#timu").val(word1);
		$(jqueryBoxId).find("#wordid").val(wordId);
		$(jqueryBoxId).find("#daan1").val(word2);
		$(jqueryBoxId).find("#daan2").val(word3);
		$(jqueryBoxId).find("#daan3").val(word4);
		$(jqueryBoxId).find("#mark").val(word5);
}
//清空所有答案框里的信息	 
function clearAnswerBoxContent(totalBoxNumber,getName){
	for(var i=1;i<=totalBoxNumber;i++){
		var jqueryBoxId="#edit"+i;
		if(getName==3 || getName==6){
	   //	$(jqueryBoxId).find("#wordid").text("");
	     	$(jqueryBoxId).find("#timu").val("");
		    $(jqueryBoxId).find("#daan").val("");
		    $(jqueryBoxId).find("#mark").val("");
		}else if(getName==1){
			$(jqueryBoxId).find("#q_sentence").val("");
		    $(jqueryBoxId).find("#q_answer").val("");
		    $(jqueryBoxId).find("#q_score").val("");
		}else if(getName==2){
			$(jqueryBoxId).find("#q_sentence").val("");
		    $(jqueryBoxId).find("#q_answer").val("");
		    $(jqueryBoxId).find("#q_score").val("");
		}else if(getName==4){
		}else{
		}
	}
}
//获取页面需求信息
function getRequestContentStc(requestPage,totalPageNumber,pageSize,getName){   //sentence 根据getname 两处要改！！！getname
	var beginIndex=(requestPage-1)*pageSize;//页面数据的开始项
	if(getName==6){
		var postType = "getRequestContentStc6";
	}else{
		var postType = "getRequestContentStc";
	}
	$.post(
		'ManageWordLibProcess.php',
		{
			requesttype : postType,//请求类型为获取要显示的数据信息
			pagesize : pageSize,//传入页面大小
			beginindex : beginIndex//传入数据的开始项
		},
		function(data,textStatus){//处理传过来的数据对页面进行更新
			var feedback=data.split(">");
			var length=feedback.length;
			var feedbackContent=eval("("+$.trim(feedback[length-1])+")");
			var contentNumber=feedbackContent[0];
			clearAnswerBoxContent(pageSize,getName);
			var newHtml="<table  style='margin-top:20px;width:800px;table-layout:fixed;' border='0' class='classes'>";
			newHtml+="<tr><th width='35%'>题目</th><th width='40%'>答案</th>";
			newHtml+="<th width='5%'>分值</th><th width='20%'>操作</th></tr>";
			for(var i=1;i<=contentNumber;i++){
				newHtml+="<tr style='height:30px'>";
				//newHtml+="<td name='wordid' style='text-align:center'>"+feedbackContent[i].wordId+"</td>";		
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

				newHtml+="<td><a href='javascript:void(0)' onclick=\"showEditDataWindow("+i+")\">编辑</a>";
				newHtml+="<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteData("+feedbackContent[i].wordId+","+getName+")\">删除</a>";
				newHtml+="<a href='javascript:void(0)' onclick=\"showReScoreDataWindow("+i+")\">重新评分</a>";
				newHtml+="</td></tr>";
				setEditDataContent(i,feedbackContent[i].wordId,feedbackContent[i].word1,feedbackContent[i].word2,feedbackContent[i].word3);				
				//++markEditId;			
			}
			for(var i=contentNumber+1;i<=pageSize;i++){
				newHtml+="<tr style='height:30px'>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				//newHtml+="<td>&nbsp;</td>";
				//newHtml+="<td>&nbsp;</td>";
				//newHtml+="<td>&nbsp;</td>";
				newHtml+="</tr>";
			}
			newHtml+="<tr><td colspan='7'>";
			newHtml+="<p style='margin-left: 0px;'>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:60px;float:left;margin-top:3px' onclick=\"firstPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"',"+getName+")\">首页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"prePage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"',"+getName+")\">上一页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"nextPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"',"+getName+")\">下一页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"lastPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"',"+getName+")\">尾页</a>"; //getname去单引号
			newHtml+="<font style='margin-left:50px' >共 "+totalPageNumber+" 页</font>";
			newHtml+="<font style='margin-left:50px'>当前第 "+requestPage+" 页</font>";
			newHtml+="</p></td></tr></table>";
			document.getElementById("ShowTable").innerHTML=newHtml;
		}
	);
}

function getRequestContentEtc(requestPage,totalPageNumber,pageSize,getName){// getname  etoc与ctoe共用
	var beginIndex=(requestPage-1)*pageSize;//页面数据的开始项
	$.post(
		'ManageWordLibProcess.php',
		{
			requesttype : "getRequestContentEtc",//请求类型为获取要显示的数据信息
			pagesize : pageSize,//传入页面大小
			getname : getName,
			beginindex : beginIndex//传入数据的开始项
		},
		function(data,textStatus){//处理传过来的数据对页面进行更新
			var feedback=data.split(">");
			var length=feedback.length;
			var feedbackContent=eval("("+$.trim(feedback[length-1])+")");
			var contentNumber=feedbackContent[0];
			clearAnswerBoxContent(pageSize,getName);
			var newHtml="<table width='800px' border='0' table-layout: fixed;  class='classes'>";
			newHtml+="<tr><th width='35%'>题目</th><th width='40%'>答案</th>";
			newHtml+="<th width='5%'>分值</th><th width='20%'>操作</th></tr>";
			for(var i=1;i<=contentNumber;i++){
				newHtml+="<tr style='height:30px'>";
				//newHtml+="<td name='wordid' style='text-align:center'>"+feedbackContent[i].wordId+"</td>";		
				if(feedbackContent[i].word1==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='timu'><p>"+feedbackContent[i].word1+"</p></td>";
				}
				if(feedbackContent[i].word2==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='daan1'>"+feedbackContent[i].word2+"&nbsp"+feedbackContent[i].word3+"&nbsp"+feedbackContent[i].word4+"&nbsp</td>";
				}
				if(feedbackContent[i].word5==null){
					newHtml+="<td style='text-align:center'>&nbsp;</td>";
				}else{
					newHtml+="<td style='text-align:center' name='mark'>"+feedbackContent[i].word5+"</td>";
				}

				newHtml+="<td><a href='javascript:void(0)' onclick=\"showEditDataWindow("+i+")\">编辑</a>";
				newHtml+="<a href='javascript:void(0)' style='margin-left:3px' onclick=\"deleteData("+feedbackContent[i].wordId+","+getName+")\">删除</a>";
				newHtml+="</td></tr>";
				setEditDataContentEtoc(i,feedbackContent[i].wordId,feedbackContent[i].word1,feedbackContent[i].word2,feedbackContent[i].word3,feedbackContent[i].word4,feedbackContent[i].word5);				
				//++markEditId;			
			}
			for(var i=contentNumber+1;i<=pageSize;i++){
				newHtml+="<tr style='height:30px'>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="<td>&nbsp;</td>";
				newHtml+="</tr>";
			}
			newHtml+="<tr><td colspan='7'>";
			newHtml+="<p style='margin-left: 0px;'>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:60px;float:left;margin-top:3px' onclick=\"firstPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"',"+getName+")\">首页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"prePage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"',"+getName+")\">上一页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"nextPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"',"+getName+")\">下一页</a>";
			newHtml+="<a href='javascript:void(0)' style='margin-left:20px;float:left;margin-top:3px' onclick=\"lastPage('"+requestPage+"','"+totalPageNumber+"','"+pageSize+"',"+getName+")\">尾页</a>"; //getname去单引号
			newHtml+="<font style='margin-left:50px' >共 "+totalPageNumber+" 页</font>";
			newHtml+="<font style='margin-left:50px'>当前第 "+requestPage+" 页</font>";
			newHtml+="</p></td></tr></table>";
			document.getElementById("ShowTable").innerHTML=newHtml;
		}
	);
}