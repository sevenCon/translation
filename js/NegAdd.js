$(document).ready(
	function(){
		$("#reset").click(
			function(){
				$("#submit").removeAttr("disabled");
			//	$("#SynWord").val("");
				$("#synNegWord1").val("");
				$("#synNegWord2").val("");
				$("#synNegWord3").val("");
				$("#synNegWord4").val("");
				$("#synNegWord5").val("");
				document.getElementById("fdback").innerHTML="";
			}	
		);
		$("#submit").click(
			function(){
				document.getElementById("fdback").innerHTML="";
				$("#submit").attr("disabled",true);
				var synNegWord1=$("#synNegWord1").val();
				var synNegWord2=$("#synNegWord2").val();
				var synNegWord3=$("#synNegWord3").val();
				var synNegWord4=$("#synNegWord4").val();
				var synNegWord5=$("#synNegWord5").val();
				 
				if($.trim(synNegWord1)==""&&$.trim(synNegWord2)==""&&$.trim(synNegWord3)==""&&$.trim(synNegWord4)==""&&$.trim(synNegWord5)==""){
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
				if($.trim(synNegWord1)!=""){
					word1=synNegWord1;
				}
				if($.trim(synNegWord2)!=""){
				    word2=synNegWord2;
				}
			    if($.trim(synNegWord3)!=""){
					word3=synNegWord3;
				}
				if($.trim(synNegWord4)!=""){
					word4=synNegWord4;
				}
				if($.trim(synNegWord5)!=""){
					word5=synNegWord5;
				}
				
				$.post(
					"additem.php",
					{
						action :"synNegAdd",
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