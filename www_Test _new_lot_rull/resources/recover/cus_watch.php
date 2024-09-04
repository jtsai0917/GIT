<?php
session_start();
$editFormAction = $_SERVER['PHP_SELF'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
}
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
<form id="form1" name="form1" method="post" action="">
  <p>查詢客戶</p>
  <p>客戶編號: 
    <label for="ctd_cust_no"></label>
    <input type="text" name="cust_no" id="cust_no" />
   客戶名稱 : 
   <label for="ctd_cust_name"></label>
  <input type="text" name="cust_name" id="cust_name" />
   <input type="submit" name="submin" id="submin" value="送出" />
  </p>
   <input type="hidden" name="MM_insert" value="form1">

</form>
<table width="350" border="1">
  <tr>
    <td width="99" class="center">客戶編號</td>
    <td width="185" class="center">客戶名稱</td>
    <td width="49" class="center">選擇</td>    
  </tr>
  <?php
$editFormAction = $_SERVER['PHP_SELF'];

	include_once("../connections/conn.php");
	$query="SELECT distinct CTD_CUST_NO,CTD_CUST_NAME
	FROM CUSTOMER_DATA  
		WHERE (CTD_CUST_NO<>'')";
	if($_GET['ctd_cust_no']<>""){
	$query=$query." AND ([CTD_CUST_NO] LIKE '%".$_GET['ctd_cust_no']."%')";}
	if($_POST['cust_no']<>""){
	$query=$query." AND ([CTD_CUST_NO] LIKE '%".$_POST['cust_no']."%')";}
	if($_POST['cust_name']<>""){
	$query=$query." AND ([CTD_CUST_NAME] LIKE '%".$_POST['cust_name']."%')";}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
	echo "<tr>";
    echo "<td>".$row['CTD_CUST_NO']."</td>";
	echo "<td>".$row['CTD_CUST_NAME']."</td>";
	echo '<td><a href="setsession_cus.php?user='.$row['create_user'].'&name='.$row['EMP_NAME'].'">選取</a></td>';
	echo "</tr>";
}
mssql_close($dbhandle);
?>
</table>
<p>&nbsp;</p>
</body>
</html>
