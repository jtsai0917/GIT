<?php  
session_start();
$_SESSION['dbhost']=$myServer = "143.2.200.55";
//$myServer="localhost";

$myUser = "sa";
$myPass = "2Iairiol!@#";
$_SESSION['dbname']=$myDB = "NUP";

//connection to the database
$dbhandle = mssql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on $myServer");

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB");
?>