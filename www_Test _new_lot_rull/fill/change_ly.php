<?php ///20161125 jtsai
session_start();
$sport = $_POST["sport"];
$select=$_POST["select"];
$myallsport = implode(";",$sport);
include("../connections/conn.php");
include("../lib/fun.php");
if(isset($_POST['cancel']))
{
	keep_session();
	echo '<script type="text/javascript">';
	echo 'window.close()';
	echo '</script>';
//	header("Location:index.php?url=fill_monthly_r&ym=".$_SESSION['ym']);
}
$query="select * from FILLPLAN_LORRY_MONTH WHERE (FLM_YEAR_MONTH = '".$_POST['ym']."') AND (PDD_PROD_NO = '".$_POST['pn']."') AND (CTD_CUST_NO = '".$_POST['cn']."')";
	$result = mssql_query($query);
	$numRows=mssql_num_rows($result);
			if($numRows>0){
				$query="UPDATE          FILLPLAN_LORRY_MONTH
						SET                   FLM_DAY".$_POST['day']." = '".$myallsport."', FLM_CREATOR".$_POST['day']." = '".$_SESSION['uid']."'
						WHERE          (CTD_CUST_NO = '".$_POST['cn']."') AND (PDD_PROD_NO = '".$_POST['pn']."') AND (FLM_YEAR_MONTH = '".$_POST['ym']."')";	
			}
	
			if($numRows<1){
					$query="INSERT INTO FILLPLAN_LORRY_MONTH
									 (FLM_YEAR_MONTH, CTD_CUST_NO, PDD_PROD_NO, FLM_DAY".$_POST['day'].")
							VALUES          ('".$_POST['ym']."','".$_POST['cn']."','".$_POST['pn']."','".$myallsport."') ";
echo $query."</br>";   					}	
$result = mssql_query($query);

$i=count($sport);
for($x=0;$x<$i;$x++)
{
	echo $x."/".$i.":".$sport[$x].":".'select'.$x."</br>";
	$query="SELECT *
			FROM              dbo.FILLPLAN_OUT_DECIDE
			WHERE          (FOD_O_YEAR_MONTH = '".$_POST['ym']."') AND (FOD_O_DAY = '".$_POST['day']."') AND (FDM_LY_NO='".$sport[$x]."')";       
    echo $query."</br>";      
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);	
	while($row=mssql_fetch_array($result))
	{
		$fodym=$row['FOD_YEAR_MONTH'];
		$fodday=$row['FOD_DAY'];
		$flo=$row['FDM_LY_NO'];
	}
	if($numRows==0)
	{
		$query="INSERT INTO dbo.FILLPLAN_OUT_DECIDE
                            ( FOD_O_YEAR_MONTH, FOD_O_DAY, CTD_CUST_NO, PDD_PROD_NO, FDM_LY_NO, FDM_CREATE_DATE, FDM_CREATOR, FDM_SPECIFIC, FDM_QTY_UNIT, FDM_PURGE)
				VALUES          ('".$_POST['ym']."','".$_POST['day']."','".$_POST['cn']."','".$_POST['pn']."','".$sport[$x]."','".date("Ymdhis")."','".$_SESSION['uid']."','LY','KG','0')";		
		echo $query."</br>";   
		$result = mssql_query($query);
	}
	else
	{
	}
}


	keep_session();
	echo '<script type="text/javascript">';
//	echo 'window.close()';
	echo '</script>';


?>