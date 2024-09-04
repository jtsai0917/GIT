<?php
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../connections/conn.php");
$path_root=$_SERVER['HTTP_HOST'];
lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>輸出樣品瓶清單</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="/js/jquery-ui.css">
  <script src="/js/jquery-1.10.2.js"></script>
  <script src="/js/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style type="text/css">
  .re {
	color: #F00;
}
  </style>
  <script type="text/javascript">
    $(function() {
    $( "#datepicker1" ).datepicker();
	$( "#datepicker2" ).datepicker();
	$( "#datepicker3" ).datepicker();
	$( "#datepicker4" ).datepicker();
  });
  </script>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="1024" border="1">
  輸出樣品瓶清單 
    <tr>
      <td width="924"><span class="re">日期</span>
        <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php 
if ($_GET['datepicker1']){
			echo $_GET['datepicker1'];
			$_SESSION['datepicker1']=$_GET['datepicker1'];
		}
		elseif($_SESSION['datepicker1']){
			echo $_SESSION['datepicker1'];
		}
		else{
		$d=strtotime("-0 Days"); echo date("m/d/Y",$d);
		}
?>" />
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php 
		if ($_GET['datepicker2']){
			echo $_GET['datepicker2'];
			$_SESSION['datepicker2']=$_GET['datepicker2'];
		}
		elseif($_SESSION['datepicker2']){
			echo $_SESSION['datepicker2'];
		}
		else{
		$d=strtotime("+0 Days"); echo date("m/d/Y",$d);
		}
		?>" />
<span class="d1">出荷先：
<input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
<input name="cust_no" type="text"  size="10" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
<input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../cust_no.php?sup=N ', 'Select');" />
<input name="cust_name" type="text" size="16" value="<?php echo $_SESSION['cust_name'];?>" />
</span>決定書號碼
<input type="text" name="deside_no" id="deside_no" value="<?php echo $_SESSION['deside_no'];?>"/><br>
<input type="text" name="lot_no" id="lot_no" value="<?php echo $_SESSION['lot_no'];?>"/> &nbsp; <input type="checkbox" name="s1" id="s1" checked/>
廠內分析樣品瓶 
<input type="checkbox" name="s2" id="s2" checked/>
隨貨或評估樣品瓶
<input type="checkbox" name="s3" id="s3" checked/>
保存樣品
<input type="checkbox" name="s4" id="s4" checked/>
先行樣品</td>
      <td width="100"><input type="submit" name="search" id="search" value="查詢" /></br>
      <input type="submit" name="list" id="list" value="匯出" /></td>
    </tr>
  </table>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if($_POST['search'])
{
	$_SESSION['datepicker1']=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$_SESSION['cust_no']=$_POST['cust_no'];
	$_SESSION['deside_no']=$_POST['deside_no'];
	echo '<table width="1024" border="1"><tr>';
	echo '<td>決定書號碼</td><td>出貨日</td><td>LOT NO</td><td>客戶</td><td>桶號</td><td>樣品瓶號</td><td>類別</td><td>樣品瓶分類</td>';
	echo '</tr>';
	$query="SELECT         dbo.Sample_All.*, dbo.OUT_PRODUCT.OTD_NO, dbo.OUT_PRODUCT.OTN_NO, 
                          dbo.OUT_PRODUCT.OPD_LOT_NO, dbo.OUT_PLAN.CTD_CUST_NO, 
                          dbo.OUT_PLAN.OPM_ETA_DATE
			FROM             dbo.Sample_All INNER JOIN
                          dbo.OUT_PRODUCT ON 
                          dbo.Sample_All.SMA_LOT = dbo.OUT_PRODUCT.OPD_LOT_NO INNER JOIN
                          dbo.OUT_PLAN ON 
                          dbo.OUT_PRODUCT.OPM_ORDER_NO = dbo.OUT_PLAN.OPM_ORDER_NO
			WHERE         (dbo.OUT_PLAN.OPM_ETA_DATE >= '".dod($_POST['datepicker1'])."') and (dbo.OUT_PLAN.OPM_ETA_DATE <= '".dod($_POST['datepicker2'])."')";
	if(($_POST['s1']=='on') or ($_POST['s2']=='on') or ($_POST['s3']=='on') or($_POST['s4']=='on')){	$query.=" and (";}
	if($_POST['s1']=='on'){$query.=" (SMA_SERVICE='1') or";}
	if($_POST['s2']=='on'){$query.=" (SMA_SERVICE='2') or";}
	if($_POST['s3']=='on'){$query.=" (SMA_SERVICE='3') or";}
	if($_POST['s4']=='on'){$query.=" (SMA_SERVICE='4') or";}
	if(($_POST['s1']=='on') or ($_POST['s2']=='on') or ($_POST['s3']=='on') or($_POST['s4']=='on')){$query=substr($query,0,-2);}
	if(($_POST['s1']=='on') or ($_POST['s2']=='on') or ($_POST['s3']=='on') or($_POST['s4']=='on')){$query.=")";}
	if($_POST['cust_no']){$query.=" and (CTD_CUST_NO='".$_POST['cust_no']."')";}
	if($_POST['deside_no']){$query.=" and (OTD_NO='".$_POST['deside_no']."')";}
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);	
	while($row=mssql_fetch_array($result))
	{
		
		echo '<tr><td>'.$row['OTD_NO'].'</td><td>'.$row['OPM_ETA_DATE'].'</td><td>'.$row['SMA_LOT'].'</td><td>'.get_cust_name($row['CTD_CUST_NO']).'</td><td>'.
		$row['SMA_DRUMNO'].'</td><td>'.$row['SMA_ID'].'</td><td>'.sma_service_type($row['SMA_SERVICE']).'</td><td>'.$row['SMA_ANA'].'</td>';
		echo '</tr>';	
	}
}	echo '</table>';

if($_POST['list'])
{
	rm_dir("./tmp/");
	$_SESSION['datepicker1']=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	$_SESSION['cust_no']=$_POST['cust_no'];
	$_SESSION['deside_no']=$_POST['deside_no'];
	$i=2;
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$fileurl="./tmp/T_".date("Ymdhis");
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("./form/sample_lst.xlsx");	
	$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
	
	$query="SELECT         dbo.Sample_All.*, dbo.OUT_PRODUCT.OTD_NO, dbo.OUT_PRODUCT.OTN_NO, 
                          dbo.OUT_PRODUCT.OPD_LOT_NO, dbo.OUT_PLAN.CTD_CUST_NO, 
                          dbo.OUT_PLAN.OPM_ETA_DATE
			FROM             dbo.Sample_All INNER JOIN
                          dbo.OUT_PRODUCT ON 
                          dbo.Sample_All.SMA_LOT = dbo.OUT_PRODUCT.OPD_LOT_NO INNER JOIN
                          dbo.OUT_PLAN ON 
                          dbo.OUT_PRODUCT.OPM_ORDER_NO = dbo.OUT_PLAN.OPM_ORDER_NO
			WHERE         (dbo.OUT_PLAN.OPM_ETA_DATE >= '".dod($_POST['datepicker1'])."') and (dbo.OUT_PLAN.OPM_ETA_DATE <= '".dod($_POST['datepicker2'])."')";
	if(($_POST['s1']=='on') or ($_POST['s2']=='on') or ($_POST['s3']=='on') or($_POST['s4']=='on')){	$query.=" and (";}
	if($_POST['s1']=='on'){$query.=" (SMA_SERVICE='1') or";}
	if($_POST['s2']=='on'){$query.=" (SMA_SERVICE='2') or";}
	if($_POST['s3']=='on'){$query.=" (SMA_SERVICE='3') or";}
	if($_POST['s4']=='on'){$query.=" (SMA_SERVICE='4') or";}
	if(($_POST['s1']=='on') or ($_POST['s2']=='on') or ($_POST['s3']=='on') or($_POST['s4']=='on')){$query=substr($query,0,-2);}
	if(($_POST['s1']=='on') or ($_POST['s2']=='on') or ($_POST['s3']=='on') or($_POST['s4']=='on')){$query.=")";}
	if($_POST['cust_no']){$query.=" and (CTD_CUST_NO='".$_POST['cust_no']."')";}
	if($_POST['deside_no']){$query.=" and (OTD_NO='".$_POST['deside_no']."')";}

	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);	
	while($row=mssql_fetch_array($result))
	{	
	
		$objPHPExcel->setActiveSheetIndex(0)
 							->setCellValue("'A".$i."'",iconv("big5","utf-8",$row['OTD_NO']))
	    					->setCellValue("'B".$i."'",iconv("big5","utf-8",$row['OPM_ETA_DATE']))
	  						->setCellValue("'C".$i."'",iconv("big5","utf-8",$row['SMA_LOT']))
							->setCellValue("'D".$i."'",iconv("big5","utf-8",get_cust_name($row['CTD_CUST_NO'])))
							->setCellValue("'G".$i."'",iconv("big5","utf-8",sma_service_type($row['SMA_SERVICE'])))
							->setCellValue("'H".$i."'",iconv("big5","utf-8",$row['SMA_ANA']))
							->setCellValue("'E".$i."'",iconv("big5","utf-8",$row['SMA_DRUMNO']))
							->setCellValue("'F".$i."'",iconv("big5","utf-8",$row['SMA_ID']));	
		$i=$i+1;
	}	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
	$objWriter->save($fileurl.".xls");
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load($fileurl.".xls");
	
	for($s=1;$s<$i;$s++){
		for($y='A';$y<='H';$y++){
		$objPHPExcel->setActiveSheetIndex()->getStyle("'".$y.$s."'")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->setActiveSheetIndex()->getStyle("'".$y.$s."'")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->setActiveSheetIndex()->getStyle("'".$y.$s."'")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}
		$objPHPExcel->setActiveSheetIndex()->getStyle("'A".$s."'")->getNumberFormat()->setFormatCode('##############');
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save($fileurl.".xlsx");
	echo '<script>document.location.href="http://'.$path_root.'/samples/'.$fileurl.'.xlsx";</script>';	
}
?>
</body>
</html>

<?php
function sma_service_type($sma_service)
{
	if($sma_service==1){return "廠內分析樣品瓶";} 
	if($sma_service==2){return "隨貨或評估樣品瓶";} 
	if($sma_service==3){return "保存樣品";}
	if($sma_service==4){return "先行樣品";}	
}
?>