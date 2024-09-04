<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("./connections/conn.php");
include("./lib/fun.php");
include("./lib/jtsai.php");
	
echo '<table border="1" width="300">';
	for($i=0;$i<$_SESSION["x"];$i++)
	{
		echo '<tr height="25">';
		for($y=0;$y<$_SESSION["c"];$y++)
		{
			echo '<td>';
			echo $_SESSION["array1"][$i][$y];
			echo '</td>';
			
		}
	}
	echo '</table></br>';
	
	for($i=0;$i<$_SESSION["x"];$i++)
	{
		$date1=trim($_SESSION["array1"][$i][1]);	
		$cust=trim($_SESSION["array1"][$i][2]);
		$prod=trim($_SESSION["array1"][$i][3]);
		$lorry_no=trim($_SESSION["array1"][$i][4]);
		$day1="FLM_DAY".substr($_SESSION['array1'][$i][1],-2,2);
		$query="SELECT          ".$day1."
				FROM              FILLPLAN_LORRY_MONTH
				WHERE          (FLM_YEAR_MONTH = '".substr($date1,0,4).substr($date1,5,2)."') AND (CTD_CUST_NO = '".$cust."') AND (PDD_PROD_NO = '".$prod."')";
		$result = mssql_query($query);
		$numrows=mssql_num_rows($result);
		if($numrows>0)
		{
			while($row = mssql_fetch_array($result))
			{
				$newlorryno=$row[$day1].";".$lorry_no;	
				$query_update="UPDATE          FILLPLAN_LORRY_MONTH
							SET                   ".$day1." ='".$newlorryno."'
						WHERE          (FLM_YEAR_MONTH = '".substr($date1,0,4).substr($date1,5,2)."') AND (CTD_CUST_NO = '".$cust."') AND (PDD_PROD_NO = '".$prod."')";	
				echo $query_update;
				echo "</br>";
//				$result = mssql_query($query_update);   //取掉將會寫入資料庫
			}
		}
		echo "</br>";
	}
	
	
	
?>
