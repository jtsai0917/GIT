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
if($_GET['pcn']=='on'){$pcn=0;}
else{
	$pcn='NULL';
} 
	
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
if($pid=='P001-200' || $pid=='P002-190' || $pid=='P006-330' || $pid=='P050-330' || $pid=='P326-280' || $pid=='P326-020'  || $pid=='UP326-280' || $pid=='UP326-020'  || $pid=='P327-300' || $pid=='P327-020' || $pid=='UP327-300' || $pid=='UP327-020' )
	{$dun=1;}else{$dun=0;}
	echo "items : ".$rull->item;
	echo "</br>";
	echo "dun : ".$dun;
	echo "</br>";
	if($_GET['item_style']=='1'){echo $test_items=$rull->Rull_Reg;}
	if($_GET['item_style']=='2'){echo $test_items=$rull->Rull_Total;}
	if($_GET['item_style']=='0'){echo $test_items=$_GET['testitems'];}
	echo "</br>";
	
				
		$queryd="UPDATE AnalyzeGroup_Lot SET active = 0 WHERE (Lot_No = N'".trim($_GET['lot_no'])."')";
//		echo $queryd."<BR>";
		$resultd=mssql_query($queryd);
		$aa=explode(',',$test_items);
		$queryc="SELECT DISTINCT ANI_GROUPNAME
		FROM              AnalyzeItem
		WHERE          (ANI_INDEX <>'') and ";
		for($i=0;$i<count($aa);$i++){
		if ($i<(count($aa)-1)){
			$queryc.="(ANI_INDEX =".$aa[$i].") OR ";}
		else {$queryc.="(ANI_INDEX =".$aa[$i].")";}	
		}

		$resultc=mssql_query($queryc);
		while($rowc=mssql_fetch_array($resultc)){
			echo $rowc['ANI_GROUPNAME']."<BR>";

			$queryd="INSERT INTO AnalyzeGroup_Lot (Lot_No, Ana_Group_Name, Creator, active) VALUES (N'".trim($_GET['lot_no'])."', N'".$rowc['ANI_GROUPNAME']."', N'".$_SESSION['uid']."', 1)";
			$resultd=mssql_query($queryd);
		}


	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".$_GET['lot_no']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows<1 and $dun==0){
		$query="INSERT INTO dbo.AnalyzeDesign
                            (PCN, sample_metal_f, sample_particle_f, CTD_CUST_NO, AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_PERSON, AND_SELECT_TYPE, AND_ITEM_ORI)
	  VALUES          (".$pcn.",".$_GET['sample_metal_f'].",".$_GET['sample_particle_f'].",'".$cid."','','','',0,'".'0'."','','','".date("Ymd")."',1,'".$_GET['lot_no']."','".$_GET['pid']."','N','".$_GET['datepicker1']."','". Regular_item($pid,$cid,$test_items)."','',".$_GET['smp_cnt'].",0,'','".$_GET['out_date']."','".$_SESSION['uid']."','".$_GET['item_style']."','".$_SESSION['item_ori']."')";
		$result = mssql_query($query);
	}
	elseif($numRows<1 and $dun==1){		 
			$query="INSERT INTO dbo.AnalyzeDesign
								(PCN, sample_metal_f, sample_particle_f, CTD_CUST_NO, AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE,AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_PERSON, AND_SELECT_TYPE, AND_ITEM_ORI)
		  VALUES          (".$pcn.",".$_GET['sample_metal_f'].",".$_GET['sample_particle_f'].",'".$cid."','','','',0,'".'0'."','','','".date("Ymd")."',1,'".$_GET['lot_no']."','".$_GET['pid']."','N','".$_GET['datepicker1']."','". Get_Drum_All($pid,$cid,$test_items)."','',".$_GET['smp_cnt'].",0,'','".$_GET['out_date']."','".$_SESSION['uid']."','".$_GET['item_style']."','".$_SESSION['item_ori']."')";
		 $result = mssql_query($query);	 
			$query="INSERT INTO dbo.AnalyzeDesign
								(PCN, sample_metal_f, sample_particle_f, CTD_CUST_NO,AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE, AND_SMP_DATETIME, AND_ITEM, AND_NOTE, AND_TOTAL, AND_OUT_QTY, AND_OUT_DATETIME, AND_REPORT_DATETIME, AND_PERSON, AND_SELECT_TYPE, AND_ITEM_ORI)
		  VALUES          (".$pcn.", ".$_GET['sample_metal_f'].",".$_GET['sample_particle_f'].",'".$cid."','','','',0,'".'0'."','','','".date("Ymd")."',1,'".$_GET['lot_no']."_','".$_GET['pid']."','N','".$_GET['datepicker1']."','". get_test_item($pid,$cid,$test_items)."','',".$_GET['smp_cnt'].",0,'','".$_GET['out_date']."','".$_SESSION['uid']."','".$_GET['item_style']."','".$_SESSION['item_ori']."')";
		 $result = mssql_query($query); 
	}	
	else
	{
		$query="UPDATE        dbo.AnalyzeDesign	SET AND_GOODS='".$_GET['pid']."', PCN=".$pcn.", AND_TOTAL=".$_GET['smp_cnt'].", sample_metal_f='".$_GET['sample_metal_f']."',sample_particle_f='".$_GET['sample_particle_f']."',AND_ITEM_ORI='".$_SESSION['item_ori']."', AND_SELECT_TYPE = '".$_GET['item_style']."', AND_ITEM = '".Regular_item($pid,$cid,$test_items)."', AND_SMP_DATETIME = '".$_GET['datepicker1']."', AND_OUT_DATETIME = '".$_GET['out_date']."', AND_PERSON = '".$_SESSION['uid']."'	WHERE (AND_LOT_NO = '".$_GET['lot_no']."')";
		$result = mssql_query($query);
	}
	  jumpto($_SESSION['edit_fill_out']);		
	if(isset($_POST['sub']))
	{
		jumpto($_SESSION['edit_fill_out']);
	}

function get_test_item($pid){
	$query="select Rull_Reg from Analyze_Rulls WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO LIKE '%C00003%')	";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function Regular_item($pid,$cid,$test_items){
	$str='';
	if(trim($cid)==''){$cid='C00001';}
	$query="select * from Analyze_Rulls WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO LIKE '%".$cid."%')	";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	if($numrow==0){	
		$query="SELECT AnalyzeItem.ANI_INDEX as id FROM RegularAnalyzeItem INNER JOIN AnalyzeItem ON RegularAnalyzeItem.ANI_ID = AnalyzeItem.ANI_ID WHERE (RegularAnalyzeItem.PROD_NO = '".$pid."')";	
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

function Get_Regular_item($pid,$cid,$test_items){
	if($cid==''){$cid='C00003';}
	$str='';
	$query="select Rull_Reg from Analyze_Rulls WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO LIKE '%".$cid."%')	";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	if($numrow==0){	
		$query="SELECT AnalyzeItem.ANI_INDEX as id FROM RegularAnalyzeItem INNER JOIN AnalyzeItem ON RegularAnalyzeItem.ANI_ID = AnalyzeItem.ANI_ID WHERE (RegularAnalyzeItem.PROD_NO = '".$pid."')";	
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
		   $str.=$row['id'].",";
		}
		return substr($str,0,-1);
	}
	else{
		$row=mssql_fetch_row($result);
		return $row[0];	
	}
}

function Get_Full_item($pid,$cid,$test_items){
	$str='';
	if($cid==''){$cid='C00001';}
	$query="select Rull_Total from Analyze_Rulls WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO LIKE '%".$cid."%')";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	if($numrow==0){	
		$query="SELECT AnalyzeItem.ANI_INDEX as id FROM RegularAnalyzeItem INNER JOIN AnalyzeItem ON RegularAnalyzeItem.ANI_ID = AnalyzeItem.ANI_ID WHERE (RegularAnalyzeItem.PROD_NO = '".$pid."')";	
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$str.=$row['id'].",";
	}
		return substr($str,0,-1);
	}
	else{
		$row=mssql_fetch_row($result);
		return $row[0];	
	}
}

function Get_Drum_All($pid,$cid){
	$str='';
	if($cid==''){$cid='C00001';}
	$query="select Rull_Total from Analyze_Rulls WHERE (PDD_PROD_NO = '".$pid."') AND (CTD_CUST_NO LIKE '%C00001%')	";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	$row=mssql_fetch_row($result);
	return $row[0];	
}

?>
