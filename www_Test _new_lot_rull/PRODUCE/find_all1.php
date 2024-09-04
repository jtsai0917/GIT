
<?php
include("../PHPEXCEL18/Classes/PHPExcel.php");
include("../PHPEXCEL18/Classes/PHPExcel/IOFactory.php");
?>
<form id="form1" name="form1" method="post" action="">
指定藥品查詢
<input name="submit1" type="submit" id="submit1" value=" 指定藥品查詢 ">
</form>
<?php
$path_root=$_SERVER['HTTP_HOST'];
		
if(isset($_POST['submit1']))
{
		echo "http://$path_root/PRODUCE/find2.xlsx";
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("QC1.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValueByColumnAndRow(0,1, iconv("big5","utf-8","LOT NO"));		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
		$objWriter->save('find2.xlsx');
		echo '</br>';
		echo "下載";
		echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/find2.xlsx";</script>';
}
?>