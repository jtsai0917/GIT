<?php  
session_start();
  	 	$_SESSION['dbhost']=$myServer = "10.181.5.100";
//  	$_SESSION['dbhost']=$myServer = "143.2.11.3";
//    $_SESSION['dbhost']=$myServer = "localhost";
$myUser = "sa";
    	$myPass = "9037";
//			$myPass = "2iairiol";
$_SESSION['dbname']=$myDB = "CHEMICAL_X";

//connection to the database

$dbhandle = mssql_connect($myServer, $myUser, $myPass)
  or die("Couldn't connect to SQL Server on $myServer");

//select a database to work with
$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB");

?>
