<?php
$serverName = "localhost";
$databaseName = "chemical";
$username = "sa";
$password = "9037";

try {
    $dsn = "odbc:Driver={SQL Server};Server=$serverName;Database=$databaseName;";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
