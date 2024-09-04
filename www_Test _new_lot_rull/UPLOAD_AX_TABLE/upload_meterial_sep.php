<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
require_once("../connections/ax_connections.php");
require_once "../PHPEXCEL/Classes/PHPExcel.php";
require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
$reader= PHPExcel_IOFactory::createReaderForFile("C:/upload_tmp/up_meterial.xlsx");
$reader->setReadDataOnly(true);
$excel= $reader->load("C:/upload_tmp/up_meterial.xlsx");	
$sheetCount = $excel->getSheetCount();
$sheetNames = $excel->getSheetNames();
$sheet = $excel->getActiveSheet(0); //讀取第一個工作表(編號從 0 開始)
$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
$highestRows = $sheet->getHighestRow(); //最高行數。從1開始 
$j=0;
echo '<form action="" method="post" name="form1"><input type="submit" name="submit" value="Update value"><table border="1"><tr><td>NO</td><td>主科目</td><td>部門</td><td>料號</td><td>分攤率</td></tr>';
for($i=2;$i<=$highestRows;$i++){
	echo '<tr><td>'.($j+1).'</td><td>'.$a = trim($sheet->getCellByColumnAndRow(0,$i)->getValue());	
	echo "</td><td>".$b = trim($sheet->getCellByColumnAndRow(1,$i)->getValue());	
	echo "</td><td>".$c = trim($sheet->getCellByColumnAndRow(2,$i)->getValue());	
	echo "</td><td>".$d = round(trim($sheet->getCellByColumnAndRow(3,$i)->getValue()),2);	
	echo "</td></tr>";
	echo '<input type="hidden" name="A'.$j.'" value="'.$a.'"><input type="hidden" name="B'.$j.'" value="'.$b.'"><input type="hidden" name="C'.$j.'" value="'.$c.'"><input type="hidden" name="D'.$j.'" value="'.$d.'">';
	$j++;
}

echo "</form>共:".$j.'筆';
if(isset($_POST['submit'])){
	for($x=0;$x<$j;$x++){
		$query="UPDATE          TYS_ITEMALLOCATIONSETUP
SET                   PERCENTAGE = ".trim($_POST['D'.$x])."
WHERE          (ITEMID = N'".trim($_POST['C'.$x])."') AND (MAINACCOUNT = N'".trim($_POST['A'.$x])."') AND (TO_DEPARTMENT = N'".trim($_POST['B'.$x])."')";
		$result=mssql_query($query);
//		echo $query."<BR>";
		/*
		echo '<tr><td>'.($x+1).'</td><td>'.trim($_POST['A'.$x]);	
		echo "</td><td>".trim($_POST['B'.$x]);		
		echo "</td><td>".trim($_POST['C'.$x]);		
		echo "</td><td>".trim($_POST['D'.$x]);	
		echo "</td></tr>";
		*/
	}
}