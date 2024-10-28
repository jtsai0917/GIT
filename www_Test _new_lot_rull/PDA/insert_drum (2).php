<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
datepick();
lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
</head>

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="161" height="86" /></a>人員：<?php echo $_SESSION['uname']?><BR />
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$query="SELECT          A.FOD_UNI, A.CTD_CUST_NO, B.CTD_CUST_NAME, A.FDM_SERIAL_NO, A.PDD_PROD_NO, C.PDD_PROD_NAME, 
                            C.PDD_PROD_SHORT_NAME, A.FDM_SAM_BEFORE, A.FDM_SAM_BEF_CNT, A.FDM_SAM_CNT, 
                            A.FDM_ATTACH_CNT, A.FDM_QTY_DRUM, A.FDM_QTY, D.CTP_ExportCountLimit, A.FDM_LY_NO
FROM              FILLPLAN_OUT_DECIDE AS A LEFT OUTER JOIN
                            CUSTOMER_DATA AS B ON A.CTD_CUST_NO = B.CTD_CUST_NO LEFT OUTER JOIN
                            PRODUCT_DATA AS C ON A.PDD_PROD_NO = C.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_PRODUCTS AS D ON A.CTD_CUST_NO = D.CTD_CUST_NO AND 
                            A.PDD_PROD_NO = D.PDD_PROD_NO
WHERE          (A.FDM_LOT_NO = '".$_GET['lid']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
	$_SESSION['pidx']=$pid=$row['PDD_PROD_NO'];
	$cid=$row['CTD_CUST_NO'];
	$prod_name=$row['PDD_PROD_NAME'];
	$cust_name=$row['CTD_CUST_NAME'];
	$smp_cnt=$row['FDM_SAM_BEF_CNT']+$row['FDM_ATTACH_CNT']+$row['FDM_SAM_CNT']+2;
	$fdm_qty=$row['FDM_QTY'];
	$lorry_no=$row['FDM_LY_NO'];
	$_SESSION['fdm_qty_drum']=$row['FDM_QTY_DRUM'];
// P1	
	echo 'Lot NO. :'.$_GET['lid'].'<BR>';
	echo '品名 ： '.$prod_name.'</br>';
	echo '日期 ： '.date("Y/m/d").'</br>';
	echo '客戶 ： '.$cust_name. '</br>';
	echo '樣品瓶數 ： '.$smp_cnt.'</br>';
	echo '充填量 ： '.$fdm_qty.'</br>';
	echo '計畫桶數：'.$_SESSION['fdm_qty_drum'].'桶</br>';
}
$query="Select count(*) from DRUM_HISTORY_NORMAL where DHN_LOT_NO1='".$_GET['lid']."' or DHN_LOT_NO2='".$_GET['lid']."' or DHN_LOT_NO3='".$_GET['lid']."'";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
$rdy=$row[0];
	echo '已充填   ： '.$rdy.'桶</br>';
if(isset($_POST['sub1']))
{
	if(trim($_POST['drum_no'])==''){
		echo '
  <br />
    輸入桶號 :
    <input type="text" name="drum_no" id="drum_no" />
	<BR />
    製造 LOT :
    <input type="text" name="pro_lid" id="pro_lid" />
	<BR />
    外觀無汙穢、變形，有效期限未滿一年
    <input type="checkbox" name="pro_lid" id="pro_lid" />
	<BR />
    桶內無變形 :
    <input type="checkbox" name="pro_lid" id="pro_lid" />
	<BR />
    標籤無脫落、破損 :
    <input type="checkbox" name="pro_lid" id="pro_lid" />
  <p>
    <input type="submit" name="sub1" id="sub1" value="下一桶" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="next" id="next" value="下一步" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="leave" id="leave" value="離  開" />
    <br />
  </p>
</form>
</html>';

		break;}
	$query="Select count(*) from DRUM_HISTORY_NORMAL where (DHN_LOT_NO1='".$_GET['lid']."' or DHN_LOT_NO2='".$_GET['lid']."' or DHN_LOT_NO3='".$_GET['lid']."') and  DHN_DRUM_NO='".$_POST['drum_no']."' ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$cnt=$row[0];
	if($cnt>0 and $_POST['drum_no']<>''){my_msg("此桶號已經輸入過");}
	else{
		$query="select DHN_USED_COUNT from  DRUM_HISTORY_NORMAL WHERE	(DHN_DRUM_NO = '".$_POST['drum_no']."')";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$cnt=$row[0];
		if($cnt=='' or $cnt==NULL){
			$query="Insert Into DRUM_HISTORY_NORMAL 	(DHN_DRUM_NO, DHN_USED_COUNT, CTD_CUST_NO1, PDD_PROD_NO1, DHN_LOT_NO1) 	VALUES ( '".$_POST['drum_no']."', ".($cnt+1).",'".$cid."','".$pid."','".$_GET['lid']."' ) ";

			$result=mssql_query($query);}
		else{
				// insert into DRUM_HISTORY_NORMAL
				$query="Insert Into DRUM_HISTORY_NORMAL 	(DHN_DRUM_NO, DHN_USED_COUNT, CTD_CUST_NO1, PDD_PROD_NO1, CTD_CUST_NO2, PDD_PROD_NO2, CTD_CUST_NO3, PDD_PROD_NO3) 	VALUES ( '".$_POST['drum_no']."',  0 ,               '''',            '''',           '''',           '''',           '''',            ''''  ) ";
				$result=mssql_query($query);
				// update sample	
		}
	}
	// insert into sample_all
	// update sample	
}

if(isset($_POST['next']))
{
	// insert into sample_all
	// update sample	
}

if(isset($_POST['leave']))
{
	jumpto($_SESSION['index']);
}

echo '
  <br />
    輸入桶號 :
    <input type="text" name="drum_no" id="drum_no" />
	<BR />
    製造 LOT :
    <input type="text" name="pro_lid" id="pro_lid" />
	<BR />
	<input type="checkbox" name="pro_lid" id="pro_lid" />
    外觀無汙穢、變形，有效期限未滿一年
	<BR />
	<input type="checkbox" name="pro_lid" id="pro_lid" />
    桶內無變形  
	<BR />
	<input type="checkbox" name="pro_lid" id="pro_lid" />
    標籤無脫落、破損
    
  <p>
    <input type="submit" name="sub1" id="sub1" value="下一桶" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="next" id="next" value="下一步" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="leave" id="leave" value="離  開" />
    <br />
  </p>
</form>
</html>';

?>
