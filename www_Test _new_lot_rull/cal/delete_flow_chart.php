<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
$query="DELETE FROM H2SO4_flow_chart_sheet WHERE ([index] = ".$_GET['id'].")";
echo $query;
$result=mssql_query($query);
close_frame();