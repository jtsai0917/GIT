<?php  
session_start();
//  	 	$_SESSION['dbhost']=$myServer = "143.2.11.3";
	   	$_SESSION['dbhost']=$myServer = "localhost";
$myUser = "spc";
    	$myPass = "spc";
//			$myPass = "2iairiol";
$_SESSION['dbname']=$myDB = "spc";

//connection to the database

$dbhandle = mssql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on $myServer");

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB");

?>