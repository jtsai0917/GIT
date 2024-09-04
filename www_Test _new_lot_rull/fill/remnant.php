<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php   ///last edit by jtsai 20161210
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
?>
<form name="form1" action="" method="post" >
充填前殘液量(KG)：
<input type="text" name="remnant" value="" />
<input type="submit" name="submit" value=" 確 定 ">
 &nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="end" value=" 離 開 ">
</form>
<?php
if(isset($_POST['submit'])){
	$query="INSERT INTO LEL_CHANGE_LOG (lid, uid, item, value, datetime) VALUES  (N'".$_GET['lid']."', N'".$_SESSION['uid']."', N'LEL_TYS_REMNANT_QTY', N'".$_POST['remnant']."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102))";
	$result1=mssql_query($query);
	
	$query="UPDATE  LORRY_EXAMINE_LIST SET LEL_TYS_REMNANT_QTY = ".$_POST['remnant'].",LEL_TYS_REMNANT_QTY_MAN='".$_SESSION['uid']."' 
			WHERE LEL_LOT_NO='".$_GET['lid']."'";
	$result=mssql_query($query);
	if($result){
		my_msg("寫入成功!!");
	}
	else
	{
		my_msg("寫入失敗!!");
	}
}

if(isset($_POST['end'])){
	close_frame();
}
?>

