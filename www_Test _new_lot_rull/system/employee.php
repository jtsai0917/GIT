<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
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
  <table width="1240" border="1">
    <tr align="center">
      <td align="center">員工代號:
        <label for="uid"></label>
      <input name="uid" type="text" id="uid" size="6" />        
      &nbsp;&nbsp;&nbsp;&nbsp;員工名稱：
      <label for="empname"></label>
      <input name="empname" type="text" id="empname" size="10" />
      &nbsp;&nbsp;&nbsp;&nbsp;部門：
      <select name="t2" id="t2">
            <option value=""></option>
<?php 
		      $query="SELECT          dbo.DEPARTMENT_DATA.*
FROM              dbo.DEPARTMENT_DATA ";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{
    echo '<option value="'.$row['DEP_NO'].'">'.$row['DEP_NO']."-".$row['DEP_NAME'].'</option>';
}
			  ?>            
      </select>
      </td>
      <td><input type="submit" name="search" id="search" value="查詢" /></td>
    </tr>
    </table>
    <table width="1240" border="1">
    <tr align="center">
      <td width="100">員工代號</td>
      <td width="100">員工姓名</td>
      <td width="100">員工部門</td>
      <td width="100">職稱</td>
      <td width="100">卡號</td>
      <td width="100">動作</td>
    </tr>
    <tr align="center">
      <td width="100"><input type="text" name="id" id="id" /></td>
      <td width="100"><input type="text" name="name" id="name" /></td>
      <td width="100"><select name="t1" id="t1">
            <option value=""></option>
            <?php include("../connections/conn.php");
		      $query="SELECT          dbo.DEPARTMENT_DATA.*
FROM              dbo.DEPARTMENT_DATA";
			  $result = mssql_query($query);

$numRows = mssql_num_rows($result);
//echo $query;
while($row = mssql_fetch_array($result))
{
    echo '<option value="'.$row['DEP_NO'].'">'.$row['DEP_NO']."-".$row['DEP_NAME'].'</option>';
}
mssql_close($dbhandle);

			  ?>            
      </select></td>
      <td width="100"><input type="text" name="title" id="title" /></td>
      <td width="100"><input type="text" name="cardno" id="cardno" /></td>
      <td width="100"><input type="submit" name="add" id="add" value="新增" /></td>
    </tr>
<?php
	$query="SELECT          dbo.EMPLOYEE_DATA.*, dbo.DEPARTMENT_DATA.DEP_NAME, dbo.EMPLOYEE_DATA.EMP_NO AS Expr1
FROM              dbo.DEPARTMENT_DATA INNER JOIN
                            dbo.EMPLOYEE_DATA ON dbo.DEPARTMENT_DATA.DEP_NO = dbo.EMPLOYEE_DATA.DEP_NO";
if($_SESSION['empid']<>''){$query=$query." where (EMP_NO like '%".$_SESSION['empid']."%')";}
if($_SESSION['empname']<>''){$query=$query." where (EMP_NAME like '%".$_SESSION['empname']."%')";}
if($_SESSION['t2']<>''){$query=$query." where (dbo.DEPARTMENT_DATA.DEP_NO = '".trim($_SESSION['t2'])."')";}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
echo '</tr>
    <tr align="center">
      <td>'.$row['EMP_NO'].'</td>
	  <td>'.$row['EMP_NAME'].'</td>
	  <td>'.$row['DEP_NO']."-".$row['DEP_NAME'].'</td>
	  <td>'.$row['EMP_TITLE'].'</td>
	  <td>'.$row['EMP_CARDID'].'</td>
      <td>
	  <input type="button" name="rull" id="rull" value="權限" onClick="'."window.open('./index.php?url=set_emp_rull&id=".$row['EMP_NO']." ', '_self');".'"/>
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
                            (EMP_NO, EMP_NAME, DEP_NO, EMP_TITLE, EMP_PASSWORD, EMP_CARDID)
			VALUES          ('".$_POST['id']."', '".$_POST['name']."', '".$_POST['t1']."', '".$_POST['title']."', 'tys', '".$_POST['cardno']."')";
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