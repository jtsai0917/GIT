<?php
	session_start();
	include("../connections/conn.php");	
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	datepick();
?>
<table border="1" align="left">
    <tr>
      <td><form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php 
		if($_SESSION['datepicker1']<>''){echo $_SESSION['datepicker1'];}
		?>" readonly/ onchange="set_date_session(this.name,this.value)">
  <input name="print" type="submit" id="print" value="  列 印  ">
  </form>
</td>
    </tr>
  </table>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["print"])){

	 	echo "列印中...";	
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$fileurl="./formlist/tmp";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("./formlist/dailyreport.xlsx");	
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$path_root=$_SERVER['HTTP_HOST'];
$obj=$objPHPExcel->setActiveSheetIndex(0);
$i=5;$x=0;$s=0;$p=21;
$query="SELECT          FILLPLAN_OUT_DECIDE.*, PRODUCT_DATA.PDD_LITER_KG, PRODUCT_DATA.PDD_CHEMICAL
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            PRODUCT_DATA ON FILLPLAN_OUT_DECIDE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
WHERE          (FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH = '".substr($_POST['datepicker1'],-4).substr($_POST['datepicker1'],0,2)."') AND (FILLPLAN_OUT_DECIDE.FOD_DAY = '".substr($_POST['datepicker1'],-7,2)."')";
echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{ 	
	$obj->mergeCells('A'.($i).":A".($i+1));
	$obj->mergeCells('b'.($i).":b".($i+1));
	$obj->mergeCells('c'.($i).":c".($i+1));
	$obj->mergeCells('d'.($i).":d".($i+1));
	$obj->mergeCells('e'.($i).":e".($i+1));
	$obj->mergeCells('f'.($i).":f".($i+1));
	$obj->mergeCells('i'.($i).":i".($i+1));
	$obj->mergeCells('j'.($i).":j".($i+1));
	$obj->mergeCells('k'.($i).":k".($i+1));
	if((substr($lot_no,-4,1)=='L') or (substr($lot_no,-4,1)=='l')){$lorry_no=substr($lot_no,-4);}else{$lorry_no='';}
	if((substr($lot_no,-4,1)=='D') or (substr($lot_no,-4,1)=='d')){$drum_no=substr($lot_no,-4);}else{$drum_no='';}
	$obj->setCellValue("'A".($i)."'",iconv("big5","utf-8",$row['FOD_YEAR_MONTH'].$row['FOD_DAY']));	
	$obj->setCellValue("'B".($i)."'",iconv("big5","utf-8",$row['PDD_CHEMICAL']));
	$obj->setCellValue("'C".($i)."'",iconv("big5","utf-8",$lorry_no));
	$obj->setCellValue("'E".($i)."'",iconv("big5","utf-8",$drum_no));
	
	for($a=65;$a<=75;$a++){
		for($b=1;$b<=($i+1);$b++)
		{
			$obj->getStyle(chr($a).$b)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
		}
	}
//	border("K",$i+1);

	$x=$x+1;
	if($x==8){$i=$i+6;$x=0;}
	else{$i=$i+2;}
	
/*
	$obj->getStyle('A'.($i).":k".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('b'.($i).":j".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('c'.($i).":i".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('d'.($i).":h".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('e'.($i).":g".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('f'.($i).":f".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('g'.($i).":e".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('h'.($i).":d".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('i'.($i).":c".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('j'.($i).":b".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$obj->getStyle('k'.($i).":a".($i+1))->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
*/
}
$page=round($numRows/8,0);
for($s=0;$s<$page;$s++)
{
		$p=$p+$s*16;
		$obj->mergeCells('g'.($p+2).":g".($p+3));
		$obj->mergeCells('h'.($p+2).":h".($p+3));
		$obj->mergeCells('i'.($p+2).":i".($p+3));
		$obj->mergeCells('j'.($p+2).":j".($p+3));
		$obj->mergeCells('k'.($p+2).":k".($p+3));
		$obj->setBreak("'L". ($p+3)."'", PHPExcel_Worksheet::BREAK_ROW );
/*
		$obj->getStyle("'G".($p+1)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'h".($p+1)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'i".($p+1)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'j".($p+1)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'k".($p+1)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'G".($p+2)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'h".($p+2)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'i".($p+2)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'j".($p+2)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'k".($p+2)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'G".($p+3)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'h".($p+3)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'i".($p+3)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'j".($p+3)."'")->applyFromArray($styleThinBlackBorderOutline);
		$obj->getStyle("'k".($p+3)."'")->applyFromArray($styleThinBlackBorderOutline);
*/
		$obj->getRowDimension($p)->setRowHeight(5);
		$obj->setCellValue("'G".($p+1)."'",iconv("big5","utf-8","充填"));
		$obj->setCellValue("'h".($p+1)."'",iconv("big5","utf-8","物流"));
		$obj->setCellValue("'i".($p+1)."'",iconv("big5","utf-8","物管"));
		$obj->setCellValue("'j".($p+1)."'",iconv("big5","utf-8","製造"));
		$obj->setCellValue("'k".($p+1)."'",iconv("big5","utf-8","品保"));
		$obj->getStyle('A1:A2')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$p=$p+4;
	}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/fill/'.$fileurl.'.xlsx";</script>';	
}
?>