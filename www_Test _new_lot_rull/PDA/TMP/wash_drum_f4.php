<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
</head>
 
<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="161" height="86" /></a>人員：<?php echo $_SESSION['uname']?> </p>
 <p>&nbsp;</p>
 <p><br />
<?php
$query="SELECT          PDD_PROD_NO, IAP_LOT_NO
FROM              IN_PLAN_PRODUCT
WHERE          (IAP_LOT_NO = '".$_SESSION['lid']."')";
$result=mssql_query($query);
$numrowi=mssql_num_rows($result);
if($numrowi>0){
	$row=mssql_fetch_row($result);
	$_SESSION['pidx']=$pid=$row[0];
}
else
{
	$query="SELECT          FDM_LOT_NO, PDD_PROD_NO
FROM              FILL_INDICATE
WHERE          (FDM_LOT_NO = '".$_SESSION['lid']."')";

$result=mssql_query($query);
$row=mssql_fetch_row($result);
$_SESSION['pidx']=$pid=$row[1];
}
echo '<form id="form1" name="form1" method="post" action="'.$loginFormAction.'">';
echo '<input type="hidden" name="lid" id="m3" value="'.$_POST['lid'].'" />';
	echo '充填前準備</br>' ;
	echo '桶號('.$_SESSION['cnt']."/".$fdm_qty_drum.')： <input type="text" name="dm_no" id="dm_no" width="50" height="20" value="'.$_POST['dm_no'].'"readonly/>使用次數：'.$qq->dm_cnt.'</br>';
	echo '洗淨時間<br>' ;
	echo '(5分鐘以上)(分)：<input type="text" name="min_1" id="min_1" width="10" height="20"  autofocus="autofocus" value="5"/>';
	echo '<br><input type="checkbox" name="pure_water"  checked="checked"/>去水<br>';
	echo '<input type="checkbox" name="exchange_parts"  checked="checked" />部品取付(上下各一片)<br>';
	echo '<input type="submit" name="next_step" id="next_step" value="下一桶" ><input type="submit" name="fill" value="充填" /><input type="submit" name="end" value="  結 束  " /></br></form>';

if(isset($_POST['next_step'])){ ///add drums
	$asd=new get_from_lot_no;
	$asd->lid=$_SESSION['lid'];
	$asd->cid();
	$ppap=$asd->pid;
	$query="Select * From DRUM_HISTORY_NORMAL Where DHN_DRUM_NO ='".$_POST['dm_no']."'";	
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$numrow1=mssql_num_rows($result);
	if($numrow1==0){ //新增桶號
		$query="Insert Into DRUM_HISTORY_NORMAL 	(DHN_DRUM_NO, DHN_USED_COUNT, CTD_CUST_NO1, PDD_PROD_NO1,DHN_LOT_NO1) 	VALUES ('".$_POST['dm_no']."',  0 ,'','".$ppap."', '".$_SESSION['lid']."'  )";
//		echo $query;
//	echo '<BR>';
		$result=mssql_query($query);	
		if($result){echo $_POST['dm_no']." has been added in DRUM_HISTORY_NORMAL.<BR>";	}
	}
	$query="select max(EWD_SERIAL_NO) from EL_WASH_DRUM WHERE (FDM_LOT_NO = '".$_SESSION['lid']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$ewd_sn=$row[0]+1;
	$query="INSERT INTO EL_WASH_DRUM
                            (FDM_LOT_NO, EWD_DRUM, EWD_USED_COUNT, EWD_SERIAL_NO, EWD_CHK_SURFACE, EWD_CHK_LABEL, 
                            EWD_CHK_DRUM_IN, EWD_CHK_FILL_LINK, EWD_WASH_TIME, EWD_CLEAR, EWD_CHK_WASHER)
			VALUES          ('".$_SESSION['lid']."','".$_POST['dm_no']."',1,".$ewd_sn.",'Y','Y','Y','Y','".$_POST['min_1']."','Y','Y')";
	$result=mssql_query($query);	
	if($result){echo $_POST['dm_no']." has been added in EL_WASH_DRUM.<BR>";	}
//	echo '<a  href=el_drum_f2.php target="_blank"> NEXT f2 </a>';
	jumpto("el_drum_f2.php");
}

if(isset($_POST['fill'])){
	$asd=new get_from_lot_no;
	$asd->lid=$_SESSION['lid'];
	$asd->cid();
	$ppap=$asd->pid;
	$_SESSION['dm_no']=$_POST['dm_no'];
	$query="Select * From DRUM_HISTORY_NORMAL Where DHN_DRUM_NO ='".$_POST['dm_no']."'";	
//	echo '<BR>';
//	echo $query;
//	echo '<BR>';
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$numrow1=mssql_num_rows($result);

	if($numrow1==0){ //新增桶號
		$query="Insert Into DRUM_HISTORY_NORMAL 	(DHN_DRUM_NO, DHN_USED_COUNT, CTD_CUST_NO1, PDD_PROD_NO1,DHN_LOT_NO1) 	VALUES ('".$_POST['dm_no']."',  0 ,'','".$ppap."', '".$_SESSION['lid']."'  )";
//		echo $query;
//		echo "<BR>";
		$result=mssql_query($query);	
		if($result){echo $_POST['dm_no']." has been added in DRUM_HISTORY_NORMAL.<BR>";	}
	}
	$query="select max(EWD_SERIAL_NO) from EL_WASH_DRUM WHERE (FDM_LOT_NO = '".$_SESSION['lid']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$ewd_sn=$row[0]+1;
	$query="INSERT INTO EL_WASH_DRUM
                            (FDM_LOT_NO, EWD_DRUM, EWD_USED_COUNT, EWD_SERIAL_NO, EWD_CHK_SURFACE, EWD_CHK_LABEL, 
                            EWD_CHK_DRUM_IN, EWD_CHK_FILL_LINK, EWD_WASH_TIME, EWD_CLEAR, EWD_CHK_WASHER)
			VALUES          ('".$_SESSION['lid']."','".$_POST['dm_no']."',1,".$ewd_sn.",'Y','Y','Y','Y','".$_POST['min_1']."','Y','Y')";
//	echo $query;
//  echo "<BR>";
		
	$result=mssql_query($query);	
	if($result){echo $_POST['dm_no']." has been added in EL_WASH_DRUM.<BR>";	}
	$query="SELECT CTP_VALID_MON, CTP_REMNANT_MON FROM CUSTOMER_PRODUCTS WHERE (PDD_PROD_NO = '".$ppap."') AND (CTD_CUST_NO = '".$_SESSION['cust_id']."')";
	$result=mssql_query($query);
	$aal=mssql_fetch_row($result);
	$valid="+".$aal[0]." month";
	$query="UPDATE OUT_PRODUCT SET OPD_TERM_DATE = '".date("Ymd",strtotime($valid))."' WHERE (OPD_LOT_NO = '".$_SESSION['lid']."')";
	
//	echo $query;
//	echo "<BR>";
		
		
	$result=mssql_query($query);	
//	echo '<a  href=el_drum_f5.php target="_blank"> NEXT f5</a>';
	jumpto("el_drum_f5.php");
}


if(isset($_POST['end'])){
	jumpto("index.php");	
}

?>
 </p>
