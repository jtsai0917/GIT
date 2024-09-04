<?php
session_start();
include("../connections/conn.php");

$query="SELECT DISTINCT ELF_FORM FROM ELEMENT_FORM ";
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
//	echo $row['ELF_FORM']."  : <BR>";
	conver_date($row['ELF_FORM']);
}


function conver_date($table){
	$query1="UPDATE ".$table. " SET TestDate = CONVERT(varchar(12), TestDate, 111)";
	$result1=mssql_query($query1);
}

?>
