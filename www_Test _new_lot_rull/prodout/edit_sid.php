<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	
	if(isset($_POST['submit'])){
		$query="update OUT_PRODUCT set COPCONFIRMSERIES='".$_POST['oid']."' WHERE   (OPM_ORDER_NO = '".$_GET['oid']."') AND (OPD_LOT_NO = '".$_GET['lid']."')";	
		$result=mssql_query($query);
 		if($result){my_msg("決定書序號修改為:".$_POST['oid']);}
	}
	if(isset($_POST['leave'])){
		close_frame();	
	}
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<font size="+4" color="#990000">編輯決定書序號</font>
<table width="480" border="1"><tr><td>
<form name="form1" method="post" action="">
<font size="6">輸入決定書序號 </font><input type="text" name="oid" size="16" style="font-size:16px">
<input type="submit" name="submit" value="確定" style="font-size:16px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="leave" value="離開" style="font-size:16px">
</form>
</td></tr></table>
