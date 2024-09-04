<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
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
	$query1="SELECT         dbo.AnalyzeDesign.*
FROM             dbo.AnalyzeDesign
WHERE         (AND_LOT_NO = '".$row['FILE_LOT_NO']."')";
	$_SESSION['q2']=$query1;
	$result1=mssql_query($query1);
	while($row1=mssql_fetch_array($result1)){
		$query2="UPDATE        dbo.".$row['FORM_ID']."
		SET                  TestDate = '".ddt($row1['AND_APPLY_DATE']."000000")."'
		WHERE         (LotNo = '".$row['FILE_LOT_NO']."')";
		$_SESSION['q3']=$query2;
		$result2=mssql_query($query2);
	}
	echo '<td>'.$row['AnalyzeTime'].'</td>';
	echo '</tr>';
}
echo '</table>>';
?>