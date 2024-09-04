<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
if(isset($_POST['lid'])){
$_POST['lid']=strtoupper($_POST['lid']);
$_SESSION['lid']=trim($_POST['lid']);}
$_SESSION['dm_no']='';

$query="select FDM_LOT_NO, PDD_PROD_NO from fill_indicate where fdm_lot_no='".$_SESSION['lid']."'";

$result=mssql_query($query);
$numr=mssql_num_rows($result);
$rr=mssql_fetch_row($result);
$pid=$rr[1];
// if($numr==0){	my_msg($_POST['lid']." 沒有充填計畫","el_drum_f1.php");}
$query="SELECT  * FROM DRUM_FILL_DAY_CHECK WHERE (FDM_LOT_NO = '".trim($_POST['lid'])."')";
$result=mssql_query($query);
$num=mssql_num_rows($result);
if($num==0){
	jumpto("ins_day_fill_chk.php?lid=".trim($_POST['lid'])."&pid=".$pid);	
}

?>
ˋ<head>
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
<BR>DRUM 充填作業<BR>
<?php
$query="SELECT  count(*) as NN FROM EL_FILLDATA_DRUM WHERE   (FDM_LOT_NO = '".$_SESSION['lid']."')";
// echo $query."<BR>";
$result=mssql_query($query);
$rows=mssql_fetch_row($result);
$_SESSION['cnt']=$rows[0];
$query="SELECT  A.FOD_UNI, A.CTD_CUST_NO, B.CTD_CUST_NAME, A.FDM_SERIAL_NO, A.PDD_PROD_NO, C.PDD_PROD_NAME, 
                   C.PDD_DRUM_KG, C.PDD_PROD_SHORT_NAME, A.FDM_SAM_BEFORE, A.FDM_SAM_BEF_CNT, A.FDM_SAM_CNT, 
                   A.FDM_ATTACH_CNT, A.FDM_QTY_DRUM, A.FDM_QTY, D.CTP_ExportCountLimit, A.FDM_LY_NO, 
                   empty_drum.weight as weight 
FROM      empty_drum RIGHT OUTER JOIN
                   PRODUCT_DATA AS C ON empty_drum.pdd_no = C.PDD_PROD_NO RIGHT OUTER JOIN
                   FILLPLAN_OUT_DECIDE AS A LEFT OUTER JOIN
                   CUSTOMER_DATA AS B ON A.CTD_CUST_NO = B.CTD_CUST_NO ON 
                   C.PDD_PROD_NO = A.PDD_PROD_NO LEFT OUTER JOIN
                   CUSTOMER_PRODUCTS AS D ON A.CTD_CUST_NO = D.CTD_CUST_NO AND 
                   A.PDD_PROD_NO = D.PDD_PROD_NO 
WHERE          (A.FDM_LOT_NO = '".$_SESSION['lid']."')";
// echo "<BR>".$query."<BR>";
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
	$_SESSION['drum_weight']=$row['weight'];
	$fdm_qty_drum=$_SESSION['fdm_qty_drum']=$row['FDM_QTY_DRUM'];
}

	echo 'Lot NO：'.$_SESSION['lid'].'<BR>';
	echo '品名 ： '.$prod_name.'</br>';
	echo '日期 ： '.date("Y/m/d").'</br>';
	echo '客戶 ： '.$cust_name. '</br>';
	echo '需要樣品瓶數 ： '.$smp_cnt.'</br>';
	echo '充填量 ： '.$fdm_qty."&nbsp;&nbsp;&nbsp;&nbsp;已充填(".$_SESSION['cnt']."/".$_SESSION['fdm_qty_drum'].')桶<BR>';
	echo '<form id="form1" name="form1" method="post" action="el_drum_f5.php">';
	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
	echo '<input type="hidden" name="lid" id="m3" value="'.$_SESSION['lid'].'" />';
	echo '<input type="hidden" name="btk" id="btk" value="'.$_POST['btk'].'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$_POST['mega_check'].'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$_POST['purge_qty'].'" />';
	if($_SESSION['dm_no']==''){
		echo '輸入桶號:<input type="text"   autocomplete="off"  style="font-size:30px" size="12" name="dm_no" value="'.$_SESSION['dm_no'].'" autofocus onchange="set_date_session(this.name,this.value)"/><br>';
		
	}
	echo '<input type="submit" name="enter" hidden="hidden" style="width:120px;height:40px;border:2px orange double;" value=" ENTER " >';
	echo '<input type="button" name="X" id="X" style="width:120px;height:40px;border:2px orange double;" value="上 一 步" onClick="window.open('."'el_drum_f1.php ', '_self'".');"><input type="submit" name="x2" style="width:120px;height:40px;border:2px orange double;" value=" 結 束 " ></br></form>';
	
	// {$dmno='';echo '<input type="submit" hidden="hidden" name="sm1" value="   下 一 步   " />';}
?>

 