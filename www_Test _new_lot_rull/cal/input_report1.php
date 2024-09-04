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
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>
    <input type="hidden" name="operator" id="operator" value="" />
  </p>
  <table width="1200" border="1"><tr><td width="400">
  <?php 
  echo '客戶：'.$cust_name."&nbsp;&nbsp;&nbsp;&nbsp;".$cust_no."</br>";
  echo "表單：".$table."</br>"; ?>
  <?php echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'];?>
  </td>
  <td><?php $itemunit=showcust_spec($cust_no,$_GET['pdd_prod_no'],$_GET['ani_groupname']) ; ?>
  <p>請務必填寫正確的樣品瓶號碼</p></td></tr></table>
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
      <td width="200" bgcolor="#CCCCCC">測試人員：
      <input name="Tester" type="text" id="Tester" value="<?php echo $_SESSION['uname']?>" size="10" readonly /></td>
      <td width="200" bgcolor="#CCCCCC"> Operator:
      <input name="Operator" type="text" id="Operator" value="<?php echo $_GET['operator'];?>" size="10" readonly />
    </td>
    </tr>
    </table>
    
    
<table width="1200" border="1">
    <tr>
    <td width="100" bgcolor="#CCCCCC">項目</td>
    <td width="50" bgcolor="#CCCCCC">輸入值</td>
    <td width="100" bgcolor="#CCCCCC">客規</td>
    <td width="50" bgcolor="#CCCCCC">DL</td>
    <td width="50" bgcolor="#CCCCCC">USL</td>
    <td width="100" bgcolor="#CCCCCC">再分析</td>
    <td width="100" bgcolor="#CCCCCC">項目</td>
    <td width="50" bgcolor="#CCCCCC">輸入值</td>
    <td width="100" bgcolor="#CCCCCC">客規</td>
    <td width="50" bgcolor="#CCCCCC">DL</td>
    <td width="50" bgcolor="#CCCCCC">USL</td>
    <td width="100" bgcolor="#CCCCCC">再分析</td>
    </tr>
    
    <tr class="centet">  
<?php
include("../connections/conn.php");
$query="SELECT DISTINCT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$i=0;
while($row = mssql_fetch_array($result)){
if (($row['COLUMN_NAME']!='LotNo') && ($row['COLUMN_NAME']!='CHK5') && ($row['COLUMN_NAME']!='CHK6') && ($row['COLUMN_NAME']!='CHK7') && ($row['COLUMN_NAME']!='CHK8') && ($row['COLUMN_NAME']!='CHK1') && ($row['COLUMN_NAME']!='CHK2') && ($row['COLUMN_NAME']!='CHK3') && ($row['COLUMN_NAME']!='CHK4') && ($row['COLUMN_NAME']!='Ok') && ($row['COLUMN_NAME']!='AnalyzeTime') && ($row['COLUMN_NAME']!='AnalyzeTime') && ($row['COLUMN_NAME']!='AnaManager') && ($row['COLUMN_NAME']!='Tester') && ($row['COLUMN_NAME']!='Operator') && ($row['COLUMN_NAME']!='SampleNo') && ($row['COLUMN_NAME']!='SerialNo') && ($row['COLUMN_NAME']!='TestDate')){
	$i=$i+1;
?>
      <td><?php echo '<font color="#0000FF">'.$row['COLUMN_NAME'].'</font>';?> ：</td><td><input name="<?php echo $row['COLUMN_NAME'];?>" type="text" id="<?php echo $row['COLUMN_NAME'];?>" value=
	  <?php 
	  if(($_GET['ani_groupname']=='M13') or ($_GET['ani_groupname']=='M21')){echo "0.000001";}
	  else {echo "0";}
	  list ($spec,$dl,$usl,$again)=get_test_spec($row['COLUMN_NAME'],$_GET['pdd_prod_no'],$cust_no,$_GET['ani_groupname']); 
	  ?>
       size="6"/></td><td><?php echo $spec;?></td><td><?php echo $dl;?></td><td><?php echo $usl;?></td><td><?php echo $again;?></td>
<?php
if (fmod($i,2)==0){echo '</tr><tr class="centet">' ;}
else {echo '</td>';}}
?>
<?php } ?>
</table>

<table width="1200" border="1">
    <tr class="centet" >

      <td>單位：<?php echo $itemunit?></td>
      <td>合否判定
      <input type="checkbox" name="Ok" id="Ok" />
      <input type="hidden" name="CHK1" id="CHK1" value="1" />
      <input type="hidden" name="CHK2" id="CHK2" value="1" />
      <input type="hidden" name="CHK3" id="CHK3" value="1" />
      <input type="hidden" name="CHK4" id="CHK4" value="1" />
      <input type="hidden" name="CHK5" id="CHK5" value="1" />
      <input type="hidden" name="CHK6" id="CHK6" value="1" />
      <input type="hidden" name="CHK7" id="CHK7" value="1" />
      <input type="hidden" name="CHK8" id="CHK8" value="1" />
      <input type="hidden" name="AnaManager" id="AnaManager" value="none" />
      <input type="hidden" name="TestDate" id="TestDate" value="<?php echo ddd($dt)." 00:00:00";?>" />
      <input type="hidden" name="AnalyzeTime" id="AnalyzeTime" value="<?php echo date("YmdHis") ?>" />

      </td>
      <td>
      <input type="submit" name="submit" id="submit" value=" 新  增 " />
      <input type="hidden" name="MM_insert" value="form1">

      <input type="button" name="button" id="button" value=" 離開  " onClick="window.open('<?php echo $_SESSION['lasturl'];?>', '_self');" />
      <input type="button" name="button2" id="button2" value=" 重  整 " onClick="window.open('<?php echo $rev;?>','_self');" />
      <?php if ($_GET['ani_groupname']=="TM") {
		  echo '<input type="button" name="TM" id="TM" value="取得分析資料" onClick="window.open('."'/cal/ctm.php?pdd_chemical=".$_GET['pdd_chemical']."&ani_groupname=".$_GET['ani_groupname']."&lot_no=".$_GET['lot_no']."&cust_no=".$cust_no."&pid=".$_GET['pdd_prod_no']."', '_self');".'"'.' />';
	  }?>
      </td>
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


<?php
session_start();
include ("../connections/conn.php");
$query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result = mssql_query($query);
echo '<table border="1" width="1200"><tr>';
echo '<td width="30">列印</td>';
while($row = mssql_fetch_array($result)){
	$i++;
	if (($row['COLUMN_NAME']=='TestDate') || ($row['COLUMN_NAME']=='CHK1') || ($row['COLUMN_NAME']=='CHK2') || ($row['COLUMN_NAME']=='CHK3') || ($row['COLUMN_NAME']=='CHK4') || ($row['COLUMN_NAME']=='CHK5') || ($row['COLUMN_NAME']=='CHK6') || ($row['COLUMN_NAME']=='CHK7') || ($row['COLUMN_NAME']=='CHK8')){;}
	else{
		$str=$str."[".$row['COLUMN_NAME']."], ";
		echo '<td>'.$row['COLUMN_NAME'].'</td>';
		}
}
echo '</tr>';
echo '<tr>';

$query="SELECT          ".substr($str,0,-2)."
FROM              ".$table." 
WHERE          (LotNo  = '".$_GET['lot_no']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$field= mssql_num_fields($result);
while ($row=mssql_fetch_array($result)){
echo '<td width="30"><a href="print_ani_result.php?lot_no='.$_GET['lot_no'].'&ani_groupname='.$_GET['ani_groupname'].'&pdd_chemical='.$_GET['pdd_chemical'].'" target="new">列印</a></td>';

for ($i=0;$i<$field;$i++){	
echo '<td width="20">';
echo $row[$i];
echo '</td>';
}
echo "</tr>";
}
?>
<?php echo '</table>';?>

</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{   
	//////////檢查樣品瓶號///////////
	check_samples($_GET['lot_no'],$_POST['SampleNo']);
	if ($_POST['Ok']=="on"){$_POST['Ok']=1;}
	else {$_POST['Ok']=0;}
	//////////取得欄位名///////////
include ("../connections/conn.php");
$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$q1="INSERT INTO ".$table." (";
$q2=" VALUES     (";
while($row = mssql_fetch_array($result)){
	
	if($row['COLUMN_NAME']=='AnalyzeTime'){
		$q1.="[".$row['COLUMN_NAME']."]";
        $q2.="'".$_POST[$row['COLUMN_NAME']];}
	elseif($row['COLUMN_NAME']=='TestDate'){
			$q1.="[".$row['COLUMN_NAME']."], ";
            $q2.="'".$_POST[$row['COLUMN_NAME']]."', "
			;}
	elseif(($row['DATA_TYPE']=='char') and ($row['CHARACTER_MAXIMUM_LENGTH']==1)) {
			$q1.="[".$row['COLUMN_NAME']."], ";
            $q2.="'".$_POST[$row['COLUMN_NAME']]."', ";
			}
	elseif($row['DATA_TYPE']=='int') {
			$q1.="[".$row['COLUMN_NAME']."], ";
            $q2.=$_POST[$row['COLUMN_NAME']].", ";
			}
	elseif($row['DATA_TYPE']=='float') {
			if ($_POST[$row['COLUMN_NAME']]==''){$_POST[$row['COLUMN_NAME']] = 'NULL';}
			$q1.="[".$row['COLUMN_NAME']."], ";
            $q2.=$_POST[$row['COLUMN_NAME']].", ";
			}

	else{
			$q1.="[".$row['COLUMN_NAME']."], ";
    		$q2.="'".$_POST[$row['COLUMN_NAME']]."', ";
	}
}
$qx=$q1.") 
".$q2."') ";
$query=$qx;
echo $query;
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {

//header("Location:cal_prod.php?lot_no=".$_POST['lot_no']);  

}
////////// Total Metal /////////
////////// Total Metal_check if exist TM /////////
if($_GET['ani_groupname']=='M13' or $_GET['ani_groupname']=='M21' or $_GET['ani_groupname']=='TT'){
	$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM,AnalyzeItem.ANI_NICKNAME, AnalyzeItem.ANI_FULLNAME
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$table=$row['ELF_FORM'];

}
updateTM($table,$_GET['lot_no'],$cust_no,$_GET['ani_groupname'],$_GET['pdd_prod_no'],$_POST['SampleNo'],$_GET['pdd_chemical']);
$message = "新增了一筆資料";
echo "<script type='text/javascript'>alert('$message');</script>";
 echo '<script>document.location.href="'.$_SESSION['lasturl'].'";</script>';
}   //////// END _ TM /////////
}

if (isset($_POST["upload"])) 
{   
	$path="../reports/".$_GET['ani_groupname']."/";
	$filename=$_GET['lot_no']."_".date("YmdHis").$_FILES["file"]["name"];
	$fullpath=$_SERVER['HTTP_HOST']."/reports/".$_GET['ani_groupname']."/".$filename;
	if (file_exists($path)){
	
		} 
	else {
		mkdir($path);
		}
	move_uploaded_file($_FILES["file"]["tmp_name"],$path.$filename);
	$query="INSERT INTO dbo.FILE_REPORTS
           	(FILE_PATH, FILE_UPLOAD_TIME, ANI_GROUP, FILE_PID, FILE_UID, FILE_LOT_NO, FILE_CID, FILE_DISC)
			VALUES          ('".$fullpath."', '".date("YmdHis")."', '".$_GET['ani_groupname']."', '".$_GET['pdd_prod_no']."', '".$_SESSION['uid']."',
			 '".$_GET['lot_no']."', '".$cust_no."', '".$_POST['disc']."')";
	$result = mssql_query($query);

//	$fp=fopen("filetmp/".$_FILES["file"]["name"],"r");
}
?>
