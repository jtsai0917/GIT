<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
include("../lib/jtsai.php");
if($_GET['aut_no_now']<>'9999'){
$query="UPDATE          SIGN_AGREE_ITEM
SET                   EMP_NO ='".$_SESSION['uid']."', SAI_OK_NG ='".$_GET['sai_ok_ng']."', SAI_TIME ='".date("YmdHi")."', SAI_MEMO ='".$_GET['memo']."'";
$query.=" WHERE          (EMP_NO IS NULL) AND (SAG_NO = '".$_GET['no']."') AND (AUT_NO = '".$_GET['aut_no_now']."')";
}
else
{
	$query="UPDATE          SIGN_AGREE
			SET                   SAG_FINISHED_MEMO ='".$_GET['memo']."', SAG_FINISHED_TIME ='".date("YmdHis")."'
			WHERE          (SAG_NO = ".$_GET['no'].")";
}
$result=mssql_query($query);
jumpto($_SESSION['sag']);
?>