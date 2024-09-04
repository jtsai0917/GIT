<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
lasturl();
$path_root=$_SERVER['HTTP_HOST'];
datepick();
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form method="post" action="<?php echo $loginFormAction; ?>">
日期
  <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 
if($_SESSION['datepicker1']){
			echo $_SESSION['datepicker1'];
		}
		else{
		$d=strtotime("-0 Days"); echo date("m/d/Y",$d);
		}
		?>" />
~
<input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 
if($_SESSION['datepicker2']){
			echo $_SESSION['datepicker2'];
		}
		else{
		$d=strtotime("+0 Days"); echo date("m/d/Y",$d);
		}
		?>" />
品名：<span class="d1">
<input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
<input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
if($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
<input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
<input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php 
if($_SESSION['pname']){
			echo $_SESSION['pname'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
Lot No：
<input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_lot.php ', '_self');" />
<input name="textfield4" type="text" id="textfield4" size="10" value="<?php 
if($_SESSION['lid']){
			echo $_SESSION['lid'];
		}
		else{
		echo '';
		}
		?>" />
<input type="submit" name="search" id="search" value="搜尋" />
<input type="submit" name="print" id="print" value="列印" />

</form>
<table width="1240" border="1">
  <tr><td width="40">編號</td><td width="100">提案日期</td><td width="100">LOT NO</td><td width="200">廠商名稱</td><td width="200">產品名稱</td><td width="100">數量</td>
  <td width="500">不合格項目 (列印)</td></tr>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$query="SELECT          FAIL_ITEM_REPORT.*
FROM              FAIL_ITEM_REPORT
WHERE          (FIR_NO <> '')";
if($_SESSION['lid']<>''){$query.=" and (FIR_LOT_NO='".$_SESSION['lid']."')";}
else{
if($_SESSION['datepicker1']<>''){$query.=" and (FIR_REPORT_DATE>='".dod($_SESSION['datepicker1'])."')";}
if($_SESSION['datepicker2']<>''){$query.=" and (FIR_REPORT_DATE<='".dod($_SESSION['datepicker2'])."')";}}
if(isset($_POST['search'])){$_SESSION['lid']=$_POST['textfield4'];$_SESSION['datepicker1']=$_POST['datepicker1'];$_SESSION['datepicker2']=$_POST['datepicker2'];refresh();;
}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{ 
	echo '<tr><td>'.$row['FIR_NO'].'</td><td>'.$row['FIR_REPORT_DATE'].'</td><td>'.$row['FIR_LOT_NO'].'</td><td>'.get_cust_name($row['FIR_CUST_NO']).'</td><td>'.get_prod_name($row['FIR_PROD_NO']).
	'</td><td>'.$row['FIR_QTY'].'</td><td><a href="index.php?url=print_fail_item_report&lot_no='.$row['FIR_LOT_NO'].'" TARGET="_blank" >'.ana_id_to_nick($row['FIR_FAIL_ITEM']).'</td></tr>';
}

if(isset($_POST['print']))
	{
	require_once ("../PHPEXCEL/Classes/PHPExcel.php");
	require_once ("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
	$objPHPExcel = new PHPExcel(); 
$objPHPExcel->setActiveSheetIndex(0);

$query="SELECT          FAIL_ITEM_REPORT.*
FROM              FAIL_ITEM_REPORT
WHERE          (FIR_NO <> '')";
$objPHPExcel->getActiveSheet()->setCellValue("A1", iconv("big5","utf-8","編號"));
$objPHPExcel->getActiveSheet()->setCellValue("B1", iconv("big5","utf-8","提案日期"));
$objPHPExcel->getActiveSheet()->setCellValue("C1", iconv("big5","utf-8","LOT NO"));
$objPHPExcel->getActiveSheet()->setCellValue("D1", iconv("big5","utf-8","廠商名稱"));
$objPHPExcel->getActiveSheet()->setCellValue("E1", iconv("big5","utf-8","產品名稱"));
$objPHPExcel->getActiveSheet()->setCellValue("F1", iconv("big5","utf-8","數量"));
$objPHPExcel->getActiveSheet()->setCellValue("G1", iconv("big5","utf-8","不合格品項目"));
$no=2;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	 $objPHPExcel->getActiveSheet()->setCellValue("'A".$no."'", $row['FIR_NO']);
	  $objPHPExcel->getActiveSheet()->setCellValue("'B".$no."'", $row['FIR_REPORT_DATE']);
	   $objPHPExcel->getActiveSheet()->setCellValue("'C".$no."'", $row['FIR_LOT_NO']);
	    $objPHPExcel->getActiveSheet()->setCellValue("'D".$no."'",iconv("big5","utf-8",get_cust_name($row['FIR_CUST_NO'])));
		 $objPHPExcel->getActiveSheet()->setCellValue("'E".$no."'", iconv("big5","utf-8",get_prod_name($row['FIR_PROD_NO'])));
		 
		  $objPHPExcel->getActiveSheet()->setCellValue("'F".$no."'", $row['FIR_QTY']);
		  $objPHPExcel->getActiveSheet()->setCellValue("'G".$no."'", ana_id_to_nick($row['FIR_FAIL_ITEM']));
		  // $objActSheet->getColumnDimension( 'A')->setWidth(30);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
 $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	   	   $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

	   $no++;
		  
	}

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save('fail.xlsx');
echo '<script>document.location.href="http://'.$path_root.'/cal/fail.xlsx";</script>';	
	
	}
?>
</table>