<?php
session_start();
include("../connections/conn.php");
$query="SELECT         [index], FILE_PATH, FILE_UPLOAD_TIME, ANI_GROUP, FILE_PID, FILE_CID, 
                          FILE_UID, FILE_LOT_NO, FILE_DISC, FILE_NAME, FORM_ID
FROM             dbo.FILE_REPORTS
WHERE         (FILE_UPLOAD_TIME > '20160601000000')
ORDER BY  FILE_UPLOAD_TIME DESC";
$_SESSION['q1']=$query;
$result=mssql_query($query);
echo '<table border="1"> ';
while($row=mssql_fetch_array($result)){
	echo '<tr><td>'.$row['index']."</td><td>".$row['FILE_UPLOAD_TIME']."</td><td>".$row['FILE_LOT_NO']."</td><td>".$row['FORM_ID']."</td><td>".$row['ANI_GROUP']."</td>";
	$query1="select * from ".$row['FORM_ID']." where (LotNo = '".$row['FILE_LOT_NO']."') and (AnalyzeTime='')";
	$_SESSION['q2']=$query1;
	$result1=mssql_query($query1);
	$numrows=mssql_num_rows($result1);
	if($numrows>0){ 
		echo '<td>No analyzetime</td>';
		$query2="UPDATE        dbo.".$row['FORM_ID']."
		SET                  AnalyzeTime = '".$row['FILE_UPLOAD_TIME']."'
		WHERE         (LotNo = '".$row['FILE_LOT_NO']."')";
		$result1=mssql_query($query2);
	}
	else{echo '<td>'.$row['AnalyzeTime'].'</td>';}
	echo '</tr>';
}
echo '</table>>';
?>