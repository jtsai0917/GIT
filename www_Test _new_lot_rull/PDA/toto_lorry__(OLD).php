<?php   ///last edit by jtsai 2018-03-09 
session_start();
unset($_SESSION['urln1']);
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
$path_root=$_SERVER['HTTP_HOST'];
$pwa= substr($uurrll=$_SERVER['REQUEST_URI'],5);
if(trim($_SESSION['stock_lot'])==''){$str1='autofocus="autofocus"';$str2='';$str3='';}
if(trim($_SESSION['fod_lot'])==''){$str1='';$str2='autofocus="autofocus"';$str3='';}
if(trim($_SESSION['stock_lot'])<>'' and trim($_SESSION['fod_lot'])<>''){$str1='';$str2='';$str3='autofocus="autofocus"';}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<style type="text/css">
body,td,th {
	font-size:20px;
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


<?php

	$_SESSION['now']=$now=date("YmdHis");
	$_SESSION['now1']=$now1=date("hi");
	$query="SELECT  IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM.* FROM IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM where fdm_lot_no='".$_SESSION['fod_lot']."'";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	
	
	$query="SELECT  FID_SAM_COUNT, FID_FILL_BEGIN_DATE, FID_FILL_END_DATE FROM FILL_INDICATE WHERE   (FDM_LOT_NO = '".$_SESSION['fod_lot']."')";
	$result0=mssql_query($query);
	$row=mssql_fetch_row($result0);
	$cnt=$row[0];
	if(trim($row[1])=='' and trim($row[2])==''){
		echo '
		<form name="form1" method="post" action="'.$pwa.'">
<font color="#FF00FF" >CAL 在庫 TOTO 轉 出荷 LORRY 移液作業</font><BR>
充填<BR>
<input type="checkbox" name="f1" checked="checked" />開啟AIR幫浦<BR>
取樣前PURGE量<BR>(10L以上)<input type="text" style="font-size:20px" name="purge_qty" autocomplete="off" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['purge_qty'].'"/><BR>
<input type="submit" name="start" autofocus="autofocus" style="font-size:20px" value=" 開始充填 " ></td></tr>
</form>
<a href="toto_lorry.php"><strong> 取消</strong></a>
';

	}
	elseif(trim($row[1])<>'' and trim($row[2])==''){
		echo '
		<form name="form1" method="post" action="'.$pwa.'">
<font color="#FF00FF" >CAL 在庫 TOTO 轉 出荷 LORRY 移液作業</font><BR>
<BR>
TOTO Lot No: <input type="text" name="TOTO_Lot_No" style="font-size:20px" size="10"><BR>
TOTO NO: <input type="text" name="TOTO_No" style="font-size:20px" size="10"><BR>
<input type="checkbox" name="f3" checked="checked" />容器外觀/期限檢查<BR>
<input type="checkbox" name="f4" checked="checked" />移液管檢查/密合確認<BR>
開啟 AIR 幫浦時間(分): <input type="text" name="air_minutes" size="10" style="font-size:20px" ><BR>
<input type="submit" name="next" autofocus="autofocus" style="font-size:20px" value=" 下一桶 " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="finish" style="font-size:20px" value=" 充填結束 " >
</form>
<a href="toto_lorry.php"><strong> 取消</strong></a>
';
	}
if(isset($_POST['next'])){
	if($_POST['f3']=='on' and $_POST['f4']=='on')
	{
		$query="INSERT INTO IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM
                (FDM_LOT_NO, T3D_LOT_NO, T3D_DRUM_NO, T3D_W_CHK_SURFACE, T3D_B_CHK_FILL_PIPE, T3D_F_TIME)
			VALUES  ('".$_GET['fod_lot']."','".$_POST['TOTO_Lot_No']."','".$_POST['TOTO_No']."','Y','Y', '".$_POST['air_minutes']."')";
		$result=mssql_query($query);
	}

}

if(isset($_POST['finish'])){
		
		$query="UPDATE  IN2OUT_CAL_TOTO_CHECK
				SET           T2M_F_END_TIME = '".$now1."', T2M_E_I_CHK_SURFACE = 'Y', T2M_E_O_CHK_SURFACE = 'Y', 
                   T2M_E_I_CHK_CLOSE_CAP = 'Y', T2M_E_O_CHK_CLOSE_CAP = 'Y', T2M_E_I_CHK_PIPE_PICKUP = 'Y', 
                   T2M_E_O_CHK_PIPE_PICKUP = 'Y', T2M_C_QTY_CHECKER = 'Y', T2M_C_CHK_PACKAGE = 'Y' 
				   WHERE   (FDM_LOT_NO = '".$_SESSION['fod_lot']."')";
		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);

		$query="UPDATE  FILL_INDICATE SET FID_FILL_END_DATE ='".$now."' WHERE (FDM_LOT_NO = '".$_SESSION['fod_lot']."')";
		echo "<BR>".$query."<BR>";
		$result1=mssql_query($query);
		unset($_SESSION['stock_lot']);
		unset($_SESSION['stock_toto']);
		unset($_SESSION['fod_toto']);
		unset($_SESSION['T2M_W_I_WEIGHT']);
		unset($_SESSION['T2M_W_O_WEIGHT']);
		my_msg($_SESSION['fod_lot']."充填完畢","toto_lorry.php");
}

if(isset($_POST['start'])){
	$query="INSERT INTO IN2LORRY_CAL_TOTO_CHECK
                   (FDM_LOT_NO, T3M_LY_NO, T3M_W_CHK_LY_SURFACE, T3M_W_LY_WEIGHT, T3M_W_LY_WEIGHT2, 
                   T3M_B_CHK_FILL_CLOSE, T3M_B_CHK_COUP_CLR, T3M_B_CHK_COUP, T3M_B_CHK_COUP_LINK, 
                   T3M_B_CHK_DM_REL, T3M_B_SET_FILL, T3M_F_CHK_OPEN_PUMP, T3M_F_SAM_COUNT, T3M_F_SAM_PURGE, 
                   T3M_E_CHK_CLOSE_PUMP, T3M_E_CHK_AIR_SEAL, T3M_E_CHK_EXHAUST, T3M_E_CHK_VALVE, T3M_E_CHK_COUP, 
                   T3M_E_CHK_PIPE_PICKUP, T3M_FILLER, T3M_C_O_WEIGHT)
			VALUES  ('".$_GET['fod_lot']."','".$_GET['lorry_no']."','Y',0,0,'Y','Y','Y','Y','Y',".$_SESSION['c7'].",'Y',5,10,'Y','Y','Y','Y','Y','Y','".$_SESSION['uid']."',0)";
		echo '<BR>';
		$result=mssql_query($query);
		if(!$result){
			echo '1新增失敗!!';}
		else{
			echo '1新增完成!!';}
			echo '<BR>';
		$query="select PDD_PROD_NO from OUT_PRODUCT where OPD_LOT_NO like '%".$_GET['fod_lot']."%'";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$PDD_PROD_NO=trim($row[0]);
		}
		$query="insert into LORRY_EXAMINE_LIST (LEL_LOT_NO, PRA_OUT_COUNT, PDD_PROD_NO, LEL_LY_NO) VALUES
		('".$_GET['fod_lot']."',1,'".$PDD_PROD_NO."','".$_GET['lorry_no']."')";
		$result=mssql_query($query);	
		
		$query="UPDATE  FILL_INDICATE SET FID_FILL_BEGIN_DATE ='".$now."' , FID_FILL_END_DATE ='' WHERE (FDM_LOT_NO = '".$_SESSION['fod_lot']."')";
	//	echo "<BR>".$query."<BR>";
		
		$result1=mssql_query($query);
		if(!$result1){
			echo '2 更新失敗!!';}
		else{
			echo '2 更新完成!!';}	
	jumpto($pwa);
}
echo '<BR>Records:<BR><table width="300" border="1">';
$qq="SELECT T3D_LOT_NO, T3D_DRUM_NO FROM IN2LORRY_CAL_TOTO_DRUM_CHECK_DRUM WHERE   (FDM_LOT_NO = '".$_GET['fod_lot']."')";
$res=mssql_query($qq);
while($row=mssql_fetch_array($res)){
	echo '<tr><td>'.$row['T3D_LOT_NO'].'</td><td>'.$row['T3D_DRUM_NO'].'</td></tr>';
}
echo '</table>';
?>

