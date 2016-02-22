<?php
    session_start();
	if(!isset($_SESSION['teacherID'])){
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='../index.php'";
		echo "</script>";
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>增加分词</title>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/addWord.js"></script>
</head>
<body>
<center>
<div id="Main">
<div id="SynNegWord" style="background-color:#337AB7;border-radius: 4px">
	<p style="color:#fff;font-size:30px">增加词库分词</p>
	</div>
	<hr>
	<div id="AddWord">
		<table>
			<tr height="30px">
				<td style="text-align:right">
					<font>词库分词1:</font>
				</td>
				<td>
				    <input type="text" id="word1">
				</td>
				<td>
					<select id="wordtype1"  style="text-align:center;width:90px">
						<option value="" selected="selected">请选择...</option>
						<option value="noun">名词</option>
						<option value="verb">动词</option>
						<option value="adj">形容词</option>
						<option value="adv">副词</option>
						<option value="conj">连词</option>
						<option value="prep">介词</option>
						<option value="pron">代词</option>
						<option value="num">数词</option>
						<option value="quan">量词</option>
						<option value="idom">成语</option>
					</select>
				</td>
			</tr>
			<tr height="30px">
				<td style="text-align:right">
					<font>词库分词2:</font>
				</td>
				<td>
				    <input type="text" id="word2">
				</td>
				<td>
					<select id="wordtype2"  style="text-align:center;width:90px">
						<option value="" selected="selected">请选择...</option>
						<option value="noun">名词</option>
						<option value="verb">动词</option>
						<option value="adj">形容词</option>
						<option value="adv">副词</option>
						<option value="conj">连词</option>
						<option value="prep">介词</option>
						<option value="pron">代词</option>
						<option value="num">数词</option>
						<option value="quan">量词</option>
						<option value="idom">成语</option>
					</select>
				</td>
			</tr>
			<tr height="30px">
				<td style="text-align:right">
					<font>词库分词3:</font>
				</td>
				<td>
				    <input type="text" id="word3">
				</td>
				<td>
					<select id="wordtype3"  style="text-align:center;width:90px">
						<option value="" selected="selected">请选择...</option>
						<option value="noun">名词</option>
						<option value="verb">动词</option>
						<option value="adj">形容词</option>
						<option value="adv">副词</option>
						<option value="conj">连词</option>
						<option value="prep">介词</option>
						<option value="pron">代词</option>
						<option value="num">数词</option>
						<option value="quan">量词</option>
						<option value="idom">成语</option>
					</select>
				</td>
			</tr>
			<tr height="30px">
				<td style="text-align:right">
					<font>词库分词4:</font>
				</td>
				<td>
				    <input type="text" id="word4">
				</td>
				<td>
					<select id="wordtype4"  style="text-align:center;width:90px">
						<option value="" selected="selected">请选择...</option>
						<option value="noun">名词</option>
						<option value="verb">动词</option>
						<option value="adj">形容词</option>
						<option value="adv">副词</option>
						<option value="conj">连词</option>
						<option value="prep">介词</option>
						<option value="pron">代词</option>
						<option value="num">数词</option>
						<option value="quan">量词</option>
						<option value="idom">成语</option>
					</select>
				</td>
			</tr>
			<tr height="30px">
				<td style="text-align:right">
					<font>词库分词5:</font>
				</td>
				<td>
				    <input type="text" id="word5">
				</td>
				<td>
					<select id="wordtype5"  style="text-align:center;width:90px">
						<option value="" selected="selected">请选择...</option>
						<option value="noun">名词</option>
						<option value="verb">动词</option>
						<option value="adj">形容词</option>
						<option value="adv">副词</option>
						<option value="conj">连词</option>
						<option value="prep">介词</option>
						<option value="pron">代词</option>
						<option value="num">数词</option>
						<option value="quan">量词</option>
						<option value="idom">成语</option>
					</select>
				</td>
			</tr>

		</table>
	</div>
	<div id="showfeedback" style="text-align:center">
		<font style="font-size:12px" color="#FF0000" id="fdback"></font>
	</div>
	<div  id="Buttons">
		<p>
			<button id="submit" name="submit">确定</button>
			<button id="reset" name="reset">重置</button>
		</p>
	</div>
</div>

</center>
</body>
</html>