
<?php 
session_start();
$_SESSION['lot_no']=$_GET['lot_no'];
/////////////////////////取得分析項目表單////////////////////////////
include("../connections/conn.php");
include("../lib/fun.php");
$_SESSION['retir']=$rev=$_SERVER['REQUEST_URI'];
$string=$_SERVER['QUERY_STRING'];
$string=substr($string,17);
$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM
FROM              dbo.ELEMENT_FORM INNER JOIN
                            dbo.AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";
//echo $query."</br>";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$i=1;
echo '<table border="1" width="140"><tr>';
if ($numRows>1){
	echo "此檢驗項目有".$numRows."張表單需要輸入</br>";
	lasturl();
while($row = mssql_fetch_array($result)){
$table=$row['ELF_FORM'];
echo '<td width="8">'.$i.'</td>';
	$ulink="index.php?efm=".$table."&".$string."&url=input_report_sec_all";
echo '<td width="30">';
echo '<a target="_self" href='.$ulink.' >'.$table.'</a></td></tr>';
$i=$i+1;
}
echo '</table>';
}
else{
	while($row = mssql_fetch_array($result)){
	$table=$row['ELF_FORM'];
	echo $table;
	}

	$ulink="index.php?efm=".$table."&".$string."&url=input_report_sec_all";
	echo '<script>document.location.href="'.$ulink.'";</script>';
}
?>