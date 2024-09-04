<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../connections/conn.php"); 
include("../checkuser.php");
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
datepick();
?>
<form name="form1" method="post" action="">
  <table width="1240" border="1">
  <tr>充填作業追蹤一覽表  = 查詢 =
  </tr>
    <tr>
      <td width="250" bgcolor="#FFFFFF" class="d1">出荷預定日:
        <input name="datepicker1" type="text" id="datepicker1" onChange="set_date_session(this.name,this.value)" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}else{
			$d=strtotime("-1 Days"); echo date("m/d/Y",$d);}?>">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" onChange="set_date_session(this.name,this.value)" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}else{
			$d=strtotime("+3 Days"); echo date("m/d/Y",$d);}?>"></td>
      <td width="250" class="d1">序號：
        <label for="sn"></label>
      <input type="text" name="sn" id="sn" onChange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['sn'];?>">
       Lot NO：
        <label for="sn3"></label>
      <input type="text" name="sn3" id="sn3" onChange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['sn3'];?>"></td>

      <td width="100" class="centerutton">        <span class="d1">
        <input type="hidden" name="excel2" id="excel2" value="EXCLE">      
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
        <input type="hidden" name="excel3" id="excel3" value=" 列   印 ">      
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
  <BR />
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
//  (FDM.FOD_O_YEAR_MONTH + FDM.FOD_O_DAY) >= '20170202' AND (FDM.FOD_O_YEAR_MONTH + FDM.FOD_O_DAY) <= '20170202' AND FDM.FDM_SERIAL_NO = '12' AND FDM.FDM_LOT_NO = 'TO17A28L610' AND FDM.PDD_PROD_NO = 'P001-000' AND FDM.CTD_CUST_NO = 'C20705' AND PDD.PDD_STYLE = 'LY' Order By FDM.FOD_O_YEAR_MONTH Desc , FDM.FOD_O_DAY Desc , PDD.PDD_PROD_NO  

$query = "select FDM.FOD_O_YEAR_MONTH + FDM.FOD_O_DAY  as [預計出荷日期], FDM.FDM_SERIAL_NO as [序號], FDM.FDM_LOT_NO as [Lot No], CTD.CTD_CUST_SHORT_NAME as [客戶], PDD.PDD_PROD_NAME as [品名],  PDD.PDD_STYLE as [荷姿], FDM.FDM_QTY as [數量(KG)], '' as [Check List 查詢],  FDM.FDM_LOT_NO as [指示報表書查詢],  FDM.FDM_LOT_NO as [捆包檢查查詢], ''  as [目前狀況], FDM.FDM_EXPECT_DATE as [預計充填時間],  FID.FID_FILL_BEGIN_DATE   as [開始充填時間], FID.FID_FILL_END_DATE   as [結束充填時間], FDM.PDD_PROD_NO as PDD_PROD_NO from FILLPLAN_OUT_DECIDE   FDM  left outer join CUSTOMER_DATA as CTD on FDM.CTD_CUST_NO = CTD.CTD_CUST_NO left outer join PRODUCT_DATA as PDD on FDM.PDD_PROD_NO = PDD.PDD_PROD_NO  left outer join FILL_INDICATE as FID on FDM.FDM_LOT_NO = FID.FDM_LOT_NO where";

if ($_POST['datepicker1']<>""){

    $query=$query." (FDM.FOD_O_YEAR_MONTH + FDM.FOD_O_DAY >= '".dod($_POST['datepicker1'])."')";
}
if ($_POST['datepicker2']<>""){
$query=$query." AND (FDM.FOD_O_YEAR_MONTH + FDM.FOD_O_DAY <= '".dod($_POST['datepicker2'])."')";
}
//if ($_POST['datepicker3']<>""){
//$query=$query." and (IN_PLAN.IPA_IN_DATE >'".dod($_POST['datepicker3'])."')";
//}
//if ($_POST['datepicker4']<>""){
//$query=$query." and (IN_PLAN.IPA_IN_DATE <'".dod($_POST['datepicker4'])."')";
//}
if ($_POST['sn']<>""){
$query=$query." and (FDM.FDM_SERIAL_NO ='".$_POST['sn']."')";
}
if ($_POST['pdd_chemical1']<>""){
$query=$query." AND (FDM.PDD_PROD_NO ='".$_POST['pdd_chemical1']."')";
}
if ($_POST['pdd_chemical3']<>""){
$query=$query." and (FDM.CTD_CUST_NO =  '".$_POST['pdd_chemical3']."')";
}
if ($_POST['t1']<>""){
$query=$query." and (PDD.PDD_STYLE like '".$_POST['t1']."%')";
}
if ($_POST['sn3']<>""){
$query=$query." and (FDM.FDM_LOT_NO ='".$_POST['sn3']."')";
}
$query.=" Order By FDM.FOD_O_YEAR_MONTH Desc , FDM.FOD_O_DAY Desc , PDD.PDD_PROD_NO  ";
$_SESSION['tmppsps']= $query;
$result = mssql_query($query);

$numRows = mssql_num_rows($result);

while($row = mssql_fetch_array($result))
{

//collect results
echo '<tr>';
echo '<td>'.$row["預計出荷日期"].'</td>';
echo '<td>'.$row["序號"].'</td>';
echo '<td>'.$row["Lot No"].'</td>';
echo '<td>'.$row["客戶"].'</td>';
echo '<td>'.$row["品名"].'</td>';
echo '<td>'.$row["荷姿"].'</td>';
echo '<td>'.$row["數量(KG)"].'</td>';
echo '<td>'.$row["Check List 查詢"].'</td>';
echo '<td>'.$row["指示報表書查詢"].'</td>';
echo '<td>'.$row["捆包檢查查詢"].'</td>';
echo '<td></td>';
echo '</tr>';

    }

		echo (" </table>");
}
//echo dod($_POST['datepicker2']);
//$n1=dod($_POST['datepicker1']);
mssql_close($dbhandle);
echo "End List .... ";


if(isset($_POST['submit'])){
	$_SESSION['datepicker1']=$_POST['datepicker1'];
	$_SESSION['datepicker2']=$_POST['datepicker2'];
}
?>
