<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
if(isset($_POST['lid'])){
$_SESSION['lid']=$_POST['lid'];
}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<style type="text/css">
body,td,th {
	font-size: 40px;
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
 

<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?></p>
<?php
$query="SELECT          A.FOD_UNI, A.CTD_CUST_NO, B.CTD_CUST_NAME, A.FDM_SERIAL_NO, A.PDD_PROD_NO, C.PDD_PROD_NAME, C.PDD_DRUM_KG,
                            C.PDD_PROD_SHORT_NAME, A.FDM_SAM_BEFORE, A.FDM_SAM_BEF_CNT, A.FDM_SAM_CNT, 
                            A.FDM_ATTACH_CNT, A.FDM_QTY_DRUM, A.FDM_QTY, D.CTP_ExportCountLimit, A.FDM_LY_NO
FROM              FILLPLAN_OUT_DECIDE AS A LEFT OUTER JOIN
                            CUSTOMER_DATA AS B ON A.CTD_CUST_NO = B.CTD_CUST_NO LEFT OUTER JOIN
                            PRODUCT_DATA AS C ON A.PDD_PROD_NO = C.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_PRODUCTS AS D ON A.CTD_CUST_NO = D.CTD_CUST_NO AND 
                            A.PDD_PROD_NO = D.PDD_PROD_NO
WHERE          (A.FDM_LOT_NO = '".$_SESSION['lid']."')";
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
	$_SESSION['drum_kg']=$row['PDD_DRUM_KG'];
	$fdm_qty_drum=$_SESSION['fdm_qty_drum']=$row['FDM_QTY_DRUM'];
// P1	
}
	echo 'Lot NO：'.$_SESSION['lid'].'<BR>';
	echo '品名 ： '.$prod_name.'</br>';
	echo '日期 ： '.date("Y/m/d").'</br>';
	echo '客戶 ： '.$cust_name. '</br>';
	echo '需要樣品瓶數 ： '.$smp_cnt.'</br>';
	echo '充填量 ： '.$fdm_qty.'<BR>';

	echo '<form id="form1" name="form1" method="post" action="wash_drum_f3.php">
		<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
	echo '<input type="hidden" name="lid" id="m3" value="'.$_SESSION['lid'].'" />';
	echo '<input type="hidden" name="btk" id="btk" value="'.$_POST['btk'].'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$_POST['mega_check'].'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$_POST['purge_qty'].'" />';
	
	if($_SESSION['reset_dmno']==1){
		echo '輸入桶號:<input type="text" style="font-size:30px" size="12" name="dm_no" value="" autofocus onchange="set_date_session(this.name,this.value)"/><br>';
		echo '製造Lot:<input type="text" style="font-size:30px" size="12" name="made_lot" value="'.$_SESSION['made_lot'].'"/><br>';
		echo '<input type="submit" hidden="hidden" autofocus name="sm1" value="   下 一 步   " />';
	}
	else{
		echo '輸入桶號:<input type="text" style="font-size:30px" size="12" name="dm_no" value="'.$_SESSION['dm_no'].'" autofocus onchange="set_date_session(this.name,this.value)"/><br>';
		echo '製造Lot:<input type="text" style="font-size:30px" size="12" name="made_lot" value="'.$_SESSION['made_lot'].'"/><br>';
		echo '<input type="submit" hidden="hidden" autofocus name="sm1" value="   下 一 步   " />';
	}
	unset($_SESSION['reset_dmno']);
	echo '<input type="button" name="X" id="X" style="width:120px;height:40px;border:2px orange double;" value="上 一 步" onClick="window.open('."'wash_drum_f1.php ', '_self'".');"></br></form>';
	
	// {$dmno='';echo '<input type="submit" hidden="hidden" name="sm1" value="   下 一 步   " />';}
?>

 