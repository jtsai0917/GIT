<?php
session_start();
$_SESSION['dbhost']=$myServer = "143.2.11.48";
//$myServer="localhost";

$myUser = "sa";
$myPass = "2iairiol";
$_SESSION['dbname']=$myDB = "CHEMICAL";

$serverName = $myServer; //serverName\instanceName
$connectionInfo = array( "Database"=>$myDB, "UID"=>$myUser, "PWD"=>$myPass);
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
