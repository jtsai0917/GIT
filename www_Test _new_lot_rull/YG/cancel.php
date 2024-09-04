
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
include("../connections/conn.php");
include("../lib/fun.php");
datepick();
session_start();
$id=$_GET['carid'];
if(isset($_POST['quit'])){
	echo '<script type="text/javascript">window.close();</script>';
}

if(isset($_POST['submit'])){
	$query="UPDATE YG_CAR_OUT SET CANCEL = N'".date("YmdHis")."' WHERE ([index] = ".$id.")";
	$_SESSION['oppd']=$query;
	$result=mssql_query($query);
	echo '<script type="text/javascript">window.close();</script>';
}


echo ' 取消作業 <BR>';
		echo '<form name="form1" method="post" action=""><input type="submit" name="submit" value=" 取消作業 " autofocus >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="quit" value=" 離 開 " autofocus ></form>';
?>
