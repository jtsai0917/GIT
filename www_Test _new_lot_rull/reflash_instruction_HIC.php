<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta http-equiv="refresh" content="30" > 
<?php 
session_start();
include("./connections/conn.php");
include("./lib/fun.php");
$date=date("Y-m-d");
if(isset($_POST['back'])){
	echo "昨天";
	 $_SESSION['date']= date('Ymd', strtotime($date. ' - 1 days'));
}
if(isset($_POST['forward'])){
	 $_SESSION['date']= date('Ymd', strtotime($date. ' + 1 days'));
	 echo "明天";
}
if(isset($_POST['now'])){
	 $_SESSION['date']= date('Ymd');
}
		if($_SESSION['orig']=='')
		{
					$_SESSION['orig']=0;
		}
if(isset($_POST['pdd_type'])){
	if($_SESSION['pdd_type']==0){
		$_SESSION['pdd_type']=1;
		$_SESSION['prod']=0;
		$_SESSION['orig']=0;
	}
	else{
		$_SESSION['pdd_type']=0;
	}
}

if(isset($_POST['prod'])){
	if($_SESSION['prod']==0){
		$_SESSION['prod']=1;
		$_SESSION['orig']=0;
		$_SESSION['pdd_type']=0;
	}
	else{
		$_SESSION['prod']=0;
	}
}
$loginFormAction = $_SERVER['PHP_SELF'];
	echo '<form name="SS" method="post"><table width="1440" border="1"><tr align="center"><td><font size="+4" color="red">'.ddd($_SESSION['date']).
	'充填狀況<input type="submit" name="back" value="<<"><input type="submit" name="now" value="||"><input type="submit" name="forward" value=">>">&nbsp;&nbsp;</font>現在時間'.date("Y-m-d H:i:s").'</td></tr></table>
<table width="1440" border="1"><tr bgcolor="#CCCCCC" align="center"><td width="50">序號</td><td width="120">
藥 品&nbsp;&nbsp;<input style="font-size:14;border:0;" type="submit" name="prod" value="↓"></td><td width="120">客戶
</td><td width="50">荷 姿&nbsp;&nbsp;<input style="font-size:14;border:0;" type="submit" name="pdd_type" value="↓"></td><td width="120">LOT NO</td><td width="100">預定時間</td><td width="100">開始時間</td>
<td width="100">結束時間</td><td width="60">充填人員</td><td width="60">充填TANK</td>
<td width="120">取樣</td>
</tr>';
if($_SESSION['date']==''){
	$_SESSION['date']=date("Ymd");
}

	$_SEEEION['dd1']=$_SESSION['date']."235959";
	$_SEEEION['dd2']=$_SESSION['date']."000000";

$query="SELECT          FILLPLAN_OUT_DECIDE.FOD_UNI, FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, FILLPLAN_OUT_DECIDE.FOD_DAY, 
                            FILLPLAN_OUT_DECIDE.FOD_O_YEAR_MONTH, FILLPLAN_OUT_DECIDE.FOD_O_DAY, 
                            FILLPLAN_OUT_DECIDE.CTD_CUST_NO, FILLPLAN_OUT_DECIDE.CTD_CUST_NO_GROUP, 
                            FILLPLAN_OUT_DECIDE.PDD_PROD_NO, FILLPLAN_OUT_DECIDE.FDM_SERIAL_NO, 
                            FILLPLAN_OUT_DECIDE.FDM_LY_NO, FILLPLAN_OUT_DECIDE.FDM_CREATE_DATE, 
                            FILLPLAN_OUT_DECIDE.FDM_CREATOR, FILLPLAN_OUT_DECIDE.FDM_SPECIFIC, 
                            FILLPLAN_OUT_DECIDE.FDM_QTY_DRUM, FILLPLAN_OUT_DECIDE.FDM_QTY, 
                            FILLPLAN_OUT_DECIDE.FDM_QTY_UNIT, FILLPLAN_OUT_DECIDE.FDM_EXPECT_DATE, 
                            FILLPLAN_OUT_DECIDE.FDM_LOT_NO, FILLPLAN_OUT_DECIDE.FDM_ITEM, 
                            FILLPLAN_OUT_DECIDE.FDM_RESULT_DATE, FILLPLAN_OUT_DECIDE.FDM_COA_BEFORE, 
                            FILLPLAN_OUT_DECIDE.FDM_OUT_DATE, FILLPLAN_OUT_DECIDE.FDM_BACK_DATE, 
                            FILLPLAN_OUT_DECIDE.FDM_SAM_BEFORE, FILLPLAN_OUT_DECIDE.FDM_SAM_BEF_CNT, 
                            FILLPLAN_OUT_DECIDE.FDM_SAM_CNT, FILLPLAN_OUT_DECIDE.FDM_ATTACH_CNT, 
                            FILLPLAN_OUT_DECIDE.FDM_TRANSFROM, FILLPLAN_OUT_DECIDE.FDM_PRINT_OUT, 
                            FILLPLAN_OUT_DECIDE.FDM_FILLED_B_DATE, FILLPLAN_OUT_DECIDE.FDM_FILLED_E_DATE, 
                            FILLPLAN_OUT_DECIDE.FDM_FILLED_MAN, FILLPLAN_OUT_DECIDE.FDM_FILLED_LY_QTY, 
                            FILLPLAN_OUT_DECIDE.FDM_FILLED_DM_QTY, FILLPLAN_OUT_DECIDE.FDM_CHK_SAM_PURGE, 
                            FILLPLAN_OUT_DECIDE.FDM_CHK_CHG_PURGE, FILLPLAN_OUT_DECIDE.FDM_PURGE, 
                            FILLPLAN_OUT_DECIDE.FDM_MOD_DATE, FILLPLAN_OUT_DECIDE.FDM_P_TOTO, 
                            FILLPLAN_OUT_DECIDE.FOD_BAR_PRN_DATE, FILLPLAN_OUT_DECIDE.FDM_REAL_OUT_TIME, 
                            FILLPLAN_OUT_DECIDE.FDM_REAL_RETURN_TIME, FILLPLAN_OUT_DECIDE.remark1, 
                            FILLPLAN_OUT_DECIDE.LY_SN, PRODUCT_DATA.PDD_PROD_NO AS Expr1, PRODUCT_DATA.PDD_TYPE, 
                            PRODUCT_DATA.PDD_DRUM_KG, PRODUCT_DATA.PDD_STYLE, PRODUCT_DATA.PDD_PACKAGE, 
                            PRODUCT_DATA.PDD_CHEMICAL, PRODUCT_DATA.PDD_PROD_NAME, PRODUCT_DATA.PDD_PROD_SHORT_NAME,
                             PRODUCT_DATA.PDD_UNIT, PRODUCT_DATA.PDD_CLASS, PRODUCT_DATA.PDD_MINIMAL, 
                            PRODUCT_DATA.PDD_LITER_KG, PRODUCT_DATA.PDD_CONSISTENCY, PRODUCT_DATA.PDD_SHOW, 
                            PRODUCT_DATA.PDD_DRUM_LITER, FILL_INDICATE.FID_FILL_BEGIN_DATE AS fb, 
                            FILL_INDICATE.FID_FILL_END_DATE AS fe, EMPLOYEE_DATA.EMP_NAME AS LFC_FILLER, 
                            Fill_Flow_Chart.flow as flow
FROM              Fill_Flow_Chart RIGHT OUTER JOIN
                            FILLPLAN_OUT_DECIDE INNER JOIN
                            PRODUCT_DATA ON FILLPLAN_OUT_DECIDE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO ON 
                            Fill_Flow_Chart.Lot_No = FILLPLAN_OUT_DECIDE.FDM_LOT_NO LEFT OUTER JOIN
                            EMPLOYEE_DATA INNER JOIN
                            FILL_INDICATE ON EMPLOYEE_DATA.EMP_NO = FILL_INDICATE.FID_OPERATOR ON 
                            FILLPLAN_OUT_DECIDE.FDM_LOT_NO = FILL_INDICATE.FDM_LOT_NO LEFT OUTER JOIN
                            LORRY_FILL_CHECK ON FILLPLAN_OUT_DECIDE.FDM_LOT_NO = LORRY_FILL_CHECK.FDM_LOT_NO
where (FILLPLAN_OUT_DECIDE.PDD_PROD_NO = 'P050-000') 
";
                            
$query.=" and ((FDM_EXPECT_DATE<='".$_SEEEION['dd1']."') and (FDM_EXPECT_DATE>='".$_SEEEION['dd2']."')) ";
if($_SESSION['orig']==1){
	$query.="order by fe,fb,dbo.FILLPLAN_OUT_DECIDE.FDM_CREATE_DATE";
}
if($_SESSION['prod']==1){
	$query.="order by FILLPLAN_OUT_DECIDE.PDD_PROD_NO,fe,fb,dbo.FILLPLAN_OUT_DECIDE.FDM_CREATE_DATE";
}
if($_SESSION['pdd_type']==1){
	$query.="order by PRODUCT_DATA.PDD_TYPE,fe,fb,dbo.FILLPLAN_OUT_DECIDE.FDM_CREATE_DATE";
}

// echo $query."<BR>";;
$result=mssql_query($query);
$i=1;
while($row=mssql_fetch_array($result)){
	if($_SESSION['pass'][$row['FDM_LOT_NO']]==1){continue;}
	else{
	$_SESSION['pass'][$row['FDM_LOT_NO']]=1;}
	$a=new get_from_lot_no;
	$a->lid=$row['FDM_LOT_NO'];
	$a->ani();
	$a->foduni=$row['FOD_UNI'];
	$cnt_smp=$row['FDM_SAM_BEFORE']+$row['FDM_SAM_BEF_CNT']+$row['FDM_SAM_CNT']+$row['FDM_ATTACH_CNT']+2;
	$a->cid();
	$a->cid2();
	$lkg=$row['PDD_LITER_KG'];
	$qty=trim($row['FDM_QTY_UNIT']);
	$st1=strpos(trim($row['flow']),"T");
	if($st1!=0){
		$st2=strpos(trim($row['flow']),"/",$st1);
		$st3=$st2-$st1;
		$tank=substr(trim($row['flow']),$st1,$st3);
	}
	else{
		$tank='';
	}
	
	//$pdd_drum=fill_drum(trim($row['PDD_PROD_NO']));
	if($qty=='L')
	{
			$a->qty=($a->qty)/$lkg;
	}
	if($a->prodstyle<>'LY'){$cname=$a->cid2;}
	else{$cname=$a->csname;}
	if($row['fb']<>''){$clr="yellow";}
	if($row['fe']<>''){$clr="#11BB00";}
	if($row['fe']=='' and $row['fb']==''){	$clr="#FFFFFF";}
	
	
	echo '<tr bgcolor="'.$clr.'" align="center"><td align="center">'.$i.'</td><td>'.get_prod_name($row['PDD_PROD_NO']).'</td><td>'.$cname.'</td><td>'.$a->prodstyle.'</td><td align="center">'.$a->lid.'</td><td>'.
	substr($row['FDM_EXPECT_DATE'],8,2).":".substr($row['FDM_EXPECT_DATE'],10,2).'</td><td>'.stm($row['fb']).'</td><td>'.stm($row['fe']).'</td><td>'.trim($row['LFC_FILLER']).'</td><td>'.$tank.'</td><td>'.$cnt_smp.'</td></tr>';
	$i++;
}
unset($_SESSION['pass']);
echo '</table></br></form>';


?>

