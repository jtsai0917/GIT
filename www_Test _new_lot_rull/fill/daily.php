<?php
	session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
datepick();

global $num;
$num=0;
?>

      充填日報表<br />
<td><form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php 
		if($_SESSION['datepicker1']<>''){echo $_SESSION['datepicker1'];}
		?>" readonly/ onchange="set_date_session(this.name,this.value)">
  <input name="print" type="submit" id="print" value="  查詢  ">
  <input name="print1" type="submit" id="print" value="  列印結果  ">
  </form>

<?php
if(isset($_POST["print"]))
{	$i=5;

	$date2=substr($_POST['datepicker1'],-4)."年".substr($_POST['datepicker1'],0,2)."月".substr($_POST['datepicker1'],3,2)."日";
	
	$date1=dod($_POST['datepicker1']);
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./ttest.xlsx");
	$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
echo '<table border="1">';
echo '<tr><td>充填日</td><td>料號</td><td>品名</td><td>LorryNo</td><td>充填量</td><td>DRUM容量</td><td>數量</td><td>荷姿</td><td>數量</td><td>Lot NO</td><td>客戶</td>';
$query="select
A.FID_FILL_BEGIN_DATE as N1,
case when A.FillType='TOTO' then PDD.PDD_PROD_NAME else PDD.PDD_CHEMICAL END AS N2, 
case when FillType='LORRY' or FillType='TOTO' then SUBSTRING(A.FDM_LOT_NO, 8, 4) else Null end as N3, 
case when FillType='LORRY' then cast(A.Qty as varchar) + ' L' when FillType='TOTO' then cast(FOD.FDM_QTY as varchar) + ' L' else Null end as N4, 
case when FillType='DRUM'  or FillType='BTL'  then SUBSTRING(A.FDM_LOT_NO, 8, 4)  else Null end as N5, 
case when FillType='DRUM'  or FillType='BTL'  then FDMC.FDMC_DRUM_COUNT else null end as N6, 
(SELECT COUNT(*) FROM Sample_All WHERE (SMA_ID LIKE 'T%') and (SMA_LOT = A.FDM_LOT_NO)) AS N7, 
(SELECT COUNT(*) FROM Sample_All WHERE (SMA_ID LIKE 'E%') and (SMA_LOT = A.FDM_LOT_NO)) AS N8, 
SUBSTRING(A.FDM_LOT_NO, 1, 7) AS N9,CTD.CTD_CUST_SHORT_NAME AS N10, A.PDD_PROD_NO AS N11 
from ( 
select distinct 'Drum' as FillType, PDD_PROD_NO, LEFT(A.FID_FILL_BEGIN_DATE, 4) + '/' + SUBSTRING(A.FID_FILL_BEGIN_DATE, 5, 2) + '/' + SUBSTRING(A.FID_FILL_BEGIN_DATE, 7, 2) AS FID_FILL_BEGIN_DATE ,A.FDM_LOT_NO,null as Qty 
from FILL_INDICATE A inner join EL_FILLDATA_DRUM B on A.FDM_LOT_NO= B.FDM_LOT_NO WHERE LEFT(A.FID_FILL_END_DATE, 8) = '".$date1."' 
Union All 
select distinct 'BTL' as FillType, PDD_PROD_NO, LEFT(A.FID_FILL_BEGIN_DATE, 4) + '/' + SUBSTRING(A.FID_FILL_BEGIN_DATE, 5, 2) + '/' + SUBSTRING(A.FID_FILL_BEGIN_DATE, 7, 2) AS FID_FILL_BEGIN_DATE ,A.FDM_LOT_NO,null as Qty 
from FILL_INDICATE A inner join [20L_FILL_CHECK_DRUM] B on A.FDM_LOT_NO= B.FDM_LOT_NO WHERE LEFT(A.FID_FILL_END_DATE, 8) = '".$date1."' 
Union All 
select distinct 'TOTO' as FillType, PDD_PROD_NO, LEFT(A.FID_FILL_BEGIN_DATE, 4) + '/' + SUBSTRING(A.FID_FILL_BEGIN_DATE, 5, 2) + '/' + SUBSTRING(A.FID_FILL_BEGIN_DATE, 7, 2) AS FID_FILL_BEGIN_DATE ,A.FDM_LOT_NO,null as Qty 
from FILL_INDICATE A inner join [IN2LORRY_CAL_TOTO_CHECK] B on A.FDM_LOT_NO= B.FDM_LOT_NO WHERE LEFT(A.FID_FILL_END_DATE, 8) = '".$date1."' 
Union All 
select distinct 'LORRY' as FillType, PDD_PROD_NO, LEFT(A.FID_FILL_BEGIN_DATE, 4) + '/' + SUBSTRING(A.FID_FILL_BEGIN_DATE, 5, 2) + '/' + SUBSTRING(A.FID_FILL_BEGIN_DATE, 7, 2) AS FID_FILL_BEGIN_DATE ,A.FDM_LOT_NO,B.LFC_E_OPER_FILL as Qty 
from FILL_INDICATE A inner join [LORRY_FILL_CHECK] B on A.FDM_LOT_NO= B.FDM_LOT_NO WHERE LEFT(A.FID_FILL_END_DATE, 8) = '".$date1."') A 
LEFT JOIN FILLPLAN_OUT_DECIDE FOD       ON FOD.FDM_LOT_NO = A.FDM_LOT_NO 
LEFT JOIN PRODUCT_DATA PDD              ON PDD.PDD_PROD_NO = A.PDD_PROD_NO LEFT JOIN FILLPLAN_DRUM_CUSTOMER FDMC   ON FDMC.FDM_LOT_NO = A.FDM_LOT_NO 
LEFT JOIN CUSTOMER_DATA CTD             ON CTD.CTD_CUST_NO = case when FillType='DRUM' or FillType='BTL' then FDMC.CTD_CUST_NO else FOD.CTD_CUST_NO end 
ORDER BY Case FillType when 'LORRY' then 1 when 'TOTO' then 2 when 'Drum' then 3 else 4 end,Case case when A.FillType='TOTO' then PDD.PDD_PROD_NAME else PDD.PDD_CHEMICAL END when 'IPA' then 1 when 'H2SO4' then 1.5 when 'H2O2' then 2 when 'NH4OH' then 3 when 'HF' then 4 when '3MAE' then 5 else 6 end, A.FDM_LOT_NO";

// echo "<BR>".$query."<BR>";
$resullt=mssql_query($query);
	while($row=mssql_fetch_array($resullt))
	{
		echo '<tr>';
		echo '<td>'.$row['N1'].'</td><td>'.$row['N11'].'</td><td>'.$row['N2'].'</td><td>'.$row['N3'].
		'</td><td>'.$row['N4'].'</td><td>'.$row['N5'].'</td><td>'.$row['N6'].'</td><td>'.$row['N7'].
		'</td><td>'.$row['N8'].
		'</td><td>'.$row['N9'].'</td><td>'.$row['N10'].'</td>';
		echo '</tr>';
		
		
		$objPHPExcel->getActiveSheet()->setCellValue("A".$i,$row['N1']);
		$objPHPExcel->getActiveSheet()->setCellValue("B".$i,$row['N2']);
		$objPHPExcel->getActiveSheet()->setCellValue("C".$i,$row['N3']);
		$objPHPExcel->getActiveSheet()->setCellValue("D".$i,$row['N4']);
		$objPHPExcel->getActiveSheet()->setCellValue("E".$i,$row['N5']);
		$objPHPExcel->getActiveSheet()->setCellValue("F".$i,$row['N6']);
		$objPHPExcel->getActiveSheet()->setCellValue("G".$i,"PFA");
		$objPHPExcel->getActiveSheet()->setCellValue("G".($i+1),"PE");
		$objPHPExcel->getActiveSheet()->setCellValue("H".$i,$row['N7']);
		$objPHPExcel->getActiveSheet()->setCellValue("H".($i+1),$row['N8']);
		$objPHPExcel->getActiveSheet()->setCellValue("I".$i,$row['N9']);	
		$objPHPExcel->getActiveSheet()->setCellValue("J".$i,iconv("big5","utf-8",$row['N10']));
		$objPHPExcel->getActiveSheet()->setCellValue("I2",iconv("big5","utf-8",$date2));
		$i=$i+2;
		if($i>19)
		{	
			$GLOBALS['num']++;
			$objPHPExcel->setActiveSheetIndex($GLOBALS['num']);
			$i=5;
			
		}
		
	}
		
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('dayreport.xlsx');

			echo '</table>';echo '</br>';

			echo '</br>';echo '</br>';
}
if(isset($_POST["print1"]))
{$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';
			echo "列印完成";
		echo '<script>document.location.href="http://'.$path_root.'/fill/dayreport.xlsx";</script>';	
}