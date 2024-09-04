<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../PHPEXCEL18/Classes/PHPExcel.php");
include("../PHPEXCEL18/Classes/PHPExcel/IOFactory.php");
datepick();
?>

      充填報表<br />
<td><form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'];?>" onchange="set_date_session(this.name,this.value)">
  ~
  <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'];?>" onchange="set_date_session(this.name,this.value)">
  <input name="print" type="submit" id="print" value="  查詢  ">
  <input name="print1" type="submit" id="print" value="  列印結果  ">
  </form>

<?php
if(isset($_POST["print"]))
{	
	$query="SELECT  FILL_INDICATE.FDM_LOT_NO, Fill_Flow_Chart.flow, PRODUCT_DATA.PDD_PROD_SHORT_NAME, FILL_INDICATE.FID_FILL_BEGIN_DATE, FILL_INDICATE.FID_QTY FROM FILL_INDICATE INNER JOIN PRODUCT_DATA ON FILL_INDICATE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN Fill_Flow_Chart ON FILL_INDICATE.FDM_LOT_NO = Fill_Flow_Chart.Lot_No
			WHERE          (FILL_INDICATE.FID_FILL_BEGIN_DATE > '".dod($_POST['datepicker1'])."000000') AND (FILL_INDICATE.FID_FILL_BEGIN_DATE < '".dod($_POST['datepicker2'])."235959') AND (PRODUCT_DATA.PDD_CHEMICAL = 'H2SO4') order by FID_FILL_BEGIN_DATE ";

	echo '<table border="1">';
	echo '<tr><td>Lot No.</td><td>FLOWS</td><td>藥品簡稱</td><td>充填日期</td><td>充填量</td></tr>';
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		echo '<tr><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.std($row[3]).'</td><td>'.$row[4].'</td></tr>';
	}
	echo "<BR>";
}


if(isset($_POST["print1"]))
{
	$path_root=$_SERVER['HTTP_HOST'];
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("lot_list.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$query="SELECT  FILL_INDICATE.FDM_LOT_NO, Fill_Flow_Chart.flow, PRODUCT_DATA.PDD_PROD_SHORT_NAME, FILL_INDICATE.FID_FILL_BEGIN_DATE, FILL_INDICATE.FID_QTY FROM FILL_INDICATE INNER JOIN PRODUCT_DATA ON FILL_INDICATE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN Fill_Flow_Chart ON FILL_INDICATE.FDM_LOT_NO = Fill_Flow_Chart.Lot_No
			WHERE          (FILL_INDICATE.FID_FILL_BEGIN_DATE > '".dod($_POST['datepicker1'])."000000') AND (FILL_INDICATE.FID_FILL_BEGIN_DATE < '".dod($_POST['datepicker2'])."235959') AND (PRODUCT_DATA.PDD_CHEMICAL = 'H2SO4') order by FID_FILL_BEGIN_DATE ";

	$i=0;
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(0,$i+2,$row[0]);	
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(1,$i+2,mb_convert_encoding($row[1],"utf-8","big5"));	
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(2,$i+2,$row[2]);	
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(3,$i+2,std($row[3]));	
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(4,$i+2,$row[4]);		
		$i++;
	}	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('find3.xlsx');
	echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/find3.xlsx";</script>';
}
