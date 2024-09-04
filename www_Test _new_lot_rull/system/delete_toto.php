<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
$query="DELETE FROM dbo.DRUM_HISTORY_TOTO
WHERE          (HTM_DRUM_NO = '".$_GET['id']."')";
sql_rec($_SERVER['QUERY_STRING'] ,$query);
$result = mssql_query($query);
if($result){echo "з╣жижs└╔";}
else {echo $query;}
jumpto($_SESSION['lasturl']);
?>