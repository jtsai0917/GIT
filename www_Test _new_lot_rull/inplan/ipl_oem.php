<?php 
include("../checkuser.php");
include("../lib/fun.php");

session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
lasturl();
datepick();
?>
<!doctype html>
<body>
 

<form name="form1" method="post" action="">

  <table width="1200" border="1">
  <tr>查詢
  </tr>
    <tr>
      <td width="240" bgcolor="#FFFFFF" class="d1"><span class="d1">P/O NO:
      <input type="text" name="pono" id="pono2">      
      </span></td>
      <td width="100" class="d1"><span class="d1">包裝型態:
          <select name="t3" id="t3">
            <option></option>
            <?php  include("../connections/conn.php");
		      $query="SELECT DISTINCT([PDD_PACKAGE]) FROM [dbo].[PRODUCT_DATA]";
			  $result = mssql_query($query);

$numRows = mssql_num_rows($result);
//echo $query;
while($row = mssql_fetch_array($result))
{
    echo '<option value='.$row['PDD_PACKAGE'].'>'.$row['PDD_PACKAGE'].'</option>';
}
mssql_close($dbhandle);

			  ?>
          </select>
      </span></td>
      <td width="300" class="d1"><span class="d1">入荷先： 
       
      
          <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
          <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="16" value="<?php echo $_SESSION['cust_no'];?>" readonly>
          <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onClick="window.open('../cust_no.php?sup=Y ', '_self');">
          <input name="pdd_chemical3" type="text" id="pdd_chemical4" size="20" value="<?php echo $_SESSION['cust_name'];?>" >
      </span></td>
      <td width="100" class="centerutton">        <span class="d1">
        <input type="submit" name="excel2" id="excel2" value="EXCLE">      
      </span></td>
    </tr>
    <tr>
      <td width="160" class="d1"><span class="d1">
      
      入荷預定日:
          <input type="text" name="datepicker1" id="datepicker1" size="10" value="<?php if(!isset($_SESSION['datepicker1'])){$d=strtotime("-1 Days"); echo date("m/d/Y",$d);}else{echo $_SESSION['datepicker1'];}?>" onchange="set_date_session(this.name,this.value)">
~
<input type="text" name="datepicker2" id="datepicker2" size="10" value="<?php if(!isset($_SESSION['datepicker2'])){$d=strtotime("+3 Days"); echo date("m/d/Y",$d);}else{echo $_SESSION['datepicker2'];}?>" onchange="set_date_session(this.name,this.value)">  
      </span></td>
      <td width="100" class="d1"><span class="d1">荷姿 : 
          <select name="t1" id="t1">
            <option value=""></option>
            <?php include("../connections/conn.php");
		      $query="SELECT DISTINCT([PDD_STYLE]) FROM [dbo].[PRODUCT_DATA]";
			  $result = mssql_query($query);

$numRows = mssql_num_rows($result);
//echo $query;
while($row = mssql_fetch_array($result))
{
    echo "<option value=".$row['PDD_STYLE'].'">'.$row['PDD_STYLE'].'</option>';
}
mssql_close($dbhandle);

			  ?>
            
          </select>
      </span></td>
      <td width="300" class="d1"><span class="d1">藥品名：
      <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
      <input name="pdd_chemical" type="text" id="pdd_chemical" size="10" value="<?php echo $_SESSION['prod_no']?>" readonly />
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php?pdd_class=原料 ', '_self');" />
      <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo $_SESSION['prod_name']?>" readonly />

      </span></td>
      <td width="100" class="centerutton">        <span class="d1">
        <input type="button" name="excel3" id="excel3" value=" 列   印 ">      
      </span></td>
    </tr>
    <tr>
      <td width="160" class="d1"><span class="d1">入貨日期 :
        <input type="text" name="datepicker3" id="datepicker3" size="10">
~
<input type="text" name="datepicker4" id="datepicker4" size="10">      
      </span></td>
      <td width="100" class="d1">&nbsp;</td>
      <td width="300" class="d1" align="right">        <span class="d1">
        <input name="submit" type="submit" class="centerutton" id="submit" value="         查      詢         ">      
      </span></td>
      <td width="100" class="centerutton">        <span class="d1">
        <input type="button" name="excel4" id="excel4" value=" 匯   出 ">      
      </span></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}



$editFormAction = $_SERVER['PHP_SELF'];


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 


{
//當確定時	
//valu test


$titles='<table border="1">
    <td width="70">入荷預定日</td>
    <td width="100">P/O NO</td>
    <td width="100">Invoice NO</td>
    <td width="50">入荷先</td>
    <td width="80">料號</td>
    <td width="80">品名</td>
    <td width="80">荷姿</td>
    <td width="30">單位</td>
    <td width="80">包裝型態</td>
    <td width="50">預交量</td>
    <td width="50">數量</td>
    <td width="70">入貨日</td>
    <td width="50">類別</td>
    <td width="100">入荷計畫書</td>
    <td width="70">目前狀況</td>
    <td width="100">原始COA數據</td>
    <td width="60">轉檔日期</td>';
	echo $titles;
include('../connections/conn.php'); 
//mysql_select_db($database_connection, $connection);
$query = "SELECT distinct IN_PLAN.IPA_ETA_DATE, IN_PLAN.IPA_PO_NO, IN_PLAN.IPA_INVOICE_NO, CUSTOMER_DATA.CTD_CUST_NAME, IN_PLAN_FROM_FILE.PDD_PROD_NO, IN_PLAN_FROM_FILE.IAF_PROD_NAME, PRODUCT_DATA.PDD_STYLE, IN_PLAN_PRODUCT.IAP_UNIT, PRODUCT_DATA.PDD_PACKAGE, IN_PLAN_PRODUCT.IAP_ORDER_QTY, IN_PLAN_PRODUCT.IAP_QTY, IN_PLAN.IPA_IN_DATE,IN_PLAN.IPA_TYPE, IN_PLAN_FROM_FILE.IPA_PO_NO AS Expr1, IN_PLAN_PRODUCT.IAP_STATE, IN_PLAN_PRODUCT.IAP_LOT_NO, IN_PLAN_PRODUCT.IAP_INWARD_DATE, CUSTOMER_DATA.CTD_SUPPLIER
FROM IN_PLAN INNER JOIN IN_PLAN_FROM_FILE ON IN_PLAN.IPA_PO_NO = IN_PLAN_FROM_FILE.IPA_PO_NO INNER JOIN IN_PLAN_PRODUCT ON IN_PLAN.IPA_PO_NO = IN_PLAN_PRODUCT.IPA_PO_NO INNER JOIN CUSTOMER_DATA ON IN_PLAN.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN PRODUCT_DATA ON IN_PLAN_FROM_FILE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO";
$query=$query." WHERE (CUSTOMER_DATA.CTD_SUPPLIER='Y')";

if ($_POST['datepicker1']<>""){
$query=$query." and (IN_PLAN.IPA_ETA_DATE >'".dod($_POST['datepicker1'])."')";
}
if ($_POST['datepicker2']<>""){
$query=$query." and (IN_PLAN.IPA_ETA_DATE <'".dod($_POST['datepicker2'])."')";
}
if ($_POST['datepicker3']<>""){
$query=$query." and (IN_PLAN.IPA_IN_DATE >'".dod($_POST['datepicker3'])."')";
}
if ($_POST['datepicker4']<>""){
$query=$query." and (IN_PLAN.IPA_IN_DATE <'".dod($_POST['datepicker4'])."')";
}
if ($_POST['t1']<>""){
$query=$query." and (PRODUCT_DATA.PDD_STYLE LIKE '".$_POST['t1']."%')";
}
if ($_POST['t3']<>""){
$query=$query." and (PRODUCT_DATA.PDD_PACKAGE LIKE '".$_POST['t3']."%')";
}
if ($_POST['pdd_chemical3']<>""){
$query=$query." and (CUSTOMER_DATA.CTD_CUST_NAME LIKE '".$_POST['pdd_chemical3']."%')";
}
if ($_POST['pdd_chemical']<>""){
$query=$query." and (PRODUCT_DATA.PDD_PROD_NO LIKE '".$_POST['pdd_chemical']."%')";
}
$query=$query." and (IN_PLAN.IPA_TYPE ='B')";
if ($_POST['pono']<>""){
$query="SELECT    distinct      IN_PLAN.IPA_ETA_DATE, IN_PLAN.IPA_PO_NO, IN_PLAN.IPA_INVOICE_NO, CUSTOMER_DATA.CTD_CUST_NAME, 
                            IN_PLAN_FROM_FILE.PDD_PROD_NO, PRODUCT_DATA.PDD_STYLE, 
                            IN_PLAN_PRODUCT.IAP_UNIT, PRODUCT_DATA.PDD_PACKAGE, IN_PLAN_PRODUCT.IAP_ORDER_QTY, 
                            IN_PLAN_PRODUCT.IAP_QTY, IN_PLAN.IPA_IN_DATE, IN_PLAN.IPA_TYPE, 
                            IN_PLAN_FROM_FILE.IPA_PO_NO AS Expr1, IN_PLAN_PRODUCT.IAP_STATE, IN_PLAN_PRODUCT.IAP_LOT_NO, 
                            CUSTOMER_DATA.CTD_SUPPLIER
FROM              IN_PLAN INNER JOIN
                            IN_PLAN_FROM_FILE ON IN_PLAN.IPA_PO_NO = IN_PLAN_FROM_FILE.IPA_PO_NO INNER JOIN
                            IN_PLAN_PRODUCT ON IN_PLAN.IPA_PO_NO = IN_PLAN_PRODUCT.IPA_PO_NO INNER JOIN
                            CUSTOMER_DATA ON IN_PLAN.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
                            PRODUCT_DATA ON IN_PLAN_FROM_FILE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO where (IN_PLAN.IPA_PO_NO LIKE '".$_POST['pono']."')";
}
$query=$query." order by dbo.IN_PLAN.IPA_ETA_DATE DESC";

//  $_SESSION['record']= $query.'<br>';

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
//collect results
$t1=$row["IPA_ETA_DATE"];
$t2=$row["IPA_PO_NO"];
$t3=$row["IPA_INVOICE_NO"];
$t4=$row["CTD_CUST_NAME"];
$t5=$row["PDD_PROD_NO"];
$t6=get_prod_name($row["PDD_PROD_NO"]);
$t7=$row["PDD_STYLE"];
$t8=$row["IAP_UNIT"];
$t9=$row["PDD_PACKAGE"];
$t10=$row["IAP_ORDER_QTY"];
$t11=$row["IAP_QTY"];
$t12=$row["IPA_IN_DATE"];
$t13=$row["IPA_TYPE"];
$t14=$row["IPA_PO_NO"];
$t15=$row["IAP_STATE"];
$t16=$row["IAP_LOT_NO"];
$t17=$row["IPA_ETA_DATE"];
//format and display results
    echo ("<tr>");
	$ts=str_split($t1, 4);
	$ss=str_split($ts[1],2);
	$ts1=$ts[0]."-".$ss[0]."-".$ss[1];
    echo ("<td>$ts1</td>");
	echo '<td><a target="_self" href="index.php?url=list_po&po_no='.$t2.'&t13=B'.'&pid='.$t5.'">'.$t2.'</a></td>';
//    echo ("<td>$t2</td>");
    echo ("<td>$t3</td>");
	echo ("<td>$t4</td>");
	echo ("<td>$t5</td>");
	echo ("<td>$t6</td>");
	echo ("<td>$t7</td>");
	echo ("<td>$t8</td>");
	echo ("<td>$t9</td>");
	echo ("<td>$t10</td>");
	echo ("<td>$t11</td>");
	echo ("<td>$t12</td>");
	if ($t13=="A"){
	$t13="原物料";}
	if ($t13=="B"){
	$t13="OEM";}
	echo ("<td>$t13</td>");
	echo ("<td>$t14</td>");
	if ($t15=="0"){
		$t15="在途";}
	if ($t15=="1"){
		$t15="已入廠";}	
	if ($t15=="P"){
		$t15="收到文件";}	
	echo ("<td>$t15</td>");
	echo ("<td>$t16</td>");
	echo ("<td>$t17</td>");
    echo ("</tr>");
    }

		echo (" </table>");
}
//echo dod($_POST['datepicker1']);
//echo $query;
//echo $_POST['pono'];

if (isset($_POST["excel2"])) 
{
	
}
?>
