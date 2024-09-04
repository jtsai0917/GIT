<?php
include "../connections/conn.php";
include ("../lib/fun.php");
include ("../lib/jtsai.php");

$query="SELECT lorry_fill_tmp.lot_no as lotno, lorry_fill_tmp.flow_speed as flow_speed, TLFLOWSPEED.speed , TLFLOWSPEED.LotNo,  lorry_fill_tmp.lorry_no as lorryno, lorry_fill_tmp.update_time as dt FROM lorry_fill_tmp LEFT OUTER JOIN TLFLOWSPEED ON lorry_fill_tmp.lot_no = TLFLOWSPEED.LotNo WHERE (TLFLOWSPEED.LotNo IS NULL)";
//	 echo $query."<BR>";
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
//	echo "AA:".$row['lotno'];
	
		$query1="INSERT INTO TLFLOWSPEED (TestDate, LotNo, SerialNo, SampleNo, speed, Ok, Tester, Operator, AnaManager, AnalyzeTime)
VALUES          ('".ddd($row['dt'])."', N'".$row['lotno']."', 1, '".substr(trim($row['lorryno']),0,2)."', ".$row['flow_speed'].", N'1', N'adm', 'adm', 'adm', 
                            N'".$row['dt']."')";
//	 echo $query1."<BR>";
		$result1=mssql_query($query1);
}
?>