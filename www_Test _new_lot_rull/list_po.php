<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>TYS 化學藥品充填及管理系統</title>
<link href="css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="gridContainer clearfix">
  <div id="LayoutDiv1">
    <div id="banner">   
      <div id="logo"><img src="pics/TYS.jpg" alt="logo" width="100" height="60"></div>
      <div id="logo_r"></div>
    </div>
    <div id="menu">
      <div id="menuleft"><?php include("./menubar.php")?></div>
      <div id="menuright"><?php include("info.php"); ?></div>
    </div>
    <div id="contain">
<?php
include_once("connections/conn.php");
$query="SELECT DISTINCT 
                            IN_PLAN.IPA_PO_NO, CUSTOMER_DATA_1.CTD_CUST_NAME, CUSTOMER_DATA.CTD_CUST_NAME AS Expr1, 
                            IN_PLAN.IPA_ETA_DATE, IN_PLAN.IPA_CUSTOMS_NO, IN_PLAN.IPA_INVOICE_NO, IN_PLAN.IPA_CONTAINER_NO, 
                            IN_PLAN.IPA_IN_DATE, IN_PLAN_FROM_FILE.IAF_ORDER_QTY, IN_PLAN.IPA_REPORT_DATE
FROM              IN_PLAN INNER JOIN
                            IN_PLAN_PRODUCT ON IN_PLAN.IPA_PO_NO = IN_PLAN_PRODUCT.IPA_PO_NO INNER JOIN
                            IN_PLAN_FROM_FILE ON IN_PLAN.IPA_PO_NO = IN_PLAN_FROM_FILE.IPA_PO_NO INNER JOIN
                            CUSTOMER_DATA ON IN_PLAN_FROM_FILE.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
                            CUSTOMER_DATA AS CUSTOMER_DATA_1 ON 
                            IN_PLAN_PRODUCT.IAP_OUT_CUST_NO = CUSTOMER_DATA_1.CTD_CUST_NO";
$query.=" WHERE          (IN_PLAN.IPA_PO_NO = '".$_GET['po_no']."')";

//echo $query;
$result = mssql_query($query);

while($row = mssql_fetch_array($result))
{
?>
<form id="form1" name="form1" method="post" action="">
<table width="1024" border="1">
  <tr>
    <td width="275">採購單號：
      <label for="po_no3"></label>
      <input name="po_no" type="text" id="po_no3" size="20" value="<?php echo $row['IPA_PO_NO']?>"/></td>
    <td width="315">預交日期：
      <label for="datepicker1"></label>
      <input name="datepicker1" type="text" id="datepicker1" size="10" />
      ~
      <input name="datepicker2" type="text" id="datepicker2" size="10" /></td>
    <td width="304">出荷先： 
      <input name="po_no2" type="text" id="po_no" value="<?=$row['CTD_CUST_NAME']?>" size="16" />
      <input type="submit" name="btn2" id="btn2" value="查詢客戶" /></td>
    <td width="112"><input type="submit" name="btn3" id="btn3" value=" 查 詢 " /></td>
    <tr>
    <td width="275">廠商：
      <label for="po_no3"></label>
      <input name="po_no" type="text" id="po_no3" value="<?=$row['CTD_CUST_NAME']?>" size="16" /> <input name="btn1" type="submit" id="btn1" value="查詢廠商" /></td>
    <td width="315">
      </td>
    <td width="304">&nbsp;</td>
    <td width="112"><input type="submit" name="btn4" id="btn4" value=" 列 印 " /></td>
  </tr>
  </tr>
</table>
</form>
<hr />
<form id="form2" name="form2" method="post" action="">
  <table width="1024" border="1">
    <tr>
      <td width="240">入荷預定日： <?=$row['IPA_ETA_DATE']?></td>
      <td width="240">報關號碼：
        <label for="cuntoms_no"></label>
      <input type="text" name="cuntoms_no" id="cuntoms_no"  value="<?=$row['IPA_CUSTOMS_NO']?>"/></td>
      <td width="240">驗收量：<?=$row['IAF_ORDER_QTY'];?></td>
    </tr>
    <tr>
      <td>INVOICE NO：
      <input type="text" name="cuntoms_no2" id="cuntoms_no2" value="<?=$row['IPA_INVOICE_NO']?>"/></td>
      <td>ＰＯ　ＮＯ：<?=$row['IPA_PO_NO']?></td>
      <td></td>
    </tr>
    <tr>
      <td>ＣＯＮＴＡＩＮＥＲ：
      <input type="text" name="cuntoms_no3" id="cuntoms_no3" value="<?=$row['IPA_CONTAINER_NO']?>" /></td>
      <td>入荷先：<?=$row['CTD_CUST_NAME']?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>實際入荷日期：<?=$row['IPA_IN_DATE']?></td>
      <td>做成日期：<?=$row['IPA_REPORT_DATE']?></td>
      <td class="center_btn"><input type="submit" name="btn5" id="btn5" value="       送       出       " /></td>
    </tr>
  </table>

</form>
<?php
}
 mssql_close($dbhandle);

?>
<form id="form3" name="form3" method="post" action=""> 
  <hr />
  <p>
    <input type="submit" name="rcv_btn" id="rcv_btn" value="　　　　驗　　　　收　　　　　" /> 
  　　　
  <input type="submit" name="rcv_lot" id="rcv_lot" value="　　　　　TYS Lot 輸入　　　　　" /> 
  　
  備註：
  <label for="remark"></label>
  <input name="remark" type="text" id="remark" size="40" />
  <input type="submit" name="submit" id="submit" value="　　儲存備註　　" />
  </p>
  <table width="1024" border="1">
    <tr class="center_btn">
      <td class="center_btn">料號</td>
      <td class="center_btn">品名</td>
      <td class="center_btn">包裝</td>
      <td class="center_btn">荷姿</td>
      <td class="center_btn">Lot No</td>
      <td class="center_btn">單位</td>
      <td class="center_btn">預交量</td>
      <td class="center_btn">數量</td>
      <td class="center_btn">PLT</td>
      <td class="center_btn">COA</td>
      <td class="center_btn">備註</td>
    </tr>
<?php
include_once("connections/conn.php");
$query="SELECT IN_PLAN_PRODUCT.PDD_PROD_NO,PRODUCT_DATA.PDD_PROD_NAME,PRODUCT_DATA.PDD_PACKAGE,PRODUCT_DATA.PDD_STYLE,IN_PLAN_PRODUCT.IAP_LOT_NO, IN_PLAN_PRODUCT.IAP_UNIT,IN_PLAN_PRODUCT.IAP_ORDER_QTY, IN_PLAN_PRODUCT.IAP_QTY, IN_PLAN_PRODUCT.IAP_PLT_NO,IN_PLAN_PRODUCT.IAP_COA_FILE, IN_PLAN_PRODUCT.IAP_MEMO FROM IN_PLAN_PRODUCT INNER JOIN PRODUCT_DATA ON IN_PLAN_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO";
$query=$query." WHERE (IN_PLAN_PRODUCT.IPA_PO_NO = '".$_GET['po_no']."')";
$result = mssql_query($query);

$numRows = mssql_num_rows($result);
$total1=0;
for ($i=0;$i<$numRows;$i++) {$row = mssql_fetch_assoc($result); //將陣列以欄位名索引   
//while($row = mssql_fetch_array($result))
//{
	echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
    echo "<td>".$row['PDD_PROD_NAME']."</td>";
	echo "<td>".$row['PDD_PACKAGE']."</td>";
	echo "<td>".$row['PDD_STYLE']."</td>";	
	echo "<td>".$row['IAP_LOT_NO']."</td>";
	echo "<td>".$row['IAP_UNIT']."</td>";
	echo "<td>".$row['IAP_ORDER_QTY']."</td>";
	echo "<td>".$row['IAP_QTY']."</td>";
	echo "<td>".$row['IAP_PLT_NO']."</td>";
	echo "<td>".$row['IAP_COA_FILE']."</td>";
	echo "<td>".$row['IAP_MEMO']."</td>";
	echo "</tr>";
	if ($row['IAP_ORDER_QTY']){$total=$row['IAP_ORDER_QTY'];}
	$total1=$total1+$row['IAP_QTY'];
}
mssql_close($dbhandle);
$remain=$total-$total1;
echo "殘量：  ".$total."-".$total1."=".$remain;
?>
  </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>

</div>
</body>
</html>
