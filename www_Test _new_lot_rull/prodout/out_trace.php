<?php
include("../lib/fun.php");
include("../connections/conn.php");
include("../lib/jtsai.php");
lasturl();
datepick();
?>
<form action="<?php echo $loginFormAction; ?>" method="post" name="form1" id="form1">
  <label for="textfield"></label>
  <table width="1240" border="1">
    <tr>
      <td>出荷決定日期
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 
	 if($_SESSION['datepicker1']){
			echo $_SESSION['datepicker1'];
		}
		else{
		$d=strtotime("-0 Days"); echo date("m/d/Y",$d);
		}
?>" onchange="set_date_session(this.name,this.value)"/>
~
<input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 
		if($_SESSION['datepicker2']){
			echo $_SESSION['datepicker2'];
		}
		else{
		$d=strtotime("+0 Days"); echo date("m/d/Y",$d);
		}
		?>" onchange="set_date_session(this.name,this.value)"/></td>
      <td>訂單單號：<span class="d1">
      <input name="orderno" type="text" id="orderno" size="22" value="<?php
	  if($_SESSION['orderno']<>''){echo $_SESSION['orderno'];}
	  ?>"/>
      </span></td>
      <td><span class="d1">
        <input type="submit" name="search2" id="search2" value="查詢" />
      </span></td>
    </tr>
    <tr>
      <td>品名：<span class="d1">
      <input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
      <input name="pid" type="text" id="pid" size="10" value="<?php 
		if($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
      <input type="button" name="pdd_no2" id="pdd_no2" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no ', '_self');" />
      <input name="pdd_chemical3" type="text" id="pdd_chemical4" size="16" value="<?php 
		if($_SESSION['pid']){
			echo get_prod_name($_SESSION['pid']);
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
      </span></td>
      <td><span class="d1">P/O NO：
          <input type="text" name="pono" id="pono" value="<?php
	  if($_SESSION['pono']<>''){echo $_SESSION['pono'];}
	  ?>"/>
      </span></td>
      <td><span class="d1">
        <input type="submit" name="excel" id="excel" value="EXCEL" />
      </span></td>
    </tr>
    <tr>
      <td><span class="d1">客戶： 
       
      
          <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
        <input name="cid" type="text" id="cid" size="16" value="<?php echo $_SESSION['cid'];?>" readonly="readonly" />
          <input type="button" name="pdd_no" id="pdd_no" value="查詢" onclick="window.open('../cust_no.php?sup=N ', '_self');" />
          <input name="pdd_chemical" type="text" id="pdd_chemical2" size="20" value="<?php echo get_cust_name($_SESSION['cid']);?>" />
      </span></td>
      <td><span class="d1">Lot No：
        <input name="lot_no" type="text" id="" size="20" value="<?php echo $_SESSION['lot_no'];?>" />
          <?php //        <input type="button" name="peint" id="peint" value="列印"> ?>
          <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />
      </span></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>荷姿：<span class="d1">
      <select name="t2" id="t2">
        <option value=""></option>
        <?php 
		      $query="SELECT DISTINCT([PDD_TYPE]) FROM [dbo].[PRODUCT_DATA]";
			  $result = mssql_query($query);

$numRows = mssql_num_rows($result);
//echo $query;
while($row = mssql_fetch_array($result))
{
    echo '<option value="'.$row['PDD_TYPE'].'">'.$row['PDD_TYPE'].'</option>';
}
mssql_close($dbhandle);

			  ?>
      </select>
      </span></td>
      <td>出荷倉別：
      <?php 
	  	if($_SESSION['storage2']=='TYS')
		{
			$str1=' selected="selected" ';
		}
		else{
			$str1='';	
		}
		if($_SESSION['storage2']=='TAINAN')
		{
			$str2=' selected="selected" ';
		}
		else{
			$str2='';	
		}
		if($_SESSION['storage2']=='TAICHUNG')
		{
			$str3=' selected="selected" ';
		}
		else{
			$str3='';	
		}
	  ?>
        <select name="storage2" id="storage" onchange="set_date_session(this.name,this.value)">
          <option value=""></option>	
          <option value="TYS" <?php echo $str1;?>>TYS</option>
          <option value="TAINAN" <?php echo $str2;?>>TAINAN</option>
          <option value="TAICHUNG" <?php echo $str3;?>>TAICHUNG</option>
      </select></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<p>
  <?php
$query=$tmp_query="SELECT 
                            OUT_PLAN.OPM_ORDER_NO, OUT_PLAN.OPM_PO_NO, OUT_PLAN.OPM_CREATE_DATE, 
                            OUT_PLAN.OPM_ETA_DATE, OUT_PLAN.CTD_CUST_NO, OUT_PRODUCT.PDD_PROD_NO, 
                            OUT_PRODUCT.OAF_PACKAGE, OUT_PRODUCT.OPD_LOT_NO, OUT_PRODUCT.OPD_TERM_DATE, 
                            OUT_PRODUCT.OPD_QTY_DRUM, OUT_PRODUCT.OPD_ACC_UNIT, OUT_PRODUCT.OPD_QTY_LITER, 
                            OUT_PRODUCT.OPD_QTY_KG, OUT_PRODUCT.OPD_REAL_QTY_KG, OUT_PRODUCT.OPD_INWARD_DATE, 
                            OUT_PRODUCT.OAF_ACC_ID, OUT_PLAN_FROM_FILE.OAF_DELI_CUST_NO, 
                            OUT_PLAN_FROM_FILE.OAF_DELI_CUST_NAME, OUT_PLAN_FROM_FILE.OAF_ORDER_CUST_NO, 
                            OUT_PLAN_FROM_FILE.OAF_ORDER_CUST_NAME, OUT_PLAN_FROM_FILE.OAF_DEP_NO, OUT_PLAN.OPM_STOCK,
                             OUT_PLAN_FROM_FILE.OAF_EXCHANGE_RATE, OUT_PLAN_FROM_FILE.OAF_UNIT, 
                            OUT_PLAN_FROM_FILE.OAF_PRICE, OUT_PRODUCT.OTD_NO, OUT_DECISION.OTD_INVOICE_NO, 
                            OUT_DECISION.OPM_ETA_DATE AS Expr1, OUT_DECISION.OPM_ETA_DATE AS ETA_DATE, 
                            PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_STYLE, PRODUCT_DATA.PDD_CLASS
FROM              OUT_PLAN INNER JOIN
                            OUT_PRODUCT ON OUT_PLAN.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO INNER JOIN
                            OUT_DECISION ON OUT_PRODUCT.OPM_ORDER_NO = OUT_DECISION.OPM_ORDER_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO LEFT OUTER JOIN
                            OUT_PLAN_FROM_FILE ON OUT_PLAN.OPM_ORDER_NO = OUT_PLAN_FROM_FILE.OPM_ORDER_NO
  WHERE (OUT_PLAN.OPM_ORDER_NO<>'') ";
if($_SESSION['datepicker1']=='')
{
	$_SESSION['datepicker1']=date("m/d/Y");
	$query=$query." AND (dbo.OUT_DECISION.OPM_ETA_DATE >='".dod($_SESSION['datepicker1'])."000000')";
}
else{$query=$query." AND (dbo.OUT_DECISION.OPM_ETA_DATE >='".dod($_SESSION['datepicker1'])."000000')";}
if($_SESSION['datepicker2']==''){
	$_SESSION['datepicker2']=date("m/d/Y");
	$query=$query." AND ( dbo.OUT_DECISION.OPM_ETA_DATE <='".dod($_SESSION['datepicker2'])."235959')";
}
else{$query=$query." AND ( dbo.OUT_DECISION.OPM_ETA_DATE <='".dod($_SESSION['datepicker2'])."235959')";}

if (!$_POST['t2']){$_SESSION['t2']='';}
if ($_POST['t2']){
	$_SESSION['t2']=$_POST['t2'];
	$query=$query." AND ( dbo.PRODUCT_DATA.PDD_TYPE ='".$_POST['t2']."')";
}
elseif($_SESSION['t2']){
	$query=$query." AND ( dbo.PRODUCT_DATA.PDD_TYPE ='".$_SESSION['t2']."')";
}

if($_SESSION['cid']<>''){
	$query=$query." AND ( dbo.OUT_PLAN.CTD_CUST_NO ='".$_SESSION['cid']."')";
}

if ($_POST['storage2']){
	$_SESSION['storage2']=$_POST['storage2'];
	if($_POST['storage2']==''){}
	else{
		$query=$query." AND (dbo.OUT_PLAN.OPM_STOCK ='".$_POST['storage2']."')";
	}
}
elseif($_SESSION['storage2']){
	$query=$query." AND (dbo.OUT_PLAN.OPM_STOCK ='".$_SESSION['storage2']."')";
}
if ($_SESSION['prod_no']<>''){
	$query=$query." AND (dbo.OUT_PRODUCT.PDD_PROD_NO = '".$_SESSION['prod_no']."') ";	
}

if($_SESSION['pono']<>''){
	$query=$query." AND ( dbo.OUT_PLAN.OPM_PO_NO ='".$_SESSION['pono']."')";
}
if($_SESSION['lot_no']<>''){
	$query=$query." AND ( dbo.OUT_PRODUCT.OPD_LOT_NO ='".$_SESSION['lot_no']."')";
}
if($_SESSION['orderno']<>''){
	$query=$query." AND ( dbo.OUT_PRODUCT.OPM_ORDER_NO ='".$_SESSION['orderno']."')";
}
$_SESSION['query']=$query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
?> 
</p>
<table width="1240" border="1">
  <tr>
    <td>決定書號碼</td>
    <td>訂單號碼</td>
    <td>P/O NO</td>
    <td>客戶</td>
    <td>藥品</td>
    <td>料號</td>
    <td>LOT NO</td>
    <td>荷姿</td>
    <td>單位</td>
    <td>決定出荷數</td>
    <td>出荷日期</td>
    <td width="90">備註</td>
    <td>轉檔日期</td>
    <td>發票號碼</td>
    <td>簽收單</td>
  </tr>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['search2'])){
$_SESSION['datepicker1']=$_POST['datepicker1'];
$_SESSION['datepicker2']=$_POST['datepicker2'];
$_SESSION['pid']=$_POST['pid'];
$_SESSION['cid']=$_POST['cid'];
$_SESSION['orderno']=$_POST['orderno'];
$_SESSION['pono']=$_POST['pono'];
$_SESSION['lot_no']=$_POST['lot_no'];
refresh();
}
while($row = mssql_fetch_array($result))
{
	//if(trim($row['OPD_LOT_NO'])==$lot1 and $PDD_PROD_NO==trim($row['PDD_PROD_NO']) and $cust_no==trim($row['CTD_CUST_NO']) and $opm_po_no==trim($row['OPM_PO_NO'])){continue;}
	if ($row['OPD_ACC_UNIT']=='KG'){$ss=$row['OPD_QTY_KG'];}
	if ($row['OPD_ACC_UNIT']=='L'){$ss=$row['OPD_QTY_LITER'];}
	$ll=new get_from_lot_no;
	$ll->lid=$row['OPD_LOT_NO'];
	$ll->cid();
	$pdd=new product;
	$pdd->pid=$ll->pid;
	$pdd->getone();
	$oaf=new out_plan_from_file;
	$oaf->lot_no=$row['OPD_LOT_NO'];
	$oaf->order_no=$row['OPM_ORDER_NO'];	
	$oaf->pdd_prod_no=$row['PDD_PROD_NO'];
	$oaf->select();
	if(($row['PDD_TYPE']=='DM' or $row['PDD_TYPE']=='BTL') and $row['PDD_CLASS']=='成品'){
		$lot=substr(trim($row['OPD_LOT_NO']),0,-4);
	}
	else{
		$lot=$row['OPD_LOT_NO'];
		}
	echo 
	'<tr>
    <td>'.$row['OTD_NO'].'</td>
    <td>'.$row['OPM_ORDER_NO'].'</td>
    <td>'.$row['OPM_PO_NO'].'</td>
    <td>'.get_cust_name($row['CTD_CUST_NO']).'</td>
    <td>'.get_prod_name($row['PDD_PROD_NO']).'</td>
    <td>'.$row['PDD_PROD_NO'].'</td>
    <td>'.$lot.'</td>
    <td>'.$pdd->pdd_package.'</td>
    <td>'.$row['OPD_ACC_UNIT'].'</td>
	<td>'.$ss.'</td>
    <td>'.substr($row['ETA_DATE'],0,8).'</td>
    <td>'.$oaf->memo.'</td>
    <td>'.$row['OPD_INWARD_DATE'].'</td>
    <td>'.$row['OTD_INVOICE_NO'].'</td>
    <td>'.$row['OPD_SIGN_RECEIPT'].'</td>
  </tr>';
  $lot1=trim($row['OPD_LOT_NO']);
  $PDD_PROD_NO=trim($row['PDD_PROD_NO']);
  $cust_no=trim($row['CTD_CUST_NO']);
  $opm_po_no=trim($row['OPM_PO_NO']);
?>
<?php
}
?>
</table>
</br>
</body>
</html>
<?php
$path_root=$_SERVER['HTTP_HOST'];
if(isset($_POST['excel'])){
	$dat=date("YmdHis");
	include("../PHPEXCEL/Classes/PHPExcel.php");
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$fileurl="./tmp/tmp";
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel = PHPExcel_IOFactory::load("./formlist/out_trace.xlsx");	
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$query=$_SESSION['query'];
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$i=2;
while($row = mssql_fetch_array($result))
	{
	//if(trim($row['OPD_LOT_NO'])==$lot1 and $PDD_PROD_NO==trim($row['PDD_PROD_NO']) and $cust_no==trim($row['CTD_CUST_NO'])){continue;}
	$ss=new product;
	$ss->pid=$row['PDD_PROD_NO'];
	$ss->getone();
	if($row['OPM_STOCK']=='TYS'){$stock='W1';}
	else{$stock='W2';}
	if($row['OPD_ACC_UNIT']=='KG'){$num=$row['OPD_QTY_KG'];}else{$num=$row['OPD_QTY_LITER'];}
	if(($row['PDD_TYPE']=='DM' or $row['PDD_TYPE']=='BTL') and $row['PDD_CLASS']=='成品'){
		$lot=substr(trim($row['OPD_LOT_NO']),0,-4);
	}
	else{
		$lot=$row['OPD_LOT_NO'];
		}
	$oaf=new out_plan_from_file;
	$oaf->lot_no=$row['OPD_LOT_NO'];
	$oaf->order_no=$row['OPM_ORDER_NO'];	
	$oaf->pdd_prod_no=$row['PDD_PROD_NO'];
	$oaf->select();
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,iconv("big5","utf-8",substr($row['ETA_DATE'],0,8)));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i,iconv("big5","utf-8",$row['OTD_NO']));                       
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i,iconv("big5","utf-8",$row['CTD_CUST_NO']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i,iconv("big5","utf-8",get_cust_name($row['CTD_CUST_NO'])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i,iconv("big5","utf-8",$row['OAF_ORDER_CUST_NO']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i,iconv("big5","utf-8",$row['OAF_ORDER_CUST_NAME']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i,iconv("big5","utf-8",$row['OAF_DEP_NO']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$i,iconv("big5","utf-8",$row['OAF_EXCHANGE_RATE']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$i,iconv("big5","utf-8",$row['PDD_PROD_NO']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$i,$num);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$i,iconv("big5","utf-8",$row['OPD_ACC_UNIT']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$i,iconv("big5","utf-8",$row['OAF_PRICE']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$i,iconv("big5","utf-8",$row['OPD_QTY_KG']*$row['OAF_PRICE']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$i,iconv("big5","utf-8",$row['OPD_QTY_DRUM']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$i,iconv("big5","utf-8",$row['OAF_PACKAGE']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$i,iconv("big5","utf-8",get_pdd_name($row['PDD_PROD_NO'])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$i,iconv("big5","utf-8",$row['OPM_ORDER_NO']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$i,iconv("big5","utf-8",$ss->pdd_package));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$i,iconv("big5","utf-8",$lot));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$i,iconv("big5","utf-8",$row['OAF_ACC_ID']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$i,iconv("big5","utf-8",$oaf->memo));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$i,iconv("big5","utf-8",$row['OPM_PO_NO']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$i,iconv("big5","utf-8",$stock));
	$i=$i+1;
	$lot1=trim($row['OPD_LOT_NO']);
    $PDD_PROD_NO=trim($row['PDD_PROD_NO']);
    $cust_no=trim($row['CTD_CUST_NO']);
	}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

$objWriter->save($fileurl.".xlsx");

echo '<script>document.location.href="http://'.$path_root.'/prodout/'.$fileurl.'.xlsx";</script>';	

}
?>

