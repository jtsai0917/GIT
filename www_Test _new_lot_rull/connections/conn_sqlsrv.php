<?php
session_start();
$_SESSION['dbhost']=$myServer = "195.7.2.100";
//$myServer="localhost";

$myUser = "sa";
$myPass = "9037";
$_SESSION['dbname']=$myDB = "CHEMICAL";
echo "S1";
$serverName = $myServer; //serverName\instanceName
$connectionInfo = array( "Database"=>$myDB, "UID"=>$myUser, "PWD"=>$myPass);
$conn = sqlsrv_connect( $serverName, $connectionInfo);
echo "S2";
if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
