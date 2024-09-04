<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
$query="DELETE FROM dbo.EMPLOYEE_DATA
WHERE          (EMP_NO = '".$_GET['id']."')";
sql_rec($_SERVER['QUERY_STRING'] ,$query);
$result = mssql_query($query);
$query="DELETE FROM dbo.EMPLOYEE_AUTHORITY
WHERE          (EMP_NO = '".$_GET['id']."')";
echo $query;
$result = mssql_query($query);jumpto($_SESSION['lasturl']);
?>