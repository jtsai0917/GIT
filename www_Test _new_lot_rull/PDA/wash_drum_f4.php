<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}

?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<style type="text/css">
body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>

</head>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<BR>DRUM 洗桶作業<BR>
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
$fdm_qty_drum=$_SESSION['fdm_qty_drum'];
echo '<form id="form1" name="form1" method="post" action="'.$loginFormAction.'">';
echo '<input type="hidden" name="lid" id="m3" value="'.$_POST['lid'].'" />';
	echo '充填前準備</br>' ;
	echo '桶號('.$_SESSION['cnt']."/".$fdm_qty_drum.')： <input type="text" name="dm_no" id="dm_no" style="font-size:30px" size="10" value="'.$_POST['dm_no'].'"readonly/><BR>		使用次數：'.$qq->dm_cnt.'</br>';
	echo '製造LOT：'.$_SESSION['made_lot']."<BR>";
	echo '洗淨時間<br>' ;
	echo '(5分鐘以上)(分)：<input type="text" name="min_1" id="min_1"  style="font-size:20px" size="5" value="5"/>';
	echo '<br><input type="checkbox" name="pure_water"  checked="checked"/>去水<br>';
	echo '<input type="checkbox" name="exchange_parts"  checked="checked" />部品取付(上下各一片)<br>';
	echo '<input type="submit" name="next_step" autofocus="autofocus" id="next_step"  style="width:120px;height:40px;border:3px orange double;" 
	value="下一桶" ><input type="submit"  style="width:120px;height:40px;border:3px orange double;"  name="end" value="  結 束  " /></br></form>';

if(isset($_POST['next_step'])){ ///add drums
	$asd=new get_from_lot_no;
	$asd->lid=$_SESSION['lid'];
	$asd->cid();
	$ppap=$asd->pid;
	//取得桶號在 DRUM_HISTORY_NORMAL 中的筆數
	$query="Select count(DHN_DRUM_NO) as max From DRUM_HISTORY_NORMAL Where DHN_DRUM_NO ='".$_POST['dm_no']."'  and disable <>1 ";	

	$result=mssql_query($query);
	$row=mssql_fetch_row($result);	
	if($row[0] >=3){ 
		my_msg("DRUM 使用過三次","wash_drum_f1.php");
	}
	else
	{	//新增桶號
		$usedcnt=$row[0]+1;
		$query="Insert Into DRUM_HISTORY_NORMAL (DISABLE, DHN_WASHED_DATE1, DHN_DRUM_NO, DHN_USED_COUNT, CTD_CUST_NO1, PDD_PROD_NO1,DHN_LOT_NO1) 	VALUES (0,'".date("Ymd")."','".$_POST['dm_no']."',  ".$usedcnt." ,'','".$ppap."', '".$_SESSION['lid']."' )";
		$result=mssql_query($query);	
		if($result){echo $_POST['dm_no']." has been added in DRUM_HISTORY_NORMAL.<BR>";	}
	}
	
	$query="SELECT  MAX(EWD_USED_COUNT) + 1 AS used_cnt FROM EL_WASH_DRUM WHERE (EWD_DRUM = '".$_POST['dm_no']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$used_cnt=$row[0];
	if($used_cnt==''){$used_cnt=1;}
	$query="select max(EWD_SERIAL_NO) AS SN from EL_WASH_DRUM WHERE (FDM_LOT_NO = '".$_SESSION['lid']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$ewd_sn=$row[0]+1;
	$query="INSERT INTO EL_WASH_DRUM
                            (FDM_LOT_NO, EWD_DRUM, EWD_USED_COUNT, EWD_SERIAL_NO, EWD_CHK_SURFACE, EWD_CHK_LABEL, 
                            EWD_CHK_DRUM_IN, EWD_CHK_FILL_LINK, EWD_WASH_TIME, EWD_CLEAR, EWD_CHK_WASHER, EWD_MAKELOT, DISABLE)
			VALUES ('".$_SESSION['lid']."','".$_POST['dm_no']."',".$used_cnt.",".$ewd_sn.",'Y','Y','Y','Y','".$_POST['min_1']."','Y','Y','".$_SESSION['made_lot']."', 0)";
	$result=mssql_query($query);	
	if($result){echo $_POST['dm_no']." has been added in EL_WASH_DRUM.<BR>";}
//	echo '<a  href=el_drum_f2.php target="_blank"> NEXT f2 </a>';
	unset($_SESSION['dm_no']);
	$_SESSION['reset_dmno']=1;
 	my_msg("桶號:".$_POST['dm_no']."   製造LOT：".$_SESSION['made_lot']."   洗桶作業完成","wash_drum_f2.php");
//	jumpto("wash_drum_f2.php");
}


if(isset($_POST['end'])){
	$query="UPDATE  FILL_INDICATE SET	FID_FILL_END_DATE = '".date("YmdHis")."'	WHERE   (FDM_LOT_NO = '".$_SESSION['lid']."')";
	$result=mssql_query($query);
	my_msg("結束時間: ".date("Y-m-d H:i:s"),"index.php");
//	jumpto($_SESSION['index']);	
}

?>
 </p>
