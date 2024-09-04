<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>部門基本資料</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="800" border="1">
    <tr align="center">
      <td align="center">員工代號:
        <label for="uid"></label>
      <input name="uid" type="text" id="uid" size="6" />        
      &nbsp;&nbsp;&nbsp;&nbsp;員工名稱：
      <label for="empname"></label>
      <input name="empname" type="text" id="empname" size="10" /> &nbsp;&nbsp;&nbsp;&nbsp;預設密碼與員工代號相同
      </td>
      <td><input type="submit" name="search" id="search" value="查詢" /></td>
    </tr>
    </table>
    <table width="800" border="1">
    <tr align="center">
      <td width="100">員工代號</td>
      <td width="100">員工姓名</td>
      <td width="100">動作</td>
    </tr>
    <tr align="center">
      <td width="100"><input type="text" name="id" id="id" /></td>
      <td width="100"><input type="text" name="name" id="name" /></td>
      <td width="100"><input type="submit" name="add" id="add" value="新增" /></td>
    </tr>
<?php
	$query="SELECT  EMP_NO, EMP_NAME, DEP_NO, EMP_TITLE, EMP_PASSWORD, EMP_CARDID
FROM      EMPLOYEE_DATA
WHERE   (DEP_NO = 'YG')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
echo '</tr>
    <tr align="center">
      <td>'.$row['EMP_NO'].'</td>
	  <td>'.$row['EMP_NAME'].'</td>
      <td>
	  <input type="button" name="delete" id="delete" value="刪除" onClick="'."window.open('./delete_emp.php?id=".$row['EMP_NO']." ', '_self');".'"/>
	  </td>
    </tr>';
}

?>
  </table>
</form>
</br>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["add"]))
{
include("../connections/conn.php");
$query="INSERT INTO dbo.EMPLOYEE_DATA
                            (EMP_NO, EMP_NAME, DEP_NO, EMP_TITLE, EMP_PASSWORD)
			VALUES          ('".$_POST['id']."', '".$_POST['name']."', 'YG', NULL, 'tys')";
//			echo $query."</br>";
$result = mssql_query($query);
$query="INSERT INTO dbo.EMPLOYEE_AUTHORITY
                            (EMP_NO)
VALUES          ('".$_POST['id']."')";

$result = mssql_query($query);
refresh();
}

if(isset($_POST["search"]))
{
	$_SESSION['empid']=$_POST['uid'];
	$_SESSION['empname']=$_POST['empname'];
	$_SESSION['t2']=$_POST['t2'];
	refresh();
}
?>