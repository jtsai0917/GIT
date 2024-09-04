<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
$_SESSION['lot_id']=$_POST['lot_id'];
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
<input type="hidden" name="lot_id" value="<?php echo $_SESSION['lot_id'];?> "/>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<BR />
  DRUM 出貨檢查作業.
  <br>
   出荷日期: <input type="text" autocomplete="off" name="toto_no" id="toto_no" style="font-size:25px" value="<?PHP echo date("Ymd") ?>" size="10"/ readonly>
  <br>
出荷決定書NO.: <?PHP echo $_SESSION['nono'];  ?><BR />            

<?php 
$_SESSION['dm_no']=$_POST['no1'];
$_SESSION['pa_no']=$_POST['no2'];
if($_SESSION['dm_no']==''){$sno1='autofocus="autofucos"';}
if($_SESSION['pa_no']==''){$sno2='autofocus="autofucos"';}
if((isset($_POST['next2'])))
{	//echo $_SESSION['QTY'].">".$_SESSION['numrow'];
	$_SESSION['pa_no']=$_POST['no2'];
	
	if($_SESSION['QTY']>$_SESSION['numrow'])
	{
		$OK=$OK+1;
	}
	$str1=explode("/",$_SESSION['LOT']);
	for($i=0;$i<count($str1);$i++)
	{	
		if(trim($_POST['no'])==trim($str1[$i]))
		{
			$OK=$OK+1;
			//echo $OK;
			$alarm=1;
		}
		
	}
	if($alarm<>1)
		{echo "LOTNO 錯誤".'<br>';}
	$drum="select * from DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_POST['no1']."'";
	$resultd = mssql_query($drum);
	$numrowss=mssql_num_rows($resultd);
//	echo "##".$numrowss."##<BR>";
	if($numrowss==0)
	{
		create_drum($_POST['no1'],$_SESSION['prod_id'],$_POST['no']);
	}
	$drum1="select * from OUT_CHECK_DRUM_DETAIL where OCD_DRUM_NO='".$_POST['no1']."' and OTD_NO='".$_SESSION['nono']."'";
//	echo $drum1."<BR>";
	$resultd1 = mssql_query($drum1);
	$numRowsd1 = mssql_num_rows($resultd1);
	$drum="select * from DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_POST['no1']."'";
	$resultd = mssql_query($drum);
		$numRowsd = mssql_num_rows($resultd);
				while($rowd = mssql_fetch_array($resultd))
				{
					$used=$rowd['DHN_USED_COUNT']; 
					$disc=trim($rowd['DHN_DISCARD_DATE']);
					$pd=trim($rowd['PDD_PROD_NO1']);
				}
				$str2=explode(",",$_SESSION['PDD']);
				for($i=0;$i<count($str2);$i++)
				{	
					
						if($numRowsd>0 and  $disc == NULL and $pd==trim($str2[$i]) and $numRowsd1==0)
						{
							$OK=$OK+1;
							$alarm1=1;
						}
				
				}
		
		if($alarm1<>1){echo "桶號錯誤或藥品不符".'<br>';}
	$bord="select * from BOARD where BOD_NO='".$_POST['no2']."'";
	$resultb = mssql_query($bord);
		$numRowsb = mssql_num_rows($resultb);
		 while($rowb = mssql_fetch_array($resultb))
		 {
			 if($rowb['BOD_OUT_DATE']==date("Ymd"))
			 {
				 $BC=2;
			 }
			 if(trim($rowb['BOD_OUT_DATE'])=='')
			 {
				 $BC=1;
			 }
		 }
		 if($BC==2)
		 {
			 $bord2="select *from OUT_CHECK_DRUM_DETAIL where OTD_NO='".$_SESSION['nono']."' and OCD_PLT_NO='".trim($_POST['no2'])."' ";
			 $_SESSION['code4']=$bord2;
			 $resultb2 = mssql_query($bord2);
			 $numRowsb2 = mssql_num_rows($resultb2);
			 if($numRowsb2 < 4)
			 {
			  	$BC=1;
			 }
		 }
		
		if($BC==1)
		{
			$OK=$OK+1;
		}
		if($BC!=1){echo "棧板錯誤".'<br>';}
		

			
			$query2="SELECT          OP.OPM_ORDER_NO, OP.OPD_SERIAL_NO, OD.OTD_NO, OP.OTN_NO, OP.OTNP_SERIAL_NO, OP.PDD_PROD_NO, 
                            OP.OAF_PACKAGE, OP.OPD_LOT_NO, OP.PRA_SERIAL_NO, OP.OPD_TERM_DATE, OP.OPD_VALIDATE, 
                            OP.OPD_QTY_DRUM, OP.OPD_ACC_UNIT, OP.OPD_QTY_LITER, OP.OPD_QTY_KG, OP.OPD_SIGN_RECEIPT, 
                            OP.OPD_COA_RECEIPT, OP.OPD_MEMO, OP.OPD_REAL_QTY_KG, OP.OPD_INWARD_DATE, OP.OPD_COA_NO, 
                            OP.COA_INDEX, OP.OAF_ACC_ID, OP.OPD_SMP_DATETIME, OP.OPD_COA_DATETIME, OD.CTD_CUST_NO
FROM              OUT_PRODUCT AS OP INNER JOIN
                            OUT_DECISION AS OD ON OP.OPM_ORDER_NO = OD.OPM_ORDER_NO 
			 where OD.OTD_NO='".$_SESSION['nono']."' and OP.OPD_LOT_NO='".$_POST['no']."'";

			 $result2 = mssql_query($query2);
			$numRows2 = mssql_num_rows($result2);
			 while($row2 = mssql_fetch_array($result2))
			 {
			
				$inser="insert into OUT_CHECK_DRUM_DETAIL (OTD_NO,OCD_LOT_NO,OCD_DRUM_NO,OCD_PLT_NO) 
				VALUES ('".trim($_SESSION['nono'])."','".trim($_POST['no'])."','".trim($_POST['no1'])."','".trim($_POST['no2'])."')";
			$_SESSION['insert']=$inser;
			$resultin = mssql_query($inser);
			if($used==0){$used=1;}
			$up1="UPDATE DRUM_HISTORY_NORMAL set CTD_CUST_NO".$used."='".$row2['CTD_CUST_NO']."',
			PDD_PROD_NO".$used."='".$row2['PDD_PROD_NO']."',DHN_LOT_NO".$used."='".$_POST['no']."',
			DHN_OUT_DATE".$used."='".date("Ymdhis")."' 
			where DHN_DRUM_NO='".$_POST['no1']."' and disable <> 1";

			$resultup1 = mssql_query($up1);
			$_SESSION['code2']=$up1;
			$up2="UPDATE BOARD set BOD_OUT_LOC='".$row2['CTD_CUST_NO']."',BOD_OUT_DATE='".date("Ymd")."' 
			where BOD_NO='".$_POST['no2']."'";
			
			$resultup2 = mssql_query($up2);
			echo "已寫入".'<br>';

	echo "LOT NO:".'<input type="text"  autocomplete="off" name="no" id="no"  style="font-size:20px" value="'.$_SESSION['aa'][0].'"/></br>';
	echo "桶  號:".'<input type="text" autocomplete="off" name="no1" id="no1" style="font-size:20px" value="'.$_SESSION['dm_no'].'"/></br>';
	echo "棧板編號:".'<input type="text"  autocomplete="off" name="no2" id="no2" style="font-size:20px" value="'.$_SESSION['pa_no'].'"/></br>';
	//echo "客戶條碼1:".'<input type="text" name="no3" id="no3" width="50" height="20" value=""/>'.'</br>';
	//echo "客戶條碼2:".'<input type="text" name="no4" id="no4" width="50" height="20" value=""/>'.'</br>';
	//echo "客戶條碼3:".'<input type="text" name="no5" id="no5" width="50" height="20" value=""/>'.'</br>';
	echo '<input type="submit" name="next2" id="next2" value="下一桶/儲存" />';
	echo "      ".'<input type="submit" name="next3" id="next3" value="結束刷桶" />'.'</br>';		
		}
}//end next2


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