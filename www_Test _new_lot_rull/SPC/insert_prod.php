<?php
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	$aa=array();
	$i=0;
	$query="SELECT PDD_PROD_NO, PDD_PROD_SHORT_NAME, PDD_PROD_NAME FROM PRODUCT_DATA";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
			$aa[$i][1]=$row['PDD_PROD_NO'];
			$aa[$i][2]=$row['PDD_PROD_SHORT_NAME'];
			$aa[$i][3]=$row['PDD_PROD_NAME'];
			$i=$i+1;
	}
	include("conn_spc.php");
	for($j=0;$j < $i;$j++){
		$query="INSERT INTO  PRODBASE (ProdNo,ProdName,SPEC,PRODMODE,DRAWINGMODE) VALUES ('".$aa[$j][1]."','".$aa[$j][3]."','".$aa[$j][2]."',0,0)";
		$result=mssql_query($query);
	}
?>