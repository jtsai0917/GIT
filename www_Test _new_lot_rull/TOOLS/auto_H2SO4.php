<meta http-equiv="Content-Type" content="text/html; charset=big5" />
導入充填流速紀錄 (H2SO4)
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick(); 
echo "<BR>";
echo $datefrom= date("Ymd") -1 ;
echo "<BR>";
echo $dateto= date("Ymd") +1 ;	
echo '<table width="800" border="1"><tr><td>PDD</td><td>LotNo</td></tr>';
$query="SELECT          AnalyzeDesign.AND_LOT_NO, PRODUCT_DATA.PDD_PROD_NO, AnalyzeDesign.AND_APPLY_DATE as apply_date
FROM              AnalyzeDesign INNER JOIN
                            PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO   WHERE (AND_APPLY_DATE >= '".$datefrom."') and (AND_APPLY_DATE <= '".$dateto."') AND (AnalyzeDesign.AND_ITEM LIKE '%,272%') AND (PRODUCT_DATA.PDD_CHEMICAL = 'H2SO4')";
							

$result=mssql_query($query);
while($row=mssql_fetch_array($result)){

	echo '<tr><td>'.$row['PDD_PROD_NO'].'</td><td>'.$row['AND_LOT_NO'].'</td></tr>';
	if($row['PDD_PROD_NO']=='P050-000'){$smp='S6';}
	if($row['PDD_PROD_NO']=='P060-000'){$smp='S9';}
	import_tbl($row['AND_LOT_NO'],$smp,$row['PDD_PROD_NO'],$row['apply_date']);
	
//	$a=substr(trim($row['SampleNo']),0,8);
//	$query1="UPDATE  QC_LotData SET SampleNo = '".$a."' WHERE   (ID = ".$row['ID'].")";
//	$result1=mssql_query($query1);
//	echo '<tr><td>'.$row['ID'].'</td><td>'.$row['LotNo'].'</td><td>'.$row['SampleNo'].'</td><td>'.$row['state'].'</td><td>'.$a.'</td><td>'.$query1.'</td></tr>';
}
echo '</table>';	

function import_tbl($lotno,$smp,$pdd,$apply_date){
	$query="SELECT top(1) LFC_F_FLOW AS a0, create_date AS a1 FROM LORRY_FILL_CHECK WHERE (FDM_LOT_NO = '".$lotno."')";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$flow_speed=$row['a0'];
		$createdate=$row['a1'];
	}
	if($createdate==''){$createdate=$apply_date;$createdate1=$apply_date."000000";}
	
	//check if exist in QC_LotData
	$query="select * from QC_LotData where LotNo='".$lotno."' and ProdNo='".$pdd."' and OrgTable='TLFLOWSPEED' and ItemName='Filling_Flow_Rate'";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	if($numrow==0)
	{
		$query="INSERT INTO QC_LotData 
					(ID, LotNo, SampleNo, TestDate, TestData, OrgTable, OrgField, ProdNo, Chemical, CustNo, ItemName, ItemUnit, OK, Tester, Operator, AnalyzeTime, state, createuser, createts, NOTE)
	VALUES          ((SELECT          MAX(ID) + 1 AS Expr1 FROM  QC_LotData),'".$lotno."', '".$smp."', '".std($createdate)." 00:00:00', ".$flow_speed.", 'TLFLOWSPEED', 'speed', '".$pdd."', 'H2SO4','', 'Filling_Flow_Rate', 'm3/hr', 1, 'adm', 'adm', '".std($createdate)." 00:00:00', 't', 'adm', CONVERT(DATETIME, '".std($createdate)." 00:00:00', 102),'')";
		echo $query."<BR>";
		$result=mssql_query($query);
	}
	else{
		echo $lotno."已經存在於 QC_LotData<BR>";	
	}
	
	//check if exist in QC_SpcData
	$query="select * from QC_SpcData where LotNo='".$lotno."' and itemname='Filling_Flow_Rate'";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	if($numrow==0)
	{	
		$query="select * from QC_LotData where LotNo='".$lotno."' and itemname='Filling_Flow_Rate'";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$query="INSERT INTO QC_SpcData
								(LotNo, SampleNo, TestDate, TestData, ProdNo, CustNo, ItemName, ItemUnit, createts, ATYPE)
	VALUES          ('".$row[1]."', '".$row[2]."', '".$row[3]."', '".$row[4]."', '".$row[7]."', '".$row[9]."', '".$row[10]."', '".$row[11]."', CONVERT(DATETIME,'".$row[15]."', 102), '1')";

		echo $query."<BR>";		
		$result=mssql_query($query);
	}
	else{
		echo $lotno."已經存在於 QC_SpcData<BR>";	
	}
	
	//check if exist in TLFLOWSPEED
	$query="select * from TLFLOWSPEED where LotNo='".$lotno."' ";
	$result=mssql_query($query);
	$numrow=mssql_num_rows($result);
	if($numrow==0)
	{	
		$query="INSERT INTO TLFLOWSPEED (TestDate, LotNo, SerialNo, SampleNo, speed, Ok, Tester, Operator, AnaManager, AnalyzeTime)
	VALUES          (CONVERT(DATETIME, '".sta($createdate1)."', 102), N'".$lotno."', 1, '".$smp."', ".$flow_speed.", N'1', N'adm', 'adm', 'adm', 
								N'".$createdate1."')";
		echo $query."<BR>";		
		$result=mssql_query($query);
	}
	else{
		echo $lotno."已經存在於 TLFLOWSPEED<BR>";	
	}
}