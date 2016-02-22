$(document).ready(
	function(){
		$("#originalPwd").blur(
			function(){
				
				if($.trim($("#originalPwd").val())==""){
					setErrorInfor("请输入原始密码");
					$("#originalPwd").val("");
				}
			}	
		);
		$("#newPwd").blur(
			function(){
				clearErrorInfo();
				var newPwd=$("#newPwd").val();
				if($.trim(newPwd)==""){
					setErrorInfo("请输入新密码,密码开头和结尾不得为空格");
					$("#newPwd").val("");
					return;
				}
				if(newPwd.length<6){
					setErrorInfo("密码长度至少为6位");
					$("#newPwd").val("");
				}
			}
		);
		$("#repeatPwd").blur(
			function(){
				clearErrorInfo();
				var newPwd=$("#newPwd").val();
				if($.trim(newPwd)==""){
					setErrorInfo("请输入新密码");
					$("#newPwd").val("");
					return;
				}
				var repeatPwd=$("#repeatPwd").val();
				if($.trim(newPwd)!=$.trim(repeatPwd)){
					setErrorInfo("密码不匹配");
				}
			}
		);
		$("#reset").click(
			function(){
				resetData();
			}
		);
	}	
);
//设置错误提示信息
function setErrorInfo(errorInfo){
	document.getElementById("errorInfo").innerHTML=errorInfo;
}
//清空错误提示信息
function clearErrorInfo(){
	document.getElementById("errorInfo").innerHTML="";
}
//重置数据
function resetData(){
	$("#originalPwd").val("");
	$("#newPwd").val("");
	$("#repeatPwd").val("");
	clearErrorInfo();
}
//提交更新密码
function submit(teacherId){
	var originalPwd=$("#originalPwd").val();
	var newPwd=$("#newPwd").val();
	var repeatPwd=$("#repeatPwd").val();
	if($.trim(originalPwd)==""){
		setErrorInfo("请输入原始密码");
		return;
	}
	if($.trim(newPwd)==""){
		setErrorInfo("请输入新密码,密码开头和结尾不得为空格");
		return;
	}
	if(newPwd.length<6){
		setErrorInfo("密码长度至少为6位");
		return;
	}
	if($.trim(newPwd)!=$.trim(repeatPwd)){
		setErrorInfo("密码不匹配");
		return;
	}
	$.post(
		'changeteapwdprocess.php',
		{
			teacherid : teacherId,
			originalpwd : $.trim(originalPwd),
			newpwd : $.trim(newPwd)
		},
		function(data,textStatus){
			var feedback=data.split(">");
			var length=feedback.length;
			//alert($.trim(feedback[length-1]));
			if($.trim(feedback[length-1])=="true"){
				alert("修改密码成功");
				resetData();
			}else{
				alert("修改密码失败");
			}
		}
	);
}