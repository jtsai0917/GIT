<?php
session_start();
include("../lib/fun.php");
auth('9-01',$_SESSION['aut']);
lasturl();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>客戶產品基本資料</title>
</head>

<body>
<p>客戶/產品 基本資料</p>
<form id="form1" name="form1" method="post" action="">
  <table width="800" border="1">
    <tr>
      <td>客戶：
          <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
          <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="16" value="<?php echo $_SESSION['cid']?>" readonly="readonly" />
          <input type="button" name="pdd_no2" id="pdd_no2" value="查詢客戶" onclick="window.open('../main.php?url=cust_no ', '_self');" />
          <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="20" value="<?php echo $_SESSION['cname']?>" />
        <input type="submit" name="submit" id="submin" value="送出" />
        <input type="submit" name="submit2" id="submin2" value="新增藥品" />
        
      </td>
    </tr>
  </table>
 <?php
$editFormAction = $_SERVER['PHP_SELF'];
include("../connections/conn.php");
echo '<table width="800" border="1"><tr><td width="100" align="center">產品代號</td><td align="center">產品說明</td><td align="center">編輯</td></tr>';
$query="SELECT         CUSTOMER_PRODUCTS.*
FROM             CUSTOMER_PRODUCTS
WHERE         (CTD_CUST_NO = '".$_SESSION['cid']."')";
lasturl();
$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
	echo '<tr><td>'.$row['PDD_PROD_NO'].'</td><td>'.$row['CTP_DESC'].'</td>';
	echo '<td align="center"><input type="button" name="pdd_no" id="pdd_no" value="編輯" onclick="window.'."open('index.php?url=edit_cust_prod&pid=".$row['PDD_PROD_NO']."&cid=".$_SESSION['cid']." ', '_self');".'" />';
	echo '</td></tr>';
}

if(isset($_POST["submit"]))
{
	$_SESSION['cid']=$_POST['pdd_chemical3'];
}
if(isset($_POST["new"]))
{
echo '
<table width="800" border="1">
  <tr>
    <td width="100">客戶編號</td>
    <td width="100"><input name="cid" type="text" size="8" id="cid"/></td>
    <td width="100">客戶名稱</td>
    <td width="300"><input name="cname" type="text" size="20" id="cname"/></td>
    <td width="100">客戶簡稱</td>
    <td width="100"><input name="cnick" type="text" size="8" id="cnick"/></td>
  </tr>
</table>';
echo '<table width="800" border="1">
  <tr>
    <td width="99">客戶地址</td>
    <td width="499"><input name="caddr" type="text" size="56" id="caddr"/></td>
    <td width="98">聯絡人</td>
    <td width="100"><input name="coname" type="text"  size="10" id="coname"/></td>
  </tr>
  <tr>
    <td width="99">送貨地址</td>
    <td width="499"><input name="daddr" type="text" size="56" id="daddr"/></td>
    <td width="98">聯絡電話</td>
    <td width="100"><input name="cphone" type="text"  size="8" id="cphone"/></td>
  </tr>
</table><table width="800" border="1">
  <tr>
    <td width=98">來回車程時間</td>
    <td width="200"><input name="needtime" type="text" size="8" id="needtime"/></td>
    <td width="100">供應商<input name="sup" type="checkbox" id="sup"  /></td>
    <td width="100">先進先出<input name="fifo" type="checkbox" id="fifo"  /></td>
    <td width="300">
	<input type="submit" name="submit" id="submit" value=" 離開 " />
	<input type="submit" name="save1" id="save1" value=" 儲存 " />
	</td>
  </tr>
</table>
';	
}

if(isset($_POST["save1"]))
{
	$query="INSERT INTO dbo.CUSTOMER_DATA
                            (CTD_CUST_NO, CTD_CUST_NAME, CTD_CUST_SHORT_NAME, CTD_ADDR, CTD_DELIVER_ADDR, 
                            CTD_DELIVER_TIME, CTD_CONTACT_MAN, CTD_CONTACT_TEL, CTD_SUPPLIER, CTD_FIFO)
VALUES          ('".$_POST['cid']."','".$_POST['cname']."','".$_POST['cnick']."','".$_POST['caddr']."','".$_POST['daddr']."','".$_POST['needtime']."',
'".$_POST['coname']."','".$_POST['cphone']."','".vsup($_POST['sup'])."','".vfifo($_POST['fifo'])."')";
echo $query;
$result = mssql_query($query);
if($result){fun_alert("儲存完畢");}
else{fun_alert("儲存失敗");}
}
if(isset($_POST["save"]))
{
	$query="UPDATE          dbo.CUSTOMER_DATA
SET                   CTD_CUST_NO ='".$_POST['pdd_chemical3']."', CTD_CUST_NAME ='".$_POST['cname']."', CTD_CUST_SHORT_NAME ='".$_POST['cnick']."', CTD_ADDR ='".$_POST['caddr']."', CTD_DELIVER_ADDR ='".$_POST['daddr']."', 
                            CTD_DELIVER_TIME ='".$_POST['needtime']."', CTD_CONTACT_MAN ='".$_POST['coname']."', CTD_CONTACT_TEL ='".$_POST['cphone']."', CTD_SUPPLIER ='".vsup($_POST['sup'])."', CTD_FIFO ='".vfifo($_POST['fifo'])."'
WHERE          (CTD_CUST_NO = '".$_POST['pdd_chemical3']."')";
$result = mssql_query($query);
if($result){fun_alert("儲存完畢");}
else{fun_alert("儲存失敗");}
}

if(isset($_POST["delete"]))
{
	$query="DELETE FROM dbo.CUSTOMER_DATA
WHERE          (CTD_CUST_NO = '".$_POST['pdd_chemical3']."')";
$result = mssql_query($query);
if($result){fun_alert("刪除完畢");}
else{fun_alert("刪除失敗");}
}
if(isset($_POST["submit2"]))
{
	$nurl="index.php?url=new_cust_prod&cid=".$_SESSION['cid'];
	jumpto($nurl);
}
?>
</form>
</body>
</html>
<?php
function sup($sup){
if(trim($sup)=="Y"){ return 'checked="checked"';}
}
function fifo($fifo){
if(trim($fifo)=="Y"){ return 'checked="checked"';}
}
function vsup($vsup){
if(trim($vsup)=="on"){ return 'Y';}
}
function vfifo($vfifo){
if(trim($vfifo)=="on"){ return 'Y';}
}
