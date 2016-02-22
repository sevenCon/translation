function set_iframe(rm) {
    var get_right_main = document.getElementById("iframe");
    if (rm == "homeworklist") get_right_main.src = "../teacher/HomeworkList.php";
    if (rm == "homeworkadd") get_right_main.src = "../teacher/HomeworkAdd.php";
	if (rm == "exportstuscore") get_right_main.src = "../teacher/exportstuscore.php";
    if (rm == "questionlist") get_right_main.src = "../teacher/QuestionList.php";
    if (rm == "etocadd") get_right_main.src = "../teacher/etocAdd.php";
    if (rm == "ctoeadd") get_right_main.src = "../teacher/ctoeAdd.php";
    if (rm == "sentadd") get_right_main.src = "../teacher/sentAdd.php";
	if (rm == "paraadd") get_right_main.src = "../teacher/paraAdd.php";
	if (rm == "artiadd") get_right_main.src = "../teacher/artiAdd.php";
	if (rm == "synlist") get_right_main.src = "../teacher/SynList.php";
	if (rm == "synadd") get_right_main.src = "../teacher/SynAdd.php";
	if (rm == "neglist") get_right_main.src = "../teacher/NegList.php";
	if (rm == "negadd") get_right_main.src = "../teacher/NegAdd.php";
	if (rm == "wordlistadd") get_right_main.src = "../teacher/WordListAdd.php";
	if (rm == "studentscore") get_right_main.src = "../teacher/StudentScore.php";
	if (rm == "inputstudent") get_right_main.src = "../teacher/InputStudent.php";
	if (rm == "modifyinfo") get_right_main.src = "../teacher/modifyinfo.php";
}
function SetWinHeight(obj) 
{ 
var win=obj;
var mid=document.getElementById("middleTeacher");

if (document.getElementById) 
{ 
if (win && !window.opera) 
{
	
if (win.contentDocument && win.contentDocument.body.offsetHeight)
	if(win.contentDocument.body.offsetHeight>600)
       mid.style.height =win.contentDocument.body.offsetHeight+90+"px";
    else mid.style.height="690px";
	
	
	
else if(win.Document && win.Document.body.scrollHeight)
	if(win.Document.body.scrollHeight>600)
       mid.style.height = win.Document.body.scrollHeight+90+"px";
    else mid.style.height="690px";
} 
}
}