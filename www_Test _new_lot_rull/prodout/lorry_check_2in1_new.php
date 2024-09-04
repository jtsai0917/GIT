<?php
session_start();
include("../lib/fun.php");
include("../PHPEXCEL/Classes/PHPExcel.php");
include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
include("../lib/barcode.php");
$path_root=$_SERVER['HTTP_HOST'];
datepick();
lasturl();
$version="";
?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  出貨日期區間:
  <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 
		if($_SESSION['datepicker1']){
			echo trim($_SESSION['datepicker1']);}
			else{
		$d=strtotime("-1 Days"); 
		echo date("m/d/Y",$d);	}
	?>"   onchange="set_date_session(this.name,this.value)"/>
~
<input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 
	if($_SESSION['datepicker2']){
		echo trim($_SESSION['datepicker2']);}
	else{
	$d=strtotime("+1 day"); 
	echo date("m/d/Y",$d);	}
?>"   onchange="set_date_session(this.name,this.value)"/>&nbsp;&nbsp;&nbsp;&nbsp;
LOT NO:
<input name="lot_no" type="text" id="lot_no" size="10" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="submit" id="submit" value=" 查  詢 " />&nbsp;&nbsp;&nbsp;&nbsp;
Barcode Size (16-24) 
<input name="font_size" type="text" id="font_size" size="10" value="15"/>&nbsp;&nbsp;&nbsp;&nbsp;

<input type="submit" name="print3" id="print3" value="列印"  />
<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
$font_size=$_POST['font_size'];
if(isset($_POST['ck'])){if($_SESSION['ck']=='on'){$_SESSION['ck']='';}else{$_SESSION['ck']='on';}refresh();}
if(isset($_POST["submit"])){
	$_SESSION['d1']=trim($_POST['datepicker1']);
	$_SESSION['d2']=trim($_POST['datepicker2']);
}
if($_SESSION['d1']){
if($_SESSION['ck']=='on'){$sh='checked="checked"';}else{$sh='';}

echo '<table width="1200" border="1" >
  <tr bgcolor="#CCCCCC"> 
    <td width="150">出貨日期</td>
    <td width="150">品名</td>
    <td width="150">決定書</td>
    <td width="150">客戶</td>
    <td>Lot No (上傳圖檔)</td>
    <td width="150">Lorry No</td>
    <td>列印&nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" name="ck" value="全選" ></td>
  </tr>

';
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
                            CUSTOMER_DATA.CTD_CUST_NAME
FROM              OUT_DECISION INNER JOIN
                            OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO AND 
                            OUT_DECISION.OTD_NO = OUT_PRODUCT.OTD_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            CUSTOMER_DATA ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO LEFT OUTER JOIN
                            OUT_CHECK_LORRY ON OUT_PRODUCT.OTD_NO = OUT_CHECK_LORRY.OTD_NO  
WHERE          (PRODUCT_DATA.PDD_TYPE = 'LY') AND (CUSTOMER_DATA.CTD_CUST_NAME LIKE '%台積%') "; 
if ($_SESSION['d1']<>""){
    $query=$query." AND (OUT_DECISION.OPM_ETA_DATE >='".dod($_SESSION['d1'])."000000')";
}
if ($_SESSION['d2']<>""){
	$query=$query." AND (OUT_DECISION.OPM_ETA_DATE <='".dod($_SESSION['d2'])."235959')";
}
$query.=" order by OUT_DECISION.OPM_ETA_DATE desc";
if ($_POST['lot_no']<>""){
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
                            CUSTOMER_DATA.CTD_CUST_NAME
FROM              OUT_DECISION INNER JOIN
                            OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO AND 
                            OUT_DECISION.OTD_NO = OUT_PRODUCT.OTD_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            CUSTOMER_DATA ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO LEFT OUTER JOIN
                            OUT_CHECK_LORRY ON OUT_PRODUCT.OTD_NO = OUT_CHECK_LORRY.OTD_NO 
WHERE          (OUT_PRODUCT.OPD_LOT_NO = '".$_POST['lot_no']."') AND (CUSTOMER_DATA.CTD_CUST_NAME LIKE '%台積%')";

}
//  echo "<BR>".$query."<BR>";
$result = mssql_query($query);
while($row = mssql_fetch_array($result))
	{
		$ocl_lot=$row['otdno'];
		$otdno=$row['otdno'];
		$OCL_LY_NO=$row['OCL_LY_NO'];
		$OPD_LOT_NO=$row['OPD_LOT_NO'];
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
		$barcode=$row['CTP_BIGHOSEBAR'];
		$pid=$row['PDD_PROD_NO'];
		$cid=$row['CTD_CUST_NO'];
		
		echo '<tr><td width="150">'.std($row['OPM_ETA_DATE']).'</td>';
    	echo '<td width="150">'.get_prod_name($pid).'</td>';
   		echo '<td width="150">'.$otdno.'</td>';
    	echo '<td width="150">'.get_cust_name($cid).'</td>';
		echo '<td><a target="_blank" href="index.php?url=upload_tsmc_id&lotno='.$OPD_LOT_NO.'">'.$OPD_LOT_NO.'</a>('.tsmc_id($OPD_LOT_NO).')</td>';
    	
    	echo '<td width="150">'.$OCL_LY_NO.'</td>';
		$_SESSION[$pid]=$aa=array($pid,$cid,$OTD_NO);
		if($_SESSION['ck']=='on'){$ch=' checked="checked"';}
    	echo '<td><input type="checkbox" name="chkbox[]" id="1" value="'.$OPD_LOT_NO.'"'.$ch.' /></td></tr>';
	}
	echo '</table>';
}
//   echo "<BR>".$query."<BR>";
?>
</form>
</br>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$n=2;
if(isset($_POST['print3']))
{
	//// Starting excel
	$dat=date("YmdHis");
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/TSMC_ID_2way_v2.xlsx");
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
			),
		),
	);
	
	$ck=$_POST['chkbox'];
	$num=count($ck);
	
	for($i=0;$i<$num;$i++)
	{
		$query="SELECT          FILLPLAN_OUT_DECIDE.PDD_PROD_NO, FILLPLAN_OUT_DECIDE.FDM_LOT_NO, 
                            CUSTOMER_PRODUCTS.CTP_PRINT_FMT, CUSTOMER_PRODUCTS.CTP_BIGHOSEBAR, 
                            FILLPLAN_OUT_DECIDE.FOD_DAY, FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                            CUSTOMER_PRODUCTS.CTP_CUSTBAR1, CUSTOMER_PRODUCTS.CTP_CUSTBAR2, 
                            CUSTOMER_PRODUCTS.CTP_CUSTBAR3, FILLPLAN_OUT_DECIDE.FOD_O_DAY, 
                            FILLPLAN_OUT_DECIDE.FOD_O_YEAR_MONTH, FILLPLAN_OUT_DECIDE.FDM_REAL_OUT_TIME, 
                            CUSTOMER_PRODUCTS.CTP_VALID_MON, CUSTOMER_PRODUCTS.CTP_REMNANT_MON, 
                            FILL_INDICATE.FID_FILL_BEGIN_DATE, CUSTOMER_PRODUCTS.PDD_PROD_NO AS Expr1, 
                            CUSTOMER_PRODUCTS.CTD_CUST_NO
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            FILL_INDICATE ON FILLPLAN_OUT_DECIDE.FDM_LOT_NO = FILL_INDICATE.FDM_LOT_NO INNER JOIN
                            OUT_PRODUCT ON FILL_INDICATE.FDM_LOT_NO = OUT_PRODUCT.OPD_LOT_NO INNER JOIN
                            CUSTOMER_PRODUCTS ON OUT_PRODUCT.PDD_PROD_NO = CUSTOMER_PRODUCTS.PDD_PROD_NO INNER JOIN
                            OUT_DECISION ON OUT_PRODUCT.OTD_NO = OUT_DECISION.OTD_NO AND 
                            CUSTOMER_PRODUCTS.CTD_CUST_NO = OUT_DECISION.CTD_CUST_NO    
WHERE          (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".trim($ck[$i])."')";

//	WHERE          (OUT_CHECK_LORRY.OTD_NO = '".trim($ck[$i])."')";
//		echo "<BR>".$query."<BR>";
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
			{
				$objPHPExcel->setActiveSheetIndex(0)
								->mergeCells('C'.($n+12).':C'.($n+13))
								->mergeCells('C'.($n+4).':C'.($n+5))
								->mergeCells('C'.($n+6).':C'.($n+7))
								->mergeCells('C'.($n+8).':C'.($n+9))
								->mergeCells('C'.($n+10).':C'.($n+11))
								
								->mergeCells('D'.($n+12).':D'.($n+13))
								->mergeCells('D'.($n+4).':D'.($n+5))
								->mergeCells('D'.($n+6).':D'.($n+7))
								->mergeCells('D'.($n+8).':D'.($n+9))
								->mergeCells('D'.($n+10).':D'.($n+11))
								
								->mergeCells('E'.($n+12).':E'.($n+13))
								->mergeCells('E'.($n+4).':E'.($n+5))
								->mergeCells('E'.($n+6).':E'.($n+7))
								->mergeCells('E'.($n+8).':E'.($n+9))
								->mergeCells('E'.($n+10).':E'.($n+11))
								;
				$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($n+4).':C'.($n+12))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								
				$OCL_LOT_NO=trim($row['FDM_LOT_NO']);
				$FDM_LOT_NO=substr($OCL_LOT_NO,-4,4);
				$produce_date=$row['FOD_YEAR_MONTH'].$row['FOD_DAY'];
				$barcode=$row['CTP_BIGHOSEBAR'];
				$CTP_CUSTBAR1=$row['CTP_CUSTBAR1'];
				$CTP_CUSTBAR2=$row['CTP_CUSTBAR2'];
				$CTP_CUSTBAR3=$row['CTP_CUSTBAR3'];
				$pid=$row['PDD_PROD_NO'];
				$cid=$row['CTD_CUST_NO'];
				$FOD_O_YEAR_MONTH=$row['FOD_O_YEAR_MONTH'];
				$FOD_O_DAY=$row['FOD_O_DAY'];
				$OCL_OUT_DATE=(substr($row['OPM_ETA_DATE'],0,8));

				$sub_mon=$row['CTP_VALID_MON']-$row['CTP_REMNANT_MON'];
				$valid_time= date( "Y/m/d", strtotime( "$prod_date +$sub_mon month" ) );
		 		$validmon=validmon($cid,$pid);
				if(substr($produce_date,4)=='0829'){
		 				$valid=$produce_date."+".$validmon." month -1 days";

			 		}
			 		elseif(substr($produce_date,4)=='0830'){
		 				$valid=$produce_date."+".$validmon." month -2 days";

			 		}
			 		elseif(substr($produce_date,4)=='0831'){
		 				$valid=$produce_date."+".$validmon." month -3 days";

			 		}
			 		else{
			 		 	$valid=$produce_date."+".$validmon." month";

			 		}
				
				list ($start, $end) = get_air_start($OCL_LOT_NO);
				$cname=get_cust_name($cid);
				$pname=get_prod_name($pid);
				// 判斷是否為台積電
				$str1 = $cname;
				$str2 = '台積';
				$order=1;			
			//	echo "<BR>19115151<BR>";	
					$subject=$barcode;
					$pattern='\[LotNo\]';
					$replacement=$OCL_LOT_NO;
					$barcode=preg_replace("/$pattern/i",$replacement,$subject);
					
					//// replace barcode 字串 [MakeDate]
					$subject=$barcode;
					$pattern='\[MakeDate\]';
					$replacement=$OCL_CHK_DATE;
					$barcode=preg_replace("/$pattern/i",$replacement,$subject);	
					$fileurl=get_tsmc_id($OCL_LOT_NO);
					$validmon=validmon($cid,$pid);
					if(substr($produce_date,4)=='0829'){
		 				$valid=$produce_date."+".$validmon." month -1 days";

			 		}
			 		elseif(substr($produce_date,4)=='0830'){
		 				$valid=$produce_date."+".$validmon." month -2 days";

			 		}
			 		elseif(substr($produce_date,4)=='0831'){
		 				$valid=$produce_date."+".$validmon." month -3 days";

			 		}
			 		else{
			 		 	$valid=$produce_date."+".$validmon." month";

			 		}
					$CTP_CUSTBAR1=trim($CTP_CUSTBAR1);
					$lum='';
					$lent=12-strlen($CTP_CUSTBAR1);
					for($i2=0;$i2<$lent;$i2++){
						$lum.=" ";
					}
					$CTP_CUSTBAR1=$CTP_CUSTBAR1.$lum;
					$n0='*'.$barcode.'*';
					$n1="*4".$CTP_CUSTBAR1.date("Ymd", strtotime($valid))."S*";
					$np1="4".$CTP_CUSTBAR1.date("Ymd", strtotime($valid))."S";
					$n2="*5".$FDM_LOT_NO."*";
					$np2="5".$FDM_LOT_NO;
					$n3="*6".substr($OCL_LOT_NO,0,7).substr($OCL_LOT_NO,8,3)."*";
					$np3="6".substr($OCL_LOT_NO,0,7).substr($OCL_LOT_NO,8,3);
					$n4="*375514419*";
					$np4="375514419";
					$n5="*E".$CTP_CUSTBAR3."*";
					$np5="E".$CTP_CUSTBAR3;
				//	
				 	$fileurl= "..".trim($fileurl);
					$qrstring="||".substr($n1,1,-1)."||".substr($n2,1,-1)."||".substr($n3,1,-1)."||".substr($n4,1,-1)."||".substr($n5,1,-1);
					qrcode($qrstring,"n".$i.".png",4);
									
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n".$i.".png");
					$objDrawing->setCoordinates('B'.($n+7)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));

					//  E 1
					$fileurl_png="../tmp/".$np1.".png";
					barcode128($np1,$fileurl_png,20);
					$objDrawing1 = new PHPExcel_Worksheet_Drawing();
					$objDrawing1->setPath($fileurl_png);
					$objDrawing1->setCoordinates('E'.($n+4)."'");
					$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//  E 2
					$fileurl_png="../tmp/".$np2.".png";
					barcode128($np2,$fileurl_png,20);
					$objDrawing1 = new PHPExcel_Worksheet_Drawing();
					$objDrawing1->setPath($fileurl_png);
					$objDrawing1->setCoordinates('E'.($n+6)."'");
					$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					
					//  E 3
					$fileurl_png="../tmp/".$np3.".png";
					barcode128($np3,$fileurl_png,20);
					$objDrawing1 = new PHPExcel_Worksheet_Drawing();
					$objDrawing1->setPath($fileurl_png);
					$objDrawing1->setCoordinates('E'.($n+8)."'");
					$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//  E 4
					$fileurl_png="../tmp/".$np4.".png";
					barcode128($np4,$fileurl_png,20);
					$objDrawing1 = new PHPExcel_Worksheet_Drawing();
					$objDrawing1->setPath($fileurl_png);
					$objDrawing1->setCoordinates('E'.($n+10)."'");
					$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//  E 5
					$fileurl_png="../tmp/".$np5.".png";
					barcode128($np5,$fileurl_png,20);
					$objDrawing1 = new PHPExcel_Worksheet_Drawing();
					$objDrawing1->setPath($fileurl_png);
					$objDrawing1->setCoordinates('E'.($n+12)."'");
					$objDrawing1->setWorksheet($objPHPExcel->getActiveSheet(0));

					
					
					
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setResizeProportional(false);
					$objDrawing->setPath($fileurl);
					$objDrawing->setHeight(390);
					$objDrawing->setWidth(600);
					$objDrawing->setCoordinates('B'.($n+15)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					/*
					//n1
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n1.png");
					$objDrawing->setCoordinates('F'.($n+4)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//n2
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n2.png");
					$objDrawing->setCoordinates('F'.($n+6)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//n3
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n3.png");
					$objDrawing->setCoordinates('F'.($n+8)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//n4
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n4.png");
					$objDrawing->setCoordinates('F'.($n+10)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//n5
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n5.png");
					$objDrawing->setCoordinates('F'.($n+12)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					*/
				//	echo "<BR>19115151<BR>";	
					$objPHPExcel->setActiveSheetIndex(0)								
				/*
								->setCellValue('E'.($n+4),$n1)
								->setCellValue('E'.($n+5),$n1)
								->setCellValue('E'.($n+6),$n2)
								->setCellValue('E'.($n+7),$n2)
								->setCellValue('E'.($n+8),$n3)
								->setCellValue('E'.($n+9),$n3)
								->setCellValue('E'.($n+10),$n4)
								->setCellValue('E'.($n+11),$n4)
								->setCellValue('E'.($n+12),$n5)
								->setCellValue('E'.($n+13),$n5)
				*/
								->setCellValue('B'.($n+1),iconv("big5","utf-8","TSMC Chemical lorry/Gas trailer Barcode二合一單")) //Material Barcode   台灣三菱化學股份有限公司（MCTW）
								->setCellValue('B'.($n+2),iconv("big5","utf-8","Material Barcode"))
								->setCellValue('B'.($n+14),iconv("big5","utf-8","Chemical lorry/Gas trailer CoA上傳資訊"))
								->setCellValue('C'.($n+4),iconv("big5","utf-8","料號"))
								->setCellValue('C'.($n+6),iconv("big5","utf-8","槽號"))
								->setCellValue('C'.($n+8),iconv("big5","utf-8","批號"))
								->setCellValue('C'.($n+10),iconv("big5","utf-8","供應商"))
								->setCellValue('C'.($n+12),iconv("big5","utf-8","廠別"))
								->setCellValue('B'.($n+35),iconv("big5","utf-8","CoA 是否符合出貨規格 (SPEC) : _Y_(Y/N)"))
								->setCellValue('B'.($n+36),iconv("big5","utf-8","Chemical lorry/Gas trailer CoA上傳資訊裡的BatchID是否與 出貨單相符: _Y_(Y/N)"))
								->setCellValue('B'.($n+37),iconv("big5","utf-8","填表人 (supplier) :____________ ; 確認者 (supplier) : ____________"))
								->setCellValue('C'.($n),iconv("big5","utf-8","台灣三菱化學股份有限公司（MCTW）"))
								->setCellValue('B'.($n-1),iconv("big5","utf-8","〔附件-3〕"))
								->setCellValue('E'.($n+38),iconv("big5","utf-8","Page 1/1 （TLEQA915020103A1）"))
		//						->setCellValue('F'.($n+38),iconv("big5","utf-8","Page 1/1（TLEQA915020103A1）"))
								;
						
					
					$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+1))->getFont()->setSize(18);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+2))->getFont()->setSize(18);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+14))->getFont()->setSize(16);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($n))->getFont()->setSize(16);
		//			$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+4).":E".($n+13))->getFont()->setSize(17);
					$objPHPExcel->getActiveSheet(0)->getStyle('B'.($n+29).":B".($n+35))->getFont()->setSize(14);
					/*
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+4))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+6))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+8))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+10))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+12))->getFont()->setName('3 of 9 Barcode' );	
					*/	
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+14))->getFont()->setSize(16);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n))->getFont()->setSize(16);
		//			$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n-1))->getFont()->setSize(10);
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+38).":F".($n+38))->getFont()->setSize(10);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+38))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+4).':E'.($n+13))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($n+4).':C'.($n+13))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
		//			$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+4).':E'.($n+4))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
					
					$objPHPExcel->setActiveSheetIndex(0)->setBreak('AE'.($n+39), PHPExcel_Worksheet::BREAK_ROW);
					$n=$n+41;
				
				
				
				
			} ///end while
			
	} //end for
//	echo "<BR>19115151<BR>";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	
	$objWriter->save("./tmp/tsmc_id.xlsx");
	echo '<script>document.location.href="http://'.$path_root.'/prodout/tmp/tsmc_id.xlsx";</script>'; 
}

if(isset($_POST['print2']))
{
	//// Starting excel
	$dat=date("YmdHis");
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/TSMC_ID_2way.xlsx");
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
			),
		),
	);
	$ck=$_POST['chkbox'];
	$num=count($ck);
	
	for($i=0;$i<$num;$i++)
	{
		$query="SELECT          FILLPLAN_OUT_DECIDE.PDD_PROD_NO, FILLPLAN_OUT_DECIDE.FDM_LOT_NO, 
                            CUSTOMER_PRODUCTS.CTP_PRINT_FMT, CUSTOMER_PRODUCTS.CTP_BIGHOSEBAR, 
                            FILLPLAN_OUT_DECIDE.FOD_DAY, FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                            CUSTOMER_PRODUCTS.CTP_CUSTBAR1, CUSTOMER_PRODUCTS.CTP_CUSTBAR2, 
                            CUSTOMER_PRODUCTS.CTP_CUSTBAR3, FILLPLAN_OUT_DECIDE.FOD_O_DAY, 
                            FILLPLAN_OUT_DECIDE.FOD_O_YEAR_MONTH, FILLPLAN_OUT_DECIDE.FDM_REAL_OUT_TIME, 
                            CUSTOMER_PRODUCTS.CTP_VALID_MON, CUSTOMER_PRODUCTS.CTP_REMNANT_MON, 
                            FILL_INDICATE.FID_FILL_BEGIN_DATE, CUSTOMER_PRODUCTS.PDD_PROD_NO AS Expr1, 
                            CUSTOMER_PRODUCTS.CTD_CUST_NO
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            FILL_INDICATE ON FILLPLAN_OUT_DECIDE.FDM_LOT_NO = FILL_INDICATE.FDM_LOT_NO INNER JOIN
                            OUT_PRODUCT ON FILL_INDICATE.FDM_LOT_NO = OUT_PRODUCT.OPD_LOT_NO INNER JOIN
                            CUSTOMER_PRODUCTS ON OUT_PRODUCT.PDD_PROD_NO = CUSTOMER_PRODUCTS.PDD_PROD_NO INNER JOIN
                            OUT_DECISION ON OUT_PRODUCT.OTD_NO = OUT_DECISION.OTD_NO AND 
                            CUSTOMER_PRODUCTS.CTD_CUST_NO = OUT_DECISION.CTD_CUST_NO    
WHERE          (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".trim($ck[$i])."')";

//	WHERE          (OUT_CHECK_LORRY.OTD_NO = '".trim($ck[$i])."')";
	//	echo "<BR>".$query."<BR>";
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
			{
				$OCL_LOT_NO=trim($row['FDM_LOT_NO']);
				$FDM_LOT_NO=substr($OCL_LOT_NO,-4,4);
				$produce_date=$row['FOD_YEAR_MONTH'].$row['FOD_DAY'];
				$barcode=$row['CTP_BIGHOSEBAR'];
				$CTP_CUSTBAR1=$row['CTP_CUSTBAR1'];
				$CTP_CUSTBAR2=$row['CTP_CUSTBAR2'];
				$CTP_CUSTBAR3=$row['CTP_CUSTBAR3'];
				$pid=$row['PDD_PROD_NO'];
				$cid=$row['CTD_CUST_NO'];
				$FOD_O_YEAR_MONTH=$row['FOD_O_YEAR_MONTH'];
				$FOD_O_DAY=$row['FOD_O_DAY'];
				$OCL_OUT_DATE=(substr($row['OPM_ETA_DATE'],0,8));

				$sub_mon=$row['CTP_VALID_MON']-$row['CTP_REMNANT_MON'];
				$valid_time= date( "Y/m/d", strtotime( "$prod_date +$sub_mon month" ) );
		 		$validmon=validmon($cid,$pid);
				
				list ($start, $end) = get_air_start($OCL_LOT_NO);
				$cname=get_cust_name($cid);
				$pname=get_prod_name($pid);
				// 判斷是否為台積電
				$str1 = $cname;
				$str2 = '台積';
				$order=1;			
			//	echo "<BR>19115151<BR>";	
					$subject=$barcode;
					$pattern='\[LotNo\]';
					$replacement=$OCL_LOT_NO;
					$barcode=preg_replace("/$pattern/i",$replacement,$subject);
					
					//// replace barcode 字串 [MakeDate]
					$subject=$barcode;
					$pattern='\[MakeDate\]';
					$replacement=$OCL_CHK_DATE;
					$barcode=preg_replace("/$pattern/i",$replacement,$subject);	
					$fileurl=get_tsmc_id($OCL_LOT_NO);
					$validmon=validmon($cid,$pid);
					if(substr($produce_date,4)=='0829'){
		 				$valid=$produce_date."+".$validmon." month -1 days";

			 		}
			 		elseif(substr($produce_date,4)=='0830'){
		 				$valid=$produce_date."+".$validmon." month -2 days";

			 		}
			 		elseif(substr($produce_date,4)=='0831'){
		 				$valid=$produce_date."+".$validmon." month -3 days";

			 		}
			 		else{
			 		 	$valid=$produce_date."+".$validmon." month";

			 		}
					$CTP_CUSTBAR1=trim($CTP_CUSTBAR1);
					$lum='';
					$lent=12-strlen($CTP_CUSTBAR1);
					for($i2=0;$i2<$lent;$i2++){
						$lum.=" ";
					}
					$CTP_CUSTBAR1=$CTP_CUSTBAR1.$lum;
					$n0='*'.$barcode.'*';
					$n1="*4".$CTP_CUSTBAR1.date("Ymd", strtotime($valid))."TS*";
					$n2="*5".$FDM_LOT_NO."*";
					$n3="*6".substr($OCL_LOT_NO,0,7).substr($OCL_LOT_NO,8,3)."*";
					$n4="*375514419*";
					$n5="*E".$CTP_CUSTBAR3."*";
					$OPQ="*".$FDM_LOT_NO."*";
				//	
				 	$fileurl= "..".trim($fileurl);
					$qrstring="||".substr($n1,1,-1)."||".substr($n2,1,-1)."||".substr($n3,1,-1)."||".substr($n4,1,-1)."||".substr($n5,1,-1);
					qrcode($qrstring,"n".$i.".png",4);
					echo $qrstring."<BR";
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n".$i.".png");
					$objDrawing->setCoordinates('B'.($n+7)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					/*
					qrcode(substr($n1,1,-1),"n1.png",2);
					qrcode(substr($n2,1,-1),"n2.png",2);
					qrcode(substr($n3,1,-1),"n3.png",2);
					qrcode(substr($n4,1,-1),"n4.png",2);
					qrcode(substr($n5,1,-1),"n5.png",2);
					*/
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setResizeProportional(false);
					$objDrawing->setPath($fileurl);
					$objDrawing->setHeight(390);
					$objDrawing->setWidth(600);
					$objDrawing->setCoordinates('B'.($n+15)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					/*
					//n1
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n1.png");
					$objDrawing->setCoordinates('F'.($n+4)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//n2
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n2.png");
					$objDrawing->setCoordinates('F'.($n+6)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//n3
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n3.png");
					$objDrawing->setCoordinates('F'.($n+8)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//n4
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n4.png");
					$objDrawing->setCoordinates('F'.($n+10)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
					//n5
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath("./n5.png");
					$objDrawing->setCoordinates('F'.($n+12)."'");
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					*/
				//	echo "<BR>19115151<BR>";	
					$objPHPExcel->setActiveSheetIndex(0)								
								->setCellValue('E'.($n+4),$n1)
								->setCellValue('E'.($n+5),$n1)
								->setCellValue('E'.($n+6),$n2)
								->setCellValue('E'.($n+7),$n2)
								->setCellValue('E'.($n+8),$n3)
								->setCellValue('E'.($n+9),$n3)
								->setCellValue('E'.($n+10),$n4)
								->setCellValue('E'.($n+11),$n4)
								->setCellValue('E'.($n+12),$n5)
								->setCellValue('E'.($n+13),$n5)
								->setCellValue('B'.($n+1),iconv("big5","utf-8","TSMC Chemical lorry/Gas trailer Barcode二合一單")) //Material Barcode   台灣三菱化學股份有限公司（MCTW）
								->setCellValue('B'.($n+2),iconv("big5","utf-8","Material Barcode"))
								->setCellValue('B'.($n+14),iconv("big5","utf-8","Chemical lorry/Gas trailer CoA上傳資訊"))
								->setCellValue('C'.($n+4),iconv("big5","utf-8","料號"))
								->setCellValue('C'.($n+6),iconv("big5","utf-8","槽號"))
								->setCellValue('C'.($n+8),iconv("big5","utf-8","批號"))
								->setCellValue('C'.($n+10),iconv("big5","utf-8","供應商"))
								->setCellValue('C'.($n+12),iconv("big5","utf-8","廠別"))
								->setCellValue('B'.($n+35),iconv("big5","utf-8","CoA 是否符合出貨規格 (SPEC) : _Y_(Y/N)"))
								->setCellValue('B'.($n+36),iconv("big5","utf-8","Chemical lorry/Gas trailer CoA上傳資訊裡的BatchID是否與 出貨單相符: _Y_(Y/N)"))
								->setCellValue('B'.($n+37),iconv("big5","utf-8","填表人 (supplier) :____________ ; 確認者 (supplier) : ____________"))
								->setCellValue('C'.($n),iconv("big5","utf-8","台灣三菱化學股份有限公司（MCTW）"))
								->setCellValue('B'.($n-1),iconv("big5","utf-8","〔附件-3〕"))
								->setCellValue('E'.($n+38),iconv("big5","utf-8","Page 1/1 （TLEQA915020103A1）"))
		//						->setCellValue('F'.($n+38),iconv("big5","utf-8","Page 1/1（TLEQA915020103A1）"))
								;
					$objPHPExcel->setActiveSheetIndex(0)
								->mergeCells('C'.($n+12).':C'.($n+13))
								->mergeCells('C'.($n+4).':C'.($n+5))
								->mergeCells('C'.($n+6).':C'.($n+7))
								->mergeCells('C'.($n+8).':C'.($n+9))
								->mergeCells('C'.($n+10).':C'.($n+11))
								;	
					
					$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+1))->getFont()->setSize(18);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+2))->getFont()->setSize(18);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+14))->getFont()->setSize(16);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($n))->getFont()->setSize(16);
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+4).":E".($n+13))->getFont()->setSize(17);
					$objPHPExcel->getActiveSheet(0)->getStyle('B'.($n+29).":B".($n+35))->getFont()->setSize(14);
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+4))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+6))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+8))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+10))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+12))->getFont()->setName('3 of 9 Barcode' );		
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+14))->getFont()->setSize(16);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n))->getFont()->setSize(16);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n-1))->getFont()->setSize(10);
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+38).":F".($n+38))->getFont()->setSize(10);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+38))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+4).':E'.($n+13))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($n+4).':E'.($n+13))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);	
					
					$objPHPExcel->setActiveSheetIndex(0)->setBreak('AE'.($n+39), PHPExcel_Worksheet::BREAK_ROW);
					$n=$n+41;
				
				
				
				
			} ///end while
			
	} //end for
//	echo "<BR>19115151<BR>";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	
	$objWriter->save("./tmp/tsmc_id.xlsx");
	echo '<script>document.location.href="http://'.$path_root.'/prodout/tmp/tsmc_id.xlsx";</script>'; 
}


if(isset($_POST['print']))
{
//// Starting excel
	$dat=date("YmdHis");
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/TSMC_ID_2way.xlsx");
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
			),
		),
	);
	$ck=$_POST['chkbox'];
	$num=count($ck);
	
	for($i=0;$i<$num;$i++)
	{
		$query="SELECT          FILLPLAN_OUT_DECIDE.PDD_PROD_NO, FILLPLAN_OUT_DECIDE.FDM_LOT_NO, 
                            CUSTOMER_PRODUCTS.CTP_PRINT_FMT, CUSTOMER_PRODUCTS.CTP_BIGHOSEBAR, 
                            FILLPLAN_OUT_DECIDE.FOD_DAY, FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, 
                            CUSTOMER_PRODUCTS.CTP_CUSTBAR1, CUSTOMER_PRODUCTS.CTP_CUSTBAR2, 
                            CUSTOMER_PRODUCTS.CTP_CUSTBAR3, FILLPLAN_OUT_DECIDE.FOD_O_DAY, 
                            FILLPLAN_OUT_DECIDE.FOD_O_YEAR_MONTH, FILLPLAN_OUT_DECIDE.FDM_REAL_OUT_TIME, 
                            CUSTOMER_PRODUCTS.CTP_VALID_MON, CUSTOMER_PRODUCTS.CTP_REMNANT_MON, 
                            FILL_INDICATE.FID_FILL_BEGIN_DATE, CUSTOMER_PRODUCTS.PDD_PROD_NO AS Expr1, 
                            CUSTOMER_PRODUCTS.CTD_CUST_NO
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            FILL_INDICATE ON FILLPLAN_OUT_DECIDE.FDM_LOT_NO = FILL_INDICATE.FDM_LOT_NO INNER JOIN
                            OUT_PRODUCT ON FILL_INDICATE.FDM_LOT_NO = OUT_PRODUCT.OPD_LOT_NO INNER JOIN
                            CUSTOMER_PRODUCTS ON OUT_PRODUCT.PDD_PROD_NO = CUSTOMER_PRODUCTS.PDD_PROD_NO INNER JOIN
                            OUT_DECISION ON OUT_PRODUCT.OTD_NO = OUT_DECISION.OTD_NO AND 
                            CUSTOMER_PRODUCTS.CTD_CUST_NO = OUT_DECISION.CTD_CUST_NO    
WHERE          (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".trim($ck[$i])."')";

//	WHERE          (OUT_CHECK_LORRY.OTD_NO = '".trim($ck[$i])."')";
//		echo "<BR>".$query."<BR>";
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
			{
				$OCL_LOT_NO=trim($row['FDM_LOT_NO']);
				$FDM_LOT_NO=substr($OCL_LOT_NO,-4,4);
				$produce_date=$row['FOD_YEAR_MONTH'].$row['FOD_DAY'];
				$barcode=$row['CTP_BIGHOSEBAR'];
				$CTP_CUSTBAR1=$row['CTP_CUSTBAR1'];
				$CTP_CUSTBAR2=$row['CTP_CUSTBAR2'];
				$CTP_CUSTBAR3=$row['CTP_CUSTBAR3'];
				$pid=$row['PDD_PROD_NO'];
				$cid=$row['CTD_CUST_NO'];
				$FOD_O_YEAR_MONTH=$row['FOD_O_YEAR_MONTH'];
				$FOD_O_DAY=$row['FOD_O_DAY'];
				$OCL_OUT_DATE=(substr($row['OPM_ETA_DATE'],0,8));

				$sub_mon=$row['CTP_VALID_MON']-$row['CTP_REMNANT_MON'];
				$valid_time= date( "Y/m/d", strtotime( "$prod_date +$sub_mon month" ) );
		 		$validmon=validmon($cid,$pid);
				
		//		echo "valid:".$valid."<BR>";
				list ($start, $end) = get_air_start($OCL_LOT_NO);
				$cname=get_cust_name($cid);
				$pname=get_prod_name($pid);
				// 判斷是否為台積電
				$str1 = $cname;
				$str2 = '台積';
				$order=1;			
			//	echo "<BR>19115151<BR>";	
					$subject=$barcode;
					$pattern='\[LotNo\]';
					$replacement=$OCL_LOT_NO;
					$barcode=preg_replace("/$pattern/i",$replacement,$subject);
					
					//// replace barcode 字串 [MakeDate]
					$subject=$barcode;
					$pattern='\[MakeDate\]';
					$replacement=$OCL_CHK_DATE;
					$barcode=preg_replace("/$pattern/i",$replacement,$subject);	
					$fileurl=get_tsmc_id($OCL_LOT_NO);
					$validmon=validmon($cid,$pid);

					if(substr($produce_date,4)=='0829'){
		 				$valid=$produce_date."+".$validmon." month -1 days";

			 		}
			 		elseif(substr($produce_date,4)=='0830'){
		 				$valid=$produce_date."+".$validmon." month -2 days";

			 		}
			 		elseif(substr($produce_date,4)=='0831'){
		 				$valid=$produce_date."+".$validmon." month -3 days";

			 		}
			 		else{
			 		 	$valid=$produce_date."+".$validmon." month";

			 		}
					$CTP_CUSTBAR1=trim($CTP_CUSTBAR1);
					$lum='';
					$lent=12-strlen($CTP_CUSTBAR1);
					for($i2=0;$i2<$lent;$i2++){
						$lum.=" ";
					}
					$CTP_CUSTBAR1=$CTP_CUSTBAR1.$lum;
					$n0='*'.$barcode.'*';
					$n1="*4".$CTP_CUSTBAR1.date("Ymd", strtotime($valid))."TS*"; 
					echo "B2:".$n1."<BR>";
					$n2="*5".$FDM_LOT_NO."*";
					$n3="*6".substr($OCL_LOT_NO,0,7).substr($OCL_LOT_NO,8,3)."*";
					$n4="*375514419*";
					$n5="*E".$CTP_CUSTBAR3."*";
					$OPQ="*".$FDM_LOT_NO."*";
				//	
				 	$fileurl= "..".trim($fileurl);

					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setResizeProportional(false);
					
					$objDrawing->setPath($fileurl);
			
					$objDrawing->setHeight(390);
					$objDrawing->setWidth(600);

					$objDrawing->setCoordinates('B'.($n+15)."'");

					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
					
				//	echo "<BR>19115151<BR>";	
					$objPHPExcel->setActiveSheetIndex(0)								
								->setCellValue('E'.($n+4),$n1)
								->setCellValue('E'.($n+5),$n1)
								->setCellValue('E'.($n+6),$n2)
								->setCellValue('E'.($n+7),$n2)
								->setCellValue('E'.($n+8),$n3)
								->setCellValue('E'.($n+9),$n3)
								->setCellValue('E'.($n+10),$n4)
								->setCellValue('E'.($n+11),$n4)
								->setCellValue('E'.($n+12),$n5)
								->setCellValue('E'.($n+13),$n5)
								->setCellValue('C'.($n+1),iconv("big5","utf-8","TSMC Chemical lorry/Gas trailer Barcode二合一單")) //Material Barcode   台灣三菱化學股份有限公司（MCTW）
								->setCellValue('B'.($n+2),iconv("big5","utf-8","Material Barcode"))
								->setCellValue('B'.($n+14),iconv("big5","utf-8","Chemical lorry/Gas trailer CoA上傳資訊"))
								->setCellValue('C'.($n+4),iconv("big5","utf-8","料號"))
								->setCellValue('C'.($n+6),iconv("big5","utf-8","槽號"))
								->setCellValue('C'.($n+8),iconv("big5","utf-8","批號"))
								->setCellValue('C'.($n+10),iconv("big5","utf-8","供應商"))
								->setCellValue('C'.($n+12),iconv("big5","utf-8","廠別"))
								->setCellValue('B'.($n+35),iconv("big5","utf-8","CoA 是否符合出貨規格 (SPEC) : _Y_(Y/N)"))
								->setCellValue('B'.($n+36),iconv("big5","utf-8","Chemical lorry/Gas trailer CoA上傳資訊裡的BatchID是否與 出貨單相符: _Y_(Y/N)"))
								->setCellValue('B'.($n+37),iconv("big5","utf-8","填表人 (supplier) :____________ ; 確認者 (supplier) : ____________"))
								->setCellValue('E'.($n),iconv("big5","utf-8","台灣三菱化學股份有限公司（MCTW）"))
								->setCellValue('B'.($n-1),iconv("big5","utf-8","〔附件-3〕"))
								->setCellValue('E'.($n+38),iconv("big5","utf-8","Page 1/1"))
								->setCellValue('F'.($n+38),iconv("big5","utf-8","（TLEQA915020103A1）"))
								;
					$objPHPExcel->setActiveSheetIndex(0)
								->mergeCells('C'.($n+12).':C'.($n+13))
								->mergeCells('C'.($n+4).':C'.($n+5))
								->mergeCells('C'.($n+6).':C'.($n+7))
								->mergeCells('C'.($n+8).':C'.($n+9))
								->mergeCells('C'.($n+10).':C'.($n+11))
								;	
					
					$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($n+1))->getFont()->setSize(18);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+2))->getFont()->setSize(18);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+14))->getFont()->setSize(16);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n))->getFont()->setSize(16);
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+4).":E".($n+13))->getFont()->setSize($font_size);
					$objPHPExcel->getActiveSheet(0)->getStyle('B'.($n+29).":B".($n+35))->getFont()->setSize(14);
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+4))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+6))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+8))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+10))->getFont()->setName('3 of 9 Barcode' );
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+12))->getFont()->setName('3 of 9 Barcode' );		
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n+14))->getFont()->setSize(16);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n))->getFont()->setSize(16);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($n-1))->getFont()->setSize(10);
					$objPHPExcel->getActiveSheet(0)->getStyle('E'.($n+38).":F".($n+38))->getFont()->setSize(10);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+38))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($n+4).':E'.($n+13))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($n+4).':E'.($n+13))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
					
					
								/*
								$objPHPExcel->getActiveSheet(0)->getStyle('K'.($n+2))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->setActiveSheetIndex(0)->getStyle('K'.($n+2))->getFont()->setSize(20);
								$objPHPExcel->getActiveSheet(0)->getStyle('I'.($n+1))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+21))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+21).':T'.($n+31))->getFont()->setSize(15);
								$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+33).':T'.($n+43))->getFont()->setSize(15);
								
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+23))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+25))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+27))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+29))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('V'.($n+21))->getFont()->setName('Free 3 of 9 Extended' );
								
								//additional page
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+33))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($n+33).':T'.($n+33))->getFont()->setSize(15);
								$objPHPExcel->setActiveSheetIndex(0)->getStyle('I'.($n+1))->getFont()->setSize(24);
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+35))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+37))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+39))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('E'.($n+41))->getFont()->setName('Free 3 of 9 Extended' );
								$objPHPExcel->getActiveSheet()->getStyle('V'.($n+33))->getFont()->setName('Free 3 of 9 Extended' );
								
								
//					$objPHPExcel->setActiveSheetIndex(0)->getStyle('V'.($n+21).':AE'.($n+22))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);		
//					$objPHPExcel->setActiveSheetIndex(0)->getStyle('V'.($n+21).':AE'.($n+22))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//					$objPHPExcel->setActiveSheetIndex(0)->getStyle('V'.($n+21).':AE'.($n+22))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//					$objPHPExcel->setActiveSheetIndex(0)->getStyle('V'.($n+21).':AE'.($n+22))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+21).':T'.($n+30))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);		
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+21).':T'.($n+30))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+21).':T'.($n+30))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+21).':T'.($n+30))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+33).':T'.($n+42))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);		
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+33).':T'.($n+42))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+33).':T'.($n+42))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+33).':T'.($n+42))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('V'.($n+24).':AE'.($n+30))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);		
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('V'.($n+24).':AE'.($n+30))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('V'.($n+24).':AE'.($n+30))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+3).':AE'.($n+19))->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);		
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+3).':AE'.($n+19))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+3).':AE'.($n+19))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+5).':A'.($n+12))->getFont()->setSize(9);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('Z'.($n+24).':Z'.($n+30))->getFont()->setSize(8);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($n+15).':AE'.($n+15))->getFont()->setSize(9);
					*/
					
					$objPHPExcel->setActiveSheetIndex(0)->setBreak('AE'.($n+39), PHPExcel_Worksheet::BREAK_ROW);
					$n=$n+41;
				
				
				
				
			} ///end while
			
	} //end for
//	echo "<BR>19115151<BR>";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	
	$objWriter->save("./tmp/tsmc_id.xlsx");
	echo '<script>document.location.href="http://'.$path_root.'/prodout/tmp/tsmc_id.xlsx"</script>'; 
}  //end post print

function tsmc_id($lotno){
	$query="SELECT TSMC_ID.* FROM TSMC_ID WHERE (FILE_LOT_NO = '".$lotno."')";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows>0){return "V";}
	
}

function get_tsmc_id($lotno){
	$query="SELECT TSMC_ID.* FROM TSMC_ID WHERE (FILE_LOT_NO = '".$lotno."') order by createdatetime desc";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[3];	
}

function qrcode($value,$filepath,$size){
	require_once("../phpqrcode/phpqrcode.php");
	QRcode::png($value,$filepath,"L",$size,2);
}

?>
