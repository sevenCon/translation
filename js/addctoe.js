$(document).ready(
	function(){
		$("#reset").click(
			function(){
				$("#submit").removeAttr("disabled");
				$("#hard").val("1");
				$("#unit").val("1");
				$("#ctoeSubject").val("");
				$("#ctoeAnswer1").val("");
				$("#ctoeAnswer2").val("");
				$("#ctoeAnswer3").val("");
				$("#ctoeScore").val("");
				document.getElementById("fdback").innerHTML="";
			}	
		);
		$("#submit").click(
			function(){
				document.getElementById("fdback").innerHTML="";
				$("#submit").attr("disabled",true);
				var ctoeSubject=$("#ctoeSubject").val();
				if($.trim(ctoeSubject)==""){
					document.getElementById("fdback").innerHTML="请输入题目内容";
					$("#submit").removeAttr("disabled");
					return;
				}
				var ctoeAnswer1=$("#ctoeAnswer1").val();
				var ctoeAnswer2=$("#ctoeAnswer2").val();
				var ctoeAnswer3=$("#ctoeAnswer3").val();
				var ctoeScore=$("#ctoeScore").val();
				if($.trim(ctoeAnswer1)==""&&$.trim(ctoeAnswer2)==""&&$.trim(ctoeAnswer3)==""){
					document.getElementById("fdback").innerHTML="请输入至少一个答案";
					$("#submit").removeAttr("disabled");
					return;
				}
				if($.trim(ctoeScore)==""){
					document.getElementById("fdback").innerHTML="请输入分数";
					$("#submit").removeAttr("disabled");
					return;
				}
				document.getElementById("fdback").innerHTML="";
				var Answer1="";
				var Answer2="";
				var Answer3="";
				var CtoeScore="";
				if($.trim(ctoeScore)!="") {
					CtoeScore=ctoeScore;
				}
				if($.trim(ctoeAnswer1)!=""){
					Answer1=ctoeAnswer1;
					if($.trim(ctoeAnswer2)!=""){
						Answer2=ctoeAnswer2;
						if($.trim(ctoeAnswer3)!=""){
							Answer3=ctoeAnswer3;
						}
					}else{
						if($.trim(ctoeAnswer3)!=""){
							Answer2=ctoeAnswer3;
						}
					}
				}else{
					if($.trim(ctoeAnswer2)!=""){
						Answer1=ctoeAnswer2;
						if($.trim(ctoeAnswer3)!=""){
							Answer2=ctoeAnswer3;
						}
					}else{
						Answer1=ctoeAnswer3;
					}
				}
				$.post(
					"additem.php",
					{
						action : "addctoe",
						hard : $("#hard").val(),
						unit : $("#unit").val(),
						ctoeScore   : CtoeScore,
						ctoesubject : ctoeSubject,
						ctoeanswer1 : Answer1,
						ctoeanswer2 : Answer2,
						ctoeanswer3 : Answer3
					},
					function(data,textStatus){
						var feedback=data.split(">");
						var length=feedback.length;
						var fdbErrorInfo=feedback[length-1];
						if(fdbErrorInfo==3){
							document.getElementById("fdback").innerHTML="题目内容超过最大长度5000字";
							$("#submit").removeAttr("disabled");
							return;
						}
						if(fdbErrorInfo==4){
							document.getElementById("fdback").innerHTML="答案超过最大长度，每个答案最长为5000字";
							$("#submit").removeAttr("disabled");
							return;
						}
						if(fdbErrorInfo==1){
							document.getElementById("fdback").innerHTML="增加中译英题失败";
							$("#submit").removeAttr("disabled");
							return;
						}
						if(fdbErrorInfo==2){
							document.getElementById("fdback").innerHTML="增加中译英题成功,点击重置继续增加";
						}
					}
				);
			}	
		);
	}
);