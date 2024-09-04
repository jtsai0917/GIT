<?php 
$dir=dirname(__FILE__);
$path_root=$_SERVER['HTTP_HOST'];
include("/lib/fun.php");
include_once("../connections/conn.php");
$pid=$_GET['pid'];
$query="SELECT DISTINCT 
                            OUT_DECISION.OTD_NO AS otdno, OUT_DECISION.OPM_PO_NO, OUT_DECISION.OPM_ORDER_NO, 
                            OUT_DECISION.OTD_INVOICE_NO, OUT_DECISION.CTD_CUST_NO, OUT_DECISION.OTD_CREATE_DATE, 
                            OUT_DECISION.OPM_ETA_DATE, OUT_DECISION.OTD_COA_BEFORE, OUT_DECISION.OTD_SAMPLE_BEFORE, 
                            OUT_DECISION.SAG_NO, OUT_DECISION.OTD_PMAN1, OUT_DECISION.OTD_PMAN2, 
                            OUT_DECISION.OTD_PMAN2_DATE, OUT_DECISION.OTD_PMAN3, OUT_DECISION.OTD_PMAN4, 
                            OUT_DECISION.OTD_PMAN4_DATE, OUT_DECISION.OTD_PMAN5, OUT_DECISION.OTD_PMAN5_DATE, 
                            OUT_DECISION.OTD_PMAN6, OUT_DECISION.OTD_PMAN6_DATE, OUT_DECISION.OTD_CANCEL, 
                            OUT_DECISION.OPM_TAINAN_CACHE, OUT_DECISION.OPM_STOCK, OUT_DECISION.OPM_TAICHUNG_CACHE, 
                            PRODUCT_DATA.PDD_TYPE, OUT_PRODUCT.PDD_PROD_NO, OUT_PRODUCT.OPD_LOT_NO, 
                            OUT_CHECK_LORRY.OTD_NO, OUT_CHECK_LORRY.OCL_LY_NO, OUT_CHECK_LORRY.OCL_LOT_NO, 
                            OUT_CHECK_LORRY.OCL_BACK_DATE, OUT_CHECK_LORRY.OCL_CHK_DATE, 
                            OUT_CHECK_LORRY.OCL_CHK_BAR, OUT_CHECK_LORRY.OCL_CHK_SPEC_LINK, 
                            OUT_CHECK_LORRY.OCL_CHK_PIPE, OUT_CHECK_LORRY.OCL_CHK_LOT_PASTED, 
                            OUT_CHECK_LORRY.OCL_CF_MAN, OUT_CHECK_LORRY.OCL_CHK_SURFACE, 
                            OUT_CHECK_LORRY.OCL_CHK_UPCAP, OUT_CHECK_LORRY.OCL_CHK_HOSE, 
                            FILLPLAN_OUT_DECIDE.FDM_LY_NO AS LYNO
FROM              OUT_DECISION INNER JOIN
                            OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO AND 
                            OUT_DECISION.OTD_NO = OUT_PRODUCT.OTD_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            FILLPLAN_OUT_DECIDE ON 
                            OUT_PRODUCT.OPD_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO LEFT OUTER JOIN
                            OUT_CHECK_LORRY ON OUT_PRODUCT.OTD_NO = OUT_CHECK_LORRY.OTD_NO 
WHERE          (OUT_CHECK_LORRY.OTD_NO = '".$_GET['otd_no']."') and OCL_LOT_NO='".$_GET['lid']."'";
$result = mssql_query($query);
while($row = mssql_fetch_array($result))
	{
		$OTD_NO=$row['OTD_NO'];
		$OCL_LY_NO=$row['LYNO'];
		$OCL_LOT_NO=$row['OCL_LOT_NO'];
		$OCL_BACK_DATE=$row['OCL_BACK_DATE'];
		$OCL_CHK_DATE=$row['OCL_CHK_DATE'];
		$OCL_CHK_BAR=$row['OCL_CHK_BAR'];
		$OCL_CHK_SPEC_LINK=$row['OCL_CHK_SPEC_LINK'];
		$OCL_CHK_PIPE=$row['OCL_CHK_PIPE'];
		$OCL_CHK_LOT_PASTED=$row['OCL_CHK_LOT_PASTED'];
		$OCL_CF_MAN=$row['OCL_CF_MAN'];
 		$OCL_CHK_SURFACE=$row['OCL_CHK_SURFACE'];
		$OCL_CHK_UPCAP=$row['OCL_CHK_UPCAP'];
		$OCL_CHK_HOSE=$row['OCL_CHK_HOSE'];
		$produce_date=$row['FOD_YEAR_MONTH'].$row['FOD_DAY'];
		$barcode=$row['CTP_BIGHOSEBAR'];
		$CTP_CUSTBAR1=trim($row['CTP_CUSTBAR1']);
		$CTP_CUSTBAR1=$CTP_CUSTBAR1.$lum;
		$CTP_CUSTBAR2=$row['CTP_CUSTBAR2'];
		$CTP_CUSTBAR3=$row['CTP_CUSTBAR3'];
		
		$cid=$row['CTD_CUST_NO'];
		$FOD_O_YEAR_MONTH=$row['FOD_O_YEAR_MONTH'];
		$FOD_O_DAY=$row['FOD_O_DAY'];
		$OCL_OUT_DATE=(substr($row['OPM_ETA_DATE'],0,8));
		}

list($start, $end) = get_air_start($OCL_LOT_NO);
$cname=get_cust_name($cid);
$pname=get_prod_name($pid);
//// 判斷是否為台積電

$str1 = $cname;
$str2 = '台積';
$str3 = '華亞';
$str4 = '力積';
if (false !== ($rst = strpos($str1, $str2))) {
    $order=1;
} 
elseif(false !== ($rst = strpos($str1, $str3)))  {
    $order=3;
}
elseif(false !== ($rst = strpos($str1, $str4)))  {
    $order=4;
}
else {$order=2;}
//// replace barcode 字串 [LotNo]
$subject=$barcode;
$pattern='\[LotNo\]';
$replacement=$OCL_LOT_NO;
$barcode=preg_replace("/$pattern/i",$replacement,$subject);

//// replace barcode 字串 [MakeDate]
$subject=$barcode;
$pattern='\[MakeDate\]';
$replacement=$OCL_CHK_DATE;
$barcode=preg_replace("/$pattern/i",$replacement,$subject);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>列印 Lorry 出荷檢查表</title>
</head>

<body>
<p>&nbsp;</p>

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>
    <input type="submit" name="submit" id="submit" value="列印" />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="submit" name="leave" id="leave" value=" 離 開 " /></p>
<table width="1200" border="0">
          <tr>
            <td>品名:<?php echo $pname=get_prod_name($pid); ?></td>
            <td>Lorry No:<?php echo $OCL_LY_NO; ?></td>
            <td>Lot No:<?php echo $OCL_LOT_NO; ?></td>
            <td>出荷先:<?php echo $cname; ?></td>
            <td>出荷日期:<?php echo $OCL_CHK_DATE; ?></td>
            <td>出荷決定書:<?php echo $OTD_NO; ?></td>
          </tr>
        </table>        
        <table width="1200" border="1">
          <tr>
            <td width="400">barcode 確認：&nbsp;<?php echo $OCL_CHK_BAR; ?></td>
            <td width="400">Lot No 是否貼上：<?php echo $OCL_CHK_LOT_PASTED; ?></td>
            <td width="400">接頭確認：<?php echo $OCL_CHK_SPEC_LINK; ?></td>
            <td width="400">管子確認：<?php echo $OCL_CHK_PIPE; ?></td>
          </tr>
          <tr>
            <td>Lorry 外觀檢查：&nbsp;<?php echo $OCL_CHK_SURFACE; ?></td>
            <td>Lorry 上蓋密合：<?php echo $OCL_CHK_UPCAP; ?></td>
            <td>Hose 外觀檢查：<?php echo $OCL_CHK_HOSE; ?></td>
            <td></td>
          </tr>
          <tr>
            <td>氣密測試：&nbsp;<?php echo $OCL_CHK_BAR; ?></td>
            <td>氣密測試開始時間：<?php echo $start; ?></td>
            <td>氣密測試結束時間：<?php echo $end; ?></td>
            <td>檢查壓力表：<?php echo $OCL_CHK_BAR; ?></td>
          </tr>
        </table>
        <input type="hidden" name="pid" value="<?php echo $pid;?>"

</form>
條碼樣式：  <img src="<?php echo $dir;?>/barcode/test_1D.php?text=<?php echo $barcode; ?>" />
</body>
</html>
<?php 
if (isset($_POST["submit"]) and $order==2)
{
	$cname1=trim(substr($cname,0,6));
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	/** PHPExcel */
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	/** PHPExcel_IOFactory */
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	include("../lib/barcode.php");
	$fileurl="../tmp/".date("YmdHis");
	barcode($barcode,$fileurl.".png",25);
//	echo'http://'.$path_root.substr($fileurl,2).'.xlsx';
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
//	$objPHPExcel->getActiveSheet()->setCellValue("A3","test1");
	$atring="../FormList/lorrycheck_v1.xlsx";	
	$objPHPExcel = PHPExcel_IOFactory::load($atring);
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setPath('../tmp/'.$fileurl.".png");
	$objDrawing->setHeight(30);
	$objDrawing->setWidth(210);
	$objDrawing->setCoordinates('D28');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// 	$objPHPExcel->setActiveSheetIndex(0)->getCell('E28')->setCellValue('E28', 'http://localhost/tmp/'.$barcode.".png");
	$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F2',iconv("big5","utf-8",$pname))
                            ->setCellValue("Y2",iconv("big5","utf-8",$cname))
                            ->setCellValue('F3',$OCL_LY_NO)
                            ->setCellValue('Y3',$OCL_OUT_DATE)
                            ->setCellValue('Y4',iconv("big5","utf-8",$OTD_NO))
                            ->setCellValue('F4',$OCL_LOT_NO)
							->setCellValue('H7',$OCL_CHK_BAR)
                            ->setCellValue("H8",$OCL_CHK_LOT_PASTED)
                            ->setCellValue('H9',$OCL_CHK_SPEC_LINK)
                            ->setCellValue('H10',$OCL_CHK_PIPE)
                            ->setCellValue('H11',$OCL_CHK_SURFACE)
							->setCellValue('H12',$OCL_CHK_UPCAP)
							->setCellValue('H13',$OCL_CHK_HOSE)
							->setCellValue('A21',$start)
							->setCellValue('G21',$end)
							//->setCellValue('E28',$barcode)
							->setCellValue('D30',$barcode0);

		$ii="*".$OCL_LOT_NO."*";
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4',$ii);

					
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp/'.$fileurl.'.xlsx";</script>';
}

if (isset($_POST["submit"]) and $order==4)
{
	$cname1=trim(substr($cname,0,6));
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	/** PHPExcel */
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	/** PHPExcel_IOFactory */
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	include("../lib/barcode.php");
	$fileurl="../tmp/".date("YmdHis");
	barcode($barcode,$fileurl.".png",25);
//	echo'http://'.$path_root.substr($fileurl,2).'.xlsx';
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
//	$objPHPExcel->getActiveSheet()->setCellValue("A3","test1");
	$atring="../FormList/lorrycheck_v1.xlsx";	
	$objPHPExcel = PHPExcel_IOFactory::load($atring);
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setPath('../tmp/'.$fileurl.".png");
	$objDrawing->setHeight(30);
	$objDrawing->setWidth(210);
	$objDrawing->setCoordinates('D28');
	$H34=replace($CTP_CUSTBAR3,$OCL_LOT_NO);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// 	$objPHPExcel->setActiveSheetIndex(0)->getCell('E28')->setCellValue('E28', 'http://localhost/tmp/'.$barcode.".png");
	$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F2',iconv("big5","utf-8",$pname))
                            ->setCellValue("Y2",iconv("big5","utf-8",$cname))
                            ->setCellValue('F3',$OCL_LY_NO)
                            ->setCellValue('Y3',$OCL_OUT_DATE)
                            ->setCellValue('Y4',iconv("big5","utf-8",$OTD_NO))
                            ->setCellValue('F4',$OCL_LOT_NO)
							->setCellValue('H7',$OCL_CHK_BAR)
                            ->setCellValue("H8",$OCL_CHK_LOT_PASTED)
                            ->setCellValue('H9',$OCL_CHK_SPEC_LINK)
                            ->setCellValue('H10',$OCL_CHK_PIPE)
                            ->setCellValue('H11',$OCL_CHK_SURFACE)
							->setCellValue('H12',$OCL_CHK_UPCAP)
							->setCellValue('H13',$OCL_CHK_HOSE)
							->setCellValue('A21',$start)
							->setCellValue('G21',$end)
							//->setCellValue('E28',$barcode)
							->setCellValue('D30',$barcode0)
							->setCellValue('H34',$H34);

		$ii="*".$OCL_LOT_NO."*";
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4',$ii);

					
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp/'.$fileurl.'.xlsx";</script>';
}

if (isset($_POST["leave"])) 
{
//	rm_dir("../tmp/");
	echo '<script>document.location.href="http://'.$dir.'/prodout/index.php?url=lorry_check_list";</script>';
}



//// For tsmc
if (isset($_POST["submit"]) and $order==1)
{
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	/** PHPExcel */
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	/** PHPExcel_IOFactory */
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$styleThinBlackBorderOutline = array(
'borders' => array (
'outline' => array (
'style' => PHPExcel_Style_Border::BORDER_THIN,  //設置border樣式
//'style' => PHPExcel_Style_Border::BORDER_THICK, 另一種樣式
'color' => array ('argb' => 'FF000000'),     //設置border顏色
),
),
);
	$lent=12-strlen($CTP_CUSTBAR1);
		for($i=0;$i<$lent;$i++){
			$lum.=" ";
		}
		
	$CTP_CUSTBAR1=$CTP_CUSTBAR1.$lum;
	include("../lib/barcode.php");
	$fileur0="../tmp/0".$barcode.date("YmdHis");
	$fileur1="../tmp/1".$barcode.date("YmdHis");
	$fileur2="../tmp/2".$barcode.date("YmdHis");
	$fileur3="../tmp/3".$barcode.date("YmdHis");
	$fileur4="../tmp/4".$barcode.date("YmdHis");
	$fileur5="../tmp/5".$barcode.date("YmdHis");
	$validmon=validmon($cid,$pid);
	$valid=$produce_date."+".$validmon." month";
	echo "</br>";
	echo "Barcode:".$n0='*'.$barcode.'*';
	echo "</br>";
	echo "料號:".$n1="*4".$CTP_CUSTBAR1.date("Ymd", strtotime($valid))."TS*";
	echo "</br>";
	echo "槽號:".$n2="*5".substr($OCL_LOT_NO,7)."*";
	echo "</br>";
	echo "批號:".$n3="*6".substr($OCL_LOT_NO,0,7).substr($OCL_LOT_NO,8,3)."*";
	echo "</br>";
	echo "供應商:".$n4="*375514419*";
	echo "</br>";
	echo "廠別:".$n5="*E".$CTP_CUSTBAR3."*";
	$n0='*'.$barcode.'*';
	$n1="*4".$CTP_CUSTBAR1.date("Ymd", strtotime($valid))."TS*";
	$n2="*5".substr($OCL_LOT_NO,7)."*";
	$n3="*6".substr($OCL_LOT_NO,0,7).substr($OCL_LOT_NO,8,3)."*";
	$n4="*375514419*";
	$n5="*E".$CTP_CUSTBAR3."*";
//	echo'http://'.$path_root.substr($fileurl,2).'.xlsx';
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
//	$objPHPExcel->getActiveSheet()->setCellValue("A3","test1");
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/lorrycheck_tsmc.xlsx");
/*	if(($_GET['cid']=='C20946' or $_GET['cid']=='C20947') and $_GET['pid']=='P050-000'){
	 $objPHPExcel->getActiveSheet(0)->setCellValue('F2',iconv("big5","utf-8",get_prod_name($pid)))
                            ->setCellValue("S2",iconv("big5","utf-8",$cname))
                            ->setCellValue('F3',$OCL_LY_NO)
                            ->setCellValue('S3',$OCL_OUT_DATE)
                            ->setCellValue('S4',$OTD_NO)
                            ->setCellValue('F4',$OCL_LOT_NO)
							->setCellValue('H7',$OCL_CHK_BAR)
                            ->setCellValue("H8",$OCL_CHK_LOT_PASTED)
                            ->setCellValue('H9',$OCL_CHK_SPEC_LINK)
                            ->setCellValue('H10',$OCL_CHK_PIPE)
                            ->setCellValue('H11',$OCL_CHK_SURFACE)
							->setCellValue('H12',$OCL_CHK_UPCAP)
							->setCellValue('H13',$OCL_CHK_HOSE)
							->setCellValue('A21',$start)
							->setCellValue('G21',$end)
							->setCellValue('w24',$n0)
	//						->mergeCells('A24:T33')
							;	
	}
	else{
		*/
	$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F2',iconv("big5","utf-8",$pname))
                            ->setCellValue("Y2",iconv("big5","utf-8",$cname))
                            ->setCellValue('F3',$OCL_LY_NO)
                            ->setCellValue('Y3',$OCL_OUT_DATE)
                            ->setCellValue('Y4',iconv("big5","utf-8",$OTD_NO))
                            ->setCellValue('F4',$OCL_LOT_NO)
							->setCellValue('H7',$OCL_CHK_BAR)
                            ->setCellValue("H8",$OCL_CHK_LOT_PASTED)
                            ->setCellValue('H9',$OCL_CHK_SPEC_LINK)
                            ->setCellValue('H10',$OCL_CHK_PIPE)
                            ->setCellValue('H11',$OCL_CHK_SURFACE)
							->setCellValue('H12',$OCL_CHK_UPCAP)
							->setCellValue('H13',$OCL_CHK_HOSE)
							->setCellValue('A21',$start)
							->setCellValue('G21',$end)
//							->setCellValue('w24',$n0)
							->setCellValue('E25',$n1)
							->setCellValue('E27',$n2)
							->setCellValue('E29',$n3)
							->setCellValue('E31',$n4)
							->setCellValue('E33',$n5)
							//->setCellValue('E28',$barcode)
							->setCellValue('D30',$barcode0)
							;
	$ii="*".$OCL_LOT_NO."*";
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4',$ii);
//	}


							
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($fileur1.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp/'.$fileur1.'.xlsx";</script>'; 
}

//// 華亞
if (isset($_POST["submit"]) and $order==3)
{
	echo "華亞科技:</br>";
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	/** PHPExcel */
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	/** PHPExcel_IOFactory */
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	include("../lib/barcode.php");
	$fileur0="../tmp/0".$barcode.date("YmdHis");
	$fileur1="../tmp/1".$barcode.date("YmdHis");
	$fileur2="../tmp/2".$barcode.date("YmdHis");
	$fileur3="../tmp/3".$barcode.date("YmdHis");
	$fileur4="../tmp/4".$barcode.date("YmdHis");
	$validmon=validmon($cid,$pid);
	$valid=$produce_date."+".$validmon." month";
	echo "</br>";
	echo "品名:".$n0=$pname;
	if ($n0=='96%H2SO4硫酸'){$n0='H2SO496%';}
	echo "</br>";
	echo "批號:".$n1=$OCL_LOT_NO;
	echo "</br>";
	echo "到期日:".$n2=date("m/d/Y", strtotime($valid));
	echo "</br>";
	echo "槽車/桶編號:".$n3=$OCL_LY_NO;
	echo "</br>";
	barcode($n0,$fileur0.".png",25);
	barcode($n1,$fileur1.".png",30);
	barcode($n2,$fileur2.".png",35);
	barcode($n3,$fileur3.".png",23);
	barcode($n4,$fileur4.".png",20);
//	echo'http://'.$path_root.substr($fileurl,2).'.xlsx';
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
//	$objPHPExcel->getActiveSheet()->setCellValue("A3","test1");
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/lorrycheck_hy.xlsx");
	 $objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setPath('../tmp/'.$fileur0.".png");
	$objDrawing->setHeight(30);
	$objDrawing->setWidth(200);
	$objDrawing->setCoordinates('H26');
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setPath('../tmp/'.$fileur1.".png");
	$objDrawing->setHeight(30);
	$objDrawing->setWidth(200);
	$objDrawing->setCoordinates('H28');
	 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setPath('../tmp/'.$fileur2.".png");
	$objDrawing->setHeight(30);
	$objDrawing->setWidth(200);
	$objDrawing->setCoordinates('H30');
	 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setPath('../tmp/'.$fileur3.".png");
	$objDrawing->setHeight(30);
	$objDrawing->setWidth(200);
	$objDrawing->setCoordinates('H32');
	 $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// 	$objPHPExcel->setActiveSheetIndex(0)->getCell('E28')->setCellValue('E28', 'http://localhost/tmp/'.$barcode.".png");
	$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F2',iconv("big5","utf-8",$pname))
                            ->setCellValue("S2",iconv("big5","utf-8",$cname))
                            ->setCellValue('F3',$OCL_LY_NO)
                            ->setCellValue('S3',$OCL_OUT_DATE)
                            ->setCellValue('S4',$OTD_NO)
                            ->setCellValue('F4',$OCL_LOT_NO)
							->setCellValue('H7',$OCL_CHK_BAR)
                            ->setCellValue("H8",$OCL_CHK_LOT_PASTED)
                            ->setCellValue('H9',$OCL_CHK_SPEC_LINK)
                            ->setCellValue('H10',$OCL_CHK_PIPE)
                            ->setCellValue('H11',$OCL_CHK_SURFACE)
							->setCellValue('H12',$OCL_CHK_UPCAP)
							->setCellValue('H13',$OCL_CHK_HOSE)
							->setCellValue('A21',$start)
							->setCellValue('G21',$end)
							//->setCellValue('E28',$barcode)
							->setCellValue('D30',$barcode0)
							;
							
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($fileur1.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp/'.$fileur1.'.xlsx";</script>'; 
}

function replace($barcode,$OCL_LOT_NO)
{
	$subject=$barcode;
	$pattern='\[LotNo\]';
	$replacement=$OCL_LOT_NO;
	$barcode=preg_replace("/$pattern/i",$replacement,$subject);	
	return $barcode;
}

function get_air_start($lot_no){
		$query="SELECT          LFC_AIR_SEAL_START, LFC_AIR_SEAL, LFC_AIR_SEAL_END
FROM              dbo.LORRY_FILL_CHECK
WHERE          (FDM_LOT_NO = '".$lot_no."')";
$_SESSION['OO']= $query;
$result = mssql_query($query);
$numrow=mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$LFC_AIR_SEAL_START=$row['LFC_AIR_SEAL_START'];
	$LFC_AIR_SEAL_END=$row['LFC_AIR_SEAL_END'];
	return array ($LFC_AIR_SEAL_START,$LFC_AIR_SEAL_END);
	}
	
	if($numrow==0){
	return array ('0','0');	
	}
}

function get_cust_name($cid){
	include_once("../connections/conn.php");
		$query="SELECT          CTD_CUST_NAME 
FROM              dbo.CUSTOMER_DATA
WHERE          (CTD_CUST_NO = '".$cid."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$uname=$row['CTD_CUST_NAME'];}
	return $uname;
}

function get_prod_name($pid){
	include("../connections/conn.php");
$query1="SELECT          PDD_PROD_NO, PDD_PROD_NAME  
FROM              dbo.PRODUCT_DATA
WHERE (PDD_PROD_NO='".trim($pid)."')";	
	$result1= mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);

	while($row1 = mssql_fetch_array($result1)){
		$pname=$row1['PDD_PROD_NAME'];
	}
	return $pname;
}
?>
