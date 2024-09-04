<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
include("../connections/ax_connections.php");
echo '<form id="form1" name="form1" method="post" action="">
客戶編號 : <input type="text" name="cust" autofocus/><input type="submit" name="bt" value="enter"/></form>';
$query="SELECT          ACCOUNTNUM, GUITITLE, BLOCKED
FROM              CUSTTABLE
WHERE          (BLOCKED = 2)";
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	echo $row['ACCOUNTNUM']."-".$row['GUITITLE']."<BR>";	
}
if(isset($_POST['bt'])){
	$query="UPDATE CUSTTABLE SET BLOCKED = 2 WHERE (ACCOUNTNUM = N'".trim($_POST['cust'])."')";	
//	echo $query;
	$result=mssql_query($query);
}