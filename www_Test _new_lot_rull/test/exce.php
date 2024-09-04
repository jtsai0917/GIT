<?php
	$fl="../FormList/PrintAnalyze/anycounts.xlsx";
	include("../PHPEXCEL/Classes/PHPExcel.php");
	include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save("test.xlsx");
/*	echo '<script>document.location.href="http://'.$path_root.$fileurl.'.xlsx";</script>';	*/
?>