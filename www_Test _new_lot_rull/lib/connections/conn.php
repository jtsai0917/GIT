<?php  
session_start();
$_SESSION['dbhost']=$myServer = "195.7.2.100";

$myUser = "sa";
$myPass = "9037";
$_SESSION['dbname']=$myDB = "CHEMICAL";

//connection to the database
$dbhandle = mssql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on $myServer");

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB");
?>