<?php  
$barcodehnd = mssql_connect("195.7.2.100", "sa", "9037")
  or die("Couldn't connect to SQL Server on $myServer");
$conn_barcode=mssql_select_db("CHEMICAL", $barcodehnd)
  or die("Couldn't open database CHEMICAL");
?>
