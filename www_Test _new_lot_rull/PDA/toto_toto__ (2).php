<?php   ///last edit by jtsai 2018-03-09 
session_start();
unset($_SESSION['urln1']);
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
$path_root=$_SERVER['HTTP_HOST'];
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

	/*
	echo "F1:".$_POST['f1']."<BR>";
	echo "F2:".$_POST['f2']."<BR>";
	echo "F3:".$_POST['f3']."<BR>";
	echo "F4:".$_POST['f4']."<BR>";
	echo "F5:".$_POST['f5']."<BR>";
	echo "F6:".$_POST['purge_qty']."<BR>";
	echo "F7:".$_POST['f7']."<BR>";
	echo "Stock TOTO:". $_SESSION['stock_toto']."<BR>";
	echo "FOD TOTO:". $_SESSION['fod_toto']."<BR>";
	echo "FOD lot:". $_SESSION['fod_lot']."<BR>";
	echo "Stock lot:". $_SESSION['stock_lot']."<BR>";
	echo "移液前:".$_SESSION['T2M_W_I_WEIGHT']."<BR>";
	echo "移液前:".$_SESSION['T2M_W_O_WEIGHT']."<BR>";
	*/
	$_SESSION['now']=$now=date("YmdHis");
	$_SESSION['now1']=$now1=date("hi");
	$query="SELECT  FID_SAM_COUNT, FID_FILL_BEGIN_DATE, FID_FILL_END_DATE FROM FILL_INDICATE WHERE   (FDM_LOT_NO = '".$_GET['fod_lot']."')";
	$result0=mssql_query($query);
	$row=mssql_fetch_row($result0);
	$cnt=$row[0];
	
	if(trim($row[1])=='' and trim($row[2])==''){
		echo '
		<form name="form1" method="post" action="'.$loginFormAction.'">
<font color="#FF00FF" >CAL 在庫 TOTO 轉 出荷 TOTO 移液作業</font><BR>
充填前準備<input type="checkbox" name="f1" value="Y" checked="checked"/><BR>
充填閥關閉確認<input type="checkbox" name="f2" value="Y" checked="checked" /><BR>
充填移液管<input type="checkbox" name="f3" value="Y" checked="checked" /><BR>
部品/O型環檢查<input type="checkbox" name="f4" value="Y" checked="checked" /><BR>
移液管密合確認<input type="checkbox" name="f5" value="Y" checked="checked" /><BR>
管路PURGE<input type="text" style="font-size:20px" name="purge_qty" autocomplete="off" onchange="set_date_session(this.name,this.value)" value="'.$_SESSION['purge_qty'].'"/><BR>
充填管密合確認<input type="checkbox" name="f7" value="Y" checked="checked" /><BR>
<input type="submit" name="start" autofocus="autofocus" style="font-size:20px" value=" 開始充填 " ></td></tr>

</form>
<a href="toto_toto.php"><strong> 取消</strong></a>
';

	}
	elseif(trim($row[1])<>'' and trim($row[2])==''){
		echo '
		<form name="form1" method="post" action="'.$loginFormAction.'">
<font color="#FF00FF" >CAL 在庫 TOTO 轉 出荷 TOTO 移液作業</font><BR>
充填終了<BR>
關閉AIR,幫浦,充填閥<input type="checkbox" name="f2" value="Y" checked="checked" /><BR>
TOTO外觀檢查<input type="checkbox" name="f3" value="Y" checked="checked" /><BR>
容器上蓋緊閉確認(扭力板手400KG)<input type="checkbox" name="f4" value="Y" checked="checked" /><BR>
充填管收納<input type="checkbox" name="f5" value="Y" checked="checked" /><BR>
<input type="submit" name="finish" autofocus="autofocus" style="font-size:20px" value=" 充填結束 " ></td></tr>
</form>
<a href="toto_toto.php"><strong> 取消</strong></a>
';
	}
	
	
if(isset($_POST['finish'])){
		
		$query="UPDATE  IN2OUT_CAL_TOTO_CHECK
				SET           T2M_F_END_TIME = '".$now1."', T2M_E_I_CHK_SURFACE = 'Y', T2M_E_O_CHK_SURFACE = 'Y', 
                   T2M_E_I_CHK_CLOSE_CAP = 'Y', T2M_E_O_CHK_CLOSE_CAP = 'Y', T2M_E_I_CHK_PIPE_PICKUP = 'Y', 
                   T2M_E_O_CHK_PIPE_PICKUP = 'Y', T2M_C_QTY_CHECKER = 'Y', T2M_C_CHK_PACKAGE = 'Y' 
				   WHERE   (FDM_LOT_NO = '".$_GET['fod_lot']."')";
		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);

		$query="UPDATE  FILL_INDICATE SET FID_FILL_END_DATE ='".$now."' WHERE (FDM_LOT_NO = '".$_GET['fod_lot']."')";
		echo "<BR>".$query."<BR>";
		$result1=mssql_query($query);
		unset($_SESSION['stock_lot']);
		unset($_SESSION['stock_toto']);
		unset($_SESSION['fod_toto']);
		unset($_SESSION['T2M_W_I_WEIGHT']);
		unset($_SESSION['T2M_W_O_WEIGHT']);		
		my_msg($_SESSION['fod_lot']."充填完畢","toto_toto.php");
}

if(isset($_POST['start'])){
	$query="INSERT INTO IN2OUT_CAL_TOTO_CHECK
					   (FDM_LOT_NO, T2M_LOT_NO, T2M_I_DRUM_NO, T2M_O_DRUM_NO, T2M_W_I_CHK_SURFACE, 
					   T2M_W_O_CHK_SURFACE, T2M_W_I_WEIGHT, T2M_W_O_WEIGHT, T2M_B_CHK_FILL_CLOSE, T2M_B_CHK_FILL_PIPE, 
					   T2M_B_I_CHK_WASHER, T2M_B_O_CHK_WASHER, T2M_B_I_CHK_FILL_PIPE, T2M_B_O_CHK_FILL_PIPE, 
					   T2M_F_CHK_OPEN_PUMP, T2M_F_BEGIN_TIME, T2M_F_END_TIME, T2M_F_CHK_CLOSE_PUMP, T2M_F_SAM_PURGE, 
					   T2M_F_SAM_COUNT, T2M_E_I_CHK_SURFACE, T2M_E_O_CHK_SURFACE, T2M_E_I_CHK_CLOSE_CAP, 
					   T2M_E_O_CHK_CLOSE_CAP, T2M_E_I_CHK_PIPE_PICKUP, T2M_E_O_CHK_PIPE_PICKUP, T2M_FILLER, 
					   T2M_C_CHK_PACKAGE, T2M_C_O_WEIGHT, T2M_C_QTY_CHECKER)
				VALUES  ('".$_GET['fod_lot']."','".$_GET['stock_lot']."','".$_GET['stock_toto']."','".$_GET['fod_toto']."','Y','Y',".$_GET['T2M_W_I_WEIGHT'].",".$_GET['T2M_W_O_WEIGHT'].",'Y','Y','Y','Y','Y','Y','Y','".$now1."','','Y',".$_POST['purge_qty'].",".$cnt.",'N','N','N','N','N','N','".$_SESSION['uid']."','N',0,'N')";
//		echo "<BR>".$query."<BR>";
		echo '<BR>';
		$result=mssql_query($query);
		if(!$result){
			echo '1新增失敗!!';}
		else{
			echo '1新增完成!!';}
			echo '<BR>';
		$query="select PDD_PROD_NO from FILLPLAN_OUT_DECIDE where FDM_LOT_NO like '%".$_GET['fod_lot']."%'";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$PDD_PROD_NO=trim($row[0]);
		}
		$query="SELECT * FROM LORRY_EXAMINE_LIST where LEL_LOT_NO LIKE '%".$_GET['fod_lot']."%'";
		$result=mssql_query($query);
		$Numrows=mssql_num_rows($result);
		if($Numrows==0){
		$query="insert into LORRY_EXAMINE_LIST (LEL_LOT_NO, PRA_OUT_COUNT, PDD_PROD_NO, LEL_LY_NO) VALUES
		('".$_GET['fod_lot']."',1,'".$PDD_PROD_NO."','".$_GET['lorry_no']."')";
		$result=mssql_query($query);
		}
		else
		{
		$query="UPDATE LORRY_EXAMINE_LIST set LEL_LOT_NO='".$_GET['fod_lot']."', PRA_OUT_COUNT=1, PDD_PROD_NO='".$PDD_PROD_NO."', LEL_LY_NO='".$_GET['lorry_no']."' where (LEL_LOT_NO LIKE '%".$_GET['fod_lot']."%')";
		$result=mssql_query($query);
		}	
		$query="UPDATE  FILL_INDICATE SET FID_FILL_BEGIN_DATE ='".$now."' , FID_FILL_END_DATE ='' WHERE (FDM_LOT_NO = '".$_SESSION['fod_lot']."')";
	//	echo "<BR>".$query."<BR>";
		
		$result1=mssql_query($query);
		if(!$result1){
			echo '2 更新失敗!!';}
		else{
			echo '2 更新完成!!';}	
refresh();
}
?>