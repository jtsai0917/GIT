<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form id="form1" name="form1" method="post" action="">
  <input type="submit" name="sub" id="return" value="送出" />
</form>

<?php
$loginFormAction = $_SERVER['PHP_SELF'];
	session_start();
	include("fun.php");
	include("jtsai.php");
	include("../connections/conn.php");
	$rull=new autoani;
	$rull->cnt=0;
	$rull->lid=$_GET['lot_no'];
	$rull->cid=$_GET['cid'];
	$rull->pid=$_GET['pid'];
	$rull->outdate=$_GET['out_date'];
	$rull->get_rull();
	$rull->status();
	$rull->items();
	
	echo "items : ".$rull->item;
	echo "</br>";
	
	if($_GET['item_style']=='1'){$test_items=$_SESSION['Reg'];}
	if($_GET['item_style']=='2'){$test_items=$_SESSION['Full'];}
	elseif($_GET['item_style']==0){$test_items=$_GET['testitems'];}
	echo "TESTITEM :".$test_items;
	echo "</br>";
	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_GET['lot_no']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows<1){
	$query="INSERT INTO dbo.AnalyzeDesign
                            (AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_PERSON, AND_SELECT_TYPE, AND_ITEM_ORI)
	  VALUES          ('','','',0,'".'0'."','','','".date("Ymd")."',1,'".$_GET['lot_no']."','".$_GET['pid']."','N','".$_GET['datepicker1']."','".
	 $test_items."','',".$_GET['smp_cnt'].",0,'','".$_GET['out_date']."','".$_SESSION['uid']."','".$_GET['item_style']."','".$_SESSION['item_ori']."')";
	}
	else{$query="UPDATE        dbo.AnalyzeDesign
	SET                  	 AND_ITEM_ORI='".$_SESSION['item_ori']."', AND_SELECT_TYPE = '".$_GET['item_style']."', AND_ITEM = '".$test_items."', AND_SMP_DATETIME = '".$_GET['datepicker1']."', AND_OUT_DATETIME = '".$_GET['out_date']."', AND_PERSON = '".$_SESSION['uid']."'
	WHERE         (AND_LOT_NO = '".$_GET['lot_no']."')";}
	$result = mssql_query($query);
	  	jumpto($_SESSION['edit_fill_out']);
if(isset($_POST['sub']))
{
		jumpto($_SESSION['edit_fill_out']);
}
?>
