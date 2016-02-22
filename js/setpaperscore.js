$(document).ready(
	function(){
	$("#submitCondition").click(
			function(){
				var homeworkList=$("#homeworkList").find("option:selected").text();
				if(homeworkList==""){
					alert("您还没选择任何题目");
					return;
				}
				var paperId=$("#homeworkList").attr("value");
				$("#recordPaperId").val(paperId);
				$.post(
					'setAdd2.php',
					{
						requesttype : 'getRequestContent',
						paperid : paperId
					}
						);	});
		
}  );
