<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form action="" method="post">
	<input type="submit" name="submit" value="確定簽核"
</form>
<?php
	session_start();
	echo "Lot NO: ".$_POST['lid']."<BR>";
	include("../../connections/conn.php");
	$query="INSERT INTO Signatory
                            (LotNo, sign_datetime, Signatory_ID, Signatory_Name, active_flag, cancel,LV)
VALUES          (N'".$_POST['lid']."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102), N'".$_SESSION['uid']."', N'".$_SESSION['uname']."', 1, 0,1)";
	$result=mssql_query($query);
	echo '<script>window.close();</script>';		
?>