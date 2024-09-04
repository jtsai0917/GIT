<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
include("../lib/fun.php");
session_start();

$loginFormAction = $_SERVER['PHP_SELF'];
if((isset($_POST["mm_insert"])) && ($_POST["mm_insert"] == "form1")){
	if ($_POST['OK']=="on"){$ok=1;}
	else {$ok=0;}
	include_once("../connections/conn.php");
	$query="INSERT INTO TLOQA9100311
                            (TestDate, CHK1, CHK2, CHK3, CHK4, LotNo, SerialNo, SampleNo, [NH4-UV], Ok, Tester, Operator, AnaManager, 
                            AnalyzeTime)";

	$query.=" VALUES          ('".$_POST['TestDate']."',1,1,1,1,'".$_POST['lot_no']."','".$_POST['serialno']."','".$_POST['sampleno']."',".$_POST['NH4-UV'].",".$ok.",'".$_SESSION['uname']."','".$_POST['operator2']."','','".$_POST['analyzetime']."')";
echo $query;
//echo $_POST['OK'];
//echo $_POST['OK2'];
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {
mssql_close($dbhandle); 
header("Location:cal_prod.php?lot_no=".$_POST['lot_no']);  

}}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />


<style type="text/css">
.centet {
	text-align: center;
	color: #F00;
}
red {
	color: #F00;
}
</style>
</head>

<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>
    <?php
session_start();
include ("../connections/conn.php");
$query="SELECT          TLOQA9100311.*
FROM              TLOQA9100311 INNER JOIN
                            AnalyzeDesign ON TLOQA9100311.LotNo = AnalyzeDesign.AND_LOT_NO
WHERE          (AnalyzeDesign.AND_LOT_NO = '".$_GET['lot_no']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);

?>
  </p>
  <p>請務必填寫樣品瓶號 </p>
  <p>Lot NO：
    <label for="lotno"></label>
    <input type="text" name="lot_no" id="lot_no" value="<?php echo $_GET['lot_no']?>" readonly="readonly">
  測試人員：
  <input type="text" name="tester" id="tester" value="<?php echo $_SESSION['uname']?>" readonly="readonly">
  取樣瓶號碼：
  <input type="text" name="sampleno" id="sampleno" value="">
  序號：
  <input type="text" name="serialno" id="serialno" value="<?php echo ($numRows+1); ?>" readonly="readonly">
  <input type="hidden" name="operator" id="operator" value="">

  
  Operator:
  <input type="text" name="operator2" id="operator2" value="<?php echo iconv("utf-8","big5",$_GET['operator']);?>" readonly="readonly" />

  </p>
  <p>項目：H2O2 UV ； 測試  單位：ppb ； 社內規格 ：?~10 ；再分析值/定量下限：?~10 </p>
  <table width="1200" border="1">
    <tr class="centet">
  <td width="83">UV分析方法</td>
      <td width="308">
        輸入: 
          <input name="NH4-UV" type="text" id="NH4-UV" value="0" size="6" />
        ppb 
        <input type="hidden" name="CHK1" id="CHK1" value="1" >
        <input type="hidden" name="CHK2" id="CHK2" value="1" >
        <input type="hidden" name="CHK3" id="CHK3" value="1" >
        <input type="hidden" name="CHK4" id="CHK4" value="1" ></td>
      <td width="192">合否判定
      <input type="checkbox" name="OK" id="OK" /></td>

      <input type="hidden" name="TestDate" id="TestDate" value="<?php echo $row['TestDate']?>" >
      <input type="hidden" name="TestDate" id="TestDate" value="<?php echo date("Y-m-d") ?>" >
      <input type="hidden" name="analyzetime" id="analyzetime" value="<?php echo date("YmdHms") ?>" >

    </tr>
  </table>
  <?php 

mssql_close($dbhandle);
?>
  <input type="submit" name="submit" id="submit" value="新增" />
  <input type="button" name="button" id="button" value=" 取 消 " onclick="window.open('<?php echo $_SESSION['lasturl'];?>', 'Select');" />
  <script type="text/javascript" language="JavaScript">
document.forms['form1'].elements['qty8'].focus();
</script>  <p>
    <input type="hidden" name="mm_insert" id="mm_insert" value="form1">
</p>
</form>
<p>&nbsp;</p>
<?php
session_start();
include ("../connections/conn.php");
$query="SELECT          TLOQA9100311.*
FROM              TLOQA9100311 INNER JOIN
                            AnalyzeDesign ON TLOQA9100311.LotNo = AnalyzeDesign.AND_LOT_NO
WHERE          (AnalyzeDesign.AND_LOT_NO = '".$_GET['lot_no']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
?>
<td><label for="lotno"></label>
  測試人員：
  <input type="text" name="tester" id="tester" value="<?php echo $row['Tester']?>" readonly="readonly">
  
  Operator:
  <input type="text" name="operator" id="operator" value="<?php echo iconv("utf-8","big5",$_GET['operator']);?>" readonly="readonly"></td>
    <table width="1200" border="1">

    <tr class="centet">
      <td width="120">序號：
      <input name="serialno2" type="text" id="serialno2" value="<?php echo $row['SerialNo']?>" size="4" readonly="readonly"></td>
      <td width="200"> 取樣瓶號碼：
      <input name="sampleno2" type="text" id="sampleno2" value="<?php echo $row['SampleNo']?>" size="10" readonly="readonly"></td>
      <td width="200"> 值 :
        <input name="qty11" type="text" id="qty11" value="<?php echo $row['NH4-UV']?>" size="10"readonly="readonly"></td>
      <td width="200">合否判定
        <input type="checkbox" name="OK2" id="OK2" <?php 
			if ($row['Ok']=="1"){echo 'checked';}
			?> />
        <input type="hidden" name="TestDate2" id="TestDate2" value="<?php echo $row['TestDate']?>" />
        <input type="hidden" name="CHK5" id="CHK5" value="1" />
        <input type="hidden" name="CHK5" id="CHK6" value="1" />
        <input type="hidden" name="CHK5" id="CHK7" value="1" />
      <input type="hidden" name="CHK5" id="CHK8" value="1" /></td>
    </tr>
  </table>
  <?php 
  }
mssql_close($dbhandle);
?>
<input type="hidden" name="mm_insert" id="mm_insert" value="form1">
<p>&nbsp; </p>
  <p>&nbsp; </p>
