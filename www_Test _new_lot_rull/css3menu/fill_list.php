<?php 
session_start();
include("../connections/conn.php"); 
include("../checkuser.php");
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="/js/jquery-ui.css">
  <script src="/js/jquery-1.10.2.js"></script>
  <script src="/js/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script type="text/javascript">
    $(function() {
    $( "#datepicker1" ).datepicker();
	$( "#datepicker2" ).datepicker();
	$( "#datepicker3" ).datepicker();
	$( "#datepicker4" ).datepicker();
  });

var d;
function sendIt() {
 if (d) document.body.removeChild(d);
 var info = document.getElementById("pdd_chemical").value;
 d = document.createElement("script");
 d.src = "pdd_prod_no.php?info="+info;
 d.type = "text/javascript";
 document.body.appendChild(d);
}
</script>
</head>

<form name="form1" method="post" action="">

  <table width="1240" border="1">
  <tr>充填作業追蹤一覽表  = 查詢 =
  </tr>
    <tr>
      <td width="250" bgcolor="#FFFFFF" class="d1">出荷預定日:
        <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}else{
			$d=strtotime("-1 Days"); echo date("m/d/Y",$d);}?>">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}else{
			$d=strtotime("+3 Days"); echo date("m/d/Y",$d);}?>"></td>
      <td width="250" class="d1">序號：
        <label for="sn"></label>
      <input type="text" name="sn" id="sn">
       Lot NO：
        <label for="sn3"></label>
      <input type="text" name="sn3" id="sn3"></td>

      <td width="100" class="centerutton">        <span class="d1">
        <input type="button" name="excel2" id="excel2" value="EXCLE">      
      </span></td>
    </tr>
    <tr>
      <td width="250" class="d1"><span class="d1">藥品名:
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="16" value="<?php echo $_SESSION['prod_no']?>" readonly>
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
<input name="pdd_chemical2" type="text" id="pdd_chemical2" size="20" value="<?php echo $_SESSION['prod_name']?>" readonly>
      </span></td>
      <td width="250" class="d1">客戶：
        <input type="button" name="X2" id="X2" value="X" onClick="window.open('../erase_customer.php ', '_self');">
        <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="16" value="<?php echo $_SESSION['cust_no']?>" readonly>
        <input type="button" name="pdd_no2" id="pdd_no2" value="查詢客戶" onClick="window.open('../cust_no.php?sup=N ', '_self');">
      <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="20" value="<?php echo $_SESSION['cust_name']?>"></td>

      <td width="100" class="centerutton">        <span class="d1">
        <input type="button" name="excel3" id="excel3" value=" 列   印 ">      
      </span></td>
    </tr>
    <tr>
<?php
include("../lib/prod_style.php");
?>
      <td width="250" class="d1">&nbsp;</td>
 
      <td width="100" class="centerutton"><span class="d1">
        <input name="submit" type="submit" class="centerutton" id="submit" value="         查      詢         ">
      </span></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="form1">
</form>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if ((isset($_POST["submit"])) && (isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
//當確定時	
//valu test
$titles='<table width="1280" border="1">
    <td width="14">預計出荷日：</td>
    <td width="20">序號</td>
    <td width="14">LOT NO</td>
    <td width="60">客戶：</td>
    <td width="40">品名：</td>
    <td width="20">荷姿：</td>
    <td width="20">數量(KG)</td>
    <td width="20">Cheklist</td>
    <td width="20">指示報告書</td>
    <td width="20">捆包檢查表</td>
    <td width="40">目前狀況</td>';
	echo $titles;
//mysql_select_db($database_connection, $connection);
$query = "SELECT          FILLPLAN_OUT_DECIDE.FDM_OUT_DATE, FILLPLAN_OUT_DECIDE.FDM_SERIAL_NO, 
                            FILLPLAN_OUT_DECIDE.FDM_LOT_NO, CUSTOMER_DATA.CTD_CUST_NAME, PRODUCT_DATA.PDD_PROD_NAME, 
                            PRODUCT_DATA.PDD_STYLE, FILLPLAN_OUT_DECIDE.FDM_QTY, FILL_INDICATE.FID_FILL_END_DATE, FILL_INDICATE.FID_FILL_BEGIN_DATE, FILLPLAN_OUT_DECIDE.FDM_EXPECT_DATE
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            FILL_INDICATE ON FILLPLAN_OUT_DECIDE.FDM_LOT_NO = FILL_INDICATE.FDM_LOT_NO INNER JOIN
                            CUSTOMER_DATA ON FILLPLAN_OUT_DECIDE.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
                            PRODUCT_DATA ON FILLPLAN_OUT_DECIDE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO";
$query.= " WHERE (FILLPLAN_OUT_DECIDE.FDM_SERIAL_NO <>'')";

if ($_POST['datepicker1']<>""){

    $query=$query." and (FILLPLAN_OUT_DECIDE.FDM_OUT_DATE >'".dod($_POST['datepicker1'])."000000')";
}
if ($_POST['datepicker2']<>""){
$query=$query." and (FILLPLAN_OUT_DECIDE.FDM_OUT_DATE <'".dod($_POST['datepicker2'])."000000')";
}
//if ($_POST['datepicker3']<>""){
//$query=$query." and (IN_PLAN.IPA_IN_DATE >'".dod($_POST['datepicker3'])."')";
//}
//if ($_POST['datepicker4']<>""){
//$query=$query." and (IN_PLAN.IPA_IN_DATE <'".dod($_POST['datepicker4'])."')";
//}
if ($_POST['sn']<>""){
$query=$query." and (FILLPLAN_OUT_DECIDE.FDM_SERIAL_NO ='".$_POST['sn']."')";
}
if ($_POST['pdd_chemical1']<>""){
$query=$query." and (PRODUCT_DATA.PDD_PROD_NO = '".$_POST['pdd_chemical1']."')";
}
if ($_POST['pdd_chemical3']<>""){
$query=$query." and (CUSTOMER_DATA.CTD_CUST_NO = '".$_POST['pdd_chemical3']."')";
}
if ($_POST['t1']<>""){
$query=$query." and (PRODUCT_DATA.PDD_STYLE LIKE '".$_POST['t1']."%')";
}
if ($_POST['sn3']<>""){
$query=$query." and (FILLPLAN_OUT_DECIDE.FDM_LOT_NO ='".$_POST['sn3']."')";
}
$query.=" ORDER BY   FILLPLAN_OUT_DECIDE.FDM_OUT_DATE";

$result = mssql_query($query);

$numRows = mssql_num_rows($result);

while($row = mssql_fetch_array($result))
{

//collect results
$t1=$row["FDM_OUT_DATE"];
$t2=$row["FDM_SERIAL_NO"];
$t3=$row["FDM_LOT_NO"];
$t4=$row["CTD_CUST_NAME"];
$t5=$row["PDD_PROD_NAME"];
$t6=$row["PDD_STYLE"];
$t7=$row["FDM_QTY"];
$t8=$row["FDM_LOT_NO"];
$t9=$row["FDM_LOT_NO"];
if ($row["FID_FILL_END_DATE"]){
$t10=$row["FID_FILL_END_DATE"]." 充填完成";
}
elseif ($row["FID_FILL_BEGIN_DATE"]){
	$t10=$row["FID_FILL_BEGIN_DATE"]." 開始充填";
	}
elseif ($row["FDM_EXPECT_DATE"]){
		$t10=$row["FDM_EXPECT_DATE"]." 預計充填";
}

//format and display results
    echo ("<tr>");
	$ts=str_split($t1, 4);
	$ss=str_split($ts[1],2);
	$ts1=$ts[0]."-".$ss[0]."-".$ss[1];
    echo ("<td>$ts1</td>");
//	echo '<td><a target="_blank" href="list_po.php?po_no='.iconv("big5","UTF-8",$t2).'">'.$t2.'</a></td>';
    echo ("<td>$t2</td>");
    echo ("<td>$t3</td>");
	echo ("<td>$t4</td>");
	echo ("<td>$t5</td>");
	echo ("<td>$t6</td>");
	echo ("<td>$t7</td>");
	echo ("<td></td>");
	echo ("<td>$t8</td>");
	echo ("<td>$t9</td>");
	echo ("<td>$t10</td>");
    echo ("</tr>");
    }

		echo (" </table>");
}
//echo dod($_POST['datepicker2']);
//$n1=dod($_POST['datepicker1']);
function dod($ds){
$dat=substr($ds,6).substr($ds,0,-8).substr($ds,3,-5);
return $dat;
}
mssql_close($dbhandle);
echo "End List .... ";


if(isset($_POST['submit'])){
	$_SESSION['datepicker1']=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$_POST['datepicker2'];
	refresh();
}
?>
