<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
$query="DELETE FROM dbo.Analyze_Rulls
WHERE ([index]='".$_GET['id']."')";
$result = mssql_query($query);
sql_rec($_SERVER['QUERY_STRING'] ,$query);
//if($result){echo "з╣жижs└╔";}
jumpto($_SESSION['lasturl']);
?>