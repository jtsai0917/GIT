<?php
$server="143.2.11.254"; //備註:local須加括號，如果用主機名稱就不用加括號
$database="CHEMICAL";
$user="sa";
$password="9037";
$conn = odbc_connect("Driver={SQL Server};Server=$server;Database=$database;", $user, $password);
if(!$conn){echo "failure";}
?>