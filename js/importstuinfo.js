$(document).ready(
	function(){
		$("#class").change(
			function(){
				var classId=$("#class").val();
				$("#recordClassID").val(classId);
			}	
		);
	}	
);