<?php
	include("../connections/conn.php");
	$query="SELECT          TABLE_NAME
FROM              INFORMATION_SCHEMA.TABLES
WHERE          (TABLE_NAME LIKE N'TL%')
ORDER BY   TABLE_NAME";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		echo $row['TABLE_NAME'];
		echo "<BR>";	
	}
?>