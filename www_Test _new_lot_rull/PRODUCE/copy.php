<?php
include ("../connections/conn_localhost.php");
$query1="SELECT          [index], form, form1
FROM              UTT_TAGNO_DATA
ORDER BY   [index]";
$i=0;
$result1=mssql_query($query1);
while($row1=mssql_fetch_array($result1)){
	$id[$i]=$row1['index'];
	$form[$i]=$row1['form'];
	$form1[$i]=$row1['form1'];
	$i++;
}
mssql_close($dbhandle);

include ("../connections/conn195t.php");
for($j=0;$j< $i;$j++){
	$query="UPDATE UTT_TAGNO_DATA SET form ='".$form[$j]."', form1 ='".$form1[$j]."' WHERE ([index] = ".$id[$j].")";
	
		$result=mssql_query($query);
		echo $query."<BR>";
	
}


?>