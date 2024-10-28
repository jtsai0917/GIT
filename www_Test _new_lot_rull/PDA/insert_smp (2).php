<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
if(substr($_GET['smp_id'],0,1)=='T'){$met='PFA';}else{$met='PE';}
$query="INSERT INTO [dbo].[Sample] ([SMP_ID], [SMP_MAT], [SMP_MID_ID], [SMP_START], [SMP_TIMES], [SMP_SERVICE], [SMP_LOT], [SMP_DRUMNO], [SMP_USER], [SMP_OUT], [SMP_SMP], [SMP_ANA], [SMP_BACK], [SMP_SAVE], [SMP_SAVE_TIME], [SMP_FINISH], [SMP_JUNK], [SMP_JUNK_D]) VALUES (N'".$_GET['smp_id']."', N'".$met."', N'".$_SESSION['s2']."', N'".date("Ymd")."', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)"; 
		$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  }	

$query="INSERT INTO dbo.Sample_All
                            (SMA_SERVICE, SMA_ID, SMA_TIMES, SMA_LOT, SMA_USER, SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
                            SMA_SAVE, SMA_SAVE_TIME, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
                            SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
                            SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
							VALUES          (0,'".$_GET['smp_id']."',1,'".$_SESSION['lot_no']."',
							'".$_SESSION['s1']."',NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."',
							NULL,NULL,NULL,'".$_GET['sn']."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
$result = mssql_query($query);
unset($_SESSION['sma_id']);
jumpto($_SESSION['smp_rcv']);
?>