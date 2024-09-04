<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
$_SESSION['lot_no']=$_GET['lot_no'];
/////////////////////////取得分析項目表單////////////////////////////
include("../connections/conn.php");
include("../lib/fun.php");
$_SESSION['retir']=$rev=$_SERVER['REQUEST_URI'];	
$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM,AnalyzeItem.ANI_NICKNAME, AnalyzeItem.ANI_FULLNAME
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";
//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$table=$row['ELF_FORM'];
$ani_nick=$row['ANI_NICKNAME'];
$ani_full=$row['ANI_FULLNAME'];
}

include("../connections/conn.php");
$query="SELECT          FILLPLAN_OUT_DECIDE.FDM_LOT_NO, FILLPLAN_OUT_DECIDE.CTD_CUST_NO, 
                            CUSTOMER_DATA.CTD_CUST_NAME
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            CUSTOMER_DATA ON FILLPLAN_OUT_DECIDE.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE          (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".$_GET['lot_no']."')";

$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$cust_no=$row['CTD_CUST_NO'];
	$cust_name=$row['CTD_CUST_NAME'];
}

$query="SELECT  AnalyzeDesign.AND_APPLY_DATE
FROM             AnalyzeDesign
WHERE          (AnalyzeDesign.AND_LOT_NO = '".$_GET['lot_no']."')";
//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);

//echo $query;
while ($row=mssql_fetch_array($result)){
	$dt=$row['AND_APPLY_DATE'];
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
.red {
	color: #F00;
}
.blue {
	color: #00F;
}
#form1 table tr td p {
	color: #F00;
	font-size: 16px;
}
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>輸入分析結果</title>
<style type="text/css">
.centet {
	text-align: center;
}
#form1 table tr td {
	text-align: center;
}
dd {
	text-align: left;
}
#form1 table tr td {
	text-align: left;
}
</style>
</head>

<body>
<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p>
    <input type="hidden" name="operator" id="operator" value="" />
  </p>
  <table width="1200" border="1"><tr><td width="500">
    <p>
      <?php 
  echo '客戶：'.$cust_name."&nbsp;&nbsp;&nbsp;&nbsp;".$cust_no."</br>";
  echo "表單：".$table."</br>"; ?>
      <?php echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'];?>
    </p>
    <p>
    </p></td>
  <td><?php $itemunit=showcust_spec($cust_no,$_GET['pdd_prod_no'],$_GET['ani_groupname']) ; ?></td></tr></table>
  <table width="1200" border="1">
    <tr class="centet">
      <td width="200" bgcolor="#CCCCCC">日期: <?php echo ddd($dt)." 00:00:00";?></td>
      <td width="200" bgcolor="#CCCCCC">Lot NO：
      <input name="LotNo" type="text" id="LotNo" value="<?php echo $_GET['lot_no']?>" size="14" readonly /></td>
      <td width="200" bgcolor="#CCCCCC"> 序號：
      <input name="SerialNo" type="text" id="SerialNo" value="<?php 
	  $query="SELECT          ".$table.".*
FROM              ".$table." 
WHERE          (LotNo  = '".$_GET['lot_no']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$field= mssql_num_fields($result);
$sn=$numRows+1;
echo $sn;
	   ?>" size="4" readonly /></td></tr><tr>
      <td width="200" bgcolor="#CCCCCC">取樣瓶號碼：
      <input name="SampleNo" type="text" id="SampleNo" value="" size="10" /></td>
      <td width="200" bgcolor="#CCCCCC">上傳人員：
        <input name="Tester" type="text" id="Tester" value="<?php echo $_SESSION['uname']?>" size="10" readonly /></td>
      <td width="200" bgcolor="#CCCCCC"> Operator:
      <input name="Operator" type="text" id="Operator" value="<?php echo $_GET['operator'];?>" size="10" readonly />
    </tr>
    </table>
    <table border="1" width="1200">
    <tr><td>檢驗報告上傳：
      <label for="file"></label>
      <input type="file" name="file" id="file" />
      檔案說明：
      <label for="disc"></label>
      <input name="disc" type="text" id="disc" size="30" />
      <input type="submit" name="upload" id="upload" value="上傳" /></td>
    </tr>
    </table>
  </form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$path="../reports/".$_GET['ani_groupname']."/";
$filename=$_GET['lot_no']."_".date("YmdHis").$_FILES["file"]["name"];
$fullpath=$_SERVER['HTTP_HOST']."/reports/".$_GET['ani_groupname']."/".$filename;
if (file_exists($path)){
	
		} 
	else {
		mkdir($path);
		}
if (isset($_POST["upload"])) 
{   
	move_uploaded_file($_FILES["file"]["tmp_name"],$path.$filename);
	$query="INSERT INTO dbo.FILE_REPORTS
           	(FILE_PATH, FILE_UPLOAD_TIME, ANI_GROUP, FILE_PID, FILE_UID, FILE_LOT_NO, FILE_CID, FILE_DISC)
			VALUES          ('".$fullpath."', '".date("YmdHis")."', '".$_GET['ani_groupname']."', '".$_GET['pdd_prod_no']."', '".$_SESSION['uid']."',
			 '".$_GET['lot_no']."', '".$cust_no."', '".$_POST['disc']."')";
	$result = mssql_query($query);

//	$fp=fopen("filetmp/".$_FILES["file"]["name"],"r");
}