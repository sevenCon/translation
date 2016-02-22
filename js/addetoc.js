$(document).ready(
	function(){
		$("#reset").click(
			function(){
				$("#submit").removeAttr("disabled");
				$("#hard").val("1");
				$("#unit").val("1");
				$("#etocSubject").val("");
				$("#etocAnswer1").val("");
				$("#etocAnswer2").val("");
				$("#etocAnswer3").val("");
				$("#etocScore").val(""); 
				document.getElementById("fdback").innerHTML="";
			}	
		);
		$("#submit").click(
			function(){
				document.getElementById("fdback").innerHTML="";
				$("#submit").attr("disabled",true);
				var etocSubject=$("#etocSubject").val();
				if($.trim(etocSubject)==""){
					document.getElementById("fdback").innerHTML="请输入题目内容";
					$("#submit").removeAttr("disabled");
					return;
				}
				var etocAnswer1=$("#etocAnswer1").val();
				var etocAnswer2=$("#etocAnswer2").val();
				var etocAnswer3=$("#etocAnswer3").val();
				var etocScore=$("#etocScore").val();

				if($.trim(etocAnswer1)==""&&$.trim(etocAnswer2)==""&&$.trim(etocAnswer3)==""){
					document.getElementById("fdback").innerHTML="请输入至少一个答案";
					$("#submit").removeAttr("disabled");
					return;
				}
				if($.trim(etocScore)==""){
					document.getElementById("fdback").innerHTML="请输入分数";
					$("#submit").removeAttr("disabled");
					return;
				}
				document.getElementById("fdback").innerHTML="";
				var Answer1="";
				var Answer2="";
				var Answer3="";
				var EtocScore="";
				if($.trim(etocScore)!="") {
					EtocScore=etocScore;
				}
				if($.trim(etocAnswer1)!=""){
					Answer1=etocAnswer1;
					if($.trim(etocAnswer2)!=""){
						Answer2=etocAnswer2;
						if($.trim(etocAnswer3)!=""){
							Answer3=etocAnswer3;
						}
					}else{
						if($.trim(etocAnswer3)!=""){
							Answer2=etocAnswer3;
						}
					}
				}else{
					if($.trim(etocAnswer2)!=""){
						Answer1=etocAnswer2;
						if($.trim(etocAnswer3)!=""){
							Answer2=etocAnswer3;
						}
					}else{
						Answer1=etocAnswer3;
					}
				}
				$.post(
					"additem.php",
					{
						action : "addetoc",
						hard : $("#hard").val(),
						unit : $("#unit").val(),

						homeworkList : $("#homeworkList").val(),

						etocScore   : EtocScore,
						etocsubject : etocSubject,
						etocanswer1 : Answer1,
						etocanswer2 : Answer2,
						etocanswer3 : Answer3
					    
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
							document.getElementById("fdback").innerHTML="增加英译中题失败";
							$("#submit").removeAttr("disabled");
							return;
						}
						if(fdbErrorInfo==2){
							document.getElementById("fdback").innerHTML="增加英译中题成功,点击 重置继续增加";
						}
					}
				);
			}	
		);
	}
);