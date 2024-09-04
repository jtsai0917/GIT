<?php  
session_start();
//$_SESSION['AX_DB']=$myServer = "143.2.200.15\MSSQLSRV"; ///暫時停用
$_SESSION['AX_DB']=$myServer = "WIN2012AX-1\MSSQLSRV16";
//$myServer="localhost";

$myUser = "sa"; 
$myPass = "2iairiol";

//$myDB = "MicrosoftDynamicsAX_Prod";
$myDB = "MicrosoftDynamicsAX_Prod";


//connection to the database
$ax_dbhandle = mssql_connect($myServer, $myUser, $myPass);
//  or die("Couldn't connect to SQL Server on $myServer");

//select a database to work with
$ax_selected = mssql_select_db($myDB, $ax_dbhandle);
 // or die("Couldn't open database $myDB");
?>