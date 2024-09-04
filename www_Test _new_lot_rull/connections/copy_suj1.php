<?php
  session_start();
  $_SESSION['dbhost']=$myServer = "143.2.200.55";
	$myUser = "sa";
  $myPass = "2Iairiol!@#";
	$_SESSION['dbname']=$myDB = "BPMPro";
	$dbhandle = mssql_connect($myServer, $myUser, $myPass)
	or die("Couldn't connect to SQL Server on $myServer");
	$selected = mssql_select_db($myDB, $dbhandle)
  or die("Couldn't open database $myDB");
	
?>