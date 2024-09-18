
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title></title>
Drum 出荷檢查表</br>
<form method="post" action="<?php echo $loginFormAction; ?>">
<input type="submit" name="print" id="print" value="列印1" />
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
global $times;
$GLOBALS['num']=0;


if(isset($_POST['leave']))
	{
		jumpto('http://10.181.140.66/prodout/index.php?url=drum_check_list');
	}
if(isset($_POST['print']))
{		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("./Drum_check.xlsx");
		$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
		
		$lotnum='B14';
		$savetime='J14';
		$qtynum='O14';
		
		$query="SELECT  PD.PDD_TYPE, OP.OPM_ORDER_NO, OP.OPD_SERIAL_NO, OP.OTD_NO, OP.OTN_NO, OP.OTNP_SERIAL_NO, 
                   OP.PDD_PROD_NO, OP.OAF_PACKAGE, OP.OPD_LOT_NO, OP.PRA_SERIAL_NO, OP.OPD_TERM_DATE, 
                   OP.OPD_VALIDATE, OP.OPD_QTY_DRUM, OP.OPD_ACC_UNIT, OP.OPD_QTY_LITER, OP.OPD_QTY_KG, 
                   OP.OPD_SIGN_RECEIPT, OP.OPD_COA_RECEIPT, OP.OPD_MEMO, OP.OPD_REAL_QTY_KG, OP.OPD_INWARD_DATE, 
                   OP.OPD_COA_NO, OP.COA_INDEX, OP.OAF_ACC_ID, OP.OPD_SMP_DATETIME, OP.OPD_COA_DATETIME, 
                   PD.PDD_PROD_NAME, CD.CTD_CUST_SHORT_NAME, OD.OPM_ETA_DATE, PD.PDD_STYLE, CD.CTD_CUST_NO 
FROM      OUT_PRODUCT AS OP INNER JOIN
                   PRODUCT_DATA AS PD ON PD.PDD_PROD_NO = OP.PDD_PROD_NO LEFT OUTER JOIN
                   OUT_DECISION AS OD ON OD.OTD_NO = OP.OTD_NO LEFT OUTER JOIN
                   CUSTOMER_DATA AS CD ON OD.CTD_CUST_NO = CD.CTD_CUST_NO 
	 where OP.OTD_NO='".$_GET['id']."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$prod=$prod.$row['PDD_PROD_NAME'].";";
		$lot1=$lot1.$row['OPD_LOT_NO'].';';
		$eta_date=$row['OPM_ETA_DATE'];
		$cust=$cust.$row['CTD_CUST_SHORT_NAME'].";";
		$qty=$qty+$row['OPD_QTY_DRUM'];
		$savedate=substr(valid($row['OPD_TERM_DATE'],$row['PDD_PROD_NO'],$row['CTD_CUST_NO'],$row['OPD_LOT_NO']),0,10);
		$objPHPExcel->getActiveSheet()->setCellValue("J11",iconv("big5","utf-8",$row['PDD_STYLE']) );
		$objPHPExcel->getActiveSheet()->setCellValue($qtynum,iconv("big5","utf-8",$row['OPD_QTY_DRUM']) );$qtynum++;
		$objPHPExcel->getActiveSheet()->setCellValue($lotnum,iconv("big5","utf-8",$row['OPD_LOT_NO']) );$lotnum++;
		$objPHPExcel->getActiveSheet()->setCellValue($savetime,iconv("big5","utf-8",$savedate) );$savetime++;	
		if(substr($row['PDD_PROD_NO'],-3)=='020')
		{
			$L20='OK';
		}
		
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B7",iconv("big5","utf-8",$_GET['id']) );
	$objPHPExcel->getActiveSheet()->setCellValue("B8",iconv("big5","utf-8",$cust) );
	$objPHPExcel->getActiveSheet()->setCellValue("B9",iconv("big5","utf-8",$prod) );
	$objPHPExcel->getActiveSheet()->setCellValue("B10",iconv("big5","utf-8",$lot1) );
	$objPHPExcel->getActiveSheet()->setCellValue("J12",iconv("big5","utf-8",$qty) );		
		$query="select * from OUT_CHECK_DRUM where OTD_NO='".$_GET['id']."' ";
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			$objPHPExcel->getActiveSheet()->setCellValue("O25",iconv("big5","utf-8",$row['OCM_PLT_NAME']) );
			$objPHPExcel->getActiveSheet()->setCellValue("O26",iconv("big5","utf-8",$row['OCM_PLT_STYLE']) );
			$objPHPExcel->getActiveSheet()->setCellValue("A2",iconv("big5","utf-8","檢查日期".$row['OCM_CHK_DATE']) );
			$objPHPExcel->getActiveSheet()->setCellValue("A3",iconv("big5","utf-8","出貨日期".substr($eta_date,0,8)) );
			$objPHPExcel->getActiveSheet()->setCellValue("K47",iconv("big5","utf-8",get_uname($row['OCM_CF_MAN']) ));


		}
	if($L20=='OK')
	{}
	else
	{
		
		$num1='29';
		$i=0;
		$count=0;
		$type=0;
		$query="select * from OUT_CHECK_DRUM_DETAIL where OTD_NO='".$_GET['id']."'";	
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			if($i==0)
			{
			$objPHPExcel->getActiveSheet()->setCellValue("A".$num1,iconv("big5","utf-8",$row['OCD_LOT_NO'] ) );
			$objPHPExcel->getActiveSheet()->setCellValue("B".$num1,iconv("big5","utf-8",$row['OCD_DRUM_NO']) );
			$objPHPExcel->getActiveSheet()->setCellValue("D".$num1,iconv("big5","utf-8",$row['OCD_PLT_NO']) );
			}
			if($i==1)
			{
			$objPHPExcel->getActiveSheet()->setCellValue("I".$num1,iconv("big5","utf-8",$row['OCD_LOT_NO'] ) );
			$objPHPExcel->getActiveSheet()->setCellValue("L".$num1,iconv("big5","utf-8",$row['OCD_DRUM_NO']) );
			$objPHPExcel->getActiveSheet()->setCellValue("O".$num1,iconv("big5","utf-8",$row['OCD_PLT_NO']) );
			}
			$i++;$count++;
			if($i==2){$i=0;$num1++;}
			if((($count)%34)==0)
			{
				$GLOBALS['num']++;
				$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
				
				$num1='29';
				$i=0;
				$count=0;
				/////
					$lotnum='B14';
		$savetime='J14';
		$qtynum='O14';
		
		$query1="select PD.PDD_TYPE, PD.PDD_PROD_NO, PD.PDD_STYLE, OP.*,PD.PDD_PROD_NAME,CD.CTD_CUST_SHORT_NAME ,CD.CTD_CUST_NO 
		from Out_Product as OP
	inner join PRODUCT_DATA as PD on PD.PDD_PROD_NO=OP.PDD_PROD_NO
	left join OUT_DECISION as OD on OD.OTD_NO=OP.OTD_NO
	left join CUSTOMER_DATA as CD on OD.CTD_CUST_NO=CD.CTD_CUST_NO
	 where OP.OTD_NO='".$_GET['id']."'";
//	echo $query1;
//	echo "<BR>";
	$result1 = mssql_query($query1);
	while($row1 = mssql_fetch_array($result1))
	{
		$prod=$prod.$row1['PDD_PROD_NAME'].";";
		$lot1=$lot1.$row1['OPD_LOT_NO'].';';
		$cust=$cust.$row1['CTD_CUST_SHORT_NAME'].";";
		$qty=$qty+$row1['OPD_QTY_DRUM'];
		$savedate=substr(valid($row1['OPD_TERM_DATE'],$row1['PDD_PROD_NO'],$row1['CTD_CUST_NO']),0,10);
		$objPHPExcel->getActiveSheet()->setCellValue($qtynum,iconv("big5","utf-8",$row1['OPD_QTY_DRUM']) );$qtynum++;
		$objPHPExcel->getActiveSheet()->setCellValue($lotnum,iconv("big5","utf-8",$row1['OPD_LOT_NO']) );$lotnum++;		
		$objPHPExcel->getActiveSheet()->setCellValue($savetime,iconv("big5","utf-8",$savedate) );$savetime++;
		$objPHPExcel->getActiveSheet()->setCellValue("J11",iconv("big5","utf-8",$row['PDD_STYLE']) );	
		if(substr($row1['PDD_PROD_NO'],-3)=='020')
		{
			$L20='OK';
		}
		
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B7",iconv("big5","utf-8",$_GET['id']) );
	$objPHPExcel->getActiveSheet()->setCellValue("B8",iconv("big5","utf-8",$cust) );
	$objPHPExcel->getActiveSheet()->setCellValue("B9",iconv("big5","utf-8",$prod) );
	$objPHPExcel->getActiveSheet()->setCellValue("B10",iconv("big5","utf-8",$lot1) );
	
	$objPHPExcel->getActiveSheet()->setCellValue("J12",iconv("big5","utf-8",$qty) );
		
		$query2="select * from OUT_CHECK_DRUM where OTD_NO='".$_GET['id']."' ";
		$result2 = mssql_query($query2);
		while($row2 = mssql_fetch_array($result2))
		{
			$objPHPExcel->getActiveSheet()->setCellValue("O25",iconv("big5","utf-8",$row2['OCM_PLT_NAME']) );
			$objPHPExcel->getActiveSheet()->setCellValue("O26",iconv("big5","utf-8",$row2['OCM_PLT_STYLE']) );
			$objPHPExcel->getActiveSheet()->setCellValue("A2",iconv("big5","utf-8","檢查日期".$row2['OCM_CHK_DATE']) );
			$objPHPExcel->getActiveSheet()->setCellValue("A3",iconv("big5","utf-8","出貨日期".substr($eta_date,0,8)) );
			$objPHPExcel->getActiveSheet()->setCellValue("K47",iconv("big5","utf-8",get_uname($row2['OCM_CF_MAN']) ));


		}
				/////
				
			}//if count
		}//while
		$path_root=$_SERVER['HTTP_HOST'];
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('print.xlsx');
			echo '</br>';
			echo "列印完成";
		echo '<script>document.location.href="http://'.$path_root.'/prodout/print.xlsx";</script>';	
		
	}
	
	
}

function valid($term,$pid,$cid,$lid)
{
	$query="SELECT  CTP_VALID_MON, CTP_REMNANT_MON
FROM      CUSTOMER_PRODUCTS
WHERE   (CTD_CUST_NO = '".$cid."') AND (PDD_PROD_NO = '".$pid."')";
//	echo $query;
//	echo "<BR>";
//	echo $term;
//	echo "<BR>";
	$result = mssql_query($query);
	$row=mssql_fetch_row($result);
	$n=$row[0]-$row[1];
$str="+".$n." month";
//	echo "<BR>";
	$term_date=substr($term,0,4)."-".substr($term,4,2)."-".substr($term,6,2)." 00:00:00";
//	echo  date("Y-m-d H:i:s",strtotime($term_date.$str));
//	echo "<BR>";
	return date("Y-m-d H:i:s",strtotime($term_date.$str));
}
	
?>