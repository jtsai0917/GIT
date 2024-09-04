<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form name="form1" method="post" action="fix_stock.php">
<font size="25" color="#663300">修改庫存 :<BR>欲變更的 Lot NO: 
<label for="lid"></label>
<input type="text" name="lid" id="lid" size="16" style="font-size:18px" ><BR>
數量 
<label for="qty"></label>
<input type="text" name="qty" id="qty" style="font-size:18px" size="8">
<input type="submit" name="enter" id="enter" value="送出" style="font-size:18px" >
</font>
</form>

<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
if(isset($_POST['enter'])){
	$_POST['lid']=trim($_POST['lid']);
	$_POST['qty']=trim($_POST['qty']);
	$query	="select count(*) as ist from  PRODUCT_STOCKS where STK_LOT_NO='".$_POST['lid']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$cnt=$row[0];
	if($cnt>0){
		$query="UPDATE  PRODUCT_STOCKS SET STK_QTY = ".$_POST['qty']." WHERE   (STK_LOT_NO = '".$_POST['lid']."') ";
		echo $query;
		$result=mssql_query($query);
 		my_msg("更改 ".$_POST['lid']." 庫存為 : ".$_POST['qty']);
	}
	else{
		my_msg("查無此LOT庫存紀錄");
	}
}
?>

