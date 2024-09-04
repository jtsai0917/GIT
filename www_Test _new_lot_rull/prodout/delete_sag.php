<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");

$query="select * from OUT_DECISION where (SAG_NO='".$_GET['no']."') and OPM_STOCK='TAINAN'";
$result = mssql_query($query);
$numrows=mssql_num_rows($result);
$query="DELETE from OUT_DECISION where (SAG_NO='".$_GET['no']."') and OPM_STOCK='TAINAN'";
$result = mssql_query($query);
if($numrows!=''){
$query="DELETE from OUT_PRODUCT where OPM_ORDER_NO='".$_GET['order_no']."'";
$query1="DELETE from OUT_PLAN where OPM_ORDER_NO='".$_GET['order_no']."'";
$query1="DELETE from OUT_PLAN_FROM_FILE where OPM_ORDER_NO='".$_GET['order_no']."'";
}
$result = mssql_query($query);
$result1 = mssql_query($query1);
$query="DELETE FROM SIGN_AGREE_ITEM where (SAG_NO='".$_GET['no']."')";
$result = mssql_query($query);
$query="DELETE FROM SIGN_AGREE where (SAG_NO='".$_GET['no']."')";
$result = mssql_query($query);
$query="update OUT_PLAN set SAG_NO='' where (SAG_NO='".$_GET['no']."')";
$result = mssql_query($query);

//search outplan

$query="SELECT  SAG_NO FROM OUT_PLAN WHERE (OPM_ORDER_NO = '".$_GET['order_no']."') ";
$result = mssql_query($query);
$row=mssql_fetch_row($result);
$sag_outplan=$row[0];


$query="UPDATE  SIGN_AGREE SET SAG_COMFIRM_COUNT = 0, SAG_FINISHED_TIME = NULL WHERE   (SAG_NO = ".$sag_outplan.") ";
$result=mssql_query($query);

$query="update OUT_PLAN set SAG_NO='' where (SAG_NO='".$_GET['no']."')";
$result = mssql_query($query);


$query="Delete  From  PRODUCT_RUNNING_ACCOUNT Where OPM_ORDER_NO = '".$_GET['order_no']."' And PRA_PURPOSE = -1";
$result = mssql_query($query);

jumpto($_SESSION['lasturl']);
?>