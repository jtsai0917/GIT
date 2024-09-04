<?php
$editFormAction = $_SERVER['PHP_SELF'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
	
	
}?>
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
<form id="form1" name="form1" method="post" action="">
  <p>查詢</p>
  <p>廠商編號 : 
    <label for="CTD_CUST_NO"></label>
    <input type="text" name="CTD_CUST_NO" id="CTD_CUST_NO" />
   廠商名稱 : 
   <label for="CTD_CUST_NAME"></label>
   <input type="text" name="CTD_CUST_NAME" id="CTD_CUST_NAME" />
   <input type="submit" name="submin" id="submin" value="送出" />
  </p>
   <input type="hidden" name="MM_insert" value="form1">

</form>
<table width="507" border="1">
  <tr>
    <td width="97" class="center">廠商編號</td>
    <td width="182" class="center">廠商名稱</td>
    <td width="206" class="center">選擇</td>    
  </tr>
  <?php
$editFormAction = $_SERVER['PHP_SELF'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
	include_once("connections/conn.php");
	$query='SELECT [CTD_CUST_NO],[CTD_CUST_NAME] FROM [dbo].[CUSTOMER_DATA] WHERE ([CTD_CUST_NO]<>"")';
	if($_POST['CTD_CUST_NO']<>""){
	$query=$query." AND ([CTD_CUST_NO] LIKE '%".$_POST['CTD_CUST_NO']."%')";}
	if($_POST['CTD_CUST_NAME']<>""){
	$query=$query." AND ([CTD_CUST_NAME] LIKE '%".$_POST['CTD_CUST_NAME']."%')";}
	$query.=" AND [CTD_SUPPLIER] ='N'";
$result = mssql_query($query);

$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
	echo "<tr>";
    echo "<td>".$row['CTD_CUST_NO']."</td>";
	echo "<td>".$row['CTD_CUST_NAME']."</td>";
	echo '<td><a href="setsession_cust.php?pro_no='.$row['CTD_CUST_NO'].'&pro_name='.$row['CTD_CUST_NAME'].'">選取</a></td>';
	echo "</tr>";
}
mssql_close($dbhandle);
}?>

</table>
<p>&nbsp;</p>
</body>
</html>
