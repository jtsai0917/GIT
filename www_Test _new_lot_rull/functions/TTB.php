<?php
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include("../connections/conn.php");
include("../lib/fun.php");
include("../jtsai.php");
$i=1;
$query="SELECT          PRODUCT_DATA.PDD_TYPE, PRODUCT_DATA.PDD_STYLE, PRODUCT_DATA.PDD_CHEMICAL, TLUQA9110121.*, 
                            TLUQA9110121.B AS Expr1, TLUQA9110121.AnalyzeTime AS Expr2
FROM              AnalyzeDesign INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO INNER JOIN
                            TLUQA9110121 ON AnalyzeDesign.AND_LOT_NO = TLUQA9110121.LotNo
WHERE          (PRODUCT_DATA.PDD_CHEMICAL = 'H2O') AND (TLUQA9110121.B <> '') AND 
                            (TLUQA9110121.AnalyzeTime > '20160901000000')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result)){
		$wt=new write_TLEQA9110101;
		$wt->lot=$row['LotNo'];
		$wt->write();
	}

class write_TLEQA9110101{
	public $lot,$ana_time,$test_date,$ok,$tester,$b,$smp_no,$sn,$i;

function write()
{
	//check if exist	
	$query="select * from TLEQA9110101 where LotNo='".trim($this->lot)."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows<1)
	{ ///寫入
		echo $i." : ";
		echo $this->lot;
		echo "</br>";
		$i=$i+1;
	}
		
}		
}
?>