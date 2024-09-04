<?php
session_start();  
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
else{$return_page="index.php";}
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>Lorry 充填</title>
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
<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>
	<BR />作 業 者：
<?php 
	echo $_SESSION['uname'];
	if($_SESSION['lid']=='' and $_SESSION['lorry_no1']=='' and $_SESSION['fid']=='')
	{$fo1='autofocus="autofocus"';}else{$fo1='';}
	if($_SESSION['lid']<>'' and $_SESSION['lorry_no1']=='' and $_SESSION['fid']=='')
	{$fo2='autofocus="autofocus"';}else{$fo2='';}
	if($_SESSION['lid']<>'' and $_SESSION['lorry_no1']<>'' and $_SESSION['fid']=='')
	{$fo3='autofocus="autofocus"';}else{$fo3='';}
?>
</p>
     Lot NO ：
    <input type="text"  autocomplete="off" name="lid" id="lid"  style="font-size:<?php echo $_SESSION['font_size'];?>px" size="12" <?php echo ' value="'.$_SESSION['lid'].'"'.$fo1; ?>  onchange="set_date_session(this.name,this.value)"/><BR /><BR />
     Lorry NO (槽車)：
    <input type="password" autocomplete="off" name="lorry_no1" id="lorry_no1"  style="font-size:<?php echo $_SESSION['font_size'];?>px" size="12" <?php echo ' value="'.$_SESSION['lorry_no1'].'"'.$fo2;; ?>  onchange="set_date_session(this.name,this.value)"/><?php  echo $lorry_no1=(trim($_SESSION['lorry_no1'])) ;$lorry_no1=substr($lorry_no1,0,6);?><BR /><BR />
    
<?php
if($_SESSION['lid']<>'' and $_SESSION['lorry_no1']<>''){
	if($_SESSION['step']=='sm1' or $_SESSION['step']=='sm2' or $_SESSION['step']=='sm3' or $_SESSION['step']=='tank' or $_SESSION['step']=='finish' or $_SESSION['step']=='end' ){$enablebutton='disabled';}
	echo '<input type="submit" name="enter" id="enter" style="font-size:'.$_SESSION['font_size'].'px;" value="送出" '.$enablebutton.'/><BR>';
	if($_SESSION['step']=='sm1'){
		sm1();
	}
	elseif($_SESSION['step']=='finish'){
		finish($_SESSION['flow']);
	}
	elseif($_SESSION['step']=='sm2'){
		sm2();
	}
	elseif($_SESSION['step']=='sm3'){
		sm3();
	}
	elseif($_SESSION['step']=='tank'){
		tank();
	}
	else{
		$_SESSION['step']='';
	}
}
?>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF']; 
if(isset($_POST['enter'])){
	enter($fid,$lorry_no1);	
}
///end enter
if(isset($_POST['end'])) 
{
	$_SESSION['step']=='end';
	if(trim($_SESSION['lid'])==''){
		$_SESSION['lid']=$_POST['lid'];
	}
	$query="select * from lorry_fill_tmp where lot_no='".$_SESSION['lid']."'";
    	$result=mssql_query($query);
    	$nummx=mssql_num_rows($result);
    	if($nummx>0){
    		while($row=mssql_fetch_array($result)){
					$remnant=$row['remnant'];	
					$m3=$row['m3'];	
					$bm3=$row['bm3'];	
					$btk=$row['btk'];	
					$lorry_no=$row['lorry_no'];	
					$qty=$row['qty'];	
					$flow_speed=$row['flow_speed'];	
					$smp_cnt=$row['smp_cnt'];	
					$tank_no=$row['tank_no'];	
					$mega_check=$row['mega-check'];	
					$_SESSION['lorry_no']=$row['lorry_no'];
					$_SESSION['remnant']=$row['remnant'];	
					$_SESSION['qty']=$row['qty'];
				}
    	}else{
    		$_SESSION['step']='';
    		my_msg("請重新開始","fill.php");
		   	break;
    	}





	$query="UPDATE  FILL_INDICATE SET FID_FILL_STATUS = 2, FID_FILL_END_DATE = '".date("YmdHis")."' WHERE   (FDM_LOT_NO = '".trim($_SESSION['lid'])."') ";
	$result1 = mssql_query($query);

	$smp_cnt=samp_count(trim($_SESSION['lid']));
 	$query="SELECT COUNT(FDM_LOT_NO) AS cnt FROM LORRY_FILL_CHECK where FDM_LOT_NO='".trim($_SESSION['lid'])."'"; 
//	echo $query."<BR>";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]==0){		
	if($_SESSION['bm3']==''){$_SESSION['bm3']=0;}
	if($_SESSION['em3']==''){$_SESSION['em3']=0;}
	if($_SESSION['mega_check']==''){$_SESSION['mega_check']='NULL';}else{$_SESSION['mega_check']="'".$_SESSION['mega_check']."'";}
	if($_SESSION['btk']==''){$btk='NULL';}else{$btk="'".$_SESSION['btk']."'";}
		$query="Insert Into LORRY_FILL_CHECK 	(create_date, creator, FDM_LOT_NO,  LFC_W_LY_NO,  LFC_B_REMAIN_QTY,  LFC_B_TROUGH_TOP,  LFC_B_CHK_COUP_CLR,  LFC_B_CHK_COUP,  LFC_B_CHK_COUP_LINK,  LFC_B_CHK_EXHAUST,  
				LFC_B_SET_FILL,  LFC_F_FLOW,  LFC_F_SAM_COUNT,  LFC_F_RESISTANCE,  LFC_E_OPER_FILL,  LFC_E_TROUGH_TOP,  LFC_E_CHK_AIR_SEAL,  LFC_E_CHK_FILL_CLOSE,  LFC_E_CHK_EXHAUST,  LFC_E_CHK_VALVE,  
				LFC_E_CHK_COUP_CLR,  LFC_E_CHK_COUP,  LFC_E_CHK_PIPE_PICKUP,  LFC_FILLER,  LFC_AIR_SEAL,  LFC_AIR_SEAL_START,  LFC_AIR_SEAL_END,  LFC_E_CHK_MEGA_CHECK,  LFC_E_CHK_SURFACE,  LFC_TANK, LFC_B_TROUGH_QTY, LFC_E_TROUGH_QTY) 
				VALUES ('".date("YmdHis")."','".$_SESSION['uid']."','".$_SESSION['lid']."','".trim($lorry_no)."',".$remnant.",".$m3.",'Y','Y','Y','Y',".$qty.",".$flow_speed.",".$smp_cnt.",".$btk.",".$qty.",'".$bm3."','Y','Y','Y','Y','Y','Y','Y','".$_SESSION['uid']."',".$_POST['sti0'].",'".$_POST['sti1']."','".$_POST['sti2']."','".$mega_check."','Y','".$tank_no."','".$_POST['etq']."','".$_POST['em3']."')";
//		echo $query."<BR>";

		
		$result1 = mssql_query($query);
		
		$query="INSERT INTO TLAQAFLOWSPEED (SerialNo, TestDate, LotNo, SampleNo, speed, Ok, Tester, AnalyzeTime) VALUES (1,CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102), N'".trim($_SESSION['lid'])."', '".substr(trim($_SESSION['lid']),1,2)."', ".$flow_speed.", N'1', N'".$_SESSION['uid']."', N'".date("YmdHis")."')";
		$remnant=mssql_query($query);
		
		$query="INSERT INTO ani_result_group
                            (lot_no, ani_group, result, creator, createtime)
VALUES     (N'".trim($_SESSION['lid'])."', N'FFR', '1', N'system', N'".date("YmdHis")."')"; 

		$result=mssql_query($query);

	
		if($result1){echo "1. Writting LORRY_FILL_CHECK .....<BR>";}else{ my_msg("LORRY_FILL_CHECK重複輸入",'lorry_fill.php');}
	}
	//	if($result3){echo "Writing LORRY_FILL_CHECK .....<BR>";}else{break;}
	$jj=new get_from_lot_no;
	$jj->lid=$_SESSION['lid'];
	$jj->ani();
	$pid=trim($jj->pid);
	$cid=trim($jj->cid);
		// 4. Update PRODUCT_STOCKS
	$query="select count(*) as cnt where  (STK_LOT_NO = '".trim($_SESSION['lid'])."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]>0){
		$query="Update PRODUCT_STOCKS Set STK_QTY = (".$_POST['qty']." - STK_PRE_OUT_QTY) Where STK_LOT_NO = '".$_SESSION['lid']."'";
//		echo "<BR>".$query."<BR>";
		$result4 = mssql_query($query);
	}
	else{
		$query="INSERT INTO PRODUCT_STOCKS
                   (STK_LOT_NO, PDD_PROD_NO, CTD_CUST_NO, STK_MAKE_DATE, STK_QTY, STK_DRUM_COUNT, 
                   STK_PRE_OUT_QTY, STK_PRE_OUT_DRUM_COUNT, STK_DECI_OUT_QTY)
VALUES  ('".trim($_SESSION['lid'])."', '".$pid."', '".$c."', '".date("Ymd")."', 100, 0, 0, 0, 0)";
		
		
	}
	$_SESSION['step']=='';
	my_msg($_SESSION['lid']."充填完畢","fill.php");
}  /// end $_POST['end']

	
if(isset($_POST['submit1'])) {
//	my_msg($_POST['remnant']);
	$_SESSION['remnant']=$_POST['remnant'] ;
// P2
$_SESSION['step']='sm1';
		$enablebutton= 'disabled';
refresh();
}

if(isset($_POST['submit2'])) {
$_SESSION['step']='sm2';
		$enablebutton= 'disabled';
refresh();
}

if(isset($_POST['submit3'])) {
	$_SESSION['step']='sm3';
			$enablebutton= 'disabled';
refresh();
}

if(isset($_POST['sm1'])) {
	$_SESSION['step']='sm1';
			$enablebutton= 'disabled';
refresh();
}
if(isset($_POST['sm2'])) {
	$_SESSION['step']='sm2';
			$enablebutton= 'disabled';
refresh();
}
if(isset($_POST['smp_fin'])) {
	$_SESSION['btk']=$_POST['btk'];
	$_SESSION['mega_check']=$_POST['mega_check'];  
	$_SESSION['purge_qty']=$_POST['purge_qty'];

	tank();
	refresh();
}
if(isset($_POST['finish'])) {
	finish($_SESSION['select_flow']);
	$_SESSION['source_lot']=$_POST['source_lot'];
	$_SESSION['tank_no']=$_POST['tank_no'];
//	refresh();
}

function bm3($lotno,$pcnt){
	$_SESSION['step']='bm3';
	$query="SELECT LFC_TANK FROM lorry_fill_tmp WHERE (lot_no = N'".$lotno."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$tkno=$row[0];
	if($tkno<>''){
		$query="SELECT capacity FROM TANK_DATA1 WHERE (PROD_TANK = '".$tkno."')";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$rtn=$row[0];
	}
	else{
		$rtn=100000;	
	}
	$rrt=$rtn*$pcnt/100;
	return $rrt;
}
function enter($fid,$lorry_no1){
	$_SESSION['step']='enter';
	$_SESSION['lid']=strtoupper(trim($_POST['lid']));
	$query="SELECT  COUNT(*) AS Numbers FROM FILL_INDICATE WHERE (FDM_LOT_NO = '".trim($_SESSION['lid'])."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]==0){
		$_SESSION['lid']=$_SESSION['fid']=$_SESSION['lorry_no1']='';
		my_msg("沒有充填指示");
	}
	$query="SELECT FILL_INDICATE.FID_FILL_STATUS, FILL_INDICATE.FID_FILL_BEGIN_DATE, FILLPLAN_OUT_DECIDE.FDM_LY_NO FROM FILL_INDICATE INNER JOIN FILLPLAN_OUT_DECIDE ON FILL_INDICATE.FDM_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO INNER JOIN lorry_fill_tmp ON FILL_INDICATE.FDM_LOT_NO = lorry_fill_tmp.lot_no WHERE   (FILL_INDICATE.FDM_LOT_NO ='".$_SESSION['lid']."') "; 
//	echo $query."<BR>";
//	break;
	$result=mssql_query($query);
	$row1=mssql_fetch_row($result);
	$_SESSION['lorry_no']=$row1[2];
	if($row1[0]>=1){$_SESSION['str']=substr($row1[1],8,2).":".substr($row1[1],10,2)."開始充填，充填進行中.......<BR>";finished();break;}
	$query="SELECT  FILL_INDICATE.FID_FILL_BEGIN_DATE, FILL_INDICATE.FID_FILL_END_DATE, LORRY_FILL_CHECK.FDM_LOT_NO FROM LORRY_FILL_CHECK INNER JOIN
		FILL_INDICATE ON LORRY_FILL_CHECK.FDM_LOT_NO = FILL_INDICATE.FDM_LOT_NO
		WHERE   (LORRY_FILL_CHECK.FDM_LOT_NO = '".$_SESSION['lid']."')";
//		echo $query."<BR>";
	$result=mssql_query($query);
	$num=mssql_num_rows($result);
	if($row1[0]==2 and $num>0){
		my_msg("此 LOT 已經充填完成","fill.php");
		break;
	}
	$query="SELECT          A.FOD_UNI, A.CTD_CUST_NO, B.CTD_CUST_NAME, A.FDM_SERIAL_NO, A.PDD_PROD_NO, C.PDD_PROD_NAME, 
                            C.PDD_PROD_SHORT_NAME, A.FDM_SAM_BEFORE, A.FDM_SAM_BEF_CNT, A.FDM_SAM_CNT, 
                            A.FDM_ATTACH_CNT, A.FDM_QTY_DRUM, A.FDM_QTY, D.CTP_ExportCountLimit, A.FDM_LY_NO
FROM              FILLPLAN_OUT_DECIDE AS A LEFT OUTER JOIN
                            CUSTOMER_DATA AS B ON A.CTD_CUST_NO = B.CTD_CUST_NO LEFT OUTER JOIN
                            PRODUCT_DATA AS C ON A.PDD_PROD_NO = C.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_PRODUCTS AS D ON A.CTD_CUST_NO = D.CTD_CUST_NO AND A.PDD_PROD_NO = D.PDD_PROD_NO
WHERE          (A.FDM_LOT_NO = '".trim($_SESSION['lid'])."')";
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
	$short_name=$row['PDD_PROD_SHORT_NAME'];
// P1	
	echo '品名 ： '.$prod_name.'</br>';
	echo '日期 ： '.date("Y/m/d").'</br>';
	echo '客戶 ： '.$cust_name. '</br>';
	echo '樣品瓶數 ： '.$smp_cnt.'</br>';
	echo '充填量 ： '.$fdm_qty.'</br></br>';
}

if($short_name<>trim($fid) and $lorry_no<>trim($lorry_no1))
{
	$_SESSION['lid']=$_SESSION['fid']=$_SESSION['lorry_no1']=$_POST['lid']='';
	my_msg("充填口,槽車皆錯誤，請檢查原因","fill.php");
}

// 檢查槽車
if($lorry_no<>trim($lorry_no1))
	{
		$_SESSION['lid']=$_SESSION['fid']=$_SESSION['lorry_no1']=$_POST['lid']='';
		my_msg("槽車號碼錯誤，請檢查原因","fill.php");
	}
	$query="SELECT lorry_fill_tmp.* FROM lorry_fill_tmp WHERE (lot_no = N'".$_SESSION['lid']."') ";
//	echo $query;
	$result=mssql_query($query);
	$nur=mssql_num_rows($result);
	if($nur>>0){
		finished();	
	}
	else{
	$query="select * FROM LORRY_FILL_CHECK WHERE (FDM_LOT_NO = '".$_SESSION['lid']."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows==0){
	$query1="UPDATE  FILL_INDICATE SET FID_FILL_BEGIN_DATE = '".date("YmdHis")."' WHERE (FDM_LOT_NO = '".$_SESSION['lid']."')";
//	echo $query1."<BR>";
	$result1 = mssql_query($query1);
	echo '重量確認</br>' ;
	$_SESSION['lorry_no']=$lorry_no;
	echo 'Lorry NO. ： '.$lorry_no.'</br>';
	echo '比重 ： '.literkg1($pid).'</br>';
	echo '<form id="form2" name="form2" method="post" onsubmit="return checkform2(this);" action="'.$loginFormAction.'">';
	echo '充填前殘液量 ： '.'<input type="text" name="remnant" id="remnant" autocomplete="off" style="font-size:'.($_SESSION['font_size']).'px" size="8" autofocus="autofocus" />';
	echo '<input type="submit" name="submit1" id="submit" value="  下 一 步  "/></br></font></form>';
	}
	else{my_msg("Lot 已充填過",$return_page);}
	}
}

function _flow($qty)
{
	if($qty<=950){return 1.5 ;}
	if($qty>950 and $qty<=2900){return 1;}
	if($qty>2900){return 4.5;}
}

function sm3(){
	$_SESSION['step']='sm3';
	$_SESSION['flow_speed']=$_POST['flow_speed'];
	$query="SELECT  FILL_INDICATE.*	FROM	FILL_INDICATE	WHERE   (FDM_LOT_NO = '".$_SESSION['lid']."')";
	
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows>0){
				
	}
	$query="select count(*) as cnt from sample_all where SMA_LOT='".$_SESSION['lid']."'";
	$result = mssql_query($query);
	$cnt=mssql_fetch_row($result);
	$_SESSION['smp_cnt']=$cnt[0];
	echo '<form id="form5" name="form5" method="post" action="'.$loginFormAction.'">';
//	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
//	echo '<input type="hidden" name="m3" id="m3" value="'.$_POST['m3'].'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$_POST['qty'].'" />';
//	echo '<input type="hidden" name="btk" id="btk" value="'.$_POST['btk'].'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$_POST['mega_check'].'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$_POST['purge_qty'].'" />';
	echo '<input type="hidden" name="flow_speed" value="'.$_POST['flow_speed'].'" />';
	if($cnt[0]>=$smp_cnt){
		echo '<input type="submit" name="sm2" value="上一步" /><input type="submit" name="smp_fin" autofocus="autofocus" value="  下 一 步  " /></br>';	
	}
}
	
function sm2(){
	$_SESSION['step']='sm2';
	echo '<form id="form4" name="form4" method="post" action="'.$loginFormAction.'">';
	echo '充填<br>' ;
	echo '流量： <input type="text"  autocomplete="off" name="flow_speed" value="'._flow($_POST['qty']).'"></br>比抵抗 (IPA>5.0GΩcm)：<input type="text"  autocomplete="off" name="btk" id="btk"  style="font-size:'.($_SESSION['font_size']).'px" autofocus="autofocus" value="N/A"/>';
	echo '<br> 取樣前Purge量(20KG) ： <input type="text"  autocomplete="off" name="purge_qty" id="purge_qty"   style="font-size:'.($_SESSION['font_size']).'px" size="12"  size="12"  value="20"/></br>';
	echo 'MEGA CHECK (1mΩ) ： <input type="text"  autocomplete="off" name="mega_check" id="mega_check"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  value="1"/></br>';
// 	echo '<input type="submit" style="width:100px;height:'.($_SESSION['font_size']+10).'px;border:2px orange double;" name="submit3" id="submit3" value="  下 一 步  " /><input type="submit" name="sm1" style="width:100px;height:'.($_SESSION['font_size']+10).'px;border:2px orange double;" value="上一步" /></br>';
//	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
//	echo '<input type="hidden" name="m3" id="m3" value="'.$_POST['m3'].'" />';
	echo '<input type="hidden" name="bm3" id="m3" value="'.$_POST['bm3'].'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$_POST['qty'].'" />';
	
	$_SESSION['flow_speed']=$_POST['flow_speed'];
	$query="SELECT  FILL_INDICATE.*	FROM	FILL_INDICATE	WHERE   (FDM_LOT_NO = '".$_SESSION['lid']."')";
	
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows>0){
				
	}
		$query="select count(*) as cnt from sample_all where SMA_LOT='".$_SESSION['lid']."'";
		$result = mssql_query($query);
		$cnt=mssql_fetch_row($result);
		$_SESSION['smp_cnt']=$cnt[0];
		if($cnt[0]>=$smp_cnt){
			echo '<input type="submit" name="smp_fin" autofocus="autofocus" value="  下 一 步  " /><input type="submit" name="sm1"  value="上一步" /></br>';	
		}	
	}
function sm1(){
	$_SESSION['step']='sm1';
//	$_SESSION['remnant']=$_POST['remnant'];
		
	// 2. Update LORRY_EXAMINE_LIST
	$query="INSERT INTO LORRY_EXAMINE_LIST
                            (LEL_LOT_NO, PRA_OUT_COUNT, PDD_PROD_NO, LEL_LY_NO, LEL_PDA_REMNANT_QTY) 
							VALUES          ('".$_SESSION['lid']."', 1,'".$_SESSION['pidx']."', '".substr($_SESSION['lid'],1,2).substr($_SESSION['lid'],-4,4)."',".$_SESSION['remnant'].")";
	$result2 = mssql_query($query);

	echo '<form id="form3" name="form3" method="post" onsubmit="return checkform3(this);" action="'.$loginFormAction.'">';
	echo '充填前準備</br>製品桶槽液量確認<BR>';
	echo '控制室(%)： <input type="text"  value="'.$_SESSION['m3'].'" autocomplete="off" name="m3" id="m3"  style="font-size:'.($_SESSION['font_size']).'px" size="10"   autofocus="autofocus"  onchange="set_date_session(this.name,this.value)" /><BR>';
	echo '控制室(m^3)： <input type="text"  value="'.$_SESSION['bm3'].'"  autocomplete="off" name="bm3" id="bm3"  style="font-size:'.($_SESSION['font_size']).'px" size="12"   autofocus="autofocus"  onchange="set_date_session(this.name,this.value)" /></br>';
	echo '<input type="checkbox" name="c1" id="c1" checked/>Coupler 洗淨<br>
		<input type="checkbox" name="c2" value="1" id="c2" checked/>Coupler/O-ring狀態確認<br>
		<input type="checkbox" name="c3" value="1" id="c3" checked/>Coupler接續<br>
		<input type="checkbox" name="c4" value="1" id="c4" checked/>排放容器氣壓(洩壓聲停一分鐘)<br>';
	echo '充填量設定： <input type="text"  value="'.$_SESSION['qty'].'" autocomplete="off" name="qty" id="qty"  onchange="set_date_session(this.name,this.value)"/></br>';
	echo '<input type="submit" name="submit2"  id="submit2" value="  下 一 步  "/></br>';
	echo '</form><br>';
	}
	
function tank(){
	$_SESSION['step']='tank';
	echo '<form id="form6" name="form6" method="post" action="'.$loginFormAction.'">';
	echo '<input type="hidden" name="m3" id="m3" value="'.$_POST['m3'].'" />';
	echo '<input type="hidden" name="bm3" id="m3" value="'.$_POST['bm3'].'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$_POST['qty'].'" />';
//	echo '<input type="hidden" name="btk" id="btk" value="'.$_POST['btk'].'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$_POST['mega_check'].'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$_POST['purge_qty'].'" />';
	echo '<input type="hidden" name="flow_speed" value="'.$_POST['flow_speed'].'" />';
	echo '<BR>來源 LOT NO:<input type="text" style="font-size:20" size="15" name="source_lot" value="'.$_SESSION['source_lot'].'" /><BR>';
	echo '充填來源：<select name="tank_no" style="font-size:20" id="tank_no"><option value=""></option>';

		$a= strripos($_SESSION['pidx'],"-");
		$str=substr($_SESSION['pidx'],0,$a);
		$query="SELECT  PROD_TANK, TANK_DESC FROM TANK_DATA1 WHERE   (PDD_PROD_NO LIKE '".$str."%')";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			if($_SESSION['tank_no']==$row['PROD_TANK']){$str1=' selected';}else{$str1='';}
			echo '<option value="'.$row['PROD_TANK'].'" '.$str1.'>['.$row['PROD_TANK'].']  -  ['.$row['TANK_DESC'].']</option>';
		}
	echo '</select>';
	$jj=new get_from_lot_no;
	$jj->lid=$_SESSION['lid'];
	$jj->ani();
	$pid=trim($jj->pid);
	if($_SESSION['select_series']=='2系' or $_SESSION['select_series']=='3系' or $_SESSION['select_series']=='4系'){
		$_SESSION['select_flow']='';
		echo '<BR>選擇充填路徑<table width="600" border="1" bgcolor="#CCCCCC"><tr><td width="50">系別</td><td width="100">TANK</td><td width="450">Pump + 過濾器 X 3 + 充填口</td></tr><tr><td>';
		select_series($pid);
		echo '</td><td>';
		selectx($_SESSION['select_series']);
		echo '</td></tr></table>';	
		echo '</br></br><input type="submit"  name="finish" value="   下 一 步   " /><input type="submit" name="submit3" value="    上 一 步    " /></br>';
		$_SESSION['select_flow']=$_SESSION['select_series']."/".$_SESSION['select_Tank']."/".$_SESSION['select_Pump'];
		echo '<input type="hidden" name="select_flow" value="'.$_SESSION['select_flow'].'">';
		echo '</form>';
	}else{
		echo '<BR>選擇充填路徑<table width="600" border="1" bgcolor="#CCCCCC"><tr><td width="100">系別</td><td width="100">TANK</td><td width="100">Pump</td></tr><tr><td>';
		select_series($pid);
		echo '</td><td>';

		select_Tank(trim($jj->pid));
		echo '</td><td>';
		select_Pump($pid);
		echo '<br><input type="text" size="5" name="p0" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['p0'].'">kg/cm^2';
		echo '</td></tr><tr>';
		
		echo '<td width="100">Filter 1</td><td width="100">Filter 2</td><td>充填站</td></tr><td>';
		select_Filter1($pid);
		echo '<br><input type="text" size="5" name="f1" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['f1'].'">kg/cm^2';
		echo '</td><td>';
		select_Filter2($pid);
		echo '<br><input type="text" size="5" name="f2" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['f2'].'">kg/cm^2';
		echo '</td><td>';
		select_Filter3($pid);
		echo '<br><input type="text" size="5" name="f3" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['f3'].'">kg/cm^2';
		echo '</td><td>';
		select_Spot($pid);
		echo '</td></tr></table>';	
		echo '</br></br><input type="submit"  name="finish" value="   下 一 步   " /><input type="submit" name="submit3" value="    上 一 步    " /></br>';
		$_SESSION['select_flow']=$_SESSION['select_series']."/".$_SESSION['select_Tank']."/".$_SESSION['select_Pump']."/".$_SESSION['select_Filter1']."/".$_SESSION['select_Filter2']."/".$_SESSION['select_Filter3']."/".$_SESSION['select_Spot'];
		echo '<input type="hidden" name="select_flow" value="'.$_SESSION['select_flow'].'">';
		echo '</form>';
	}
}

function finish($flow){
	$_SESSION['step']='finish';
	$jj=new get_from_lot_no;
	$jj->lid=$_SESSION['lid'];
	$jj->ani();
	$pid=trim($jj->pid);
	$cid=trim($jj->cid);
	if($_POST['select_series']<>'')
	{$flow.=trim($_POST['select_series'])."/";}
	if($_POST['select_Tank']<>'')
	{$flow.=trim($_POST['select_Tank'])."/";}
	if($_POST['select_Pump']<>'')
	{$flow.=trim($_POST['select_Pump'])."/";}
	if($_POST['select_Tank1']<>'')
	{$flow.=trim($_POST['select_Tank1'])."/";}
	if($_POST['select_Pump1']<>'')
	{$flow.=trim($_POST['select_Pump1'])."/";}
	if($_POST['select_Filter1']<>'')
	{$flow.=trim($_POST['select_Filter1'])."/";}
	if($_POST['select_Filter2']<>'')
	{$flow.=trim($_POST['select_Filter2'])."/";}
	if($_POST['select_Filter3']<>'')
	{$flow.=trim($_POST['select_Filter3'])."/";}
	if($_POST['select_Spot']<>'')
	{$flow.=trim($_POST['select_Spot'])."/";}
	$_SESSION['select_flow']=$flow=substr($flow,0,-1);
	
	if($_SESSION['select_series']<>'')
	{$pressure.="NA/";}
	if($_SESSION['select_Tank']<>'')
	{$pressure.="NA/";}
	if($_SESSION['select_Pump']<>'')
	{$pressure.=trim($_SESSION['p0'])."/";}
	if($_SESSION['select_Tank1']<>'')
	{$pressure.="NA/";}
	if($_SESSION['select_Pump1']<>'')
	{$pressure.=trim($_SESSION['p1'])."/";}
	if($_SESSION['select_Filter1']<>'')
	{$pressure.=trim($_SESSION['f1'])."/";}
	if($_SESSION['select_Filter2']<>'')
	{$pressure.=trim($_SESSION['f2'])."/";}
	if($_SESSION['select_Filter3']<>'')
	{$pressure.=trim($_SESSION['f3'])."/";}
	if($_SESSION['select_Spot']<>'')
	{$pressure.="NA/";}
	$_SESSION['pressure']=$pressure=substr($pressure,0,-1);
	
	$query="SELECT leave FROM Tank_batch where active=1 and tank_no='".$_SESSION['tank_no']."' ORDER BY [index] DESC";

	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$leave=$row[0];

	// 4. Update PRODUCT_STOCKS
	$query="select count(*) as cnt where  (STK_LOT_NO = '".trim($_SESSION['lid'])."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]>0){
		$query="Update PRODUCT_STOCKS Set STK_QTY = (".$_SESSION['qty']." - STK_PRE_OUT_QTY) Where STK_LOT_NO = '".$_SESSION['lid']."'";

		$result4 = mssql_query($query);
	}
	else{
		$query="INSERT INTO PRODUCT_STOCKS
                   (STK_LOT_NO, PDD_PROD_NO, CTD_CUST_NO, STK_MAKE_DATE, STK_QTY, STK_DRUM_COUNT, 
                   STK_PRE_OUT_QTY, STK_PRE_OUT_DRUM_COUNT, STK_DECI_OUT_QTY)
VALUES  ('".trim($_SESSION['lid'])."', '".$pid."', '".$c."', '".date("Ymd")."', 100, 0, 0, 0, 0)";
		$result4 = mssql_query($query);

	}
		
	// 6. Update PRODUCT_RUNNING_ACCOUNT
	$query="Update PRODUCT_RUNNING_ACCOUNT Set PRA_FAKE_IN_DATE2 = '".date("Ymd")."', PRA_REAL_IN_DATE = '".date("Ymd")."', PRA_REAL_IN_QTY = ISNULL(PRA_REAL_IN_QTY,0)+".$_SESSION['qty']." 
	Where PRA_LOT_NO = '".$_SESSION['lid']."' and CTD_CUST_NO = '".$cid."' and PRA_PURPOSE = 1"; 

	$result6 = mssql_query($query);
	
	// 7. 設定充填路徑
	$query="INSERT INTO dbo.Fill_Flow_Chart (Lot_No, flow, creator, date, pressure) VALUES ('".$_SESSION['lid']."','".$flow."','".$_SESSION['uid']."','".date("YmdHis")."','".$pressure."')";	
//	echo $query."<BR>";
	$result=mssql_query($query);
	
// 8. 封槽管理項目
	$query="SELECT *  FROM Tank_batch where active=1 and tank_no='".$_SESSION['tank_no']."'";

	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows>0){
			$row=mssql_fetch_row($result);
			$qty_before=$row[9];
			$lot_no1=$row[4];
			$qty_fill=$_SESSION['qty']/1000;
			$qty_new=$qty_before-$qty_fill;
			$now=date("YmdHis");
			$query="INSERT INTO Tank_Lot (Batch_Lot_No, Lot_No, fill_qty, createtime, creator, active) VALUES (N'".$lot_no1."', N'".$_SESSION['lid']."', ".$_SESSION['qty'].", N'".$now."', N'".$_SESSION['uid']."', 1)";
//			echo $query."<BR>";
			$result=mssql_query($query);
			$query="update Tank_batch set active=0  where active=1 and tank_no='".$_SESSION['tank_no']."'";
			$result=mssql_query($query);
			
			$query="INSERT INTO Tank_batch
						   (createtime, creator, lot_no, tank_no, filldatetime, memo, active, leave, PDD_PROD_NO,last_modify)
		VALUES  ('".$now."','".$row[3]."','".$lot_no1."','".$row[5]."','".$row[6]."','".$row[7]."',1,".$qty_new.",'".$row[1]."', '".$_SESSION['uid']."')";
//			echo $query."<BR>";
			$result=mssql_query($query);
			if($result<>''){
			echo "建立日期 :".$now."<BR>";
			echo "TANK NO :".$_POST['tank_no']."<BR>";
			echo "建檔人員 :".get_uname($_SESSION['uid'])."<BR>";
		}
	}
	else{echo "沒有封槽資料<BR>";}	
	//5. Update FILL_INDICATE	
	$query="Update FILL_INDICATE Set FID_FILL_BEGIN_DATE = '".date("YmdHis")."', FID_SOURCE_LOT = N'".strtoupper($_SESSION['source_lot'])."', FID_FILL_STATUS=1, FID_QTY = ".$_SESSION['qty'].", FID_WASHED_COUNT = 0, FID_OPERATOR = '".$_SESSION['uid']."'   Where FDM_LOT_NO = '".$_SESSION['lid']."'"; 

	$result5 = mssql_query($query);
	$query="select * from lorry_fill_tmp where lot_no='".trim($_SESSION['lid'])."'";

	$result=mssql_query($query);
	$numr=mssql_num_rows($result);
	if($numr==0){
		if(trim($_SESSION['btk'])=='N/A'){$_SESSION['btk']=0;}
		if($_POST['mega_check']==''){$_POST['mega_check']='NULL';}
		$query="INSERT INTO lorry_fill_tmp (lot_no, remnant, m3, btk, [mega-check], purge_qty, tank_no, LFC_TANK, flow_speed, smp_cnt, update_time, lorry_no, qty, bm3)
			VALUES  (N'".$_SESSION['lid']."', ".$_SESSION['remnant'].", ".$_SESSION['m3'].", '".$_SESSION['btk']."', ".$_SESSION['mega_check'].",".$_SESSION['purge_qty'].", N'".trim($_POST['source_lot'])."', 
			N'".trim($_POST['tank_no'])."', ".$_SESSION['flow_speed'].", ".$_SESSION['smp_cnt'].", N'".date("YmdHis")."', '".trim($_SESSION['lorry_no'])."',".$_SESSION['qty'].",".$_SESSION['bm3'].")";

		$_SESSION['step']='';
		$result=mssql_query($query);
		$query="INSERT INTO TLFLOWSPEED (TestDate, LotNo, SerialNo, SampleNo, speed, Ok, Tester, Operator, AnaManager, AnalyzeTime)
VALUES          (CONVERT(DATETIME, '".date("Y-m-d")."', 102), N'".$_SESSION['lid']."', 1, '".substr(trim($_SESSION['lorry_no']),0,2)."', ".$_SESSION['flow_speed'].", N'1', N'adm', 'adm', 'adm', 
                            N'".date("YmdHis")."')";
              
		$result=mssql_query($query);
	}
	else{
		while($row=mssql_fetch_array($result)){
			$_POST['remnant']=$row['remnant'];
		}
	}
	
	echo '<form id="form7" name="form7" method="post" onsubmit="return checkform7(this);" action="'.$loginFormAction.'">';
	echo '充填終了確認</br>製品桶槽液面確認' ;
	echo '(%)： <input type="text"  autocomplete="off" name="etq" id="m32"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  autofocus="autofocus" value="90" /></br>';
	echo '製品桶槽液面確認' ;
	echo '(m^3)： <input type="text"  autocomplete="off" name="em3" id="em3"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  autofocus="autofocus" value="" /></br>';
	echo '氣密測試(1.9kg/cm^2 10分鐘): <input type="text"  autocomplete="off" name="sti0" id="sti0"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  value="1.9" /><br>';
	echo '氣密測試開始時間 : <input type="text"  autocomplete="off" name="sti1" id="sti1"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  value="" /><br>';
	echo '氣密測試結束時間 : <input type="text"  autocomplete="off" name="sti2" id="sti2" value=""  style="font-size:'.($_SESSION['font_size']).'px" size="12" /><br>';
	echo '充填路徑： <input type="text"  autocomplete="off" name="flow" value="'.$_SESSION['select_flow'].'"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  readonly="readonly"/></br>';
	echo '<input type="hidden" name="remnant" id="remnant" value="'.$_POST['remnant'].'"/>';
	echo '<input type="hidden" name="m3" id="m3" value="'.$_POST['m3'].'" />';
	echo '<input type="hidden" name="bm3" id="m3" value="'.$_POST['bm3'].'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$_POST['qty'].'" />';
	echo '<input type="hidden" name="btk" id="btk" value="'.$_POST['btk'].'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$_POST['mega_check'].'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$_POST['purge_qty'].'" />';
	echo '<input type="hidden" name="rd" id="rd" value="'.$_POST['rd'].'" />';
	echo '<input type="hidden" name="tank_no" value="'.$_POST['tank_no'].'" />';
	echo '<input type="hidden" name="flow_speed" value="'.$_POST['flow_speed'].'" />';
	echo '<input type="checkbox" name="d1" id="c1" checked="checked" />HOSE外觀檢查<br>
		<input type="checkbox" name="d2" value="1" id="c2"  checked="checked" />充填閥關閉<br>
		<input type="checkbox" name="d3" value="1" id="c3"  checked="checked" />容器氣壓排放確認(洩壓聲停一分鐘)<br>
		<input type="checkbox" name="d4" value="1" id="c3"  checked="checked" />容器(氣、液)閥緊閉確認<br>
		<input type="checkbox" name="d5" value="1" id="c3"  checked="checked" />Coupler取出洗淨<br>
		<input type="checkbox" name="d6" value="1" id="c3"  checked="checked" />Coupler/O-ring狀態確認<br>
		<input type="checkbox" name="d7" value="1" id="c4"  checked="checked" />軟管收納<br>';
	echo '</br></br><input type="submit" name="end" value="  完成/結束  " /><input type="submit"  name="smp_fin" value="上一步" /></br>';
	echo '</form>';	
}

function finished()
{
	$_SESSION['step']='finished';
	$smp_cnt=samp_count($_POST['lid']);
	$query="select * from lorry_fill_tmp where lot_no='".$_SESSION['lid']."'";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$remnant=$row['remnant'];
		$m3=$row['m3'];
		$btk=$row['btk'];
		$megacheck=$row['mega-check'];
		$purge_qty=$row['purge_qty'];
		$tank_no=$row['tank_no'];
		$LFC_TANK=$row['LFC_TANK'];
		$flow_speed=$row['flow_speed'];
		$smp_cnt=$row['smp_cnt'];
		$_SESSION['lorry_no']=$lorry_no=$row['lorry_no'];
		$qty=$row['qty'];
		$bm3=$row['bm3'];
	}
	
	$query="select * FROM LORRY_FILL_CHECK WHERE (FDM_LOT_NO = '".$_SESSION['lid']."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows==0){
		$_SESSION['lorry_no']=$lorry_no;
	}
	else
	{
		my_msg("Lot 已充填過",$return_page);
	}
	
	echo '<form id="form7" name="form7" method="post" onsubmit="return checkform7(this);" action="'.$loginFormAction.'">';
	echo $_SESSION['lid']." : ".$_SESSION['str'];
	echo '充填終了確認</br>製品桶槽液面確認' ;
	echo '(%)： <input type="text"  autocomplete="off" name="etq" id="m32"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  autofocus="autofocus" value="90" /></br>';
	echo '製品桶槽液面確認' ;
	echo '(m^3)： <input type="text"  autocomplete="off" name="em3" id="em3"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  autofocus="autofocus" value="" /></br>';
	echo '氣密測試(1.9kg/cm^2 10分鐘): <input type="text"  autocomplete="off" name="sti0" id="sti0"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  value="1.9" /><br>';
	echo '氣密測試開始時間 : <input type="text"  autocomplete="off" name="sti1" id="sti1"  style="font-size:'.($_SESSION['font_size']).'px" size="12"  value="" /><br>';
	echo '氣密測試結束時間 : <input type="text"  autocomplete="off" name="sti2" id="sti2" value=""  style="font-size:'.($_SESSION['font_size']).'px" size="12" /><br>';
	/*
	echo '<input type="hidden" name="remnant" id="remnant" value="'.$remnant.'"/>';
	echo '<input type="hidden" name="m3" id="m3" value="'.$m3.'" />';
	echo '<input type="hidden" name="qty" id="qty" value="'.$qty.'" />';
	echo '<input type="hidden" name="btk" id="btk" value="'.$btk.'" />';
	echo '<input type="hidden" name="mega_check" id="mega_check" value="'.$megacheck.'" />';
	echo '<input type="hidden" name="purge_qty" id="qty" value="'.$purge_qty.'" />';
	*/
	echo '<input type="hidden" name="rd" id="rd" value="'.$LFC_TANK.'" />';
	/*
	echo '<input type="hidden" name="tank_no" value="'.$tank_no.'" />';
	echo '<input type="hidden" name="flow_speed" value="'.$flow_speed.'" />';
	echo '<input type="hidden" name="lorry_no" value="'.$lorry_no.'" />';

	echo '<input type="hidden" name="bm3" id="m3" value="'.$bm3.'" />';
*/
	
	echo '<input type="checkbox" name="d1" id="c1" checked="checked" />HOSE外觀檢查<br>
		<input type="checkbox" name="d2" value="1" id="c2"  checked="checked" />充填閥關閉<br>
		<input type="checkbox" name="d3" value="1" id="c3"  checked="checked" />容器氣壓排放確認(洩壓聲停一分鐘)<br>

		<input type="checkbox" name="d4" value="1" id="c3"  checked="checked" />容器(氣、液)閥緊閉確認<br>
		<input type="checkbox" name="d5" value="1" id="c3"  checked="checked" />Coupler取出洗淨<br>
		<input type="checkbox" name="d6" value="1" id="c3"  checked="checked" />Coupler/O-ring狀態確認<br>
		<input type="checkbox" name="d7" value="1" id="c4"  checked="checked" />軟管收納<br>';
	echo '</br></br><input type="submit"  name="end" value="  完成/結束  " /></br>';
	echo '</form>';	
}


function select_series($pid){
	echo '<select name="select_series" id="select_series"  onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT series FROM FILL_Series WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['series'])==$_SESSION['select_series']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['series'].'" '.$select.'>'.$row['series'].'</option>';
	}
	echo '</select>';
}

function select_Tank($pid){
	echo '<select name="select_Tank" id="select_Tank"  onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT PROD_TANK FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PROD_TANK'])==$_SESSION['select_Tank']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PROD_TANK'].'" '.$select.'>'.$row['PROD_TANK'].'</option>';
	}
	echo '</select>';
}

function selectx($series){
	echo '<select name="select_Tank" id="select_Tank"  onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query_flow="SELECT DISTINCT SUBSTRING(flow, CHARINDEX('/', flow) + 1, CHARINDEX('/', flow, CHARINDEX('/', flow) + 1) - CHARINDEX('/', flow) - 1) AS Expr1 FROM Fill_Flow WHERE (flow LIKE '".$series."%')";
		$result_flow=mssql_query($query_flow);
		$numrow_flow=mssql_num_rows($result_flow);
		if($numrow_flow > 0 ){
			while($row_flow=mssql_fetch_array($result_flow)){
				$string=trim($row_flow['Expr1']);
				if($string==$_SESSION['select_Tank']){$select='selected';}
				else{$select='';}
				echo '<option value="'.$string.'" '.$select.'>'.$string.'</option>';
			}
		}
		echo '</select>';
	echo '</td><td>';
	$S1=trim($series)."/".trim($_SESSION['select_Tank']) ;
	echo '<select name="select_Pump" id="select_Pump"  onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query_flow="SELECT SUBSTRING(flow, CHARINDEX('/', flow, CHARINDEX('/', flow) + 1) + 1, LEN(flow) ) AS ExtractedText FROM Fill_Flow WHERE (flow LIKE '".$S1."%')";
	
		$result_flow=mssql_query($query_flow);
		$numrow_flow=mssql_num_rows($result_flow);
		if($numrow_flow > 0 ){
			while($row_flow=mssql_fetch_array($result_flow)){
				$string=trim($row_flow['ExtractedText']);
				if($string==$_SESSION['select_Pump']){$select='selected';}
				else{$select='';}
				echo '<option value="'.$string.'" '.$select.'>'.$string.'</option>';
			}
		}
	echo '</select>';	
	echo '<br>Pump<input type="text" size="1" name="p0" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['p0'].'">
	F1<input type="text" size="1" name="f1" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['f1'].'">
	F2<input type="text" size="1" name="f2" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['f2'].'">
	F3<input type="text" size="1" name="f3" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['f3'].'">kg/cm^2';
	echo '</td></tr></table>';
			
}


function select_Tank1($pid){
	echo '<select name="select_Tank1" id="select_Tank1"  onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT PROD_TANK FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PROD_TANK'])==$_SESSION['select_Tank1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PROD_TANK'].'" '.$select.'>'.$row['PROD_TANK'].'</option>';
	}
	echo '</select>';
}

function select_Pump($pid){
	echo '<select name="select_Pump" id="select_Pump"  onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PumpNo'])==$_SESSION['select_Pump']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PumpNo'].'" '.$select.'>'.$row['PumpNo'].'</option>';
	}
	echo '</select>';
}

function select_Pump1($pid){
	echo '<select name="select_Pump1" id="select_Pump1" onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PumpNo'])==$_SESSION['select_Pump1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PumpNo'].'" '.$select.'>'.$row['PumpNo'].'</option>';
	}
	echo '</select>';
}

function select_Filter1($pid){
	echo '<select name="select_Filter1" id="select_Filter1" onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Filter2($pid){
	echo '<select name="select_Filter2" id="select_Filter2" onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter2']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Filter3($pid){
	echo '<select name="select_Filter3" id="select_Filter3" onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter3']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Spot($pid){
	echo '<select name="select_Spot" id="select_Spot" onchange="set_date_session(this.name,this.value)">';
	echo '<option value=""></option>';
	$query="SELECT SpotNo FROM Fill_Spot WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['SpotNo'])==$_SESSION['select_Spot']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['SpotNo'].'" '.$select.'>'.$row['SpotNo'].'</option>';
	}
	echo '</select>';
}

if(isset($_POST['save'])){
/*
	$query="select count(*) FROM dbo.Fill_Flow_Chart where Lot_No='".$_GET['lot_no']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$n=$row[0];
*/
	$query="INSERT INTO dbo.Fill_Flow_Chart (Lot_No, flow, creator, date) VALUES ('".$_SESSION['lot_no']."','".$_SESSION['flow']."','".$_SESSION['uid']."','".date("YmdHis")."')";	
	$result=mssql_query($query);
	echo "完成";
	echo '<script type="text/javascript">window.close()</script>';
}

function samp_count($lid){
	$query="select count(*) as aaaa from Sample_All where SMA_LOT = '".$lid."'";
//	echo "<BR>".$query."<BR>";
	$result=mssql_query($query);
	$row2=mssql_fetch_row($result);
	return $row2[0];
}
if(isset($_POST['rtmain'])){
	$_SESSION['step']='';
	jumpto("fill.php");
}

?>


<BR>
<form id="formx" method="post" action="" >
	<input type="submit" name="rtmain" value="返回充填目錄">
</form>
