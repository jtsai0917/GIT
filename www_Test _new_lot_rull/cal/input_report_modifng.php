
<?php 
session_start();
$_SESSION['lot_no']=$_GET['lot_no'];
/////////////////////////取得分析項目表單////////////////////////////
include("../connections/conn.php");
include("../lib/fun.php");
$_SESSION['retir']=$rev=$_SERVER['REQUEST_URI'];
$string=$_SERVER['QUERY_STRING'];
$string=substr($string,17);
$query="SELECT AND_ITEM FROM AnalyzeDesign WHERE (AND_LOT_NO = '".$_GET['lot_no']."')";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
$itemstr=$row[0];
$aa=explode(",",$itemstr);
$num=count($aa);
echo '<table border="1" width="140"><tr>';

for($i=0;$i<$num;$i++){
	echo $i.":".$aa[$i]."<BR>";	
	$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM
FROM              dbo.ELEMENT_FORM INNER JOIN
                            dbo.AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."') and ANI_INDEX=".$aa[$i]."";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if ($numRows>0){
		
	}
}

$i=1;

if ($numRows>1){
	/*
	$query1="SELECT DISTINCT ELEMENT_FORM.ELF_FORM, AnalyzeItem.ANI_INDEX FROM ELEMENT_FORM INNER JOIN AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX WHERE (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";
	$result1=mssql_query($query1);
	while($row1=mssql_fetch_array($result1)){
		
	}
	*/
	
	echo "此檢驗項目有".$numRows."張表單需要輸入</br>";
	lasturl();
while($row = mssql_fetch_array($result)){
$table=$row['ELF_FORM'];
echo '<td width="8">'.$i.'</td>';
	$ulink="index.php?efm=".$table."&".$string."&url=input_report_sec";
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

	$ulink="index.php?efm=".$table."&".$string."&url=input_report_sec";
	echo '<script>document.location.href="'.$ulink.'";</script>';
}

?>