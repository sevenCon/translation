$(document).ready(
	function(){
		//确定按钮单击事件，先检测是否输入信息，再进行数据写入
		$("#submit").click(
			function(){
			   // alert( 'dfsa');
				document.getElementById("fdback").innerHTML="";
				$("#submit").attr("disabled",true);
				//alert("comeon");
				if($.trim($("#word1").val())==""&&$.trim($("#wordtype1").val())!=""||$.trim($("#word1").val())!=""&&$.trim($("#wordtype1").val())==""){
					document.getElementById("fdback").innerHTML="您还没有输入词语1或没有选择词类型";
					$("#submit").removeAttr("disabled");
				}else if($.trim($("#word2").val())==""&&$.trim($("#wordtype2").val())!=""||$.trim($("#word2").val())!=""&&$.trim($("#wordtype2").val())==""){
					document.getElementById("fdback").innerHTML="您还没有输入词语2或没有选择词类型";
					$("#submit").removeAttr("disabled");
				}else if($.trim($("#word3").val())==""&&$.trim($("#wordtype3").val())!=""||$.trim($("#word3").val())!=""&&$.trim($("#wordtype3").val())==""){
					document.getElementById("fdback").innerHTML="您还没有输入词语3或没有选择词类型";
					$("#submit").removeAttr("disabled");
				}else if($.trim($("#word4").val())==""&&$.trim($("#wordtype4").val())!=""||$.trim($("#word4").val())!=""&&$.trim($("#wordtype4").val())==""){
					document.getElementById("fdback").innerHTML="您还没有输入词语4或没有选择词类型";
					$("#submit").removeAttr("disabled");
				}else if($.trim($("#word5").val())==""&&$.trim($("#wordtype5").val())!=""||$.trim($("#word5").val())!=""&&$.trim($("#wordtype5").val())==""){
					document.getElementById("fdback").innerHTML="您还没有输入词语5或没有选择词类型";
					$("#submit").removeAttr("disabled");
				}else{
					var Word1=$("#word1").val();
					var Word2=$("#word2").val();
					var Word3=$("#word3").val();
					var Word4=$("#word4").val();
					var Word5=$("#word5").val();
					var wordType1=$("#wordtype1").val();
					var wordType2=$("#wordtype2").val();
					var wordType3=$("#wordtype3").val();
					var wordType4=$("#wordtype4").val();
					var wordType5=$("#wordtype5").val();
					$.post(
							"additem.php",
							{
								action : "addWord",
								word1 : Word1,
								wordtype1 :wordType1,
								word2 : Word2,
								wordtype2 :wordType2,
								word3 : Word3,
								wordtype3 :wordType3,
								word4 : Word4,
								wordtype4 :wordType4,
								word5 : Word5,
								wordtype5 :wordType5
							},
							function(data,textStatus){
								var feedback=data;
								var iserror=feedback.split(">");
								var length=iserror.length;
								if(iserror[length-1]==2){
									document.getElementById("fdback").innerHTML="增加成功,点击重置继续增加";
									$("#submit").removeAttr("disabled");
									return;
								}
								if(iserror[length-1]==1){
									document.getElementById("fdback").innerHTML="增加失败";
									$("#submit").removeAttr("disabled");
									return;
								}
								
							}
					);
				}
			}
		);
		//重置按钮事件
		$("#reset").click(
			function(){
				 $("#word1").val(""),
			     $("#wordtype1").val(""),
				 $("#word2").val(""),
			     $("#wordtype2").val(""),
				 $("#word3").val(""),
			     $("#wordtype3").val(""),
				 $("#word4").val(""),
				 $("#wordtype4").val(""),
				 $("#word5").val(""),
				 $("#wordtype5").val(""),
				document.getElementById("fdback").innerHTML="";
			}
		);
	}
);