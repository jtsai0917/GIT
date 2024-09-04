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
	$pid=$_GET['pid'];
	$cid=$_GET['cid'];
if(($pid=='P001-200' || $pid=='P002-190' || $pid=='P006-330' || $pid=='P050-330') and ($cid<>'C16043' and $cid<>'C20701' and $cid<>'C20703' and $cid<>'C20704' and $cid<>'C20705' and $cid<>'C20707' and $cid<>'C20708' and $cid<>'C20709' and $cid<>'C20801' and $cid<>'C20901' and $cid<>'C20904' and $cid<>'C20942' and $cid<>'C20955' and $cid<>'C20956'))
	{$dun=1;}else{$dun=0;}
	echo "items : ".$rull->item;
	echo "</br>";
	
	if($_GET['item_style']=='1'){$test_items=$_SESSION['Reg'];}
	if($_GET['item_style']=='2'){$test_items=$_SESSION['Full'];}
	elseif($_GET['item_style']==0){$test_items=$_GET['testitems'];}
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
	 Regular_item($pid,$cid,$test_items)."','',".$_GET['smp_cnt'].",0,'','".$_GET['out_date']."','".$_SESSION['uid']."','".$_GET['item_style']."','".$_SESSION['item_ori']."')";
	 echo $query;
	 echo "<BR>";
	 $result = mssql_query($query);
	 if($dun==1){
		$query="INSERT INTO dbo.AnalyzeDesign
                            (AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, 
                            AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_TOTAL, AND_OUT_QTY, 
                            AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_PERSON, AND_SELECT_TYPE, AND_ITEM_ORI)
	  VALUES          ('','','',0,'".'0'."','','','".date("Ymd")."',1,'".$_GET['lot_no']."_','".$_GET['pid']."','N','".$_GET['datepicker1']."','".
	 get_test_item($_GET['pid'])."','',".$_GET['smp_cnt'].",0,'','".$_GET['out_date']."','".$_SESSION['uid']."','".$_GET['item_style']."','".$_SESSION['item_ori']."')";
	 echo $query;
	 echo "<BR>";
	 $result = mssql_query($query); 
	 }
	}
	else{
		$query="UPDATE        dbo.AnalyzeDesign	SET AND_ITEM_ORI='".$_SESSION['item_ori']."', AND_SELECT_TYPE = '".$_GET['item_style']."', AND_ITEM = '".Regular_item($pid,$cid,$test_items)."', AND_SMP_DATETIME = '".$_GET['datepicker1']."', AND_OUT_DATETIME = '".$_GET['out_date']."', AND_PERSON = '".$_SESSION['uid']."'	WHERE (AND_LOT_NO = '".$_GET['lot_no']."')";
		echo $query;
	 echo "<BR>";
	$result = mssql_query($query);
	if($dun==1){
		$query="UPDATE        dbo.AnalyzeDesign	SET AND_ITEM_ORI='".$_SESSION['item_ori']."', AND_SELECT_TYPE = '".$_GET['item_style']."', AND_ITEM = '".get_test_item($_GET['pid'])."', AND_SMP_DATETIME = '".$_GET['datepicker1']."', AND_OUT_DATETIME = '".$_GET['out_date']."', AND_PERSON = '".$_SESSION['uid']."'	WHERE (AND_LOT_NO = '".$_GET['lot_no']."_')";
		echo $query;
	 echo "<BR>";
	 $result = mssql_query($query); 
	 }
	}
	  	jumpto($_SESSION['edit_fill_out']);
if(isset($_POST['sub']))
{
		jumpto($_SESSION['edit_fill_out']);
}

function get_test_item($pid){
	$query="select Rull_Reg from Analyze_Rulls WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO LIKE '%C00003%')	";
	echo $query;
	 echo "<BR>";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function Regular_item($pid,$cid,$test_items){
	$str='';
	$query="select * from Analyze_Rulls WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO LIKE '%".$cid."%')	";
	echo $query;
	 echo "<BR>";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	if($numrow==0){	
		$query="SELECT AnalyzeItem.ANI_INDEX as id FROM RegularAnalyzeItem INNER JOIN AnalyzeItem ON RegularAnalyzeItem.ANI_ID = AnalyzeItem.ANI_ID WHERE (RegularAnalyzeItem.PROD_NO = '".$pid."')";	
		echo $query;
	 echo "<BR>";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
		   $str.=$row['id'].",";
		}
		return substr($str,0,-1);
	}
	else{
		return $test_items;	
	}
}

?>
