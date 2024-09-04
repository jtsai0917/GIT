<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	include  "../PHPEXCEL/Classes/PHPExcel.php";
	include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$path_root=$_SERVER['HTTP_HOST'];
	lasturl();
	datepick();
	echo "樣品瓶號 ： ".$_GET['smpid'];
	echo '<form id="form1" action="" method="post"><input type="submit" name="print" value="列印" ></form>';
	
	$query="SELECT          CUSTOMER_DATA.CTD_CUST_SHORT_NAME, PRODUCT_DATA.PDD_CHEMICAL, Sample_Back.BACK_NAME, 
                            EMP1.EMP_NAME AS SMP_NAME, EMP2.EMP_NAME AS ANA_NAME, Sample.SMP_MAT, Sample.SMP_MID_ID, 
                            Sample.SMP_START, Sample_All.SMA_ID, Sample_All.SMA_TIMES, Sample_All.SMA_SERVICE, 
                            Sample_All.SMA_LOT, Sample_All.SMA_USER, Sample_All.SMA_OUT, Sample_All.SMA_SMP, Sample_All.SMA_ANA, 
                            Sample_All.SMA_BACK, Sample_All.SMA_SAVE, Sample_All.SMA_SAVE_TIME, Sample_All.ISREWORK, 
                            Sample_All.SMA_FINISH, Sample_All.SMA_JUNK, Sample_All.SMA_JUNK_D, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_SERIAL_NO, Sample_All.DHN_DRUM_NO, Sample_All.SMA_PREPSAMPLE_DATE, 
                            Sample_All.SMA_PREPSAMPLE_MAN, Sample_All.SMA_PREPSAMPLE_BACK_DATE, 
                            Sample_All.SMA_PREPSAMPLE_BACK_MAN, Sample_All.SMA_RETURN, Sample_All.SMA_RETURN_MAN, 
                            CASE WHEN SMA_SERVICE = 1 THEN '廠內分析' WHEN SMA_SERVICE = 2 THEN '隨貨或評估' WHEN SMA_SERVICE
                             = 3 THEN '保存樣品' WHEN SMA_SERVICE = 4 THEN '先行樣品' END AS 'SERVICE'
FROM              Sample_All INNER JOIN
                            Sample ON Sample.SMP_ID = Sample_All.SMA_ID LEFT OUTER JOIN
                            CUSTOMER_DATA ON CUSTOMER_DATA.CTD_CUST_NO = Sample_All.SMA_USER LEFT OUTER JOIN
                            PRODUCT_DATA ON PRODUCT_DATA.PDD_PROD_NO = Sample.SMP_MID_ID LEFT OUTER JOIN
                            EMPLOYEE_DATA AS EMP1 ON EMP1.EMP_NO = Sample_All.SMA_SMP LEFT OUTER JOIN
                            EMPLOYEE_DATA AS EMP2 ON EMP2.EMP_NO = Sample_All.SMA_ANA LEFT OUTER JOIN
                            Sample_Back ON Sample_Back.BACK_ID = Sample_All.SMA_BACK
WHERE          (Sample_All.SMA_ID = '".$_GET['smpid']."')";

	echo '<table width="" border="1">';
	echo '<tr bgcolor="#999999"><td width="40">次數</td><td width="50">材質</td><td width="50">Service</td><td width="50">藥品</td><td width="50">瓶號</td><td width="70">啟用日期</td>
	<td width="50">Lot No</td><td width="100">客戶</td><td width="50">出廠日</td><td width="50">取樣人</td><td width="50">分析人</td><td width="50">回收</td><td width="70">保存期限</td>
	<td width="70">回收期限</td><td width="70">廢棄原因</td><td width="70">廢棄日期</td></tr>';
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row=mssql_fetch_array($result))
	{
	echo '<tr><td>'.$row['SMA_TIMES'].'</td><td>'.$row['SMP_MAT'].'</td><td>'.$row['SERVICE'].'</td><td>'.$row['PDD_CHEMICAL'].'</td><td>'.$row['SMA_ID'].'</td><td>'.$row['SMP_START'].'</td><td>'
	.$row['SMA_LOT'].'</td><td>'.$row['CTD_CUST_SHORT_NAME'].'</td><td>'.$row['SMP_OUT'].'</td><td>'.$row['SMP_NAME'].'</td><td>'.$row['SMP_ANA'].'</td><td>'.$row['SMA_BACK'].'</td><td>'.$row['SMA_SAVE'].'</td><td>'
	.$row['SMA_FINISH'].'</td><td>'.$row['SMA_JUNK'].'</td><td>'.$row['SMA_JUNK_D'].'</td></tr>';
	}
	echo '</table>';
	
if($_POST['print']){
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("sample.xlsx");	
	
    $objPHPExcel->setActiveSheetIndex(0);
	$query="SELECT          CUSTOMER_DATA.CTD_CUST_SHORT_NAME, PRODUCT_DATA.PDD_CHEMICAL, Sample_Back.BACK_NAME, 
                            EMP1.EMP_NAME AS SMP_NAME, EMP2.EMP_NAME AS ANA_NAME, Sample.SMP_MAT, Sample.SMP_MID_ID, 
                            Sample.SMP_START, Sample_All.SMA_ID, Sample_All.SMA_TIMES, Sample_All.SMA_SERVICE, 
                            Sample_All.SMA_LOT, Sample_All.SMA_USER, Sample_All.SMA_OUT, Sample_All.SMA_SMP, Sample_All.SMA_ANA, 
                            Sample_All.SMA_BACK, Sample_All.SMA_SAVE, Sample_All.SMA_SAVE_TIME, Sample_All.ISREWORK, 
                            Sample_All.SMA_FINISH, Sample_All.SMA_JUNK, Sample_All.SMA_JUNK_D, Sample_All.SMA_DRUMNO, 
                            Sample_All.SMA_SERIAL_NO, Sample_All.DHN_DRUM_NO, Sample_All.SMA_PREPSAMPLE_DATE, 
                            Sample_All.SMA_PREPSAMPLE_MAN, Sample_All.SMA_PREPSAMPLE_BACK_DATE, 
                            Sample_All.SMA_PREPSAMPLE_BACK_MAN, Sample_All.SMA_RETURN, Sample_All.SMA_RETURN_MAN, 
                            CASE WHEN SMA_SERVICE = 1 THEN '廠內分析' WHEN SMA_SERVICE = 2 THEN '隨貨或評估' WHEN SMA_SERVICE
                             = 3 THEN '保存樣品' WHEN SMA_SERVICE = 4 THEN '先行樣品' END AS 'SERVICE'
			FROM              Sample_All INNER JOIN
                            Sample ON Sample.SMP_ID = Sample_All.SMA_ID LEFT OUTER JOIN
                            CUSTOMER_DATA ON CUSTOMER_DATA.CTD_CUST_NO = Sample_All.SMA_USER LEFT OUTER JOIN
                            PRODUCT_DATA ON PRODUCT_DATA.PDD_PROD_NO = Sample.SMP_MID_ID LEFT OUTER JOIN
                            EMPLOYEE_DATA AS EMP1 ON EMP1.EMP_NO = Sample_All.SMA_SMP LEFT OUTER JOIN
                            EMPLOYEE_DATA AS EMP2 ON EMP2.EMP_NO = Sample_All.SMA_ANA LEFT OUTER JOIN
                            Sample_Back ON Sample_Back.BACK_ID = Sample_All.SMA_BACK
			WHERE          (Sample_All.SMA_ID = '".$_GET['smpid']."') order by SMP_TIMES";	
			
	$result=mssql_query($query);
	$x=2;
	while($row=mssql_fetch_array($result)){
		$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$x,iconv("big5","utf-8",$row['SMA_TIMES']))
							->setCellValue('B'.$x,iconv("big5","utf-8",$row['SMP_MAT']))
							->setCellValue('C'.$x,iconv("big5","utf-8",$row['SERVICE']))
							->setCellValue('D'.$x,iconv("big5","utf-8",$row['PDD_CHEMICAL']))
							->setCellValue('E'.$x,iconv("big5","utf-8",$row['SMA_ID']))
							->setCellValue('F'.$x,iconv("big5","utf-8",$row['SMP_START']))
							->setCellValue('G'.$x,iconv("big5","utf-8",$row['SMA_LOT']))
							->setCellValue('H'.$x,iconv("big5","utf-8",$row['CTD_CUST_SHORT_NAME']))
							->setCellValue('I'.$x,iconv("big5","utf-8",$row['SMP_OUT']))
							->setCellValue('J'.$x,iconv("big5","utf-8",$row['SMP_NAME']))
							->setCellValue('K'.$x,iconv("big5","utf-8",$row['SMP_BACK']))
							->setCellValue('L'.$x,iconv("big5","utf-8",$row['SMA_SAVE']))
							->setCellValue('M'.$x,iconv("big5","utf-8",$row['SMA_FINISH']))
							->setCellValue('N'.$x,iconv("big5","utf-8",$row['SMA_JUNK']))
							->setCellValue('O'.$x,iconv("big5","utf-8",$row['SMA_JUNK_D']))
							;
		$x=$x+1;
	}
		
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("tmp/tmp11.xlsx");
	echo '<script>document.location.href="http://'.$path_root.'/samples/tmp/tmp11.xlsx";</script>';		
}
function sma_service_type($sma_service)
{
	if($sma_service==1){return "廠內分析";} 
	if($sma_service==2){return "隨貨或評估";} 
	if($sma_service==3){return "保存樣品";}
	if($sma_service==4){return "先行樣品";}	
}

?>
 <style type="text/css">
 body,td,th {
	font-size: 12px;
}
 </style>
