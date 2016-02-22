//左边导航条的跳转控制
function set_iframe(rm) {
    var get_right_main = document.getElementById("iframe");
    if (rm == "modifyinfo") get_right_main.src = "../student/modifyinfo.php";
    if (rm == "wordtranslation") get_right_main.src = "../student/wordtranslation.php";
    if (rm == "sentencetranslation") get_right_main.src = "../student/sentencetranslation.php";
	if (rm == "sentencetranslation_hanyue") get_right_main.src = "../student/sentencetranslation_hanyue.php";  //汉译越
    if (rm == "paragraphtranslation") get_right_main.src = "../student/paragraphtranslation.php";
    if (rm == "articletranslation") get_right_main.src = "../student/articletranslation.php";
    if (rm == "article_translation") get_right_main.src = "../student/articletranslation.php";
}
//左边导航条的交互效果控制

window.onload=function(){
    var counter = 0;
    $("#footer-hidden").click(function(){
        if(counter%2==0){
            $("#footer-hidden").css("transform","rotate(90deg)");
            $("#footer").animate({bottom:'-0px'});
            counter++;
        }
        else{
            $("#footer-hidden").css("transform","rotate(-90deg)");
            $("#footer").animate({bottom:'-239px'});
            counter--;
        }
        
    });
        
    
    
	 var chosen=0;  
	 $("#nav1").click(function () {
    	chosen=1;
        $("#nav1").css("background-color", "#458fce"); 
        $("#nav2").css("background-color", "white");       
        $("#nav3").css("background-color", "white");      
        $("#nav4").css("background-color", "white");       
        $("#nav5").css("background-color", "white");
       	
		$("#a1").css("color", "white");
		$("#a1").css("font-weight", "bold");
		$("#a2").css("color", "rgb(97, 105, 101)");
		$("#a2").css("font-weight", "normal");
		$("#a3").css("color", "rgb(97, 105, 101)");
		$("#a3").css("font-weight", "normal");
		$("#a4").css("color", "rgb(97, 105, 101)");
		$("#a4").css("font-weight", "normal");
		$("#a5").css("color", "rgb(97, 105, 101)");
		$("#a5").css("font-weight", "normal");
    });
	$("#nav2").click(function () {
    	chosen=2;
        $("#nav2").css("background-color", "#458fce");
        $("#nav3").css("background-color", "white");
        $("#nav4").css("background-color", "white");
        $("#nav5").css("background-color", "white");
        $("#nav1").css("background-color", "white");

		$("#a2").css("color", "white");
		$("#a2").css("font-weight", "bold");
		$("#a1").css("color", "rgb(97, 105, 101)");
		$("#a1").css("font-weight", "normal");
		$("#a3").css("color", "rgb(97, 105, 101)");
		$("#a3").css("font-weight", "normal");
		$("#a4").css("color", "rgb(97, 105, 101)");
		$("#a4").css("font-weight", "normal");
		$("#a5").css("color", "rgb(97, 105, 101)");
		$("#a5").css("font-weight", "normal");
    });
	$("#nav3").click(function () {
    	chosen=3;
        $("#nav3").css("background-color", "#458fce");
        $("#nav4").css("background-color", "white");
        $("#nav5").css("background-color", "white");
        $("#nav1").css("background-color", "white");
        $("#nav2").css("background-color", "white");

		$("#a3").css("color", "white");
		$("#a3").css("font-weight", "bold");
		$("#a2").css("color", "rgb(97, 105, 101)");
		$("#a2").css("font-weight", "normal");
		$("#a1").css("color", "rgb(97, 105, 101)");
		$("#a1").css("font-weight", "normal");
		$("#a4").css("color", "rgb(97, 105, 101)");
		$("#a4").css("font-weight", "normal");
		$("#a5").css("color", "rgb(97, 105, 101)");
		$("#a5").css("font-weight", "normal");
    });
    $("#nav4").click(function () {
    	chosen=4;
        $("#nav4").css("background-color", "#458fce");
        $("#nav5").css("background-color", "white");
        $("#nav1").css("background-color", "white");
        $("#nav2").css("background-color", "white");
        $("#nav3").css("background-color", "white");
		$("#a4").css("color", "white");
		$("#a4").css("font-weight", "bold");
		$("#a2").css("color", "rgb(97, 105, 101)");
		$("#a2").css("font-weight", "normal");
		$("#a3").css("color", "rgb(97, 105, 101)");
		$("#a3").css("font-weight", "normal");
		$("#a1").css("color", "rgb(97, 105, 101)");
		$("#a1").css("font-weight", "normal");
		$("#a5").css("color", "rgb(97, 105, 101)");
		$("#a5").css("font-weight", "normal");
    });
    $("#nav5").click(function () {
    	chosen=5;
        $("#nav5").css("background-color", "#458fce");
        $("#nav1").css("background-color", "white");
        $("#nav2").css("background-color", "white");
        $("#nav3").css("background-color", "white");
        $("#nav4").css("background-color", "white");

		$("#a5").css("color", "white");
		$("#a5").css("font-weight", "bold");
		$("#a2").css("color", "rgb(97, 105, 101)");
		$("#a2").css("font-weight", "normal");
		$("#a3").css("color", "rgb(97, 105, 101)");
		$("#a3").css("font-weight", "normal");
		$("#a4").css("color", "rgb(97, 105, 101)");
		$("#a4").css("font-weight", "normal");
		$("#a1").css("color", "rgb(97, 105, 101)");
		$("#a1").css("font-weight", "normal");
    });
	    $("#nav1").mouseover(function () {
    	if(chosen!=1){
    	$("#nav1").css("background-color", "silver");
    	}
    });
    $("#nav1").mouseout(function () {
    	if(chosen!=1){
    	$("#nav1").css("background-color", "white");
    	}
    });
    $("#nav2").mouseover(function () {
    	if(chosen!=2){
    	$("#nav2").css("background-color", "silver");
    	}
    });
    $("#nav2").mouseout(function () {
    	if(chosen!=2){
    	$("#nav2").css("background-color", "white");
    	}
    });
    $("#nav3").mouseover(function () {
    	if(chosen!=3){
    	$("#nav3").css("background-color", "silver");
    	}
    });
    $("#nav3").mouseout(function () {
    	if(chosen!=3){
    	$("#nav3").css("background-color", "white");
    	}
    });
    $("#nav4").mouseover(function () {
    	if(chosen!=4){
    	$("#nav4").css("background-color", "silver");
    	}
    });
    $("#nav4").mouseout(function () {
    	if(chosen!=4){
    	$("#nav4").css("background-color", "white");
    	}
    });
    $("#nav5").mouseover(function () {
    	if(chosen!=5){
    	$("#nav5").css("background-color", "silver");
    	}
    });
    $("#nav5").mouseout(function () {
    	if(chosen!=5){
    	$("#nav5").css("background-color", "white");
    	}
    });	
	
	
$(function(){ 


//获取要定位元素距离浏览器顶部的距离 
// var navH = $("#leftPanel").offset().top-68;

//滚动条事件 
$(window).scroll(function(){ 
//获取滚动条的滑动距离


var scroH = $(this).scrollTop(); 
//滚动条的滑动距离大于等于定位元素距离浏览器顶部的距离，就固定，反之就不固定 
if(scroH>=navH){ 
$("#leftPanel").css({"position":"fixed","top":"68px","left":"50%","margin-left":"-515px"}); 
}else if(scroH<navH){ 
$("#leftPanel").css({"position":"absolute","top":"15px","left":"0px","margin-left":"0px"}); 
} 
}) 
}) 
}






