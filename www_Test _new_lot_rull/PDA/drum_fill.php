<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>DRUM 充填</title>
<style type="text/css">
body,td,th {
	font-size: 20px;
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
<script language="JavaScript" type="text/javascript">
<!--

function checkform2 ( form )
{
  // ** START **
  if (form2.remnant.value == "") {
    alert( "請輸入殘液量" );
    form2.remnant.focus();
    return false ;
  }
  // ** END **
  return true ;
}
function checkform4 ( form )
{
  // ** START **
  if (form4.remnant.value == "") {
    alert( "請輸入殘液量" );
    form2.remnant.focus();
    return false ;
  }
  // ** END **
  return true ;
}

function checkform3 ( form )
{
  // ** START **
  if (form3.m3.value == "") {
    alert( "請輸入桶槽液量" );
    form3.m3.focus();
    return false ;
  }
   if (form3.c1.checked =="") {
    alert( "Coupler 洗淨" );
    form3.c1.focus();
    return false ;
  }
   if (form3.c2.checked =="") {
    alert( "Coupler/O-ring狀態確認" );
    form3.c2.focus();
    return false ;
  }
   if (form3.c3.checked =="") {
    alert( "Coupler接續" );
    form3.c3.focus();
    return false ;
  }
   if (form3.c4.checked =="") {
    alert( "洩壓" );
    form3.c4.focus();
    return false ;
  }
   if (form3.qty.value == "") {
    alert( "充填量設定" );
    form3.qty.focus();
    return false ;
  }
  // ** END **
  return true ;
}
function checkform7 ( form )
{
  // ** START **
  if (form7.d1.checked == "") {
    alert( "HOSE外觀檢查" );
    form7.d1.focus();
    return false ;
  }
   if (form7.d2.checked =="") {
    alert( "充填閥關閉" );
    form7.d2.focus();
    return false ;
  }
   if (form7.d3.checked =="") {
    alert( "容器氣壓排放確認" );
    form7.d3.focus();
    return false ;
  }
   if (form7.d4.checked =="") {
    alert( "容器(氣、液)閥緊閉確認" );
    form7.d4.focus();
    return false ;
  }
   if (form7.d5.checked =="") {
    alert( "Coupler取出洗淨" );
    form7.d5.focus();
    return false ;
  }
   if (form7.d6.checked == "") {
    alert( "Coupler/O-ring狀態確認" );
    form7.d6.focus();
    return false ;
  }
  if (form7.d7.checked == "") {
    alert( "軟管收納" );
    form7.d7.focus();
    return false ;
  }
  // ** END **
  return true ;
}
//-->
</script></head>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="161" height="86" /></a>人員：<?php echo $_SESSION['uname']?>
<br />
    Lot No. ：
    <input type="text" name="lid" id="lid" width="50" height="20"  <?php echo ' value="'.$_SESSION['lid'].'"'; ?>  onKeyPress="return SubmitEnter(this,event)" onchange="set_date_session(this.name,this.value)"/>
    <input type="submit" name="enter" id="enter" value="送出" />
<br />
</form>
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
// P1	
	echo '品名 ： '.$prod_name.'</br>';
	echo '日期 ： '.date("Y/m/d").'</br>';
	echo '客戶 ： '.$cust_name. '</br>';
	echo '樣品瓶數 ： '.$smp_cnt.'</br>';
	echo '充填量 ： '.$fdm_qty.'</br></br>';
}
if(isset($_POST['enter'])) {
	$query="select * FROM              LORRY_FILL_CHECK
WHERE          (FDM_LOT_NO = '".$_POST['lid']."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows==0){
	echo '重量確認</br>' ;
	echo 'Lorry NO. ： '.$lorry_no.'</br>';
	echo '比重 ： '.literkg1($pid).'</br>';
	echo '<form id="form2" name="form2" method="post" onsubmit="return checkform2(this);" action="'.$loginFormAction.'">';
	echo '充填前殘液量 ： '.'<input type="text" name="remnant" id="remnant" width="50" height="20" autofocus="autofocus" onKeyPress="return SubmitEnter(this,event)" />';
	echo '<input type="submit" name="submit1" id="submit" value="  下 一 步  " /></br></font></form>';
	}
	else{my_msg("Lot 已充填過",$_SESSION['index']);}

}///end enter

	
if(isset($_POST['submit1'])) {
// P2
sm1();
}

if(isset($_POST['submit2'])) {
sm2();
}

if(isset($_POST['submit3'])) {
// P2
	sm3();
}

if(isset($_POST['sm1'])) {
	sm1();
}
if(isset($_POST['sm2'])) {
	sm2();
}
if(isset($_POST['smp_fin'])) {
	tank();
}
if(isset($_POST['finish'])) {
	finish();
}

if(isset($_POST['add_smp'])) {	
	$query="Update Sample Set SMP_TIMES = ISNULL(SMP_TIMES,0)+1,SMP_SERVICE = 3,SMP_LOT ='".$_SESSION['lid']."', SMP_DRUMNO ='1~8', SMP_USER = '".$cid."', SMP_SMP = '".$_SESSION['uid']."', SMP_SAVE = '".date("Ymd")."' 
	Where SMP_ID = '".$_POST['smp_no']."' ";
//	echo $query;
//	echo "</br>";
	$result = mssql_query($query);
	$query="Insert Into Sample_All 
	(SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT , SMA_SERIAL_NO , SMA_DRUMNO ,  SMA_USER, SMA_SMP, SMA_SAVE, SMA_SAVE_TIME, ISREWORK , DHN_DRUM_NO) 
	select '".$_POST['smp_no']."' ,SMP_TIMES,3,'".$_SESSION['lid']."',1,'1~8','".$cid."','".$_SESSION['uid']."','".date("Ymd")."','".date("His")."','0','' from sample Where SMP_ID = '".$_POST['smp_no']."' ";
	$result = mssql_query($query);
	sm3();
}

if(isset($_POST['end'])) 
{
	// 1. Insert into lorry_fill_check
	// 2. Update LORRY_EXAMINE_LIST
	// 3. Update LORRY_FILL_CHECK     
	// 4. Update PRODUCT_STOCKS
	// 5. Update FILL_INDICATE
	// 6. Update PRODUCT_RUNNING_ACCOUNT
	
	// 1. Insert into lorry_fill_check
	if($_POST['btk']=='N/A'){$_POST['btk']=0;}
	$query="Insert Into LORRY_FILL_CHECK 	(FDM_LOT_NO,  LFC_W_LY_NO,  LFC_B_REMAIN_QTY,  LFC_B_TROUGH_TOP,  LFC_B_CHK_COUP_CLR,  LFC_B_CHK_COUP,  LFC_B_CHK_COUP_LINK,  LFC_B_CHK_EXHAUST,  
			LFC_B_SET_FILL,  LFC_F_FLOW,  LFC_F_SAM_COUNT,  LFC_F_RESISTANCE,  LFC_E_OPER_FILL,  LFC_E_TROUGH_TOP,  LFC_E_CHK_AIR_SEAL,  LFC_E_CHK_FILL_CLOSE,  LFC_E_CHK_EXHAUST,  LFC_E_CHK_VALVE,  
			LFC_E_CHK_COUP_CLR,  LFC_E_CHK_COUP,  LFC_E_CHK_PIPE_PICKUP,  LFC_FILLER,  LFC_AIR_SEAL,  LFC_AIR_SEAL_START,  LFC_AIR_SEAL_END,  LFC_E_CHK_MEGA_CHECK,  LFC_E_CHK_SURFACE,  LFC_TANK) 
			VALUES ('".$_SESSION['lid']."','".$lorry_no."',".$_POST['remnant'].",".$_POST['m3'].",'Y','Y','Y','Y',".$_POST['qty'].","._flow($_POST['qty']).",".$smp_cnt.",".$_POST['btk'].",".$_POST['qty']."
			,".$_POST['m32'].",'Y','Y','Y','Y','Y','Y','Y','".$_SESSION['uid']."',".$_POST['sti0'].",'".$_POST['sti1']."','".$_POST['sti0']."','1','Y','".$_POST['rd']."')";
	$result1 = mssql_query($query);
	if($result1){echo "1. Writting LORRY_FILL_CHECK .....<BR>";}else{my_msg("LORRY_FILL_CHECK重複輸入",'');break;}
	
	// 2. Update LORRY_EXAMINE_LIST
	$query="Select * From LORRY_EXAMINE_LIST Where LEL_LOT_NO ='".$_SESSION['lid']."'";
	$result2 = mssql_query($query);
	$numRows2 = mssql_num_rows($result2);
	if($numRows2>0){
	$query="Update LORRY_EXAMINE_LIST  Set LEL_PDA_REMNANT_QTY = ".$_POST['remnant']."  Where LEL_LOT_NO = '".$_SESSION['lid']."' And PRA_OUT_COUNT = 
	(Select Max(PRA_OUT_COUNT) as MAX_OUT_COUNT  From LORRY_EXAMINE_LIST Where LEL_LOT_NO ='".$_SESSION['lid']."')";}
	else{$query="INSERT INTO LORRY_EXAMINE_LIST
                            (LEL_LOT_NO, PRA_OUT_COUNT, PDD_PROD_NO, LEL_LY_NO, LEL_FAKE_QTY, LEL_REMNANT_QTY, LEL_PDA_REMNANT_QTY) 
							VALUES          ('".$_SESSION['lid']."', 1,'".$_SESSION['pidx']."', '".substr($_SESSION['lid'],1,2).substr($_SESSION['lid'],-4,4)."', ".$_SESSION['qty'].", 
							".$_SESSION['remnant'].", ".$_POST['m32'].")";}
	
	$result2 = mssql_query($query);
	if($result2){echo "2.Writting LORRY_EXAMINE_LIST .....<BR>";}else{break;}
	
	//	if($result3){echo "Writing LORRY_FILL_CHECK .....<BR>";}else{break;}
	
	// 4. Update PRODUCT_STOCKS
	$query="Update PRODUCT_STOCKS Set STK_QTY = (".$_SESSION['qty']." - STK_PRE_OUT_QTY) Where STK_LOT_NO = '".$_SESSION['lid']."'";
//	echo $query;
	$result4 = mssql_query($query);
	if($result4){echo "3.Updating PRODUCT_STOCKS .....<BR>";}else{break;}
	
	//5. Update FILL_INDICATE	
	$query="Update FILL_INDICATE Set FID_FILL_END_DATE = '".date("YmdHis")."', FID_QTY = ".$_SESSION['qty'].", FID_WASHED_COUNT = 0, FID_OPERATOR = '".$_SESSION['uid']."'   Where FDM_LOT_NO = '".$_SESSION['lid']."'"; 
	$result5 = mssql_query($query);
	if($result5){echo "4.Updating FILL_INDICATE .....<BR>";}else{break;}
	
	$query="Update PRODUCT_RUNNING_ACCOUNT Set PRA_FAKE_IN_DATE2 = '".date("Ymd")."', PRA_REAL_IN_DATE = '".date("Ymd")."', PRA_REAL_IN_QTY = ISNULL(PRA_REAL_IN_QTY,0)+".$_SESSION['qty']." 
	Where PRA_LOT_NO = '".$_SESSION['lid']."' and CTD_CUST_NO = '".$cid."' and PRA_PURPOSE = 1"; 
	$result6 = mssql_query($query);
	if($result6){echo "5.Updating PRODUCT_RUNNING_ACCOUNT .....<BR>.....完成";}else{break;}
}

function _flow($qty)
{
	if($qty<=950){return 1.5 ;}
	if($qty>950 and $qty<=2900){return 1;}
	if($qty>2900){return 2.5;}
}

function sm3(){
	$query="select count(*) as cnt from sample_all where SMA_LOT='".$_SESSION['lid']."'";
	$result = mssql_query($query);
	$cnt=mssql_fetch_row($result);
	echo '<form id="form5" name="form5" method="post" action="'.$loginFormAction.'">';
	echo '第'.($cnt[0]+1).'瓶<input type="text" name="smp_no" id="smp_no"   autofocus="autofocus"/>';
	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
	echo '<input type="hidden" name="m3" id="m3" value="'.$_POST['m3'].'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$_POST['qty'].'" />';
	echo '<input type="hidden" name="btk" id="btk" value="'.$_POST['btk'].'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$_POST['mega_check'].'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$_POST['purge_qty'].'" />';
	echo '<input type="submit" name="add_smp" value="新增/下一瓶" />';	
	if($cnt[0]>=$smp_cnt){
		echo '<input type="submit" name="sm2" value="上一步" /><input type="submit" name="smp_fin" value="  下 一 步  " /></br>';	
	}
	echo '</form>';	
}
	
function sm2(){
	$_SESSION['m3']=$_POST['m3'];
	$_SESSION['qty']=$_POST['qty'];
	echo '<form id="form4" name="form4" method="post" action="'.$loginFormAction.'">';
	echo '充填<br>' ;
	echo '流量： '._flow($_POST['qty']).'</br>比抵抗 (IPA>5.0GΩcm)：<input type="text" name="btk" id="btk" width="10" height="20"  autofocus="autofocus" value="N/A"/>';
	echo '<br> 取樣前Purge量(20KG) ： <input type="text" name="purge_qty" id="purge_qty" width="12" height="20" /></br>';
	echo 'MEGA CHECK (1mΩ) ： <input type="text" name="mega_check" id="mega_check" width="12 height="20" /></br>';
	echo '<input type="submit" name="sm1" value="上一步" /><input type="submit" name="submit3" id="submit3" value="  下 一 步  " /></br>';
	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
	echo '<input type="hidden" name="m3" id="m3" value="'.$_POST['m3'].'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$_POST['qty'].'" />';
	echo '</form>';	
	}
function sm1(){
	$_SESSION['remnant']=$_POST['remnant'];
		echo '<form id="form3" name="form3" method="post" onsubmit="return checkform3(this);" action="'.$loginFormAction.'">';

	echo '充填前準備</br>製品桶槽液量確認' ;
	echo '控制室(m^3)(%)： <input type="text" name="m3" id="m3" width="50" height="20"  autofocus="autofocus" /></br>';
	echo '<input type="checkbox" name="c1" id="c1" />Coupler 洗淨<br>
		<input type="checkbox" name="c2" value="1" id="c2" />Coupler/O-ring狀態確認<br>
		<input type="checkbox" name="c3" value="1" id="c3" />Coupler接續<br>
		<input type="checkbox" name="c4" value="1" id="c4" />排放容器氣壓(洩壓聲停一分鐘)<br>';
	echo '充填量設定： <input type="text" name="qty" id="qty" width="50" height="20"  onKeyPress="return SubmitEnter("this",event)"/></br>';
	echo '<input type="submit" name="submit2" id="submit2" value="  下 一 步  "/></br>';
	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/></form><br>';
	}
	
function tank(){
	echo '<form id="form6" name="form6" method="post" action="'.$loginFormAction.'">';
	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
	echo '<input type="hidden" name="m3" id="m3" value="'.$_POST['m3'].'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$_POST['qty'].'" />';
	echo '<input type="hidden" name="btk" id="btk" value="'.$_POST['btk'].'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$_POST['mega_check'].'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$_POST['purge_qty'].'" />';
	$query="select * FROM              TANK_DATA
WHERE          (PDD_PROD_NO = '".$_SESSION['pidx']."') ";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){
		echo '<input type="radio" name="rd" value="'.$row['TANK_NO'].'" autofocus="autofocus"/>'.$row['TANK_KEY'].".".$row['TANK_DESC'].'<br>';;
	}
	echo '</br></br><input type="submit" name="submit3" value="上一步" /><input type="submit" name="finish" value="   下 一 步   " /></br>';
	echo '</form>';	
}

function finish(){
	echo '<form id="form7" name="form7" method="post" onsubmit="return checkform7(this);" action="'.$loginFormAction.'">';
	echo '充填終了確認</br>製品桶槽液面確認' ;
	echo '(m^3)： <input type="text" name="m32" id="m32" width="50" height="20"  autofocus="autofocus" /></br>';
	echo '氣密測試(1.9kg/cm^2 10分鐘): <input type="text" name="sti0" id="sti0" value="" /><br>';
	echo '氣密測試開始時間 : <input type="text" name="sti1" id="sti1" value="" /><br>';
	echo '氣密測試結束時間 : <input type="text" name="sti2" id="sti2" value="" /><br>';
	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
	echo '<input type="hidden" name="m3" id="m3" value="'.$_POST['m3'].'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$_POST['qty'].'" />';
	echo '<input type="hidden" name="btk" id="btk" value="'.$_POST['btk'].'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$_POST['mega_check'].'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$_POST['purge_qty'].'" />';
	echo '<input type="hidden" name="rd" id="rd" value="'.$_POST['rd'].'" />';
	echo '<input type="checkbox" name="d1" id="c1" />HOSE外觀檢查<br>
		<input type="checkbox" name="d2" value="1" id="c2" />充填閥關閉<br>
		<input type="checkbox" name="d3" value="1" id="c3" />容器氣壓排放確認(洩壓聲停一分鐘)<br>
		<input type="checkbox" name="d4" value="1" id="c3" />容器(氣、液)閥緊閉確認<br>
		<input type="checkbox" name="d5" value="1" id="c3" />Coupler取出洗淨<br>
		<input type="checkbox" name="d6" value="1" id="c3" />Coupler/O-ring狀態確認<br>
		<input type="checkbox" name="d7" value="1" id="c4" />軟管收納<br>';
	echo '</br></br><input type="submit" name="smp_fin" value="上一步" /><input type="submit" name="end" value="  結 束  " /></br>';
	echo '</form>';	

}
?>

</form>
<a href="<?php echo $_SESSION['index'];?>"><strong> 取消</strong></a>