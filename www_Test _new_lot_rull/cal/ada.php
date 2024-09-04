<?php 
include("../lib/fun.php");
//////////////////////////////////////////////////////////
	$smptime=dod($_POST['datepicker1']).$_POST['order_time'].$_POST['order_time2'];
	$outtime=dod($_POST['datepicker2']).$_POST['deliver_time1'].$_POST['deliver_time2'];
	$ordertime=dod($_POST['datepicker3']).$_POST['order_time3'].$_POST['order_time4'];
	$query="INSERT INTO dbo.AnalyzeDesign
                            (AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, CTD_CUST_NO, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_ANA_ID, AND_VALUE, AND_MEMO, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_MARK, AND_GET_QTY, AND_GET_DATETIME, 
                            AND_RESULT_DATETIME, AND_RESULT, AND_NOTE, AND_PERSON, ALM_IDENTITY51, ALM_IDENTITY52, 
                            ALM_IDENTITY53, ALM_IDENTITY54, ALM_IDENTITY55, ALM_IDENTITY56, ALM_IDENTITY13)
VALUES          ('".date("Ymd")."',".$_POST['class'].",'".$_POST['lot_no']."',".$_POST['pdd_chemical1']."',,".$_POST['before'].",'".$smptime."','".$_SESSION['testitems']."',,,'".$_POST['remark'].",".$_POST['sample_number'].",0,'".$outtime."','".$ordertime."',,,,,,,'".$_SESSION['uid']."',,,,,,,)";	
$sss= $query;
echo $query;

?>
