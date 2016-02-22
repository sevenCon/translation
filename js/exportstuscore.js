$(document).ready(
	function(){
		//控制导出按钮的样式
		$(".button_style").mouseover(
				function(){
	    			$(".button_style").css("background-color","#6666FF");
					$(".button_style").css("font-size","17px");
	    		}
			);
	    $(".button_style").mouseout(
			function(){
	  	  		$(".button_style").css("background-color","#071A94");
				$(".button_style").css("font-size","15px");
	  		}
		);
	}	
);
//选择本页所有试卷
function selectAll(){
	if($("#selectAll").attr("checked")){
		$("[name='selectPaper']").attr( "checked", "true");
	}else{
		$("[name='selectPaper']").removeAttr("checked");
	}
}
//导出学生成绩
function exportStuScore(){
	var selectedClass=$("#class").val();
	var selectedPapers=$("#hideSelectedPapers").val();
	//alert(selectedPapers);
	if(selectedClass==""){
		return;
	}
	if($.trim(selectedPapers)==""){
		alert("您还未选择任何题目");
		return;
	}
	$.post(
		'exportstuscoreprocess.php',
		{
			requesttype : "exportData",
			classid : selectedClass,
		},
		function(data,textStatus){
			var feedback=data.split(">");
			var length=feedback.length;
			var information=$.trim(feedback[length-1]);
			if(information==2){
				alert("导出失败");
			}else{
				clearData();
				var filePath="../exportdatatmp/"+information;//获取文件路径
				window.location.href=filePath;
			}
		}
	);
}
//清空页面数据
function clearData(){
	$("#hideSelectedPapers").val("");
	$("[name='selectPaper']").removeAttr("checked");
	$("#selectAll").removeAttr("checked");
}
//第一页
function firstPage(currentPage,pageSize,totalPage){
	if(currentPage<=1){
		return;
	}
	getRequestPageContent(1,totalPage,pageSize);
}
//最后一页
function lastPage(currentPage,pageSize,totalPage){
	if(currentPage>=totalPage){
		return;
	}
	getRequestPageContent(totalPage,totalPage,pageSize);
}
//上一页
function prePage(currentPage,pageSize,totalPage){
	if(currentPage<=1){
		alert("已经为第一页");
		return;
	}
	getRequestPageContent(currentPage-1,totalPage,pageSize);
}
//下一页
function nextPage(currentPage,pageSize,totalPage){
	if(currentPage>=totalPage){
		alert("已经是最后一页");
		return;
	}
	getRequestPageContent(currentPage+1,totalPage,pageSize);
}
//获取页面内容
function getRequestPageContent(requestPage,totalPage,pageSize){
	var beginIndex=(requestPage-1)*pageSize+1;
	$.post(
		'exportstuscoreprocess.php',
		{
			requesttype : "getPageContent",
			requestpage : requestPage,
			beginindex : beginIndex,
			pagesize : pageSize,
			totalpage : totalPage
		},
		function(data,textStatus){
			var feedback=data.split(">");
			var length=feedback.length;
			var requestPageContent=eval("("+$.trim(feedback[length-1])+")");
			var dataNumber=requestPageContent[0].requestPaperNumber;
			var newDataTable="<table id='exportPapers' border=0  style='width:60%' class='classes'>";
			newDataTable+="<tr>";
			newDataTable+="<th width='10%'>题目号</th>";
			newDataTable+="<th width='65%'>题目名</th>";
			newDataTable+="<th width='15%'>创建时间</th>";
			newDataTable+="<th width='10%'><input type='checkbox' id='selectAll' onclick='selectAll()'/>&nbsp;全选</th>";
			newDataTable+="</tr>";
			var selectedPapersString=$("#hideSelectedPapers").val();
			var selectedPapersArray=selectedPapersString.split(",");//获取各个页面选到的试卷
			for(var i=1;i<=dataNumber;i++){
				newDataTable+="<tr>";
				newDataTable+="<td><p>"+requestPageContent[i].paperId+"</p></td>";
				newDataTable+="<td><textarea name='timu' id='timu' style='width:100%;height:70px'>"+requestPageContent[i].paperName+"</textarea></td>";
				newDataTable+="<td>"+requestPageContent[i].createTime+"</td>";
				if(binarySearchId(selectedPapersArray,requestPageContent[i].paperId,0,selectedPapersArray.length-1)){
					newDataTable+="<td><input type='checkbox' id='selectPaper' checked='checked' name='selectPaper' value='"+requestPageContent[i].paperId+"' /></td>";
				}else{
					newDataTable+="<td><input type='checkbox' id='selectPaper' name='selectPaper' value='"+requestPageContent[i].paperId+"' /></td>";
				}
				newDataTable+="</tr>";
			}
			for(var i=dataNumber+1;i<=pageSize;i++){
				newDataTable+="<tr>";
				newDataTable+="<td>&nbsp;</td>";
				newDataTable+="<td>&nbsp;</td>";
				newDataTable+="<td>&nbsp;</td>";
				newDataTable+="<td>&nbsp;</td>";
				newDataTable+="</tr>";
			}
			newDataTable+="<tr>";
			newDataTable+="<td colspan='4'>";
			newDataTable+="<a href='javascript:saveSelectedPapers("+requestPage+")'>保存本页选择</a>";
			newDataTable+="<a class='marginLeftStyle' href='javascript:firstPage("+requestPage+","+pageSize+","+totalPage+")'>首页</a>";
			newDataTable+="<a class='marginLeftStyle' href='javascript:prePage("+requestPage+","+pageSize+","+totalPage+")'>上一页</a>";
			newDataTable+="<a class='marginLeftStyle' href='javascript:nextPage("+requestPage+","+pageSize+","+totalPage+")'>下一页</a>";
			newDataTable+="<a class='marginLeftStyle' href='javascript:lastPage("+requestPage+","+pageSize+","+totalPage+")'>尾页</a>";
			newDataTable+="<label class='marginLeftStyle' style='margin-top:0px'>转至第<input type='text' id='moveTo' style='width:30px' onblur='moveToPage("+totalPage+","+pageSize+")'/>页</label>";
			newDataTable+="<label class='marginLeftStyle'>当前第<span style='color:red'>"+requestPage+"</span>页</label>";
			newDataTable+="<label class='marginLeftStyle'>共<span style='color:red'>"+totalPage+"</span>页</label>";
			newDataTable+="</td>";
			newDataTable+="</tr>";
			newDataTable+="</table>";
			document.getElementById("papers").innerHTML=newDataTable;
		}
	);
}
//保存页面选择的试卷号
function saveSelectedPapers(currentPage){
	//先获取试卷选择框并获得选到的试卷号数组
	var selectStatus=document.getElementsByName("selectPaper");
	var selectedPapersArray=new Array();
	var j=0;
	for(var i=0;i<selectStatus.length;i++){
		if(selectStatus[i].checked){
			selectedPapersArray[j]=selectStatus[i].value;
			++j;
		}
	}
	var selectedPapers=selectedPapersArray.toString();
	//alert(selectedPapers);
	$.post(
		'exportstuscoreprocess.php',
		{
			requesttype : 'saveSelectedPapers',
			currentpage : currentPage,
			selectedpapers : selectedPapers
		},
		function(data,textStatus){
			var feedback=data.split(">");
			var length=feedback.length;
			//alert($.trim(feedback[length-1]));
			if($.trim(feedback[length-1])=="false"){
				alert("您未选择题目");
			}else{
				$("#hideSelectedPapers").val($.trim(feedback[length-1]));
			}
		}
	);
}
//跳转页面
function moveToPage(totalPage,pageSize){
	var requestPage=$("#moveTo").val();
	if(requestPage==""){
		return;
	}
	if(requestPage.search("^-?\\d+$") != 0){
		alert("请输入页面范围内的整数");
		$("#moveTo").val("");
		return;
	}
	if(parseInt(requestPage)<1||parseInt(requestPage)>totalPage){
		alert("超过页面的有效范围");
		$("#moveTo").val("");
		return;
	}
	getRequestPageContent(parseInt(requestPage),totalPage,pageSize);
}
//进行二分查找以确认试卷是否被选中，选中则返回true
//selectedPapers:选择到的试卷号集，paperID:要查找的试卷id，beginIndex:查看的初始位置，endIndex:查看的结尾位置
function binarySearchId(selectedPapers,paperID,beginIndex,endIndex){
	if(beginIndex>endIndex){
		return false;
	}
	var mid=Math.floor((endIndex+beginIndex)/2);
	if(parseInt(selectedPapers[mid]) ==parseInt(paperID)){
		return true;
	}
	if(parseInt(selectedPapers[mid])>parseInt(paperID)){
		return binarySearchId(selectedPapers,paperID,beginIndex,mid-1);
	}else{
		return binarySearchId(selectedPapers,paperID,mid+1,endIndex);
	}
}