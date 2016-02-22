var empty=1;
var empty1=1;
function onPropChanged(event)
{
	if(document.getElementById("userid").value=="")
	{
		document.getElementById("log_lab1").style.color="rgb(204,204,204)";
                empty=1;
	}

	else
	{
		document.getElementById("log_lab1").style.color="white";
		empty=0;
	}
}
function OnInput(event)
{
	if(document.getElementById("userid").value=="")
	{
		document.getElementById("log_lab1").style.color="rgb(204,204,204)";
		empty=1;
	}

	else
	{
		document.getElementById("log_lab1").style.color="white";
		empty=0;
	}
}
function onPropChanged1(event)
{
	if(document.getElementById("password").value=="")
	{
		document.getElementById("log_lab2").style.color="rgb(204,204,204)";
                empty1=1;
	}

	else
	{
		document.getElementById("log_lab2").style.color="white";
		empty1=0;
	}
}
function OnInput1(event)
{
	if(document.getElementById("password").value=="")
	{
		document.getElementById("log_lab2").style.color="rgb(204,204,204)";
		empty1=1;
	}

	else
	{
		document.getElementById("log_lab2").style.color="white";
		empty1=0;
	}
}
$(document).ready(function () {
	
    $("#userid").focus(function () {
    	if(empty==1)
        $("#log_lab1").css("color", "rgb(204,204,204)"); 
    });       
    $("#userid").blur(function () {
    	if(empty==1)
        $("#log_lab1").css("color", "#8B9096"); 
    });     
    $("#password").focus(function () {
    	if(empty==1)
        $("#log_lab2").css("color", "rgb(204,204,204)");     
    });
    $("#password").blur(function () {
    	if(empty==1)
        $("#log_lab2").css("color", "#8B9096");     
    });
    $("#submit1").mouseover(function () {
    	
        $("#submit1").css("background-color", "rgb(139, 158, 216)");     
    });
    $("#submit1").mouseout(function () {
    	
        $("#submit1").css("background-color", "rgb(77,101,175)");     
    });
    $("#submit2").mouseover(function () {
    	
        $("#submit2").css("background-color", "rgb(213, 241, 139)");     
    });
    $("#submit2").mouseout(function () {
    	
        $("#submit2").css("background-color", "rgb(189,226,94)");     
    });
});