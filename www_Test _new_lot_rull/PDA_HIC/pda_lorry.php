<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
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
  <p>
    <label for="lot_no"></label>
  <a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
  <p>
 Lorry出貨檢查作業.
  <br>
   充填日期: <input type="text" name="toto_no" id="toto_no" autocomplete="off" style="font-size:25px" size="10" value="<?PHP echo date("Ymd") ?>"/ readonly>
  <br>
   出荷決定書NO.:<BR /> <input type="text" name="nono" id="nono"  autocomplete="off" style="font-size:25px" size="12" value="<?PHP echo $_POST['nono'];  ?>"/>
				<input type="submit" name="enter" id="enter" style="font-size:25px" value="送出" /><br>
                <a href="<?php 
				if($_SESSION['index']<>''){echo $_SESSION['index'];}else{echo "index.php";}?>"><strong>取消/離開</strong></a><br>
<?php
if(isset($_POST['enter']))
{
	$chksql="select OTD_NO from OUT_DECISION where OTD_NO='".$_POST['nono']."'";
		$resultsql = mssql_query($chksql);
		$numRowssql = mssql_num_rows($resultsql);
	$query="SELECT          PD.PDD_TYPE, OP.OPM_ORDER_NO, OP.OPD_SERIAL_NO, OP.OTN_NO, OP.OTNP_SERIAL_NO, OP.PDD_PROD_NO,
                             OP.OAF_PACKAGE, OP.OPD_LOT_NO, OP.PRA_SERIAL_NO, OP.OPD_TERM_DATE, OP.OPD_VALIDATE, 
                            OP.OPD_QTY_DRUM, OP.OPD_ACC_UNIT, OP.OPD_QTY_LITER, OP.OPD_QTY_KG, OP.OPD_SIGN_RECEIPT, 
                            OP.OPD_COA_RECEIPT, OP.OPD_MEMO, OP.OPD_REAL_QTY_KG, OP.OPD_INWARD_DATE, OP.OPD_COA_NO, 
                            OP.COA_INDEX, OP.OAF_ACC_ID, OP.OPD_SMP_DATETIME, OP.OPD_COA_DATETIME, PD.PDD_PROD_NAME, 
                            CD.CTD_CUST_SHORT_NAME, OD.OTD_NO
FROM              OUT_PRODUCT AS OP INNER JOIN
                            PRODUCT_DATA AS PD ON PD.PDD_PROD_NO = OP.PDD_PROD_NO INNER JOIN
                            OUT_DECISION AS OD ON OP.OPM_ORDER_NO = OD.OPM_ORDER_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS CD ON OD.CTD_CUST_NO = CD.CTD_CUST_NO
	 where OD.OTD_NO='".$_POST['nono']."'";

	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
			$chdrum=$chdrum."/".$row['PDD_TYPE'];
		$chdrum=substr($chdrum,1);
		//echo $chdrum.'<br>';
		$strc=explode("/",$chdrum);
		for($i=0;$i<count($strc);$i++)
		{	//echo $strc[$i]="LY";
			if(trim($strc[$i])=="LY")
			{	
				
				$BOOM=1;//BOOM是一就是LY
			}
		}
		$LOT=$LOT."/".$row['OPD_LOT_NO'];
		$CUST=$CUST."/".$row['CTD_CUST_SHORT_NAME'];
		$_SESSION['lot']=$row['OPD_LOT_NO'];
		$_SESSION['cus']=$row['CTD_CUST_SHORT_NAME'];
	}	//WHILE
	if($numRowssql==0 or $BOOM!=1){echo "OTD NO 輸入錯誤";break;}
	if($numRowssql>=1 and $BOOM==1)
	{	
			
		$design="select AND_RESULT from AnalyzeDesign where AND_LOT_NO='".$_SESSION['lot']."' ";
		//echo $design;
		$resultdes = mssql_query($design);
		$numRowsdes = mssql_num_rows($resultdes);			
		while($rowdes = mssql_fetch_array($resultdes))
		{
			if(trim($rowdes['AND_RESULT'])!='OK' and trim($rowdes['AND_RESULT'])!='NG')
			{
				echo $_SESSION['lot'].":未分析".'</br>';
				echo '<input type="submit" name="nex" id="nex" value="繼續" />';
				echo '<input type="submit" name="end" id="end" value="取消" />'.'</br>';
			}
			if(trim($rowdes['AND_RESULT'])=='NG')
			{
				echo $_SESSION['lot'].":分析NG".'</br>';
				echo '<input type="submit" name="nex" id="nex" value="繼續" />';
				echo '<input type="submit" name="end" id="end" value="取消" />'.'</br>';
			}
			if(trim($rowdes['AND_RESULT'])=='OK')
			{
				echo "LORRY NO:".'<input type="text" name="lono" id="lono" autocomplete="off" style="font-size:25px" value=""/>'.'</br>'.'</br>';
				echo "LOTNO:".$_SESSION['lot'].'<br>';
				echo "客戶名稱:".$_SESSION['cus'].'</br>';
				echo '<input type="submit" name="next1" id="next1" value="下一步" />';
				echo '</br>';
			}
		}
	}
}
if(isset($_POST['next1']))
{	$_SESSION['LY']=$_POST['lono'];
	$L=substr($_POST['lono'],-4);
	$LL=substr($_SESSION['lot'],-4);
	if($L==$LL)
	{
			echo "Barode確認".'<input type="checkbox" name="chk1" checked="checked" value="Y">'.'</br>'; 
			echo "Lot NO 是否貼上".'<input type="checkbox" name="chk2" checked="checked" value="Y">'.'</br>'; 
			echo "特殊接管是否裝上".'<input type="checkbox" name="chk3" checked="checked" value="Y">'.'</br>'; 
			echo "管子是否殘液或變形".'<input type="checkbox" name="chk4" checked="checked" value="Y">'.'</br>'; 
			echo "Lorry外觀檢查".'<input type="checkbox" name="chk5" checked="checked" value="Y">'.'</br>'; 
			echo "Lorry上蓋是否密合".'<input type="checkbox" name="chk6" checked="checked" value="Y">'.'</br>'; 
			echo "HOSE 外觀檢查".'<input type="checkbox" name="chk7" checked="checked" value="Y">'.'</br>'; 
			echo '<input type="submit" name="next2" id="next2" value="下一步" />'.'</br>';
	}
	else{
		echo "請輸入正確LY NO".'<br>';
	echo "LORRY NO:".'<input type="text" name="lono" id="lono" autocomplete="off" style="font-size:25px" value=""/>'.'</br>'.'</br>';
					echo "LOTNO:".$_SESSION['lot'].'<br>';
					echo "客戶名稱:".$_SESSION['cus'].'</br>';
					echo '<input type="submit" name="next1" id="next1" value="下一步" />';
					echo '</br>';}
}
if(isset($_POST['next2']))
{
	for($i=0;$i<7;$i++)
	{
		if($_POST['chk'.$i.'']!='Y')
		{
			$_POST['chk'.$i.'']='N';
		}
	}
	 $last="select OTD_NO from OUT_CHECK_LORRY where OTD_NO='".$_POST['nono']."' ";
//  	echo "<BR>";echo $last;echo "<BR>";
	$resultlast = mssql_query($last);
	$numRowslast = mssql_num_rows($resultlast);
	if($numRowslast>0)
	{
		$up="update OUT_CHECK_LORRY set OCL_LY_NO='".$_SESSION['LY']."', OCL_LOT_NO='".$_SESSION['lot']."',
		OCL_CHK_DATE='".date("Ymd")."',OCL_CHK_BAR='".$_POST['chk1']."',OCL_CHK_SPEC_LINK='".$_POST['chk2']."'
		,OCL_CHK_PIPE='".$_POST['chk3']."',OCL_CHK_LOT_PASTED='".$_POST['chk4']."'
		,OCL_CF_MAN='".$_SESSION['uid']."',OCL_CHK_SURFACE='".$_POST['chk5']."',OCL_CHK_UPCAP='".$_POST['chk6']."'
		,OCL_CHK_HOSE='".$_POST['chk7']."'
		where OTD_NO='".$_POST['nono']."'";
//		echo "<BR>UP:";echo $up;echo "<BR>";
		$resultup3 = mssql_query($up3);
	}
	if($numRowslast==0)
	{
		$inser1="insert into OUT_CHECK_LORRY (OTD_NO,OCL_LY_NO,OCL_LOT_NO,OCL_BACK_DATE,OCL_CHK_DATE,OCL_CHK_BAR
		,OCL_CHK_SPEC_LINK,OCL_CHK_PIPE,OCL_CHK_LOT_PASTED,OCL_CF_MAN,OCL_CHK_SURFACE,OCL_CHK_UPCAP,OCL_CHK_HOSE) 
		VALUES ('".$_POST['nono']."','".$_SESSION['LY']."','".$_SESSION['lot']."',NULL,'".date("Ymd")."','".$_POST['chk1']."'
		,'".$_POST['chk2']."','".$_POST['chk3']."','".$_POST['chk4']."','".$_SESSION['uid']."',
		'".$_POST['chk5']."','".$_POST['chk6']."','".$_POST['chk7']."'	)";
//		echo "<BR>insert1";echo $inser1;echo "<BR>";
			$resultins1 = mssql_query($inser1);
			echo "完成";
			
	}
	echo '<input type="submit" style="font-size:'.$_SESSION['font_size'].'" name="end" value="回首頁">';
}
if(isset($_POST['end']))
{
	header("Location: " .$_SESSION['index']);
}
if(isset($_POST['nex']))
		{
			echo "LORRY NO:".'<input type="text" name="lono" id="lono"  autocomplete="off" style="font-size:25px" value=""/>'.'</br>'.'</br>';
			echo "LOTNO:".$_SESSION['lot'].'<br>';
			echo "客戶名稱:".$_SESSION['cus'].'</br>';
			echo '<input type="submit" name="next1" id="next1" value="下一步" />';
			echo '</br>';
		}
?>