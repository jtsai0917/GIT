<?php
	include("../connections/conn.php");
	include("../lib/fun.php");
	$query="DELETE FROM dbo.REANALYZE_REQUEST_DATA
	WHERE         (RAN_NO = ".$_GET['ranid'].")";
	$result = mssql_query($query);
	jumpto("../close_pag.php");
?>