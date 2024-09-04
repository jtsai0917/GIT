<?php  
 	$barcode_server = "localhost";
	$barcode_user = "sa";
	$barcode_pass = "2iairiol";
	
 	$spc_server = "localhost";
	$spc_user = "sa";
  $spc_Pass = "2iairiol";

	$db_barcode="chemical";
	$db_spc = "spc";
	
	$hnd_barcode = mssql_connect($barcode_server, $barcode_user, $barcode_pass)
	or die("Couldn't connect to SQL Server on $myServer");
	
	$hnd_spc = mssql_connect($spc_server, $spcuser, $spcPass)
  or die("Couldn't connect to SQL Server on $myServer");

function $conn_barcode(){
	$select_barcode = mssql_select_db($db_barcode, $dbhandle)
	or die("Couldn't open database $myDB");
}

function $conn_spc(){
	$select_spc = mssql_select_db($db_spc, $dbhandle1)
  or die("Couldn't open database $myDB");
  
?>