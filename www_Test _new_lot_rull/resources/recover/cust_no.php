
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
    <label for="pdd_pro_no"></label>
    <input type="text" name="pdd_pro_no" id="pdd_pro_no" />
   廠商名稱 : 
   <label for="pdd_prod_name"></label>
   <input type="text" name="pdd_prod_name" id="pdd_prod_name" />
   <input type="submit" name="submit" id="submit" value="送出" />
  </p>
   <input type="hidden" name="MM_insert" value="form1">

</form>
<table width="350" border="1">
  <tr>
    <td width="99" class="center">廠商編號</td>
    <td width="185" class="center">廠商名稱</td>
    <td width="49" class="center">選擇</td>    
  </tr>
  <?php
$editFormAction = $_SERVER['PHP_SELF'];
	include_once("../connections/conn.php");
	$query="SELECT [CTD_CUST_NO],[CTD_CUST_NAME] FROM [CUSTOMER_DATA]  WHERE (CTD_CUST_NO<>'') ";
	if($_GET['sup']<>''){$query=$query." and ([CTD_SUPPLIER]='".$_GET['sup']."')";}
	if($_POST['pdd_pro_no']<>""){
	$query=$query." AND ([CTD_CUST_NO] LIKE '%".$_POST['pdd_pro_no']."%')";}
	if($_POST['pdd_prod_name']<>""){
	$query=$query." AND ([CTD_CUST_NAME] LIKE '%".$_POST['pdd_prod_name']."%')";}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
	
	echo "<tr>";
    echo "<td>".$row['CTD_CUST_NO']."</td>";
	echo "<td>".$row['CTD_CUST_NAME']."</td>";
	echo '<td><a href="setsession_cust.php?serial_id='.$_GET['serial_id'].'&cust_no='.$row['CTD_CUST_NO'].'&cust_name='.$row['CTD_CUST_NAME'].'">選取</a></td>';
	echo "</tr>";
}
mssql_close($dbhandle);
?>

</table>
<p>&nbsp;</p>
</body>
</html>
