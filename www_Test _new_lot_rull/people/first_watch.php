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
  <p>查詢</p>
  <p>工號 : 
    <label for="pdd_pro_no"></label>
    <input type="text" name="pdd_pro_no" id="pdd_pro_no" />
   名稱 : 
   <label for="pdd_prod_name"></label>
   <input type="text" name="pdd_prod_name" id="pdd_prod_name" />
   <input type="submit" name="submin" id="submin" value="送出" />
  </p>
   <input type="hidden" name="MM_insert" value="form1">

</form>
<table width="350" border="1">
  <tr>
    <td width="99" class="center">工號</td>
    <td width="185" class="center">名稱</td>
    <td width="49" class="center">選擇</td>    
  </tr>
  <?php
$editFormAction = $_SERVER['PHP_SELF'];

	include_once("../connections/conn.php");
	$query="SELECT distinct AF.first,ED.EMP_NAME 
	FROM analyze_first as AF 
	inner join EMPLOYEE_DATA as ED on AF.first=ED.EMP_NO
	WHERE (AF.first<>'')";
	
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
	echo "<tr>";
    echo "<td>".$row['first']."</td>";
	echo "<td>".$row['EMP_NAME']."</td>";
	echo '<td><a href="setsession_first.php?no1='.$row['first'].'&no2='.$row['EMP_NAME'].'">選取</a></td>';
	echo "</tr>";
}
mssql_close($dbhandle);
?>
</table>
<p>&nbsp;</p>
</body>
</html>
