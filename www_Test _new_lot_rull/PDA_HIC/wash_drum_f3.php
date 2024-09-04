<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
if(trim($_POST['made_lot'])==''){my_msg("製造LOT不可以為空","wash_drum_f2.php");}
$_POST['dm_no']=strtoupper(trim($_POST['dm_no']));
$end_2=substr($_POST['dm_no'],-2,2);
if(strlen($_POST['dm_no'])<>8){my_msg($_POST['dm_no']."不是 8 碼，請重新輸入","wash_drum_f2.php");}
elseif($end_2>12 or $end_2<0){my_msg($_POST['dm_no']." 尾數編碼錯誤 ： ","wash_drum_f2.php");}

//echo "END:".$end_2;
$query="SELECT  COUNT(*) AS cnt
FROM      EL_WASH_DRUM
WHERE   (FDM_LOT_NO = '".$_POST['lid']."') AND (EWD_DRUM = '".$_POST['dm_no']."')";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
if($row[0]>0){my_msg(" 桶號重複 ： ".$_POST['dm_no'],"wash_drum_f2.php");}

datepick();
$_POST['lid']=trim($_POST['lid']);
$sa=new get_from_lot_no;
$sa->lid=$_POST['lid'];
$sa->ani();
$pdd_prod_short_name=trim($sa->pdd_prod_short_name);
if(substr($_POST['dm_no'],0,2)<>$pdd_prod_short_name)
{
	my_msg(" 桶號錯誤 ： ".$_POST['dm_no'],"wash_drum_f2.php");
}
if($_SESSION['uid']==''){jumpto("login.php");}
if(substr(trim($_POST['dm_no']),0,1)<>substr(trim($_SESSION['lid']),1,1)){my_msg("桶號不符合規則!!","wash_drum_f2.php");}
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
$_SESSION['made_lot']=$_POST['made_lot'];
$query="SELECT  COUNT(*) AS cnt
FROM      EL_WASH_DRUM
WHERE   (FDM_LOT_NO = '".$_SESSION['lid']."')";
//	echo "<BR>".$query."<BR>";
	$result=mssql_query($query);
	$a=mssql_fetch_row($result);
	$_SESSION['cnt']=$a[0]+1;
$query="SELECT          A.FOD_UNI, A.CTD_CUST_NO, B.CTD_CUST_NAME, A.FDM_SERIAL_NO, A.PDD_PROD_NO, C.PDD_PROD_NAME, 
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
	$fdm_qty_drum=$_SESSION['fdm_qty_drum']=$row['FDM_QTY_DRUM'];
// P1	
}
	echo 'Lot NO：'.$_SESSION['lid'].'<BR>';
	echo '品名 ： '.$prod_name.'</br>'; 
	echo '日期 ： '.date("Y/m/d").'</br>';
	echo '客戶 ： '.$cust_name. '</br>';
	echo '需要樣品瓶數 ： '.$smp_cnt.'</br>';
	echo '充填量 ： '.$fdm_qty."&nbsp;&nbsp;&nbsp;&nbsp;(".$_SESSION['cnt']."/".$_SESSION['fdm_qty_drum'].')桶<BR>';

	echo '<form id="form1" name="form1" method="post" action="wash_drum_f4.php">';
	echo '<input type="hidden" name="lid" id="m3" value="'.$_POST['lid'].'" />';
	echo '<input type="hidden" name="dm_no" id="m3" value="'.$_POST['dm_no'].'" />';
	echo '<input type="hidden" name="cnt" id="cnt" value="'.$a[0].'" />';
	echo '充填前準備</br>' ;
	echo '桶號('.$_SESSION['cnt']."/".$fdm_qty_drum.')： <input type="text" name="dm_no" autocomplete="off" id="dm_no" style="font-size:30px" size="10" value="'.$_POST['dm_no'].'"readonly/><BR>
	使用次數：'.$qq->dm_cnt.'</br>';
	echo '<input type="checkbox" name="c1" id="c1" checked="checked"/>外觀無汙穢、變形、有效期限(未滿1年)<br>
		<input type="checkbox" name="c2" value="1" id="c2" checked="checked" />桶內無變形<br>
		<input type="checkbox" name="c3" value="1" id="c3" checked="checked" />標籤無脫落、破損<br>';
	echo '<input type="button" style="width:120px;height:40px;border:3px orange double;" name="X" id="X" value="上 一 步" onClick="window.open('."'wash_drum_f2.php ', '_self'".');"><input type="submit" name="sm1" value="   下 一 步  " autofocus style="width:120px;height:40px;border:3px orange double;" /></br></form>';

?>

