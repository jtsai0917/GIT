<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if(isset($_POST['end'])){
	jumpto("index.php");
}	
if($_GET['lot']==''){
	$_SESSION['lot']=$_POST['lot'];
}
else{
	$_SESSION['lot']=$_POST['lot']=$_GET['lot'];	
}
$_SESSION['dno']=$_POST['dno'];
if($_SESSION['font_size']==''){$_SESSION['font_size']=20;}

if(isset($_POST['next1']))
{
	$_SESSION['makeno']=trim($_POST['makeno']);
}
if(trim($_POST['dno'])<>'' and trim($_POST['lot'])<>'')
	{
		$str1=' autofocus="autofocus" ';
		$str2='';
		$str3='';
	}
	elseif(trim($_POST['dno'])=='' and $_POST['lot']<>'')
	{
		$str1='';
		$str3='';
		$str2=' autofocus="autofocus" ';
	}
	else {
		$str1='';
		$str3=' autofocus="autofocus" ';
		$str2='';
	}
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>無標題文件</title>
<style type="text/css">
body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<form id="form0" name="form0" method="post">
<input type="submit" name="_fill" style="width:120px;height:30px;border:2px #666666 double; background-color:#FF0; font-size:18px" value="充填" >
&nbsp;&nbsp;
<input type="submit" name="_wash" style="width:120px;height:30px;border:2px #666666 double; background-color:#CCC; font-size:18px" value="洗淨"><BR>
</form>
 20L PE 充填作業.
  <br>
   日期: <?PHP echo date("Y-m-d"); ?>
  <br>
<?php	
	$_SESSION['sttime']=date("YmdHis");
	echo "客戶名:".$_SESSION['cus1'].'<br />';
	echo "充填量:基準值20.0~20.2kg"."<br />";
	echo '<form id="form1" name="form1" method="post" action="pda_20L_FILL__.php">';
	echo "Lot NO:".$_POST['lot']."<BR>";
	echo "充填來源 Lot NO:". '<input type="text" name="sourcelotno" id="sourcelotno" size="14" style="font-size:20px" value="'.$_SESSION['sourcelotno'].'" /><BR>';
	echo 'DRUM NO <input type="text" style="font-size:20px" autofocus="autofocus" name="dno" size="10" >';	
	echo '<input type="text" name="lot" hidden="hidden" value="'.$_POST['lot'].'">';
	echo '<input type="submit" style="font-size:20px" name="fill" id="fill" value="充填" /><br>';	

if(isset($_POST['_fill'])){
	jumpto("pda_20L_FILL.php");
}
if(isset($_POST['_wash'])){
	jumpto("pda_20L_WASH.php");
}
?>
