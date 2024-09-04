<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
datepick();
if($_SESSION['nono']==''){
	$sno1=' audofocus="autofucos" ';	
}
elseif($_SESSION['nono']<>'' and $_SESSION['lot_id']==''){
	$sno1='';
	$sno2=' audofocus="autofucos" ';	
}
elseif($_SESSION['nono']<>'' and $_SESSION['lot_id']<>''){
	$sno1='';
	$sno2='';
	$sno3=' audofocus="autofucos" ';	
}
 $dat=getdate(mon)."/".getdate(mday)."/".getdate(year);
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>無標題文件</title>
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

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
    <label for="lot_no"></label>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<BR />
  DRUM 出貨檢查作業.
  <br>
   出荷日期: <input type="text" autocomplete="off" name="toto_no" id="toto_no" style="font-size:25px" value="<?PHP echo date("Ymd") ?>" size="10" onchange="set_date_session(this.name,this.value)"/ readonly>
  <br>
   內銷.<br>
出荷決定書NO.: <BR /><input type="text"  autocomplete="off"  name="nono" id="nono" <?php echo $sno1;?> style="font-size:25px" size="12" value="<?PHP echo $_SESSION['nono'];  ?>" onchange="set_date_session(this.name,this.value)"/>
				<input type="submit" style="font-size:25px" name="enter" id="enter" <?php echo $sno2;?>  value="送出" /><br>
</form>
<?php 

if(isset($_POST['enter']))
{	
	echo '<form id="form2" name="form2" method="post" action="pda_drum_.php">';
	$_SESSION['PDD']='';
	$_SESSION['QTY']='';
		$_SESSION['nono']=$_POST['nono'] ;
		$chksql="SELECT          CUSTOMER_DATA.CTD_CUST_NAME, CUSTOMER_DATA.CTD_CUST_NO, PRODUCT_DATA.PDD_PROD_NAME, 
                            PRODUCT_DATA.PDD_PROD_NO, OUT_PRODUCT.OPD_LOT_NO
FROM              OUT_DECISION INNER JOIN
                            OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO INNER JOIN
                            CUSTOMER_DATA ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
WHERE          OUT_DECISION.OTD_NO ='".$_SESSION['nono']."'";
//  echo $chksql;
	$resultsql = mssql_query($chksql);
	$numRowssql = mssql_num_rows($resultsql);
	$selectlot='<select name="lot_id" style="font-size:20px">';
	while($rowsql=mssql_fetch_array($resultsql)){
		$_SESSION['cust_id']=$rowsql['CTD_CUST_NO'];
		$_SESSION['cidcname']=$rowsql['CTD_CUST_NO'].":".$rowsql['CTD_CUST_NAME'];
		$_SESSION['pidcname']=$rowsql['PDD_PROD_NO'].":".$rowsql['PDD_PROD_NAME'];
		$selectlot=$selectlot.'<option value="'.$rowsql['OPD_LOT_NO'].'">'.$rowsql['OPD_LOT_NO'].'</option>';
	}
	$selectlot=$selectlot.'</select>';
	
	$query="select PD.PDD_TYPE,OP.*,PD.PDD_PROD_NAME,CD.CTD_CUST_SHORT_NAME 
		FROM              OUT_PRODUCT AS OP INNER JOIN
                            PRODUCT_DATA AS PD ON PD.PDD_PROD_NO = OP.PDD_PROD_NO INNER JOIN
                            OUT_DECISION AS OD ON OP.OPM_ORDER_NO = OD.OPM_ORDER_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS CD ON OD.CTD_CUST_NO = CD.CTD_CUST_NO

	 where OD.OTD_NO='".$_SESSION['nono']."'";
	 
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$chdrum=$chdrum."/".$row['PDD_TYPE'];
		$chdrum=substr($chdrum,1);
		//echo $chdrum;
		$strc=explode("/",$chdrum);
		for($i=0;$i<count($strc);$i++)
		{	
			if(trim($strc[$i])=="LY")
			{	
				$BOOM=1;
			}
		}
	
	
			$_SESSION['CUST']=$CUST=$row['CTD_CUST_SHORT_NAME'];
			$_SESSION['PDD']=$_SESSION['PDD'].",".$row['PDD_PROD_NO'];
			//$_SESSION['PDD']=substr($_SESSION['PDD'],0,-1);
			$_SESSION['PDD']=substr($_SESSION['PDD'],1);
			$PDD=$PDD."/".$row['PDD_PROD_NAME'];
			$OPDLOT=($OPDLOT."/".$row['OPD_LOT_NO']);
			
			$L=$L."   ".$row['OAF_PACKAGE'];
			$KG=$row['OPD_ACC_UNIT'];
			$QTY=$QTY."   ".$row['OPD_QTY_DRUM'];
			$_SESSION['QTY']=$_SESSION['QTY']+$row['OPD_QTY_DRUM'];
		}
		if($numRowssql==0 or $BOOM==1){echo "輸入錯誤或不屬於DRUM的OTD NO";}	
		if($numRowssql>=1 and $BOOM!=1)
		{	$OPD=array();
			$_SESSION['LOT']=$OPDLOT;
			echo '<br>';
			echo  "客戶 : (".$_SESSION['cidcname'].')<br>';
			echo  "品名 : (".$_SESSION['pidcname'].')<br>';
			echo  "批號 : ".$selectlot.'<br>';
			echo '<br>';
			echo '<input type="checkbox" name="chk1" checked="checked" value="1">'."容器荷姿:(".$L.$KG.") 與容器對照是否無錯誤.".'</br>';
			echo '<input type="checkbox" name="chk2" checked="checked" value="2">'."數量".$QTY."與容器對照是否無錯誤".'</br>';
			echo '<input type="checkbox" name="chk3" checked="checked" value="3" >'."附帶條件有無?".'</br>';
			echo '<input type="submit" name="next1" id="next1" style="font-size:20px" value="下一步" />'.'</br>';
	}
}


if(isset($_POST['next3']))
{
	echo '<input type="checkbox" name="chk1" value="1">'."捆包檢查確認".'</br>';
	echo '<input type="checkbox" name="chk2" value="2">'."外觀檢查、髒汙、洩漏、傷痕、凹凸等".'</br>';
	echo "棧板:".'<br>';
	echo "木頭".'<input type="radio" name="chose1" value="1">';
	echo "塑膠".'<input type="radio" name="chose2" value="2">';
	echo "其他".'<input type="radio" name="chose3" value="3">'.'</br>';
	echo "櫃內荷姿:".'<br>';
	echo "木架".'<input type="radio" name="chose11" value="1">';
	echo "消毒".'<input type="radio" name="chose22" value="2">';
	echo "其他".'<input type="radio" name="chose33" value="3">'.'</br>';
	echo '<input type="submit" name="next4" id="next4" value="確定並結束" />';
		
}
if((isset($_POST['next4'])) and $_POST['chk1']<>1 and $_POST['chk2']<>2)
{
	echo '<input type="checkbox" name="chk1" value="1">'."捆包檢查確認".'</br>';
	echo '<input type="checkbox" name="chk2" value="2">'."外觀檢查、髒汙、洩漏、傷痕、凹凸等".'</br>';
	echo "棧板:".'<br>';
	echo "木頭".'<input type="radio" name="chose1" value="1">';
	echo "塑膠".'<input type="radio" name="chose1" value="2">';
	echo "其他".'<input type="radio" name="chose1" value="3">'.'</br>';
	echo "櫃內荷姿:".'<br>';
	echo "木架".'<input type="radio" name="chose11" value="1">';
	echo "消毒".'<input type="radio" name="chose11" value="2">';
	echo "其他".'<input type="radio" name="chose11" value="3">'.'</br>';
	echo '<input type="submit" name="next4" id="next4" value="確定並結束" />';
	echo "請確認輸入無誤";
}
if((isset($_POST['next4'])) and $_POST['chk1']==1 and $_POST['chk2']==2)
{
	if($_POST['chose1']==1){$zhi="木頭";}
	if($_POST['chose1']==2){$zhi="塑膠";}
	if($_POST['chose1']==3){$zhi="其他";}
	if($_POST['chose11']==1){$hoz="木架";}
	if($_POST['chose11']==2){$hoz="消毒";}
	if($_POST['chose11']==3){$hoz="其他";}
    $last="select OTD_NO from OUT_CHECK_DRUM where OTD_NO='".$_SESSION['nono']."' ";
	$resultlast = mssql_query($last);
	$numRowslast = mssql_num_rows($resultlast);
	if($numRowslast>0)
	{
		$up3="update OUT_CHECK_DRUM set OCM_CHK_DATE='".date("Ymd")."',OCM_CHK_STYLE='Y',OCM_CHK_ADDITION='Y',OCM_CHK_SURFACE='Y',
		OCM_PLT_NAME='".$zhi."',OCM_PLT_STYLE='".$hoz."',OCM_CF_MAN='".$_SESSION['uid']."'
		where OTD_NO='".$_SESSION['nono']."'";
		$resultup3 = mssql_query($up3);
		$_SESSION['pda']=$up3;
	}
	if($numRowslast==0)
	{
		$inser1="insert into OUT_CHECK_DRUM (OTD_NO,OCM_CHK_DATE,OCM_CHK_STYLE,
		OCM_CHK_ADDITION,OCM_CHK_SURFACE,OCM_PLT_NAME,OCM_PLT_STYLE,OCM_CF_MAN) VALUES ('".$_SESSION['nono']."','".date("Ymd")."','Y','Y','Y',
			'".$zhi."',	'".$hoz."','".$_SESSION['uid']."')";
			$resultins1 = mssql_query($inser1);
			$_SESSION['pda']=$inser1;
	}
	jumpto($_SESSION['index']);
}
?>
</form>
<a href="pda_drum.php"><strong>上一步</strong></a>&nbsp;&nbsp;&nbsp;<a href="index.php"><strong>取消/離開</strong></a><br>