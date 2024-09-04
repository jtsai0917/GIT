<?php 
session_start();
include ("../lib/fun.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>prod info</title>
<style type="text/css">
.center {
	text-align: center;
}
</style></head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>查詢</p>
  <p>廠商編號 : 
    <input type="text" name="cid" id="cid" />
   廠商名稱 : 
   <input type="text" name="cname" id="cname" />
   <input type="submit" name="submit" id="submit" value="查詢" />
   <input type="submit" name="submit2" id="submit2" value=" 選擇完成 " />
  </p>
<table width="350" border="1">
  <tr>
    <td width="99" class="center">廠商編號</td>
    <td width="185" class="center">廠商名稱</td>
    <td width="120" class="center">選擇</td>    
  </tr>
  <?php
$editFormAction = $_SERVER['PHP_SELF'];

if(isset($_POST['submit2'])){
	$fill = $_POST["fill"];
	$myallcust = implode(";",$fill);	
	$_SESSION['myallcust']=$myallcust;
	$_SESSION['CTD_CUST_NO']=$myallcust;
	echo $myallcust;
	$url="index.php?url=edit_ani&id=".$_GET['ani_rull_id'];
	echo '<script>document.location.href="'.$url.'";</script>';
}

if(isset($_POST['none'])){
	jumpto($_SESSION['lasturl1']);
}
include_once("connections/conn.php");
if(isset($_POST['submit'])){$_SESSION['cid']=$_POST['cid'];$_SESSION['cname']=$_POST['cname'];}
	$query='SELECT [CTD_CUST_NO],[CTD_CUST_NAME] FROM [dbo].[CUSTOMER_DATA]';
	if ($_GET['sup']<>''){ $query=$query." WHERE ([CTD_SUPPLIER]='".$_GET['sup']."')";}
	if($_POST['cid']<>""){
	$query=$query." AND ([CTD_CUST_NO] LIKE '%".$_POST['cid']."%')";}
	if($_POST['cname']<>""){
	$query=$query." AND ([CTD_CUST_NAME] LIKE '%".$_POST['cname']."%')";}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
	echo "<tr>";
    echo "<td>".$row['CTD_CUST_NO']."</td>";
	echo "<td>".$row['CTD_CUST_NAME']."</td>";
	if (false !== ($rst = strpos($_SESSION['myallcust'],$row['CTD_CUST_NO']))){$ck='checked="checked"';}else{$ck='';}
	echo '<td align="center"><input type="checkbox" name="fill[]" id="fill" value="'.$row['CTD_CUST_NO'].'" '.$ck.'></td>';
	echo "</tr>";
}


?>
</table>
</form>
</body>
</html>
