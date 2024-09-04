<?php
session_start();  
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
auth_PDA('20-01',$_SESSION['aut']);
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
else{$return_page="index.php";}
if($_GET['lid']<>''){$_SESSION['lid']=$_GET['lid'];}

?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>Lorry 充填</title>
顯示本頁表示，表示充填儲存失敗
<form name="form1" method="post" action="">
  <p>Lot No
    <label for="Lotno"></label>
  <input type="text" name="Lotno" id="Lotno" value="<?php echo $_SESSION['lid'];?>">
  </p>
  <p>LorryNo 
    <input type="text" name="lorry_no" value="<?php echo $_SESSION['lorry_no'];?>">
  </p>
   <p>殘量 
    <input type="text" name="rem" id="rem">
  </p>
  <p>取樣數
    <input type="text" name="smp_cnt" value="<?php echo $_SESSION['smp_cnt'];?>">
  </p>
   <p>開始液位(%) 
    <input type="text" name="m3"  value="<?php echo $_SESSION['m3'];?>">
  </p>
  <p>開始液位(m3) 
    <input type="text" name="bm3" id="bm3">
  </p>
   <p>結束液位(%) 
    <input type="text" name="etq" id="rem">
  </p>
  <p>結束液位(m3) 
    <input type="text" name="em3" id="rem">
  </p>
  <p>充填量設定
    <input type="text" name="qty"  value="<?php echo $_SESSION['qty'];?>">
  </p>
  <p>流量
    <input type="text" name="flow_speed"  value="<?php echo $_SESSION['flow_speed'];?>">
  </p>
  <p>比抵抗 (可為空格) 
    <input type="text" name="btk" id="rem">
  </p>
  <p>充填手工號 
    <input type="text" name="uid" id="rem">
  </p>
  <p>氣密測試1.9 
    <input type="text" name="sti0" id="rem" value="1.9">
  </p>
   <p>氣密測試開始時間
    <input type="text" name="sti1" id="rem" value="0800">
  </p>
   <p>氣密測試結束時間 
    <input type="text" name="sti2" id="rem" value="0810">
  </p>
  <p>MEGA_CHECK (可為空格)
    <input type="text" name="mega_check" id="rem" value="">
  </p>
  <p>來源TANK 
    <input type="text" name="tank_no" id="rem" value="">
  </p>
  <input type="submit" name="submit" value="確定">
</form>
<?php
if(isset($_POST['submit'])){
	if($_POST['mega_check']==''){$_POST['mega_check']='NULL';}
	if($_POST['btk']==''){$_POST['btk']='NULL';}
		$query="Insert Into LORRY_FILL_CHECK 	(FDM_LOT_NO,  LFC_W_LY_NO,  LFC_B_REMAIN_QTY,  LFC_B_TROUGH_TOP,  LFC_B_CHK_COUP_CLR,  LFC_B_CHK_COUP,  LFC_B_CHK_COUP_LINK,  LFC_B_CHK_EXHAUST,  
				LFC_B_SET_FILL,  LFC_F_FLOW,  LFC_F_SAM_COUNT,  LFC_F_RESISTANCE,  LFC_E_OPER_FILL,  LFC_E_TROUGH_TOP,  LFC_E_CHK_AIR_SEAL,  LFC_E_CHK_FILL_CLOSE,  LFC_E_CHK_EXHAUST,  LFC_E_CHK_VALVE, LFC_E_CHK_COUP_CLR,  LFC_E_CHK_COUP,  LFC_E_CHK_PIPE_PICKUP,  LFC_FILLER,  LFC_AIR_SEAL,  LFC_AIR_SEAL_START,  LFC_AIR_SEAL_END,  LFC_E_CHK_MEGA_CHECK,  LFC_E_CHK_SURFACE,  LFC_TANK, LFC_B_TROUGH_QTY, LFC_E_TROUGH_QTY) 
				VALUES ('".$_POST['Lotno']."','".trim($_POST['lorry_no'])."',".trim($_POST['rem']).",".trim($_POST['m3']).",'Y','Y','Y','Y',".trim($_POST['qty']).",".trim($_POST['flow_speed']).",".trim($_POST['smp_cnt']).",".trim($_POST['btk']).",".trim($_POST['qty']).",'".$_POST['etq']."','Y','Y','Y','Y','Y','Y','Y','".$_POST['uid']."',".$_POST['sti0'].",'".$_POST['sti1']."','".$_POST['sti2']."',".$_POST['mega_check'].",'Y','".trim($_POST['tank_no'])."',".$_POST['bm3'].",".$_POST['em3'].")";
//		echo $query ;
//		break;
		$result1 = mssql_query($query);
}
?>