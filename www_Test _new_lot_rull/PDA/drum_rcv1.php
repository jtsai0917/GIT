<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
if($_SESSION['uid']==''){jumpto("login.php");}
if(!isset($_SESSION['font_size'])){$_SESSION['font_size']='20';}
$_SESSION['lid']='';
datepick();
echo 'DRUM 回收作業 <BR>';
echo '
<form id="form1" name="form1" method="post" action="">
輸入要回收的桶號 :  <input type="text" name="rdrum_id" value="'.trim($_SESSION['rdrum_id']).'" onchange="set_date_session(this.name,this.value)" size="8"/><br><br>';

if ($_POST['rdrum_id']<>''){
	echo 'DRUM 桶 ['.$_POST['rdrum_id'].'] 使用紀錄 :<BR>';
	$query = "SELECT EL_WASH_DRUM.FDM_LOT_NO, OUT_PRODUCT.OPD_QTY_DRUM FROM EL_WASH_DRUM INNER JOIN OUT_PRODUCT ON EL_WASH_DRUM.FDM_LOT_NO = OUT_PRODUCT.OPD_LOT_NO WHERE (EWD_DRUM = '".$_POST['rdrum_id']."') AND (DISABLE <> 1) ";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		echo $row[0].' ['.$row[1].']桶<BR>';
	}
}

echo '<input type="submit" name="invalid" value=" 作廢 " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="rnt" value=" 回收 " >
</form>';

?>