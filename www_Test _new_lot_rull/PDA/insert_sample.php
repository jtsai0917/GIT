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

}

if(isset($_POST['sub1']))
{
	$query="Select Count(*) as SAM_COUNT From Sample_All  Where SMA_ID = '".$_POST['sma_no']."'  And SMA_LOT = '".$_GET['lid']."' ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$cnt=$row[0];
	if($cnt>0){my_msg("此瓶號已經輸入過");}
	else{
			// insert into sample_all
			$query="select SMP_TIMES+1 from Sample where SMP_ID='".$_POST['sma_no']."'";
			$result=mssql_query($query);
			$numrows=mssql_num_rows($result);
			if($numrows>0){
				$rr=mssql_fetch_row($result);
				$smp_times=$rr[0];
				$query="INSERT INTO Sample_All
								(SMA_ID, SMA_SERVICE, SMA_TIMES, SMA_LOT, SMA_SMP, SMA_SAVE, SMA_SAVE_TIME, ISREWORK, 
								SMA_DRUMNO, SMA_SERIAL_NO)
	VALUES          ('".$_POST['sma_no']."', 1, ".$smp_times.", '".$_GET['lid']."', '".$_SESSION['uid']."', '".date("Ymd")."', '".date("His")."', '0', '0~0', 1)";
				$result=mssql_query($query);
				
				// update sample	
				$query="UPDATE          Sample
SET                   SMP_MID_ID ='".$_SESSION['pidx']."', SMP_TIMES =".$smp_times.", SMP_SERVICE =1, SMP_LOT ='".$_GET['lid']."', SMP_DRUMNO ='0~0', SMP_SMP ='".$_SESSION['uid']."', 
                            SMP_SAVE = '".date("Ymd")."' , SMP_SAVE_TIME = '".date("His")."'
WHERE          (SMP_ID = '".$_POST['sma_no']."')";
				$result=mssql_query($query);
				
			}
			else{my_msg("查無此瓶號");}
		}
}

if(isset($_POST['next']))
{
	jumpto("insert_drum.php?lid=".$_GET['lid']);
}

if(isset($_POST['leave']))
{
	jumpto($_SESSION['index']);
}

?>
充填口取樣，已取 <?php $query="SELECT          COUNT(*) AS SAM_COUNT
FROM              Sample_All
WHERE          (SMA_LOT = '".$_GET['lid']."')";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
$cnt=$row[0];
echo $cnt;?> 瓶
  <br />
    樣品瓶號:
    <input type="text" name="sma_no" id="sma_no" />
  </p>
  <p>
    <input type="submit" name="sub1" id="sub1" value="下一瓶" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="next" id="next" value="下一步" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="leave" id="leave" value="離  開" />
    <br />
  </p>
</form>
</html>
