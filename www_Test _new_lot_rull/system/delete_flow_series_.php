<?php
	session_start();
	include("../connections/conn.php");
	if($_GET['table']=='TANK_DATA'){$query="delete FROM TANK_DATA where [TANK_NO]=".$_GET['index'];}
	else{$query="delete FROM ".$_GET['table']." where [TANK_NO]=".$_GET['index'];}
	echo $query;
	$result=mssql_query($query);
	echo '<script type="text/javascript">window.close()</script>';
?>