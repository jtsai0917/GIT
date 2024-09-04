<?php  
session_start();
$myServer = "localhost";
//  $_SESSION['dbhost']=$myServer = "143.2.11.3";

	$myUser = "spc";
  $myPass = "spc";


//	$myUser = "sa";
//  $myPass = "2iairiol";


$myDB = "spc";

$dbhandle = mssql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on $myServer");

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB");
  
?>