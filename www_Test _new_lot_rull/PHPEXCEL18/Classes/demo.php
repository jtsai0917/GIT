<?php
include "PHPExcel.php";
/** PHPExcel_Writer_Excel2007 */
//include "PHPExcel/Writer/Excel2007.php";
/** Error reporting */
error_reporting(E_ALL);
/** PHPExcel */
require_once "PHPExcel.php";
/** PHPExcel_IOFactory */
require_once "PHPExcel/IOFactory.php";

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

//合併儲存隔
$objPHPExcel->getActiveSheet()->mergeCells("A1:D2");

//設定漸層背景顏色雙色(灰/白)
$objPHPExcel->getActiveSheet()->getStyle("A1:D2")->applyFromArray(
    array(
    "font"    => array(
    "bold"   => true
    ),
    "alignment" => array(
    "horizontal" => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    "borders" => array(
    "top" => array(
"style" => PHPExcel_Style_Border::BORDER_THIN
)
    ),
    "fill" => array(
"type"    => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
  "rotation"   => 90,
"startcolor" => array(
"rgb" => "DCDCDC"
),
"endcolor"   => array(
"rgb" => "FFFFFF"
)
)
    )
);

//設定字型大小
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16);

//設定A1欄位顯示文字PHPEXCEL TEST
$objPHPExcel->getActiveSheet()->setCellValue("A1","PHPEXCEL TEST");

//設定字體顏色
//$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);

//設定背景顏色單色
$objPHPExcel->getActiveSheet()->getStyle("A3:D3")->applyFromArray(
    array("fill" => array(
    "type"     => PHPExcel_Style_Fill::FILL_SOLID,
    "color"     => array("rgb" => "D1EEEE")
    ),
)
    );

//設定欄位值
$objPHPExcel->getActiveSheet()->setCellValue("A3","test1");
$objPHPExcel->getActiveSheet()->setCellValue("B3","test2");
$objPHPExcel->getActiveSheet()->setCellValue("C3","test3");
$objPHPExcel->getActiveSheet()->setCellValue("D3","test4");

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle("sheet");

//設定的欄位寬度(自動)
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Export to Excel2007 (.xlsx) 匯出成2007

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save("test.xlsx");

// Export to Excel5 (.xls) 匯出成2003



?>