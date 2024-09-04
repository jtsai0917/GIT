
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title></title>
Drum 出荷檢查表</br>
<form method="post" action="<?php echo $loginFormAction; ?>">
<input type="submit" name="print" id="print" value="列印" />
<input type="submit" name="leave" id="leave" value="離開" />

<?PHP 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
datepick();
echo'</br>';
echo $_GET['id'];
echo'</br>';
global $num;
$GLOBALS['num']=0;


if(isset($_POST['leave']))
	{
		jumpto('http://'.$_SERVER['HTTP_HOST'].'/prodout/index.php?url=drum_check_list');
	}
if(isset($_POST['print']))
{		
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./DM_check.xlsx");
	$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
	$query="SELECT  SUM(OPD_QTY_DRUM) as unit_sum, CD.CTD_CUST_SHORT_NAME  
						FROM      OUT_PRODUCT AS OP INNER JOIN
					   PRODUCT_DATA AS PD ON PD.PDD_PROD_NO = OP.PDD_PROD_NO LEFT OUTER JOIN
					   OUT_DECISION AS OD ON OD.OTD_NO = OP.OTD_NO LEFT OUTER JOIN
					   CUSTOMER_DATA AS CD ON OD.CTD_CUST_NO = CD.CTD_CUST_NO 
		where OP.OTD_NO='".$_GET['id']."' GROUP BY   CD.CTD_CUST_SHORT_NAME";
//		echo $query."<BR>";
	$reslut=mssql_query($query);
	$row=mssql_fetch_row($reslut);
	$cust_short_name=$row[1];
	$objPHPExcel->getActiveSheet()->setCellValue("L2",iconv("big5","utf-8",$cust_short_name) );
	$objPHPExcel->getActiveSheet()->setCellValue("S2",iconv("big5","utf-8",$_GET['id']) );
		
/*		
		
		$query="SELECT          PD.PDD_TYPE, OP.OPM_ORDER_NO, OP.OPD_SERIAL_NO, OP.OTD_NO, OP.OTN_NO, OP.OTNP_SERIAL_NO, 
                            OP.PDD_PROD_NO, OP.OAF_PACKAGE, OP.OPD_LOT_NO, OP.PRA_SERIAL_NO, OP.OPD_TERM_DATE, 
                            OP.OPD_VALIDATE, OP.OPD_QTY_DRUM, OP.OPD_ACC_UNIT, OP.OPD_QTY_LITER, OP.OPD_QTY_KG, 
                            OP.OPD_SIGN_RECEIPT, OP.OPD_COA_RECEIPT, OP.OPD_MEMO, OP.OPD_REAL_QTY_KG, 
                            OP.OPD_INWARD_DATE, OP.OPD_COA_NO, OP.COA_INDEX, OP.OAF_ACC_ID, OP.OPD_SMP_DATETIME, 
                            OP.OPD_COA_DATETIME, PD.PDD_PROD_NAME, CD.CTD_CUST_SHORT_NAME, OD.OPM_ETA_DATE, 
                            PD.PDD_STYLE, CD.CTD_CUST_NO, PD.PDD_CLASS 
FROM              OUT_DECISION AS OD FULL OUTER JOIN
                            OUT_PRODUCT AS OP INNER JOIN
                            PRODUCT_DATA AS PD ON PD.PDD_PROD_NO = OP.PDD_PROD_NO ON 
                            OD.OTD_NO = OP.OTD_NO FULL OUTER JOIN
                            CUSTOMER_DATA AS CD ON OD.CTD_CUST_NO = CD.CTD_CUST_NO  
	 where OP.OTD_NO='".$_GET['id']."' ORDER BY   OP.OPD_SERIAL_NO";

	$y=5;
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$savedate=substr(valid($row['PDD_CLASS'],$row['PDD_PROD_NO'],$row['CTD_CUST_NO'],$row['OPD_LOT_NO']),0,10);
		$objPHPExcel->getActiveSheet()->setCellValue("A".$y,iconv("big5","utf-8",$row['PDD_PROD_NAME']));			//
		$objPHPExcel->getActiveSheet()->setCellValue("F".$y,iconv("big5","utf-8",$row['OAF_PACKAGE']));
		$objPHPExcel->getActiveSheet()->setCellValue("J".$y,iconv("big5","utf-8",$row['OPD_LOT_NO']));
		$objPHPExcel->getActiveSheet()->setCellValue("O".$y,iconv("big5","utf-8",$savedate));
		$objPHPExcel->getActiveSheet()->setCellValue("S".$y,iconv("big5","utf-8",$row['OPD_QTY_DRUM']));	
		$x++;$y++;
	}

		$query="SELECT          EMPLOYEE_DATA.EMP_NAME, OUT_CHECK_DRUM.OCM_CHK_DATE, OUT_DECISION.OPM_ETA_DATE  
  FROM              OUT_CHECK_DRUM INNER JOIN EMPLOYEE_DATA ON OUT_CHECK_DRUM.OCM_CF_MAN = EMPLOYEE_DATA.EMP_NO INNER JOIN 
                            OUT_DECISION ON OUT_CHECK_DRUM.OTD_NO = OUT_DECISION.OTD_NO 
                            where OUT_CHECK_DRUM.OTD_NO='".$_GET['id']."' ";
  //                          	echo $query."<BR>";
		$result = mssql_query($query);
		$row = mssql_fetch_row($result);
		
		$objPHPExcel->getActiveSheet()->setCellValue("P29",iconv("big5","utf-8",$row[0] ));  // 出貨擔當
		$objPHPExcel->getActiveSheet()->setCellValue("C2",iconv("big5","utf-8",ddd($row[1])));   // 檢查日期
		$objPHPExcel->getActiveSheet()->setCellValue("G2",iconv("big5","utf-8",ddd($row[2])));
*/	

		$num1='12';
		$i=0;
		$count=0;
		$type=0;
		$_SESSION['lottest']='';
		$query="SELECT DISTINCT 
                            OUT_CHECK_DRUM_DETAIL.OTD_NO, OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO, 
                            OUT_CHECK_DRUM_DETAIL.OCD_DRUM_NO, OUT_CHECK_DRUM_DETAIL.OCD_CUST_BAR, 
                            OUT_CHECK_DRUM_DETAIL.OCD_PLT_NO, OUT_PRODUCT.PDD_PROD_NO, OUT_PRODUCT.OPD_LOT_NO, 
                            OUT_PRODUCT.OPD_SERIAL_NO, OUT_PRODUCT.OPD_QTY_DRUM, OUT_DECISION.CTD_CUST_NO, 
                            CUSTOMER_PRODUCTS.CTP_CUSTBAR1, OUT_CHECK_DRUM_DETAIL.OCD_CUSTBAR1 as CPN
						FROM              OUT_CHECK_DRUM_DETAIL INNER JOIN
                            OUT_CHECK_DRUM ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_CHECK_DRUM.OTD_NO INNER JOIN
                            OUT_PRODUCT ON OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO = OUT_PRODUCT.OPD_LOT_NO AND 
                            OUT_CHECK_DRUM.OTD_NO = OUT_PRODUCT.OTD_NO INNER JOIN
                            OUT_DECISION ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_DECISION.OTD_NO LEFT OUTER JOIN
                            CUSTOMER_PRODUCTS ON OUT_PRODUCT.PDD_PROD_NO = CUSTOMER_PRODUCTS.PDD_PROD_NO AND 
                            OUT_DECISION.CTD_CUST_NO = CUSTOMER_PRODUCTS.CTD_CUST_NO 
            where OUT_CHECK_DRUM_DETAIL.OTD_NO='".$_GET['id']."' 
            ORDER BY OUT_PRODUCT.OPD_SERIAL_NO ";	
    
//		echo $query."<BR>";
 		$y=5;
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			if($_SESSION['lottest']<>$row['OCD_LOT_NO']){
		//		echo $row['OCD_LOT_NO']."<BR>";
				upper($row['OCD_LOT_NO'],$_GET['id'],$y,$objPHPExcel);
				$_SESSION['lottest']=$row['OCD_LOT_NO'];
				$y++;
			}
			if($i==0)
			{
				$objPHPExcel->getActiveSheet()->setCellValue("A".$num1,iconv("big5","utf-8",$row['OCD_LOT_NO'] ) );
				$objPHPExcel->getActiveSheet()->setCellValue("D".$num1,iconv("big5","utf-8",$row['OCD_DRUM_NO']) );
				$objPHPExcel->getActiveSheet()->setCellValue("F".$num1,iconv("big5","utf-8",$row['CPN']) );
				$objPHPExcel->getActiveSheet()->setCellValue("I".$num1,iconv("big5","utf-8",$row['OCD_PLT_NO']) );
			}
			if($i==1)
			{
				$objPHPExcel->getActiveSheet()->setCellValue("K".$num1,iconv("big5","utf-8",$row['OCD_LOT_NO'] ) );
				$objPHPExcel->getActiveSheet()->setCellValue("M".$num1,iconv("big5","utf-8",$row['OCD_DRUM_NO']) );
				$objPHPExcel->getActiveSheet()->setCellValue("R".$num1,iconv("big5","utf-8",$row['CPN']) );
				$objPHPExcel->getActiveSheet()->setCellValue("T".$num1,iconv("big5","utf-8",$row['OCD_PLT_NO']) );
			}
			$i++;$count++;
			if($i==2){
				$i=0;
				$num1++;
			}
			if((($count)%32)==0)
			{
				$GLOBALS['num']++;
				$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
				$num1='12';
				$i=0;
				$count=0;
				$y=5;
				upper($row['OCD_LOT_NO'],$_GET['id'],$y,$objPHPExcel);
				$_SESSION['lottest']=$row['OCD_LOT_NO'];
				$y++;
				
			}
		}
		$path_root=$_SERVER['HTTP_HOST'];
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
		$objWriter->save('print.xlsx');
		echo '</br>';
		echo "列印完成";
		echo '<script>document.location.href="http://'.$path_root.'/prodout/print.xlsx";</script>';	
	
}
function upper($lotno,$otdno,$y,$objPHPExcel){
		$query="SELECT  SUM(OPD_QTY_DRUM) as unit_sum, CD.CTD_CUST_SHORT_NAME  
						FROM      OUT_PRODUCT AS OP INNER JOIN
					   PRODUCT_DATA AS PD ON PD.PDD_PROD_NO = OP.PDD_PROD_NO LEFT OUTER JOIN
					   OUT_DECISION AS OD ON OD.OTD_NO = OP.OTD_NO LEFT OUTER JOIN
					   CUSTOMER_DATA AS CD ON OD.CTD_CUST_NO = CD.CTD_CUST_NO 
		where OP.OTD_NO='".$_GET['id']."' GROUP BY   CD.CTD_CUST_SHORT_NAME";
//		echo $query."<BR>";
	$reslut=mssql_query($query);
	$row=mssql_fetch_row($reslut);
	$cust_short_name=$row[1];
	$objPHPExcel->getActiveSheet()->setCellValue("L2",iconv("big5","utf-8",$cust_short_name) );
	$objPHPExcel->getActiveSheet()->setCellValue("S2",iconv("big5","utf-8",$_GET['id']) );
			$query="SELECT          EMPLOYEE_DATA.EMP_NAME, OUT_CHECK_DRUM.OCM_CHK_DATE, OUT_DECISION.OPM_ETA_DATE  
  FROM              OUT_CHECK_DRUM INNER JOIN EMPLOYEE_DATA ON OUT_CHECK_DRUM.OCM_CF_MAN = EMPLOYEE_DATA.EMP_NO INNER JOIN 
                            OUT_DECISION ON OUT_CHECK_DRUM.OTD_NO = OUT_DECISION.OTD_NO 
                            where OUT_CHECK_DRUM.OTD_NO='".$_GET['id']."' ";
  //                          	echo $query."<BR>";
		$result = mssql_query($query);
		$row = mssql_fetch_row($result);
		
		$objPHPExcel->getActiveSheet()->setCellValue("P29",iconv("big5","utf-8",$row[0] ));  // 出貨擔當
		$objPHPExcel->getActiveSheet()->setCellValue("C2",iconv("big5","utf-8",ddd($row[1])));   // 檢查日期
		$objPHPExcel->getActiveSheet()->setCellValue("G2",iconv("big5","utf-8",ddd($row[2])));
		
			$query="SELECT          PD.PDD_TYPE, OP.OPM_ORDER_NO, OP.OPD_SERIAL_NO, OP.OTD_NO, OP.OTN_NO, OP.OTNP_SERIAL_NO, 
                            OP.PDD_PROD_NO, OP.OAF_PACKAGE, OP.OPD_LOT_NO, OP.PRA_SERIAL_NO, OP.OPD_TERM_DATE, 
                            OP.OPD_VALIDATE, OP.OPD_QTY_DRUM, OP.OPD_ACC_UNIT, OP.OPD_QTY_LITER, OP.OPD_QTY_KG, 
                            OP.OPD_SIGN_RECEIPT, OP.OPD_COA_RECEIPT, OP.OPD_MEMO, OP.OPD_REAL_QTY_KG, 
                            OP.OPD_INWARD_DATE, OP.OPD_COA_NO, OP.COA_INDEX, OP.OAF_ACC_ID, OP.OPD_SMP_DATETIME, 
                            OP.OPD_COA_DATETIME, PD.PDD_PROD_NAME, CD.CTD_CUST_SHORT_NAME, OD.OPM_ETA_DATE, 
                            PD.PDD_STYLE, CD.CTD_CUST_NO, PD.PDD_CLASS 
FROM              OUT_DECISION AS OD FULL OUTER JOIN
                            OUT_PRODUCT AS OP INNER JOIN
                            PRODUCT_DATA AS PD ON PD.PDD_PROD_NO = OP.PDD_PROD_NO ON 
                            OD.OTD_NO = OP.OTD_NO FULL OUTER JOIN
                            CUSTOMER_DATA AS CD ON OD.CTD_CUST_NO = CD.CTD_CUST_NO  
	 where OP.OTD_NO='".$otdno."' and OP.OPD_LOT_NO='".$lotno."'";
//	echo $query."<BR>";
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result))
	{
		$savedate=substr(valid($row['PDD_CLASS'],$row['PDD_PROD_NO'],$row['CTD_CUST_NO'],$row['OPD_LOT_NO']),0,10);
		$objPHPExcel->getActiveSheet()->setCellValue("A".$y,iconv("big5","utf-8",$row['PDD_PROD_NAME']));			//
		$objPHPExcel->getActiveSheet()->setCellValue("F".$y,iconv("big5","utf-8",$row['OAF_PACKAGE']));
		$objPHPExcel->getActiveSheet()->setCellValue("J".$y,iconv("big5","utf-8",$row['OPD_LOT_NO']));
		$objPHPExcel->getActiveSheet()->setCellValue("O".$y,iconv("big5","utf-8",$savedate));
		$objPHPExcel->getActiveSheet()->setCellValue("S".$y,iconv("big5","utf-8",$row['OPD_QTY_DRUM']));	
		$y++;
	}
}
function valid($PDD_CLASS,$pid,$cid,$lid)
{
	$S=trim($lid);
	$T=substr($S,0,1);
	$len=strlen($S);
//	echo $T.":".$len."<BR>";
	if(($len==7 or $len==11))
	{   //TYS 自製品
		$query="SELECT CTP_VALID_MON, CTP_REMNANT_MON FROM CUSTOMER_PRODUCTS WHERE (CTD_CUST_NO = '".$cid."') AND (PDD_PROD_NO = '".$pid."')";
//		echo $query."<BR>";
		$result = mssql_query($query);
		$row=mssql_fetch_row($result);
		$n=$row[0];
		$query="SELECT FOD_YEAR_MONTH, FOD_DAY, FDM_LOT_NO FROM FILLPLAN_OUT_DECIDE WHERE (FDM_LOT_NO = '".$lid."')";
//		echo $query."<BR>";
		$result=mssql_query($query);
		$numrows=mssql_num_rows($result);
		$row=mssql_fetch_row($result);
		if($PDD_CLASS=='成品'){
			$d1=substr(trim($lid),3,4);
			$term_="202".substr($d1,0,1)._exmonth(substr($d1,1,1)).substr($d1,2,2);
		}
		else{
			$yr=substr(date("Y"),0,3).substr($lid,-4,1);
			$mo=_exmonth(substr($lid,-3,1));
			$day=substr($lid,-2,2);
			$term=$yr.$mo.$day;
		}
		
		$str="+".$n." month";
		if($term_<>''){$term=$term_;}
	//	echo "<BR>";
		$term_date=substr($term,0,4)."-".substr($term,4,2)."-".substr($term,6,2)." 00:00:00";
	//	echo  date("Y-m-d H:i:s",strtotime($term_date.$str));
	//	echo "<BR>";
		$stt=strtotime($term_date.$str);
		$stt=date("Y-m-d H:i:s",strtotime($term_date.$str));
		if(substr($stt,0,10)=='1970-01-01'){
			$term=date("Ymd");
			$term_date=substr($term,0,4)."-".substr($term,4,2)."-".substr($term,6,2)." 00:00:00";
			$stt=strtotime($term_date.$str);
			$stt=date("Y-m-d H:i:s",strtotime($term_date.$str));
		}
	}
	else{
		$stt='';
	}
	return $stt;
}
	
?>