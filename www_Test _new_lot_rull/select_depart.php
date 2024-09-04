<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>部門基本資料</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="640" border="1">
    <tr align="center">
      <td align="center">部門代號</td>
      <td>部門名稱</td>
      <td>動作</td>
    </tr>
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$query="SELECT          dbo.DEPARTMENT_DATA.*
FROM              dbo.DEPARTMENT_DATA";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
echo '</tr>
    <tr align="center">
      <td>'.$row['DEP_NO'].'</td>
      <td>'.$row['DEP_NAME'].'</td>
      <td><input type="button" name="select" id="select" value="選擇" onClick="'."window.open('./select_dep.php?id=".$row['DEP_NO']." ', '_self');".'"/></td>
    </tr>';
}
?>
    
  </table>
</form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["add"])){
}
?>