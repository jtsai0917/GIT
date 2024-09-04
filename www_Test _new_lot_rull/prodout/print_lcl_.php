<script language="javascript" type="text/javascript">
    //===============================================
    //函式名稱：列印指定的 Word 文件
    //傳入參數：docPath - Word 文件路徑
    //===============================================
    function PrintDoc(docPath)
    {
        var oHead = document.getElementsByTagName("HEAD");
        
        if(oHead)
        {
            var nNode = document.createElement("link");
            nNode.setAttribute("rel","alternate");
            nNode.setAttribute("media","print");
            nNode.setAttribute("href",docPath);
            oHead[0].appendChild(nNode);
            window.print();
            oHead[0].removeChild(nNode);
        }
    }
</script>
<?php 

$path_root=$_SERVER['HTTP_HOST'];
include("../lib/fun.php");
include("../connections/conn.php");
$query="SELECT          OUT_CHECK_LORRY.OCL_LY_NO, OUT_CHECK_LORRY.OCL_LOT_NO, OUT_CHECK_LORRY.OCL_BACK_DATE, 
                            OUT_CHECK_LORRY.OCL_CHK_DATE, OUT_CHECK_LORRY.OCL_CHK_BAR, 
                            OUT_CHECK_LORRY.OCL_CHK_SPEC_LINK, OUT_CHECK_LORRY.OCL_CHK_PIPE, 
                            OUT_CHECK_LORRY.OCL_CHK_LOT_PASTED, OUT_CHECK_LORRY.OCL_CF_MAN, 
                            OUT_CHECK_LORRY.OCL_CHK_SURFACE, OUT_CHECK_LORRY.OCL_CHK_UPCAP, 
                            OUT_CHECK_LORRY.OCL_CHK_HOSE, FILLPLAN_OUT_DECIDE.PDD_PROD_NO, 
                            FILLPLAN_OUT_DECIDE.FDM_LOT_NO, CUSTOMER_PRODUCTS.CTP_PRINT_FMT, 
                            CUSTOMER_PRODUCTS.CTP_BIGHOSEBAR, FILLPLAN_OUT_DECIDE.FOD_DAY, 
                            FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, CUSTOMER_PRODUCTS.CTP_CUSTBAR1, 
                            CUSTOMER_PRODUCTS.CTP_CUSTBAR2, CUSTOMER_PRODUCTS.CTP_CUSTBAR3, 
                            FILLPLAN_OUT_DECIDE.FOD_O_DAY, FILLPLAN_OUT_DECIDE.FOD_O_YEAR_MONTH, OUT_DECISION.OTD_NO, 
                            OUT_DECISION.CTD_CUST_NO, dbo.FILLPLAN_OUT_DECIDE.FDM_REAL_OUT_TIME, dbo.OUT_DECISION.OPM_ETA_DATE

FROM              OUT_CHECK_LORRY INNER JOIN
                            FILLPLAN_OUT_DECIDE ON OUT_CHECK_LORRY.OCL_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO INNER JOIN
                            CUSTOMER_PRODUCTS ON 
                            FILLPLAN_OUT_DECIDE.PDD_PROD_NO = CUSTOMER_PRODUCTS.PDD_PROD_NO INNER JOIN
                            OUT_DECISION ON OUT_CHECK_LORRY.OTD_NO = OUT_DECISION.OTD_NO AND 
                            CUSTOMER_PRODUCTS.CTD_CUST_NO = OUT_DECISION.CTD_CUST_NO
WHERE          (OUT_CHECK_LORRY.OTD_NO = '".$_GET['otd_no']."')";
$result = mssql_query($query);
// $_SESSION['tmp']= $query;
while($row = mssql_fetch_array($result))
	{
		$OTD_NO=$row['OTD_NO'];
		$OCL_LY_NO=$row['OCL_LY_NO'];
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
		$pid=$row['PDD_PROD_NO'];
		$cid=$row['CTD_CUST_NO'];
		$FOD_O_YEAR_MONTH=$row['FOD_O_YEAR_MONTH'];
		$FOD_O_DAY=$row['FOD_O_DAY'];
		$OCL_OUT_DATE=(substr($row['OPM_ETA_DATE'],0,8));
		}
list ($start, $end) = get_air_start($OCL_LOT_NO);
$cname=get_cust_name($cid);
$pname=get_prod_name($pid);
//// 判斷是否為台積電
$str1 = $cname;
$str2 = '台積';
$str3 = '華亞';
if (false !== ($rst = strpos($str1, $str2))) {
    $order=1;
} 
elseif(false !== ($rst = strpos($str1, $str3)))  {
    $order=3;
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
            <td>品名:<?php echo get_prod_name($pid); ?></td>
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

</form>
條碼樣式：  <img src="../barcode/test_1D.php?text=<?php echo $barcode; ?>" />
</body>
</html>
<?php 

$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($_POST["submit"]) and $order==2)
{
	
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
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/lorrycheck_v1.xlsx");
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setPath('../tmp/'.$fileurl.".png");
	$objDrawing->setHeight(30);
	$objDrawing->setWidth(250);
	$objDrawing->setCoordinates('D28');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// 	$objPHPExcel->setActiveSheetIndex(0)->getCell('E28')->setCellValue('E28', 'http://localhost/tmp/'.$barcode.".png");
	$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F2',iconv("big5","utf-8",get_prod_name($pid)))
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
							->setCellValue('D30',$barcode0);
					
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp/'.$fileurl.'.xlsx";</script>';
}

if (isset($_POST["leave"])) 
{
	rm_dir("../tmp/");
	echo '<script>document.location.href="http://'.$path_root.'/prodout/index.php?url=lorry_check_list";</script>';
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
			$lum.="&nbsp;";
		}
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
	if(($_GET['cid']=='C20946' or $_GET['cid']=='C20947') and $_GET['pid']=='P050-000'){
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
	$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F2',iconv("big5","utf-8",get_prod_name($pid)))
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
							->setCellValue('E25',$n1)
							->setCellValue('E27',$n2)
							->setCellValue('E29',$n3)
							->setCellValue('E31',$n4)
							->setCellValue('E33',$n5)
							//->setCellValue('E28',$barcode)
							->setCellValue('D30',$barcode0)
							;
	}

	$objPHPExcel->getActiveSheet(0)->getStyle("w23")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle("w23")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle("w24")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle("w24")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet(0)->getStyle( 'w23:w24')->applyFromArray($styleThinBlackBorderOutline);
							
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
                            ->setCellValue('F2',iconv("big5","utf-8",get_prod_name($pid)))
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
?>
