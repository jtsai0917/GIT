<?php
	session_start();
	$path=dirname(__FILE__);
	include($path."/../lib/fun.php");
	include($path."/../checkuser.php");
	include($path."/../lib/jtsai.php");
	include($path."/sinetics_lib.php");
	include $path."/../PHPEXCEL/Classes/PHPExcel.php";
	include $path."/../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	
	$path_root=$_SERVER['HTTP_HOST'];
	$particle_trend_chart_st3=$path."/form/particle_trend_chart_st3.xlsx";
	$particle_trend_chart_basic=$path."/form/particle_trend_chart_basic.xlsx";
	

	$objReader = PHPExcel_IOFactory::createReader('Excel2007'); 
	$objReader->setIncludeCharts(TRUE); 	
	$objPHPExcel= $objReader->load($particle_trend_chart_st3); 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
	$objWriter->setIncludeCharts(TRUE); 

	$objWriter->save($path."/tmp/tmp.xlsx");
//	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
//	$objWriter->save($path."/tmp/tmp.xlsx"); 
	echo '<script>document.location.href="http://'.$path_root.'/cal/tmp/tmp.xlsx";</script>';	

?>