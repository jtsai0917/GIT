<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	auth('9-01',$_SESSION['aut']);
	lasturl()
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>部門基本資料</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="640" border="1">
    <tr align="center">
      <td align="center">群組名稱</td>
      <td>動作</td>
    </tr>
    <tr align="center">
      <td><label for="id"></label>
      <input type="text" name="id" id="id" /></td>
      <td><input type="submit" name="add" id="add" value="新增" /></td>
    </tr>
<?php

	
	$query="SELECT          dbo.AUTHORITY_DATA.*
FROM              dbo.AUTHORITY_DATA";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
echo '</tr>
    <tr align="center">
      <td>'.$row['AUT_NAME'].'</td>
      <td><input type="button" name="delete" id="delete" value="刪除" onClick="'."window.open('./delete_group.php?id=".$row['AUT_NAME']." ', '_self');".'"/></td>
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
  	$query="INSERT INTO dbo.AUTHORITY_DATA
                            (AUT_NAME)
VALUES          ('".$_POST['id']."')";
	$result = mssql_query($query);
	refresh();
}
?>