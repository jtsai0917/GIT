<?php
session_start();
$sport = $_POST["sport"];
$myallsport = implode(";",$sport);
include("../connections/conn.php");
$query="SELECT          *
FROM              dbo.FILLPLAN_LORRY_MONTH
WHERE          (CTD_CUST_NO = '".$_POST['cn']."') AND (PDD_PROD_NO = '".$_POST['pn']."') AND (FLM_YEAR_MONTH = '".$_POST['ym']."')";

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo "資料數:".$numRows."筆"."</br>";

if($numRows>0){
	$query="UPDATE          FILLPLAN_LORRY_MONTH
SET                   FLM_DAY".$_POST['day']." = '".$myallsport."', FLM_CREATOR".$_POST['day']." = '".$_SESSION['uid']."'
WHERE          (CTD_CUST_NO = '".$_POST['cn']."') AND (PDD_PROD_NO = '".$_POST['pn']."') AND (FLM_YEAR_MONTH = '".$_POST['ym']."')";
echo "更新:".$query."</br>";

$result = mssql_query($query);
	}
else {
	$query="INSERT INTO dbo.FILLPLAN_LORRY_MONTH
                            (FLM_YEAR_MONTH, CTD_CUST_NO, PDD_PROD_NO, FLM_DAY".$_POST['day'].", FLM_CREATOR".$_POST['day'].")
VALUES          ('".$_POST['ym']."', '".$_POST['cn']."', '".$_POST['pn']."', '".$myallsport."', '".$_SESSION['uid']."')";
echo "插入:".$query."</br>";

	$result = mssql_query($query);
	}

$fodday=$fodym=$flo='';
$i=count($sport);
for($x=0;$x<$i;$x++){
	$query="SELECT *
			FROM              dbo.FILLPLAN_OUT_DECIDE
			WHERE          (FOD_YEAR_MONTH = '".$_POST['ym']."') AND (FOD_DAY = '".$_POST['day']."') AND 
                            (CTD_CUST_NO = '".$_POST['cn']."') and (FDM_LY_NO='".$sport[$x]."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);	
while($row=mssql_fetch_array($result)){
	$fodym=$row['FOD_YEAR_MONTH'];
	$fodday=$row['FOD_DAY'];
	$flo=$row['FDM_LY_NO'];
	}
//echo "筆數:".$numRows."</br>";
$fodday=$fodym=$flo='';
			$q="(FDM_LY_NO<>'".$sport[$x]."') and ";
			$q2=$q2.$q;

if($numRows==0){
		$query="INSERT INTO dbo.FILLPLAN_OUT_DECIDE
                            ( FOD_O_YEAR_MONTH, FOD_O_DAY, CTD_CUST_NO, PDD_PROD_NO, FDM_LY_NO, FDM_CREATE_DATE, FDM_CREATOR, FDM_SPECIFIC, FDM_QTY_UNIT, FDM_PURGE)
				VALUES          ('".$_POST['ym']."','".$_POST['day']."','".$_POST['cn']."','".$_POST['pn']."','".$sport[$x]."','".date("Ymdhis")."','".$_SESSION['uid']."','LY','KG','0')";		
		$result = mssql_query($query);	
	}
}
$q2=substr($q2,0,-5);
$query="DELETE FROM dbo.FILLPLAN_OUT_DECIDE where ";
if($q2!=''){$query=$query."(".$q2.") and ";}
$query=$query."(FOD_YEAR_MONTH = '".$_POST['ym']."') AND (FOD_DAY = '".$_POST['day']."') AND (PDD_PROD_NO = '".$_POST['pn']."') AND 
                            (CTD_CUST_NO = '".$_POST['cn']."')";						
echo "刪除:".$query."</br>";
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {
header("Location:index.php?url=fill_monthly_r&ym=".$_POST['ym']);
}
?>