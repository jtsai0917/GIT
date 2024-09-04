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
    <tr align="center">
      <td><label for="id"></label>
      <input type="text" name="id" id="id" /></td>
      <td><label for="name"></label>
      <input type="text" name="name" id="name" /></td>
      <td><input type="submit" name="add" id="add" value="新增" /></td>
    </tr>
<?php
session_start();
include("../lib/fun.php");
auth('9-01',$_SESSION['aut']);
lasturl();
	include("../connections/conn.php");
	$query="SELECT          dbo.DEPARTMENT_DATA.*
FROM              dbo.DEPARTMENT_DATA";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
echo '</tr>
    <tr align="center">
      <td>'.$row['DEP_NO'].'</td>
      <td>'.$row['DEP_NAME'].'</td>
      <td><input type="button" name="delete" id="delete" value="刪除" onClick="'."window.open('./delete_dep.php?id=".$row['DEP_NO']." ', '_self');".'"/></td>
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
  	$query="INSERT INTO dbo.DEPARTMENT_DATA
                            (DEP_NO, DEP_NAME)
VALUES          ('".$_POST['id']."', '".$_POST['name']."')";
sql_rec($_SERVER['QUERY_STRING'] ,$query);
	$result = mssql_query($query);
	refresh();
}
?>