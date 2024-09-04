<?php
$serverName = "localhost";
$databaseName = "CHEMICAL";
$username = "sa";
$password = "9037";

try {
   $conn_odbc = odbc_connect("Driver={SQL Server};Server=$server;Database=$database;", $username, $password);
 //   echo "connected";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
