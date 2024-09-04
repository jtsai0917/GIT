<?php 
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
$lot_no=$_GET['lot_no'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=big5" />
<title>台帳列印</title>
</head>

<body>
<form action="<?php echo $loginFormAction; ?>" method="post" name="form1" class="big24" id="form1">
<table width="1240" border="1">
  <tr>
  <td>
製品、受入檢查作業台帳 < <?php echo $_GET['lot_no'];?> ></td></tr></table>
  <table width="1240" border="1">
    <tr>
<?php 
$query="SELECT AnalyzeDesign.*, AnalyzeDesign.AND_CANCEL, AnalyzeDesign.AND_REPORT_DATETIME, EMPLOYEE_DATA.EMP_NAME, AnalyzeDesign.AND_MEMO, AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_APPLY_DATE, PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_PROD_NO, PRODUCT_DATA.PDD_CHEMICAL, PRODUCT_DATA.PDD_PROD_NAME, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_NEED_NO, dbo.FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, dbo.FILLPLAN_OUT_DECIDE.FOD_DAY, FILLPLAN_OUT_DECIDE.CTD_CUST_NO FROM dbo.AnalyzeDesign INNER JOIN dbo.PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN dbo.EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO LEFT OUTER JOIN dbo.FILLPLAN_OUT_DECIDE ON dbo.AnalyzeDesign.AND_LOT_NO = dbo.FILLPLAN_OUT_DECIDE.FDM_LOT_NO WHERE (AnalyzeDesign.AND_NEED_NO<=150) and 
(AnalyzeDesign.AND_LOT_NO = '".$_GET['lot_no']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);


	$vv=new get_from_lot_no;
	$vv->lid=$_GET['lot_no'];
	$vv->ani();
	$needno=$vv->needno;
	if ((($_GET['type']=='DM') or ($_GET['type']=='BTL')) and (($_GET['pid']=='NH4OH') or ($_GET['pid']=='IPA')
	or ($_GET['pid']=='H2O2') or ($_GET['pid']=='H2SO4') or (($_GET['pid']=='HF') and ($needno<=150)) or ($_GET['pid']=='HNO3'))){
		include("../lib/print_ani.php");
		$gflot=new get_from_lot_no;
		$gflot->lid=$_GET['lot_no'];
		$gflot->cid();
		$prt=new prt_count;
		$prt->pdd_chemical=$gflot->pdd_chemical;
		$prt->table=get_table('M13',$gflot->pdd_chemical);
		$query="SELECT DISTINCT Sample_All.SMA_DRUMNO, dbo.Sample_All.SMA_ID
				FROM              Sample_All INNER JOIN
                            ".$prt->table." ON Sample_All.SMA_LOT = ".$prt->table.".LotNo AND 
                            Sample_All.SMA_ID = ".$prt->table.".SampleNo
				WHERE          (".$prt->table.".LotNo = '".$_GET['lot_no']."')";

	   echo '  <td width="140">三項分析:選擇桶號</td>';
	   echo'   <td width="200" align="center">充填口
        <select name="co0" id="co0">'.
	$result = mssql_query($query);
	$x=1;
	while($row = mssql_fetch_array($result)){
	if ($x==1){$s=' selected="selected"';}
	else{$s='';}
	echo '<option'.$s.' value="'.$row['SMA_DRUMNO'].'">'.$row['SMA_DRUMNO']."--".$row['SMA_ID'].'</option>';
	$x=$x+1;
	}
      ;
	   echo' </select></td>  <td width="200" align="center">前段
        <select name="co1" id="co1">
          <option></option>'.
		
	$result = mssql_query($query);
	$x=1;
	while($row = mssql_fetch_array($result)){
	if ($x==2){$s=' selected="selected"';}
	else{$s='';}
	echo '<option'.$s.' value="'.$row['SMA_DRUMNO'].'">'.$row['SMA_DRUMNO']."--".$row['SMA_ID'].'</option>';
	$x=$x+1;
	}
      '</select></td>';
	   echo'   <td width="200" align="center">中段
        <select name="co2" id="co2">
          <option></option>'.
	$result = mssql_query($query);
	$x=1;
	while($row = mssql_fetch_array($result)){
	if ($x==3){$s=' selected="selected"';}
	else{$s='';}
	echo '<option'.$s.' value="'.$row['SMA_DRUMNO'].'">'.$row['SMA_DRUMNO']."--".$row['SMA_ID'].'</option>';
	$x=$x+1;
	}
      '</select></td>';
	   echo'   <td width="200" align="center">後段
        <select name="co3" id="co3">
          <option></option>'.
	$result = mssql_query($query);
	$x=1;
	while($row = mssql_fetch_array($result)){
	if ($x==4){$s=' selected="selected"';}
	else{$s='';}
	echo '<option'.$s.' value="'.$row['SMA_DRUMNO'].'">'.$row['SMA_DRUMNO']."--".$row['SMA_ID'].'</option>';
	$x=$x+1;
	}
      '</select></td>';

	}
	else {echo '<td></td>';}
echo '
      <td align="center">
      <input type="submit" name="print" id="print" value=" 列 印 " />
    <input type="submit" name="leave" id="leave" value=" 離 開 " />';

$query="SELECT          FILLPLAN_OUT_DECIDE.FDM_LOT_NO, FILLPLAN_OUT_DECIDE.CTD_CUST_NO, 
                            CUSTOMER_DATA.CTD_CUST_NAME
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            CUSTOMER_DATA ON FILLPLAN_OUT_DECIDE.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE          (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".$_GET['lot_no']."')";
$result = mssql_query($query);
$numrows=mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$cust_no=$row['CTD_CUST_NO'];
	$cust_name=$row['CTD_CUST_NAME'];
}
////////////    Get anylize info   //////////////
$query="SELECT          dbo.AnalyzeDesign.*, dbo.PRODUCT_DATA.PDD_PROD_NAME,dbo.PRODUCT_DATA.PDD_CHEMICAL
FROM              dbo.AnalyzeDesign INNER JOIN
                            dbo.PRODUCT_DATA ON dbo.AnalyzeDesign.AND_GOODS = dbo.PRODUCT_DATA.PDD_PROD_NO
WHERE          (dbo.AnalyzeDesign.AND_LOT_NO = '".$_GET['lot_no']."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$prod_name=$row['PDD_PROD_NAME'];
	$sample_get_datetime=$row['AND_GET_DATETIME'];
	$get_qty=$row['AND_GET_QTY'];
	$result_datetime=$row['AND_RESULT_DATETIME'];
	$report_datetime=$row['AND_REPORT_DATETIME'];
	$and_item=$_GET['items'];
	$pid=$row['AND_GOODS'];
	$pdd_cam=$row['PDD_CHEMICAL'];
	$operator=$row['AND_PERSON'];
	$needno=$row['AND_NEED_NO'];
}
if($needno<=150){$type="製品檢查作業台帳";$style=1;}
if(($needno>150) and ($needno<=270)){$type="解析檢查作業台帳";$stype=2;}
if($needno>=270){$type="受入檢查作業台帳";$style=3;}
///////////// 取得測試項目groupname //////////////
$str="(ANI_INDEX='".str_replace(",","') or (ANI_INDEX='",$and_item)."')";
$query="SELECT          dbo.AnalyzeItem.ANI_INDEX, dbo.AnalyzeItem.ANI_ID, dbo.AnalyzeItem.ANI_NICKNAME, 
                            dbo.AnalyzeItem.ANI_FULLNAME, dbo.AnalyzeItem.ANI_UNIT, dbo.AnalyzeItem.ANI_ANR_ID, 
                            dbo.AnalyzeItem.ANI_GROUPNAME, dbo.AnalyzeItem.ANI_DATAFIELD, dbo.AnalyzeItem.ANI_ORDER
FROM              dbo.AnalyzeItem INNER JOIN
                            dbo.QC_Item ON dbo.AnalyzeItem.ANI_FULLNAME = dbo.QC_Item.ItemName
WHERE         ".$str." ORDER BY   QC_Item.ItemType, AnalyzeItem.ANI_GROUPNAME, QC_Item.AtomicNo, QC_Item.ItemNickName";
$result = mssql_query($query);

echo'</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>';

////print out
$path_root=$_SERVER['HTTP_HOST'];
$loginFormAction = $_SERVER['PHP_SELF'];
if((isset($_POST["print"]) and ($_GET['type']=='LY')) or (isset($_POST["print"]) and ($needno>=270)) or (isset($_POST["print"]) and ($_GET['pid']=='CAN'))) {
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$fileurl="../tmp1/T__".$_GET['lot_no'];
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./form/count.xlsx");	
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
    $objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(8);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(11);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(8);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('g4:g5'); 
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('h4:h5');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('i4:i5');
	$objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('d1',iconv("big5","utf-8",$type))
							->setCellValue('a2',iconv("big5","utf-8","藥品名：").iconv("big5","utf-8",$prod_name))
							->setCellValue('a3',"LotNO:".$lot_no)							
							->setCellValue('a4',iconv("big5","utf-8","Sample取得:<".$sample_get_datetime."共".$get_qty."瓶>"))	
							->setCellValue('d2',iconv("big5","utf-8","客戶：".$cust_name))
							->setCellValue('a5',iconv("big5","utf-8","時間：".date("H時i分")))
							->setCellValue('d4',iconv("big5","utf-8","報告希望：".$report_datetime))
							->setCellValue('g3',iconv("big5","utf-8","作業指示者"))
							->setCellValue('h3',iconv("big5","utf-8","分析高專，主任"))
							->setCellValue('i3',iconv("big5","utf-8","分析部門主管"))
							->setCellValue('a6',iconv("big5","utf-8",'合否判定依經審查核准，最新版次之 "各客戶規格及OOC"'))
							->setCellValue('a7',iconv("big5","utf-8","ITEMS"))
							->setCellValue('b7',iconv("big5","utf-8","SPEC"))
							->setCellValue('c7',iconv("big5","utf-8","UNIT"))
							->setCellValue('d7',iconv("big5","utf-8","報告值"))
							->setCellValue('e7',iconv("big5","utf-8","合否判定"))
							->setCellValue('f7',iconv("big5","utf-8","DL"))
							->setCellValue('g7',iconv("big5","utf-8","測定者"))
							->setCellValue('h7',iconv("big5","utf-8","測定實施日"))
							->setCellValue('i7',iconv("big5","utf-8","備考"));
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('a7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('b7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('c7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('d7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('e7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('f7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('g7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('h7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('i7')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('G3')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('H3')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('I3')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('G3:I5')->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A7:I7')->getBorders()->getAllborders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("g4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("g4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$i=8;$i1=1;
$ani_group_cmp="";

while($row = mssql_fetch_array($result)){
	
	$ani_index=$row['ANI_INDEX'];
	$ani_nick=$row['ANI_NICKNAME'];
	$ani_group=$row['ANI_GROUPNAME'];	
	$ani_datefield=$row['ANI_DATAFIELD'];
	$tb=new tbl;
	$tb->aid=$ani_index;
	$tb->dfd=$ani_datefield;
	$tb->pdd_chemical=$pdd_cam;
//	
	$table_=get_table_($ani_group,$pdd_cam);
	$aa=explode(",",$table_);
	$n=count($aa);
	for($p=0;$p<$n;$p++)
	{
		$table=$aa[$p];
		
	$fix_lotno=_is_fixed($_GET['lot_no'],$table);
	$OPP=test($ani_datefield,$table);
	if($OPP>0){
//	echo $fix_lotno."<BR>";
//    if(($ani_datefield=='PO4') or ($ani_datefield=='SO4' or ($ani_datefield=='CL'))){$table=$tb->tbno;}
	if(trim($cust_no)==''){$cust_no='C00001';$cust_name='TYS Internal';}
	if(strpos(trim($_GET['lot_no']),"HIC")>0){$cust_no='C00000';$cust_name='TYS';}
	$_SESSION['cust3']=$_SESSION['cid']=$cust_no;
	list ($itm_name,$spc,$unit,$dl,$fullname,$usl,$lsl,$showmode,$stdu,$stdl)=showcust_spec1($cust_no,$pid,$ani_index);
	if($spc=='none'){$spc='-------';$showmode='';$Ok='無客規';}
	if($unit=='none'){$unit='-------';}
	if($dl=='none'){$dl='-------';}
//	echo "#".$tb->aid."#";
//	echo "<BR>";
//	if($tb->aid==19){echo $table,$_GET['lot_no'],$ani_datefield,$spc,$dl,$usl,$lsl,$showmode,$stdu,$stdl;}
//	echo "<BR>";
	list ($report,$SampleNo,$Tester,$AnalyzeTime,$Ok)=get_report_value($table,$fix_lotno,$ani_datefield,$spc,$dl,$usl,$lsl,$showmode,$stdu,$stdl);
	if($fix_lotno<>$_GET['lot_no']){$AnalyzeTime= ddd(lotno_to_date($_GET['lot_no']));}
	
//	echo $fix_lotno.":".$ani_datefield.":".$table.":".$report."<BR>";
//	echo "<BR>".$table."#datefield:".$ani_datefield."#spc:".$spc."#dl:".$dl."#report:".$report."<BR>";
//	if(($report>$lsl) and ($report<>'') and ($spec=='') and ($usl=='') and ($lsl<>'')){$Ok="合";}
	if($Ok=='0'){$Ok="否";}

	if ($ani_group_cmp!=$row['ANI_GROUPNAME']){
	$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("'A".$i."'",iconv("big5","utf-8","樣品瓶號：".$SampleNo))
							->setCellValue("'E".$i."'",iconv("big5","utf-8","測試項目：".$ani_group));
							$i=$i+1;							
							}
	$report=trim($report);
	if(((trim($stdu)<>'') and (trim($stdl)<>'')) and (($report>$stdu) or ($report<$stdl)) and ($report<>'')){$Ok="否";}
	if(((trim($stdu)<>'') and (trim($stdl)<>'')) and (($report<=$stdu) or ($report>=$stdl)) and ($report<>'')){$Ok="合";}
	if(($report=='1.0E-6') and ($spc<>'')){ $report='<DL';$Ok="合";}
	if(($report<>'') and ($spc=='')){$dl=$spc=$unit=$Ok="--------";}
	if($report<$dl){ $report='<DL';$Ok="合";}
	if($report=='0'){ $report='0';}
	if(trim($report)=='none'){$report=' ';}
	if((trim($Ok)=='none') or (trim($Ok)=='')){;$showmode='';$Ok=' ';}
	if(trim($Tester)=='none'){$Tester=' ';}
	if(trim($AnalyzeTime)=='none'){$AnalyzeTime=' ';}
	if($Ok==' '){
//	$spc='________';	$unit='________';  $dl='________';
	}
	if($ani_nick=='RP'){$ani_nick='SRP';}
	if($ani_nick=='Cs'){$_SESSION['value']=$report;}
	if($ani_nick=='Au')
	{
		if($i1==1){
		$au=$i;$i1++;$i=$i+1; }
		
		$objPHPExcel->setActiveSheetIndex(0)
 							->setCellValue("'A".$au."'",iconv("big5","utf-8",$ani_nick))
	    					->setCellValue("'B".$au."'",iconv("big5","utf-8",$spc))
	  						->setCellValue("'C".$au."'",iconv("big5","utf-8",$unit))
							->setCellValue("'D".$au."'",iconv("big5","utf-8",$report))
							->setCellValue("'E".$au."'",iconv("big5","utf-8",$Ok))
							->setCellValue("'F".$au."'",iconv("big5","utf-8",$dl))
							->setCellValue("'G".$au."'",iconv("big5","utf-8",$Tester))
							->setCellValue("'H".$au."'",iconv("big5","utf-8",$AnalyzeTime))
							;
			if(trim($Ok)=='否')
			{
				$objPHPExcel->getActiveSheet()->getStyle('E'.$au)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$au)->getFill()->getStartColor()->setARGB('30FF3300');
				$objPHPExcel->getActiveSheet()->getStyle('E'.$au)->getFont()->getColor()->setRGB('000000');
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$au,iconv("big5","utf-8",$Ok));
			}
	}
	else{
	$objPHPExcel->setActiveSheetIndex(0)
 							->setCellValue("'A".$i."'",iconv("big5","utf-8",$ani_nick))
	    					->setCellValue("'B".$i."'",iconv("big5","utf-8",$spc))
	  						->setCellValue("'C".$i."'",iconv("big5","utf-8",$unit))
							->setCellValue("'D".$i."'",iconv("big5","utf-8",$report))
							->setCellValue("'E".$i."'",iconv("big5","utf-8",$Ok))
							->setCellValue("'F".$i."'",iconv("big5","utf-8",$dl))
							->setCellValue("'G".$i."'",iconv("big5","utf-8",$Tester))
							->setCellValue("'H".$i."'",iconv("big5","utf-8",$AnalyzeTime))
							;
		if(trim($Ok)=='否')
		{
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFill()->getStartColor()->setARGB('30FF3300');
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getFont()->getColor()->setRGB('000000');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$i,iconv("big5","utf-8",$Ok));
		}
	$i=$i+1; 
	}
	}  /// end $OPP
	}  ///end divided table

$ani_group_cmp=$row['ANI_GROUPNAME'];
	}
	$query="SELECT          AIL_ISO_NO
FROM              dbo.ACCOUNT_ISO_LIST
WHERE          (PDD_CHEMICAL = '".$pdd_cam."') AND (PDD_CLASS = '商品')";
if($style==3){
$query="SELECT          AIL_ISO_NO
FROM              dbo.ACCOUNT_ISO_LIST
WHERE          (PDD_CHEMICAL = '".$pdd_cam."') AND (PDD_CLASS = '原料')";
}

$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
	$ver=$row['AIL_ISO_NO'];
	}
$objPHPExcel->setActiveSheetIndex(0)
 							->setCellValue("'H".$i."'",iconv("big5","utf-8",$ver));
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
$objWriter->save($fileurl.".xls");

///////load
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load($fileurl.".xls");
	for($s=7;$s<$i;$s++){
		for($y='A';$y<='I';$y++){
		$objPHPExcel->setActiveSheetIndex()->getStyle("'".$y.$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex()->getStyle("'".$y.$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex()->getStyle("'".$y.$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}
		$objPHPExcel->setActiveSheetIndex()->getStyle("'A".$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
$objWriter->save($fileurl.".xlsx");
echo '<script>document.location.href="http://'.$path_root.'/tmp1/'.$fileurl.'.xlsx";</script>';	
}
if (isset($_POST["leave"])) 
{
	rm_dir("../tmp1/");
	echo '<script>document.location.href="http://'.$path_root.'/cal/index.php?url=cal_prod_count_";</script>';
}

if((isset($_POST["print"])) and (($_GET['type']=='DM') or ($_GET['type']=='BTL')) and (($_GET['pid']=='H2SO4') or ($_GET['pid']=='H2O2') 
or ($_GET['pid']=='NH4OH') or ($_GET['pid']=='IPA') or ($_GET['pid']=='HNO3') or ($_GET['pid']=='HF'))){
	$prt->lot_no=$_GET['lot_no'];
 	$prt->type=$_GET['type'];
	$prt->select0=$_POST['co0'];
	$prt->select1=$_POST['co1'];
	$prt->select2=$_POST['co2'];
	$prt->select3=$_POST['co3'];
	if($prt->pdd_chemical =='H2SO4'){$prt->activesheet=0;$prt->prt_3_h2so4();}
	if($prt->pdd_chemical =='H2O2'){$prt->activesheet=1;$prt->prt_3_h2o2();}
	if($prt->pdd_chemical =='NH4OH'){$prt->activesheet=2;$prt->prt_3_nh4oh();}
	if($prt->pdd_chemical =='IPA'){$prt->activesheet=3;$prt->prt_3_ipa();}
	if($prt->pdd_chemical =='CAN'){$prt->activesheet=5;$prt->prt_3_can();}
	if($_GET['pid'] =='HF'){$prt->activesheet=4;$prt->prt_3_hf();}

}

function _is_fixed($lotno,$table,$group){
	$query="SELECT          AND_GOODS
FROM              AnalyzeDesign
WHERE          AND_LOT_NO ='".$lotno."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$pdd_id=$row[0];
	$query="SELECT          AnalyzeDesign.AND_GOODS, ".$table.".LotNo, ".$table.".Ok
FROM              AnalyzeDesign INNER JOIN
                            ".$table." ON AnalyzeDesign.AND_LOT_NO = ".$table.".LotNo
WHERE          (".$table.".Ok = '1') AND (AnalyzeDesign.AND_GOODS = '".$pdd_id."') AND 
                            (".$table.".LotNo = '".$lotno."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$numrow=mssql_num_rows($result);
	if($numrow>0){return $lotno;}
	else{
		$query="SELECT    top 1      ".$table.".LotNo
FROM              AnalyzeDesign INNER JOIN
                            ".$table." ON AnalyzeDesign.AND_LOT_NO = ".$table.".LotNo
WHERE          (".$table.".Ok = '1') AND (AnalyzeDesign.AND_GOODS = '".$pdd_id."') ORDER BY   ".$table.".AnalyzeTime DESC";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		return $row[0];
	}
}

function test($datafield,$table){
	$query="SELECT          COLUMN_NAME
FROM              INFORMATION_SCHEMA.COLUMNS
WHERE          (TABLE_NAME = '".$table."') AND (COLUMN_NAME = '".$datafield."')";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);	
	return $numrow;
}

?>
