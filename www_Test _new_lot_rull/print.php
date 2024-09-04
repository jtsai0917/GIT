<?php 
session_start();
include("../connections/conn.php");
class print_outplan{
public $order_no,$activesheet,$aa ;
function print_out_plan(){
	$line=$line1=1;
	$this->activesheet=0;
	$path_root=$_SERVER['HTTP_HOST'];
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
			),
		),
	);		
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("../prodout/formlist/out_plan.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	for($i=0;$i<count($this->aa);$i++)
	{
		include_once("../lib/sag_in.php");
		$sag=new sign_in;
		$sag->order_no=$this->order_no;
		$sag->sag_no_from_order_no_outplan();
		$sag->read_agree_item();
		if($sag->finished_time<>''){$sure="已確認";}else{$sure='未確認';}
		$line1=$line+30;
		$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('A'.($line1).':B'.($line1))
							->mergeCells('G'.($line1).':i'.($line1))
							->setCellValue('A'.($line1),"簽核單位")	
							->mergeCells('A'.($line1+1).':B'.($line1+1))
							->mergeCells('G'.($line1+1).':i'.($line1+1))
							->setCellValue('A'.($line1+1),"簽核結果")
							->mergeCells('A'.($line1+2).':B'.($line1+2))
							->mergeCells('G'.($line1+2).':i'.($line1+2))
							->setCellValue('A'.($line1+2),"確認時間")
							->mergeCells('A'.($line1+3).':B'.($line1+3))
							->mergeCells('G'.($line1+3).':i'.($line1+3))
							->setCellValue('A'.($line1+3),"意見")							
							->setBreak("'A". ($line1+4)."'", PHPExcel_Worksheet::BREAK_ROW );
							;
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($line1),"製表人員-".conv(get_uname($sag->create_man)));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($line1+1),"NA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($line1+2),sta($sag->create_time));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($line1+3),"NA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($line1),"最後確認-".conv(get_uname($sag->create_man)));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($line1+1),$sure);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($line1+2),sta($sag->finished_time));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($line1+3),conv($sag->finished_memo));
		for($o=0;$o<3;$o++){
			$col=ord("D")+$o;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($col).($line1),conv($sag->aa_aut_name[$o]."-".get_uname($sag->aa_emp_no[$o])));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($col).($line1+1),$sag->aa_sai_ok[$o]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($col).($line1+2),sta($sag->aa_sai_time[$o]));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($col).($line1+3),conv($sag->aa_sai_memo[$o]));
		}
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getRowDimension($line1+3)->setRowHeight(50);	
		for($y=0;$y<4;$y++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}
		$this->aa[$i];
		$query="SELECT          OPM_ORDER_NO, OPM_PO_NO, OPM_ETA_DATE, CTD_CUST_NO
				FROM              OUT_PLAN
				WHERE          (OPM_ORDER_NO = '".$this->aa[$i]."')";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row = mssql_fetch_array($result))
		{
			$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('K'.$line.':L'.$line)
							->mergeCells('K'.($line+2).':L'.($line+2))
							->setCellValue('K'.$line,conv("SALES ORDER NO."))
							->setCellValue('K'.($line+2),conv("P/O NO."))
							->setCellValue('C'.$line,conv(get_cust_name($row['CTD_CUST_NO'])))
							->setCellValue('C'.($line+1),conv(std($row['OPM_ETA_DATE'])))
							->setCellValue('C'.($line+2),"16:00")
							->setCellValue('N'.$line,"# ".conv($row['OPM_ORDER_NO']))
							->setCellValue('N'.($line+2),"# ".conv($row['OPM_PO_NO']))
							;
		}

	$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('A'.$line.':B'.$line)
							->setCellValue('A'.$line,"USER:")
							->mergeCells('A'.($line+1).':B'.($line+1))
							->setCellValue('A'.($line+1),"出荷計畫日：")
							->mergeCells('A'.($line+2).':B'.($line+2))
							->setCellValue('A'.($line+2),"時間：")
							->setCellValue('L'.$line,"SALES ORDER NO.：")
							->setCellValue('L'.($line+2),"P/O NO.：")
							->mergeCells('D'.($line+1).':I'.($line+2))
							->setCellValue('D'.($line+1),"出荷計畫書")
							->getStyle('D'.($line+1))->getFont()->setSize(26)
							;
	$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('A'.($line+4).':A'.($line+5))
							->setCellValue('A'.($line+4),"序號")
							->mergeCells('B'.($line+4).':b'.($line+5))
							->setCellValue('b'.($line+4),"料號")
							->mergeCells('c'.($line+4).':c'.($line+5))
							->setCellValue('c'.($line+4),"品名")
							->mergeCells('d'.($line+4).':d'.($line+5))
							->setCellValue('d'.($line+4),"包裝型態\n(DM/LY):")
							->mergeCells('e'.($line+4).':e'.($line+5))
							->setCellValue('e'.($line+4),"LOT NO")
							->mergeCells('f'.($line+4).':f'.($line+5))
							->setCellValue('f'.($line+4),"出貨期限")
							->mergeCells('g'.($line+4).':g'.($line+5))
							->setCellValue('g'.($line+4),"單位")
							->mergeCells('h'.($line+4).':h'.($line+5))
							->setCellValue('h'.($line+4),"桶數")
							->mergeCells('i'.($line+4).':i'.($line+5))
							->setCellValue('i'.($line+4),"數量KG")
							->mergeCells('j'.($line+4).':j'.($line+5))
							->setCellValue('j'.($line+4),"數量L")
							->mergeCells('k'.($line+4).':k'.($line+5))
							->setCellValue('k'.($line+4),"備註")
							->mergeCells('l'.($line+4).':l'.($line+5))
							->setCellValue('l'.($line+4),"轉檔日期")
							->mergeCells('n'.($line+4).':q'.($line+4))
							->setCellValue('n'.($line+4),"變更事項")
							->setCellValue('n'.($line+5),"LOT NO")
							->setCellValue('o'.($line+5),"變量(KG)")
							->setCellValue('p'.($line+5),"數量(L)")
							->setCellValue('q'.($line+5),"日期時間")
							;	
	$s=$line+4;		
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$s.":A".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$s.":A".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$s.":A".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$s.":b".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$s.":b".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$s.":b".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$s.":c".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$s.":c".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$s.":c".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$s.":d".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$s.":d".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$s.":d".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$s.":e".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$s.":e".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$s.":e".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$s.":f".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$s.":f".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$s.":f".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$s.":g".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$s.":g".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$s.":g".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$s.":h".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$s.":h".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$s.":h".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$s.":i".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$s.":i".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$s.":i".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$s.":j".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$s.":j".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$s.":j".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$s.":k".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$s.":k".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$s.":k".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s.":l".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s.":l".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$s.":l".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s.":q".$s."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s.":q".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$s.":q".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('D'.($line+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('D'.($line+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$line=$line+6;
	$query="SELECT          OUT_DECISION.OPM_ORDER_NO AS Expr1, OUT_PRODUCT.OPM_ORDER_NO AS Expr2, 
                            PRODUCT_DATA.PDD_STYLE, PRODUCT_DATA.PDD_PROD_NAME, OUT_PRODUCT.OPD_LOT_NO, 
                            OUT_PRODUCT.OPD_TERM_DATE, OUT_DECISION.OTD_CREATE_DATE, OUT_PRODUCT.OPD_ACC_UNIT, 
                            OUT_PRODUCT.OPD_QTY_DRUM, OUT_PRODUCT.OPD_QTY_KG, OUT_PRODUCT.OPD_QTY_LITER, 
                            OUT_PRODUCT.OPD_MEMO, OUT_PRODUCT.OPD_INWARD_DATE, OUT_PRODUCT.OPD_SERIAL_NO,
							OUT_PRODUCT.PDD_PROD_NO
			FROM              OUT_DECISION INNER JOIN
										OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO INNER JOIN
										PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
			WHERE          (OUT_DECISION.OPM_ORDER_NO = '".$this->aa[$i]."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$da=std($row['OTD_CREATE_DATE']);
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$line,$row['OPD_SERIAL_NO'])	
							->setCellValue('B'.$line,$row['PDD_PROD_NO'])
							->setCellValue('C'.$line,conv($row['PDD_PROD_NAME']))
							->setCellValue('D'.$line,$row['PDD_STYLE'])
							->setCellValue('E'.$line,$row['OPD_LOT_NO'])
							->setCellValue('F'.$line,$row['OPD_TERM_DATE'])	
							->setCellValue('G'.$line,$row['OPD_ACC_UNIT'])
							->setCellValue('H'.$line,$row['OPD_QTY_DRUM'])
							->setCellValue('I'.$line,$row['OPD_QTY_KG'])
							->setCellValue('J'.$line,$row['OPD_QTY_LITER'])
							->setCellValue('K'.$line,$row['OPD_MEMO'])
							->setCellValue('L'.$line,conv($da))
							;
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$line=$line+1;
	}
	$line=$line1+5;
					
	}//end each order_no
	
/*
	for($s=13;$s<37;$s++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}
*/
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save("../lib/tmp/tmp.xlsx"); 
	echo '<script>document.location.href="http://'.$path_root.'/lib/tmp/tmp.xlsx";</script>';	
}//end function print_out_plan
}// end class print_outplan


class print_outdecision{
public $order_no,$activesheet,$aa ;
function print_out_decision(){
	$line=$line1=2;
	$this->activesheet=0;
	$path_root=$_SERVER['HTTP_HOST'];
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
			),
		),
	);		
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("../prodout/formlist/out_decision.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	for($i=0;$i<count($this->aa);$i++)
	{
		include_once("../lib/sag_in.php");
		$sag=new sign_in;
		$sag->order_no=$this->order_no=$this->aa[$i];
		$sag->sag_no_from_order_no_outdecision();
		$sag->read_agree_item();
		if($sag->finished_time<>''){$sure="已確認";}else{$sure='未確認';}
		$line1=$line+30;
		$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('A'.($line1).':B'.($line1))
							->mergeCells('H'.($line1).':I'.($line1))
							->mergeCells('J'.($line1).':K'.($line1))
							->mergeCells('L'.($line1).':N'.($line1))
							->setCellValue('A'.($line1),"簽核單位")	
							->mergeCells('A'.($line1+1).':B'.($line1+1))
							->mergeCells('H'.($line1+1).':i'.($line1+1))
							->mergeCells('J'.($line1+1).':K'.($line1+1))
							->mergeCells('L'.($line1+1).':N'.($line1+1))
							->setCellValue('A'.($line1+1),"簽核結果")
							->mergeCells('A'.($line1+2).':B'.($line1+2))
							->mergeCells('H'.($line1+2).':I'.($line1+2))
							->mergeCells('J'.($line1+2).':K'.($line1+2))
							->mergeCells('L'.($line1+2).':N'.($line1+2))
							->setCellValue('A'.($line1+2),"確認時間")
							->mergeCells('A'.($line1+3).':B'.($line1+3))
							->mergeCells('H'.($line1+3).':I'.($line1+3))
							->mergeCells('J'.($line1+3).':K'.($line1+3))
							->mergeCells('L'.($line1+3).':N'.($line1+3))
							->setCellValue('A'.($line1+3),"意見")							
							->setBreak("'A". ($line1+4)."'", PHPExcel_Worksheet::BREAK_ROW );
							;
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($line1),"製表人員-".conv(get_uname($sag->create_man)));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($line1+1),"NA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($line1+2),sta($sag->create_time));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($line1+3),"NA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($line1),"最後確認-".conv(get_uname($sag->create_man)));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($line1+1),$sure);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($line1+2),sta($sag->finished_time));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($line1+3),conv($sag->finished_memo));
			$o=0;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".($line1),conv($sag->aa_aut_name[$o]."-".get_uname($sag->aa_emp_no[$o])));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".($line1+1),$sag->aa_sai_ok[$o]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".($line1+2),sta($sag->aa_sai_time[$o]));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".($line1+3),conv($sag->aa_sai_memo[$o]));

		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getRowDimension($line1+3)->setRowHeight(50);	
		for($y=0;$y<4;$y++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".($line1+$y).":I".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".($line1+$y).":I".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".($line1+$y).":I".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".($line1+$y).":k".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".($line1+$y).":k".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".($line1+$y).":k".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".($line1+$y).":n".($line1+$y)."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".($line1+$y).":n".($line1+$y)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'l".($line1+$y).":n".($line1+$y)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}
		$this->aa[$i];
		$query="SELECT          OPM_ORDER_NO, OPM_PO_NO, OPM_ETA_DATE, CTD_CUST_NO, OTD_NO

				FROM              OUT_DECISION
				WHERE          (OPM_ORDER_NO = '".$this->aa[$i]."')";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row = mssql_fetch_array($result))
		{
			$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('K'.$line.':M'.$line)
							->mergeCells('K'.($line+2).':M'.($line+2))
							->setCellValue('K'.$line,conv("SALES ORDER NO."))
							->setCellValue('K'.($line+2),conv("P/O NO."))
							->setCellValue('C'.$line,conv(get_cust_name($row['CTD_CUST_NO'])))
							->setCellValue('C'.($line+1),conv(std($row['OPM_ETA_DATE'])))
							->setCellValue('C'.($line+2),conv(stm($row['OPM_ETA_DATE'])))
							->setCellValue('N'.$line,":		  ".conv($row['OPM_ORDER_NO']))
							->setCellValue('N'.($line+2),":		  ".conv($row['OPM_PO_NO']))
							->setCellValue('A'.($line-1),"*".$row['OTD_NO']."*")
							;
		}
	$objPHPExcel->getActiveSheet()->getStyle('A'.($line-1))->getFont()->setSize(26);
	$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('A'.($line-1).':C'.($line-1))
							->mergeCells('A'.$line.':B'.$line)
							->setCellValue('A'.$line,"USER:")
							->mergeCells('A'.($line+1).':B'.($line+1))
							->setCellValue('A'.($line+1),"出荷計畫日：")
							->mergeCells('A'.($line+2).':B'.($line+2))
							->setCellValue('A'.($line+2),"時間：")
							->setCellValue('L'.$line,"SALES ORDER NO.")
							->setCellValue('L'.($line+2),"P/O NO.")
							->mergeCells('D'.($line-1).':I'.($line-1))
							->setCellValue('D'.($line-1),"出荷決定書")
							->getStyle('D'.($line-1))->getFont()->setSize(26)
							;
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$line.":B".$line."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".($line+1).":B".(($line+1))."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".($line+2).":B".($line+2)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$line.":d".$line."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".($line+1).":d".(($line+1))."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".($line+2).":d".($line+2)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)
							->mergeCells('A'.($line+3).':A'.($line+4))
							->setCellValue('A'.($line+3),"序號")
							->mergeCells('B'.($line+3).':b'.($line+4))
							->setCellValue('b'.($line+3),"料號")
							->mergeCells('c'.($line+3).':c'.($line+4))
							->setCellValue('c'.($line+3),"品名")
							->mergeCells('d'.($line+3).':d'.($line+4))
							->setCellValue('d'.($line+3),"包裝型")
							->mergeCells('e'.($line+3).':e'.($line+4))
							->setCellValue('e'.($line+3),"LOT NO")
							->mergeCells('f'.($line+3).':f'.($line+4))
							->setCellValue('f'.($line+3),"COA NO")
							->mergeCells('g'.($line+3).':g'.($line+4))
							->setCellValue('g'.($line+3),"出貨期限")
							->mergeCells('h'.($line+3).':h'.($line+4))
							->setCellValue('h'.($line+3),"單位")
							->mergeCells('i'.($line+3).':i'.($line+4))
							->setCellValue('i'.($line+3),"桶數")
							->mergeCells('j'.($line+3).':j'.($line+4))
							->setCellValue('j'.($line+3),"數量\n(KG)")
							->mergeCells('k'.($line+3).':k'.($line+4))
							->setCellValue('k'.($line+3),"數量\n(L)")
							->mergeCells('m'.($line+3).':n'.($line+3))
							->setCellValue('m'.($line+3),"出荷檢查")
							->setCellValue('m'.($line+4),"簽收單檢查")
							->setCellValue('n'.($line+4),"COA檢查")
							->setCellValue('p'.($line+3),"備註")
							;	
	$s=$line+3;		
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$s.":A".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$s.":A".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$s.":A".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$s.":b".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$s.":b".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$s.":b".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$s.":c".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$s.":c".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$s.":c".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$s.":d".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$s.":d".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$s.":d".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$s.":e".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$s.":e".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$s.":e".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$s.":f".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$s.":f".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$s.":f".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$s.":g".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$s.":g".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$s.":g".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$s.":h".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$s.":h".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$s.":h".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$s.":i".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$s.":i".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$s.":i".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$s.":j".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$s.":j".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$s.":j".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$s.":k".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$s.":k".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$s.":k".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s.":m".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s.":m".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s.":m".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s.":n".$s."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s.":n".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$s.":n".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s+1)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s+1)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s+1)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s)."'")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s)."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".($s)."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('D'.($line-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle('D'.($line-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$line=$line+5;
	$query="SELECT          OUT_DECISION.OPM_ORDER_NO AS Expr1, OUT_PRODUCT.OPM_ORDER_NO AS Expr2, 
                            PRODUCT_DATA.PDD_STYLE, PRODUCT_DATA.PDD_PROD_NAME, OUT_PRODUCT.OPD_LOT_NO, 
                            OUT_PRODUCT.OPD_TERM_DATE, OUT_DECISION.OTD_CREATE_DATE, OUT_PRODUCT.OPD_ACC_UNIT, 
                            OUT_PRODUCT.OPD_QTY_DRUM, OUT_PRODUCT.OPD_QTY_KG, OUT_PRODUCT.OPD_QTY_LITER, 
                            OUT_PRODUCT.OPD_MEMO, OUT_PRODUCT.OPD_INWARD_DATE, OUT_PRODUCT.OPD_SERIAL_NO, 
                            OUT_PRODUCT.PDD_PROD_NO, PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PACKAGE, 
                            PRODUCT_DATA.PDD_CONSISTENCY, OUT_PRODUCT.OPD_COA_NO
			FROM              OUT_DECISION INNER JOIN
										OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO INNER JOIN
										PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
			WHERE          (OUT_DECISION.OPM_ORDER_NO = '".$this->aa[$i]."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$da=std($row['OTD_CREATE_DATE']);
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$line,$row['OPD_SERIAL_NO'])	
							->setCellValue('B'.$line,$row['PDD_PROD_NO'])
							->setCellValue('C'.$line,conv($row['PDD_PROD_NAME']))
							->setCellValue('D'.$line,$row['PDD_STYLE'])
							->setCellValue('E'.$line,$row['OPD_LOT_NO'])
							->setCellValue('F'.$line,$row['OPD_COA_NO'])	
							->setCellValue('G'.$line,$row['OPD_TERM_DATE'])
							->setCellValue('H'.$line,$row['OPD_ACC_UNIT'])
							->setCellValue('I'.$line,$row['OPD_QTY_DRUM'])
							->setCellValue('J'.$line,$row['OPD_QTY_KG'])
							->setCellValue('K'.$line,$row['OPD_QTY_LITER'])
							->setCellValue('P'.$line,conv($row['OPD_MEMO']))
							;
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'A".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'b".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'c".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'d".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'e".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'f".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'g".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'h".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'i".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'j".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'k".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'m".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'n".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'o".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$line."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$line."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'p".$line."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		$line=$line+1;
	}
	$line=$line1+6;
					
	}//end each order_no
	
/*
	for($s=13;$s<37;$s++){
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex($this->activesheet)->getStyle("'q".$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	}
*/
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save("../lib/tmp/tmp.xlsx"); 
	echo '<script>document.location.href="http://'.$path_root.'/lib/tmp/tmp.xlsx";</script>';	
}//end function print_out_decision
}// end class print_out_decision
?>