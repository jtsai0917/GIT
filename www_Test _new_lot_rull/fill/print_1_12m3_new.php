<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>列印</title>
<style type="text/css">
.big24 {
	font-size: 24px;
}
normal {
	font-size: 14px;
}
tr td {
	font-size: 14px;
}
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <span class="big24">
  (1~12m3) 容器充填檢查作業表
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="submit" name="print" id="print" value=" 列 印 " />
  </span><span class="big24">
  <input type="submit" name="leave" id="leave" value=" 離 開 " />
  </span>
</form>
<?php 
include("../lib/fun.php");
$lids=$_GET['lid'];
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_POST["print"]))
{
	$tb=new _12m3;
	$tb->host=$_SERVER['HTTP_HOST'];	
	$tb->lid=$lids;
	$tb->print_table();
}

if (isset($_POST["leave"])) 
{
	rm_dir("../tmp2/");
}

class _12m3{
public $lid;
public $fileurl;
public $host;
function print_table(){
	require_once("../PHPEXCEL/Classes/PHPExcel.php");
	require_once("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
	$this->fileurl="../FormList/checklistform/tmp/".$this->lid;
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("./formlist/3_14.xlsx");
	
require_once("../lib/fun.php");
require_once("../connections/conn.php");
$query="SELECT      TOP (1) LORRY_FILL_CHECK.FDM_LOT_NO, FILLPLAN_OUT_DECIDE.PDD_PROD_NO, 
                            FILLPLAN_OUT_DECIDE.FDM_CREATOR, PRODUCT_DATA.PDD_PROD_NAME, 
                            FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, FILLPLAN_OUT_DECIDE.FOD_DAY, 
                            FILLPLAN_OUT_DECIDE.CTD_CUST_NO, FILLPLAN_OUT_DECIDE.FDM_LY_NO, 
                            FILLPLAN_OUT_DECIDE.FDM_SAM_CNT, FILLPLAN_OUT_DECIDE.FDM_QTY, 
                            FILLPLAN_OUT_DECIDE.FDM_LY_NO AS Expr1, PRODUCT_DATA.PDD_LITER_KG, 
                            LORRY_FILL_CHECK.LFC_B_REMAIN_QTY, LORRY_FILL_CHECK.LFC_B_TROUGH_TOP, 
                            LORRY_FILL_CHECK.LFC_B_CHK_COUP_CLR, LORRY_FILL_CHECK.LFC_B_CHK_COUP, 
                            LORRY_FILL_CHECK.LFC_B_CHK_COUP_LINK, LORRY_FILL_CHECK.LFC_B_CHK_EXHAUST, 
                            LORRY_FILL_CHECK.LFC_B_SET_FILL, LORRY_FILL_CHECK.LFC_F_FLOW, 
                            LORRY_FILL_CHECK.LFC_F_SAM_COUNT, LORRY_FILL_CHECK.LFC_F_RESISTANCE, 
                            LORRY_FILL_CHECK.LFC_E_OPER_FILL, LORRY_FILL_CHECK.LFC_E_TROUGH_TOP, 
                            LORRY_FILL_CHECK.LFC_E_CHK_AIR_SEAL, LORRY_FILL_CHECK.LFC_E_CHK_FILL_CLOSE, 
                            LORRY_FILL_CHECK.LFC_E_CHK_EXHAUST, LORRY_FILL_CHECK.LFC_E_CHK_VALVE, 
                            LORRY_FILL_CHECK.LFC_E_CHK_COUP_CLR, LORRY_FILL_CHECK.LFC_E_CHK_COUP, 
                            LORRY_FILL_CHECK.LFC_E_CHK_PIPE_PICKUP, LORRY_FILL_CHECK.LFC_FILLER, 
                            LORRY_FILL_CHECK.LFC_W_PRIOR_WEIGHT, FILLPLAN_OUT_DECIDE.FDM_QTY_UNIT, 
                            LORRY_FILL_CHECK.LFC_B_TROUGH_QTY, LORRY_FILL_CHECK.LFC_E_TROUGH_QTY, 
                            LORRY_FILL_CHECK.LFC_E_CHK_MEGA_CHECK AS megacheck, Fill_Flow_Chart.flow, 
                            Fill_Flow_Chart.pressure
FROM           LORRY_FILL_CHECK INNER JOIN
                            FILLPLAN_OUT_DECIDE ON 
                            LORRY_FILL_CHECK.FDM_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO INNER JOIN
                            PRODUCT_DATA ON 
                            FILLPLAN_OUT_DECIDE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN
                            Fill_Flow_Chart ON FILLPLAN_OUT_DECIDE.FDM_LOT_NO = Fill_Flow_Chart.Lot_No 
WHERE       (LORRY_FILL_CHECK.FDM_LOT_NO = '".$this->lid."') ORDER BY Fill_Flow_Chart.[index] DESC" ; 
$result = mssql_query($query);
$numRows = mssql_num_rows($result);	
$p=0;
	while($row = mssql_fetch_array($result))
		{
			$I11=$row['FDM_QTY_UNIT'];
			if($I11=='KG'){$qty=$row['FDM_QTY']/$row['PDD_LITER_KG'];}else{$qty=$row['FDM_QTY'];}
			$big_drum_volume=get_big_drum_volume($row['FDM_LY_NO']);
			$f6=get_prod_name($row['PDD_PROD_NO']);
			$f7=$row['FOD_YEAR_MONTH'].$row['FOD_DAY'];
			$f8=get_cust_name($row['CTD_CUST_NO']);
			$f9=$row['FDM_LOT_NO'];
			$f10=$row['FDM_SAM_CNT'];
			$f11=$qty;
			$f12=$row['FDM_LY_NO'];
			$f13=$row['LFC_W_PRIOR_WEIGHT'];
			$h14=$row['PDD_LITER_KG'];
			$f15=$row['LFC_B_REMAIN_QTY'];
			$d16=get_uname($row['FDM_CREATOR']);
			$f18=$row['LFC_B_TROUGH_TOP'];
//			$f30=$row['LFC_E_TROUGH_TOP'];
			$h18=$row['LFC_E_TROUGH_TOP'];
			$ah18=$big_drum_volume*$f18/100;
			$f19=$row['LFC_B_CHK_COUP_CLR'];
			$f20=$row['LFC_B_CHK_COUP'];
			$f21=$row['LFC_B_CHK_COUP_LINK'];
			$f22=$row['LFC_B_CHK_EXHAUST'];
			$f24=$row['LFC_B_SET_FILL'];
			$f25=$row['LFC_F_FLOW'];
			$f26=$row['LFC_F_SAM_COUNT'];
			$f27=$row['LFC_F_RESISTANCE'];
			$f28=$row['megacheck'];
			$f29=$row['LFC_E_OPER_FILL'];		
			$f31=$row['LFC_E_CHK_AIR_SEAL'];
			$f32=$row['LFC_E_CHK_FILL_CLOSE'];
			$f33=$row['LFC_E_CHK_EXHAUST'];
			$f34=$row['LFC_E_CHK_EXHAUST'];
			$f36=$row['LFC_E_CHK_VALVE'];
			$f37=$row['LFC_E_CHK_COUP_CLR'];
			$f38=$row['LFC_E_CHK_COUP'];
			$f39=$row['LFC_E_CHK_PIPE_PICKUP'];
			$d40=get_uname($row['LFC_FILLER']);
	//		$h18=$row['LFC_B_TROUGH_QTY'];
			$f30=$row['LFC_B_TROUGH_QTY'];
			$h30=$row['LFC_E_TROUGH_QTY'];
		// 分割字串
			$chart=$row['flow'];
//		echo '<BR>';
			$presure=$row['pressure'];
			$ac=explode('/',$chart);
			$pc=explode('/',$presure);
			$an=count($ac);
			for($n=0;$n<$an;$n++){
				if(substr($ac[$n],0,1)=='P'){
					$j24=trim($ac[$n]).'出口壓力 X1：'.trim($pc[$n]).'Kg/cm2';
					$pc0=$pc[$n];
				}
				if(substr($ac[$n],0,1)=='S'){
					if($p==0){
						$j25=trim($ac[$n]).'出口壓力 X2：'.trim($pc[$n]).'Kg/cm2';
						$pc1=$pc[$n];
					}
					if($p==1){
						$j26=trim($ac[$n]).'出口壓力 X3：'.trim($pc[$n]).'Kg/cm2';
						$pc2=$pc[$n];
					}
					$p++;
				}
			}
			$pd1=$pc0-$pc1;
			$pd2=$pc0-$pc2;
			$j27='總壓差1=X1-X2 = '.trim($pd1).' Kg/cm2';
			$j28='總壓差2=X1-X3 = '.trim($pd2).' Kg/cm2';
		}
$query="SELECT          SMA_LOT , SMA_ID
FROM              Sample_All
WHERE          (SMA_LOT = '".$this->lid."')  AND (ISREWORK <> '0')";
$result = mssql_query($query);
$i=6;
while($row = mssql_fetch_array($result)){
$j="J".$i;
$objPHPExcel->getActiveSheet(0)->setCellValue($j,$row['SMA_ID']);
$i=$i+1;
}
	
	
	$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('f6',iconv("big5","utf-8",$f6))
                            ->setCellValue("f7",$f7)
                            ->setCellValue('f8',iconv("big5","utf-8",$f8))
                            ->setCellValue('f9',$f9)
                            ->setCellValue('f10',$f10)
                            ->setCellValue('f11',$f11)
							->setCellValue('f12',$f12)
							->setCellValue('f13',$f13)
                            ->setCellValue("h14",$h14)
                            ->setCellValue('f15',$f15)
                            ->setCellValue('d16',iconv("big5","utf-8",$d16))
							->setCellValue('f18',$f18)
							->setCellValue('H18',$h18)
                            ->setCellValue('f19',$f19)
							->setCellValue('f20',$f20)
							->setCellValue('f21',$f21)
							->setCellValue('f22',$f22)
							->setCellValue('f23',$f23)
							->setCellValue('f24',$f24)
							->setCellValue('f25',$f25)
							->setCellValue('f26',$f26)
							->setCellValue('f27',$f27)
							->setCellValue('f28',$f28)
							->setCellValue('f29',$f29)
							->setCellValue('f30',$f30)
							->setCellValue('h30',$h30)
							->setCellValue('f32',$f32)
							->setCellValue('f33',$f33)
							->setCellValue('f34',$f34)
							->setCellValue('f36',$f36)
							->setCellValue('f37',$f37)
							->setCellValue('f38',$f38)
							->setCellValue('f39',$f39)
							->setCellValue('h18',$h18)
							->setCellValue('j24',iconv("big5","utf-8",$j24))
							->setCellValue('j25',iconv("big5","utf-8",$j25))
							->setCellValue('j26',iconv("big5","utf-8",$j26))
							->setCellValue('j27',iconv("big5","utf-8",$j27))
							->setCellValue('j28',iconv("big5","utf-8",$j28))
							->setCellValue('d40',iconv("big5","utf-8",$d40))
							;
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f6')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f8')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f9')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f10')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f11')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f12')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f13')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f20')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f21')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f22')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f23')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('D16')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f25')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f26')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f28')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f30')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f32')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f33')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f34')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f36')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('B18')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('F18')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('H18')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f37')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f38')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f39')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('d40')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet(0)->getStyle('f6:f39')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('f6:f39')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('d16:d40')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('d16:d40')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('H18:H30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('H18:H30')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('G18:G30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('G18:G30')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle('j6:j29')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($this->fileurl.".xlsx");

echo '<script>document.location.href="http://'.$this->host.substr($this->fileurl,2).'.xlsx";</script>';  
}
}

function get_big_drum_volume($lorry_no){
	include("../conn/connections.php");
	$query="SELECT         dbo.BIG_DRUM_VOLUME.*
FROM             dbo.BIG_DRUM_VOLUME
WHERE         (PDD_PROD_SHORT_NAME = '".$lorry_no."')";	

$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	$big_drum_volume=$row['BDV_VOLUME'];
}	
	return $big_drum_volume;
}
?>
