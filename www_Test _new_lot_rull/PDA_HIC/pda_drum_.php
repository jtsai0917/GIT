<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
datepick();
 $dat=getdate(mon)."/".getdate(mday)."/".getdate(year);
if(isset($_POST['next1'])){
	$_SESSION['lot_id']=$_POST['lot_id'];	
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
</head>

<form id="form1" name="form1" method="post" action="pda_drum__.php">
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<BR />
  DRUM 出貨檢查作業.
  <br>
   出荷日期: <input type="text" autocomplete="off" name="toto_no" id="toto_no" style="font-size:25px" value="<?PHP echo date("Ymd") ?>" size="10"/ readonly>
  <br>
出荷決定書NO.: <?PHP echo $_SESSION['nono'];  ?><BR />            

<?php 
if($_SESSION['no1']==''){$sno1='autofocus="autofucos"';}
if($_SESSION['no2']==''){$sno2='autofocus="autofucos"';}	
if($_SESSION['no2']<>'' and $_SESSION['no1']<>''){$sno3='autofocus="autofucos"';}	
	$_SESSION['aa']=$aa=explode(",",$_POST['lot_id']);
	$_SESSION['prod_id']=$aa[1];
	$QTY1=$_SESSION['QTY'];
	echo  "客戶 : (".$_SESSION['cidcname'].')<br>';
	echo  "品名 : (".$_SESSION['pidcname'].')<br>';
	echo "LOT NO: ".$_SESSION['lot_id'].'</br>';
	echo "桶  號: ".'<input type="text"  autocomplete="off" size="12" name="no1" id="no1" '.$sno1.' style="font-size:20px" value="'.$_SESSION['no1'].'"  onchange="set_date_session(this.name,this.value)"/></br>';
	echo "棧板編號: ".'<input type="text"  autocomplete="off" size="9 "name="no2" id="no2" '.$sno2.' style="font-size:20px" value="'.$_SESSION['no2'].'"  onchange="set_date_session(this.name,this.value)"/></br>';
	//echo "客戶條碼1:".'<input type="text" name="no3" id="no3" width="50" height="20" value=""/>'.'</br>';
	//echo "客戶條碼2:".'<input type="text" name="no4" id="no4" width="50" height="20" value=""/>'.'</br>';
	//echo "客戶條碼3:".'<input type="text" name="no5" id="no5" width="50" height="20" value=""/>'.'</br>';
//	echo '</form>';
//	echo '<form id="form2" name="form2" method="post" action="pda_drum__.php">';
	echo '<input type="submit" name="next2" id="next2"  style="font-size:20px" '.$sno3.' value="下一桶/儲存" />';
	echo "      ".'<input type="submit" name="next3" id="next3" style="font-size:20px"  value="結束刷桶" />'.'</br>';
	$query1="select  * from OUT_CHECK_DRUM_DETAIL
			 where OTD_NO='".$_SESSION['nono']."' ";
	$result1 = mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);
	$_SESSION['numrow']=$numRows1;
	echo "共".$QTY1."桶 , 已完成".$numRows1."桶";
		//$_SESSION['QTY']='';
?>
</form>
<a href="pda_drum.php"><strong>上一步</strong></a>&nbsp;&nbsp;&nbsp;<a href="index.php"><strong>取消/離開</strong></a><br>