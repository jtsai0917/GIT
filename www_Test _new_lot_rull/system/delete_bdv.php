<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
$query="DELETE FROM dbo.BIG_DRUM_VOLUME
WHERE          (PDD_PROD_SHORT_NAME = '".$_GET['id']."')";
sql_rec($_SERVER['QUERY_STRING'] ,$query);
$result = mssql_query($query);
if($result){echo "з╣жижs└╔";}
else {echo $query;}
jumpto($_SESSION['lasturl']);
?>