<?php
header('Content-type: text/html; charset=utf-8');
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
$loginFormAction = $_SERVER['PHP_SELF'];
$path_root=$_SERVER['HTTP_HOST'];
	$_SESSION['d2']=$_GET['d2'];
	$_SESSION['d1']=$_GET['d1'];
	$_SESSION['lot1']=$lot1=substr($_GET['d1'],-1).exmonth(substr($_GET['d1'],0,2)).substr($_GET['d1'],3,2);
	$_SESSION['lot2']=$lot1=substr($_GET['d2'],-1).exmonth(substr($_GET['d2'],0,2)).substr($_GET['d2'],3,2);

//////////////////////////////////////////////////////////
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$fileurl="./form/tmp";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("./form/analyze_order.xlsx");	
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

	include("../connections/conn.php");
	$query="SELECT    DISTINCT      AnalyzeDesign.AND_APPLY_DATE, AnalyzeDesign.AND_NEED_NO, AnalyzeDesign.AND_LOT_NO, 
                            AnalyzeDesign.AND_GOODS, AnalyzeDesign.CTD_CUST_NO, AnalyzeDesign.AND_BEFORE, 
                            AnalyzeDesign.AND_SMP_DATETIME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_ANA_ID, 
                            AnalyzeDesign.AND_VALUE, AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_TOTAL, 
                            AnalyzeDesign.AND_OUT_QTY, AnalyzeDesign.AND_OUT_DATETIME, AnalyzeDesign.AND_REPORT_DATETIME, 
                            AnalyzeDesign.AND_MARK, AnalyzeDesign.AND_GET_QTY, AnalyzeDesign.AND_GET_DATETIME, 
                            AnalyzeDesign.AND_RESULT_DATETIME, AnalyzeDesign.AND_RESULT, 
                            AnalyzeDesign.AND_PERSON, AnalyzeDesign.ALM_IDENTITY51, AnalyzeDesign.ALM_IDENTITY52, 
                            AnalyzeDesign.ALM_IDENTITY53, AnalyzeDesign.ALM_IDENTITY54, AnalyzeDesign.ALM_IDENTITY55, 
                            AnalyzeDesign.ALM_IDENTITY56, AnalyzeDesign.ALM_IDENTITY13, PRODUCT_DATA.PDD_PROD_NAME, 
                            EMPLOYEE_DATA.EMP_NAME,PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_PACKAGE, AnalyzeDesign.AND_CANCEL,
							PRODUCT_DATA.PDD_TYPE
FROM              AnalyzeDesign INNER JOIN
                            EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO
WHERE          (AND_APPLY_DATE <> '') ";
if ($_SESSION['lot_no']<>""){
		$query.=" AND (AnalyzeDesign.AND_LOT_NO like '%".$_SESSION['lot_no']."%')";
}
else{
	$query.=" AND (((SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 2, 4) >= '".$_SESSION['lot1']."') AND 
                          (SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 2, 4) <= '".$_SESSION['lot2']."')) or ((SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 7, 4) >= '".$_SESSION['lot1']."') AND 
                          (SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 7, 4) <= '".$_SESSION['lot2']."')) or ((SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 4, 4) >= '".$_SESSION['lot1']."') AND 
                          (SUBSTRING(dbo.AnalyzeDesign.AND_LOT_NO, 4, 4) <= '".$_SESSION['lot2']."')))";

}
$query.= " order by PDD_TYPE desc, PRODUCT_DATA.PDD_PROD_NO";
$result = mssql_query($query);
$x=1;$y=3;
$numRows=mssql_num_rows($result);
if($numRows>0){
	while($row = mssql_fetch_array($result)){
		$hh=new get_from_lot_no;
		$hh->lid=$row['AND_LOT_NO'];
		$hh->cid();
		$hh->ani();
		if($row['AND_NEED_NO']<=150){$tmp="製品";}
		if(($row['AND_NEED_NO']<=250) and ($row['AND_NEED_NO']>150)){$tmp="解析";}
		if($row['AND_NEED_NO']>270){$tmp="受入";}
		$I1="( ".substr($row['AND_APPLY_DATE'],4,2)."  月  ".substr($row['AND_APPLY_DATE'],6,2)."  日  )";
		$a[$x]=$row['AND_REPORT_DATETIME'];
		$b[$x]=get_prod_name($row['AND_GOODS']);
		$c[$x]=$row['PDD_PACKAGE'];
		$d[$x]=$row['AND_LOT_NO'];
		$e[$x]=$tmp;
		$f[$x]=$row['AND_SMP_DATETIME'];
		$g[$x]=$hh->ani_group();
		$h[$x]=$row['AND_TOTAL'];	
		if($hh->cname=='TYS Internal'){$cname='';}
		else{$cname=$hh->cname;}
		$i[$x]=$cname;	
		$k[$x]=$hh->outdate;
		$l[$x]=$row['AND_REPORT_DATETIME'];	
		if($row['AND_CANCEL']==1){$row['AND_CANCEL']='取消';}
		else{$row['AND_CANCEL']='';}
		$o[$x]=$row['AND_CANCEL'];
		$x=$x+1;	
	}	
}
$obj=$objPHPExcel->setActiveSheetIndex(0);
			$obj->getColumnDimension('A')->setWidth(8);
			$obj->getColumnDimension('B')->setWidth(8);
			$obj->getColumnDimension('C')->setWidth(8);
			$obj->getColumnDimension('D')->setWidth(11);
			$obj->getColumnDimension('E')->setWidth(8);
			$obj->getColumnDimension('F')->setWidth(8);
			$obj->getColumnDimension('G')->setWidth(30);
			$obj->getColumnDimension('H')->setWidth(10);
			$obj->getColumnDimension('I')->setWidth(10);
			$obj->getColumnDimension('J')->setWidth(8);
			$obj->getColumnDimension('K')->setWidth(10);
			$obj->getColumnDimension('L')->setWidth(10);
			$obj->getColumnDimension('M')->setWidth(8);
			$obj->getColumnDimension('N')->setWidth(10);
			$obj->getColumnDimension('O')->setWidth(10);	
			
	for($x=1;$x<($numRows+1);$x++){	
		for($z=0;$z<8;$z++){
		$obj->mergeCells('I'.($y).":J".($y));
		if($b[$x]){
		$obj->setCellValue('A'.$y,"○");}
		$obj->getStyle("'A".($y)."'")->getFont()->setSize(24);
		$obj->setCellValue('B'.$y,iconv("big5","utf-8",$b[$x]));
		$obj->getStyle("'B".($y)."'")->getFont()->setSize(9);
		$obj->setCellValue('C'.$y,iconv("big5","utf-8",$c[$x]));
		$obj->setCellValue('D'.$y,$d[$x]);
		$obj->setCellValue('E'.$y,$e[$x]);
		$obj->getStyle("'B".($y).":O".($y)."'")->getFont()->setSize(9);
		if($f[$x]<>''){$value_date2=substr($f[$x],4,2)."/".substr($f[$x],6,2)."\n".substr($f[$x],8,2).":".substr($f[$x],10,2);}
		else{$value_date2=NULL;}
		$obj->setCellValue('F'.$y,$value_date2);
		$obj->setCellValue('G'.$y,$g[$x]);
		$obj->setCellValue('H'.$y,$h[$x]);
		$obj->setCellValue('I'.$y,iconv("big5","utf-8",$i[$x]));
		if($k[$x]<>''){$value_date=substr($k[$x],4,2)."/".substr($k[$x],6,2);}
		else{$value_date='';}
		$obj->setCellValue('K'.$y,$value_date);
		if($l[$x]<>''){$value_date1=substr($l[$x],4,2)."/".substr($l[$x],6,2)."\n".substr($l[$x],8,2).":".substr($l[$x],10,2);}
		else{$value_date1='';}
		$obj->setCellValue('L'.$y,$value_date1);
		$obj->setCellValue('O'.$y,$o[$x]);
		$obj->getRowDimension($y)->setRowHeight(50);
		$obj->getStyle("'A".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'B".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'C".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'D".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'E".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'F".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'G".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'H".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'I".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'J".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'K".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'L".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'M".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'N".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'O".$y."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'A".($y).":O".$y."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$obj->getStyle("'A".($y).":O".$y."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$obj->getStyle("'A".$y.":O".$y."'")->getAlignment()->setWrapText(true);
		$x=$x+1;
		$y=$y+1;
		}
		$y=$y+4;
		$x=$x-1;
		$obj->getRowDimension($y-1)->setRowHeight(21);
		$obj->getRowDimension($y-2)->setRowHeight(41.25);
		$obj->getRowDimension($y-3)->setRowHeight(15.75);
		$obj->getRowDimension($y-4)->setRowHeight(35.25);
		$obj->mergeCells('A'.($y-4).":O".($y-4));
		$obj->getStyle("'A".($y-4)."'")->getFont()->setSize(9);
		$sss="A=濃度、M=金屬、P=微粒子、I=陰離子；1=第一優先、2=第二優先；臨時分析依賴者對分析報告值是否需分析師判定".' "合格/不合格"'." (依社內再分析基準)，請在備註欄中註記‧";
		$elai='(依賴者:';
		$elai1='           )';
		$dep_ani='(分析課:';
		$dep_ani1='           )';
		$dep_QA='(品保課:';		
		$dep_QA1='           )';
		$obj->getStyle("'A".($y-4)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$obj->getStyle("'A".($y-4)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$obj->setCellValue('A'.($y-4),$sss);
		$obj->setCellValue('H'.($y-3),$elai);
		$obj->setCellValue('I'.($y-3),$elai1);
		$obj->setCellValue('K'.($y-3),$dep_ani);
		$obj->setCellValue('L'.($y-3),$dep_ani1);
		$obj->setCellValue('N'.($y-3),$dep_QA);
		$obj->setCellValue('O'.($y-3),$dep_QA1);
		$obj->getStyle("H".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("K".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("N".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("I".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("L".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("O".($y-2))->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'A".($y-4).":O".($y-4)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->setBreak("'A". ($y-1)."'", PHPExcel_Worksheet::BREAK_ROW );
		$obj->setCellValue('O'.$y,$o[$x]);
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
	$objWriteHTML = new PHPExcel_Writer_HTML($objPHPExcel,'HTML');
	$objWriteHTML->save("php://output");
?>