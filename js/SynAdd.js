$(document).ready(
	function(){
		$("#reset").click(
			function(){
				$("#submit").removeAttr("disabled");
			//	$("#SynWord").val("");
				$("#synWord1").val("");
				$("#synWord2").val("");
				$("#synWord3").val("");
				$("#synWord4").val("");
				$("#synWord5").val("");
				document.getElementById("fdback").innerHTML="";
			}	
		);
		$("#submit").click(
			function(){
				document.getElementById("fdback").innerHTML="";
				$("#submit").attr("disabled",true);
				var synWord1=$("#synWord1").val();
				var synWord2=$("#synWord2").val();
				var synWord3=$("#synWord3").val();
				var synWord4=$("#synWord4").val();
				var synWord5=$("#synWord5").val();

				if($.trim(synWord1)==""&&$.trim(synWord2)==""&&$.trim(synWord3)==""&&$.trim(synWord4)==""&&$.trim(synWord5)==""){
					document.getElementById("fdback").innerHTML="您还没有输入单词";
					$("#submit").removeAttr("disabled");
					return;
				}
				
				document.getElementById("fdback").innerHTML="";
				var word1="";
				var word2="";
				var word3="";
				var word4="";
				var word5="";
				if($.trim(synWord1)!=""){
					word1=synWord1;
				}
				if($.trim(synWord2)!=""){
				    word2=synWord2;
				}
			    if($.trim(synWord3)!=""){
					word3=synWord3;
				}
				if($.trim(synWord4)!=""){
					word4=synWord4;
				}
				if($.trim(synWord5)!=""){
					word5=synWord5;
				}
				$.post(
					"additem.php",
					{
						action : "synAdd",
						w1: word1,
						w2: word2,
						w3: word3,
						w4: word4,
						w5: word5
					},
					function(data,textStatus){
						var feedback=data.split(">");
						var length=feedback.length;
						var fdbErrorInfo=feedback[length-1];
						if(fdbErrorInfo==1){
							document.getElementById("fdback").innerHTML="增加问答题失败";
							$("#submit").removeAttr("disabled");
							return;
						}
						if(fdbErrorInfo==2){
							document.getElementById("fdback").innerHTML="增加问答题成功,点击重置继续增加";
						}
					}
				);
			}	
		);
	}	
);