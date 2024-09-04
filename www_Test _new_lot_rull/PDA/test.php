<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
if($_SESSION['uid']==''){jumpto("login.php");}
$_SESSION['lid']='';
datepick();
echo  "報廢回收作業";
echo '<form id="form1" name="form1" method="post" action="">'.'輸入桶號：'.'  <input type="text" name="rdrum_id" value="'.trim($_SESSION['rdrum_id']).'" onchange="set_date_session(this.name,this.value)" size="8"/><br><br></form>';

if ($_POST['rdrum_id']<>''){
	echo 'DRUM  ['.$_POST['rdrum_id'].'] '."使用紀錄".' :<BR>';
	$query = "SELECT EL_WASH_DRUM.FDM_LOT_NO, OUT_PRODUCT.OPD_QTY_DRUM FROM EL_WASH_DRUM INNER JOIN OUT_PRODUCT ON EL_WASH_DRUM.FDM_LOT_NO = OUT_PRODUCT.OPD_LOT_NO WHERE (EWD_DRUM = '".$_POST['rdrum_id']."') AND (DISABLE <> 1) ";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		echo $row[0].' ['.$row[1].'] ';
		echo "桶 <BR>";
	}
}
echo '<input type="submit" name="invalid" value=" '."報廢".' " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="rnt" value=" '."回收".' " >';

if(isset($_POST['invalid'])){
	echo '作廢';
}

if(isset($_POST['rnt'])){
	echo "回收";
}

?> 