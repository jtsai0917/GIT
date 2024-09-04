<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form method="post" action="" method="post" >
 <input type="submit" name="sql" value="上傳" />
 </form>
 
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
	
	
if(isset($_POST["sql"]))
{
	for($i=0;$i<$_SESSION["x"];$i++)
	{
		$time1=strtotime($_SESSION["array1"][$i][1]);
				$time2=strtotime("-1 day",$time1);
				$time3=date("Y-m-d",$time2);
				$time4=date("Ym",$time2);
		$date1=trim($_SESSION["array1"][$i][1]);	
		$cust=trim($_SESSION["array1"][$i][2]);
		$prod=trim($_SESSION["array1"][$i][3]);
		$lorry_no=trim($_SESSION["array1"][$i]);
		$day1="FLM_DAY".substr($time3,-2,2);
				
		$query="SELECT 		FLM_YEAR_MONTH,CTD_CUST_NO,PDD_PROD_NO,".$day1."
		FROM 		FILLPLAN_LORRY_MONTH3
		WHERE		 (FLM_YEAR_MONTH = '".substr($time3,0,4).substr($time3,5,2)."') AND (CTD_CUST_NO = '".$cust."') AND (PDD_PROD_NO = '".$prod."')";
		
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
	
		if($numRows==0)//找不到資料時
		{
			$query1="INSERT INTO			 FILLPLAN_LORRY_MONTH3(FLM_YEAR_MONTH,CTD_CUST_NO,PDD_PROD_NO,".$day1.") 
			VALUES ( '".$time4."', '".$_SESSION["cn"][$i][2]."', '".$_SESSION["pn"][$i][3]."','".$_SESSION["lo"][$i]."')";	
		$result1 = mssql_query($query1);
		echo $query1 ;
		echo "插入";
		echo "</br>";
		}
			
		else
		{//有找到資料

			while($row = mssql_fetch_array($result))
			{	
				if($row[$day1]==NULL){$newlorryno=$_SESSION["lo"][$i];}//echo"鍵入新的";}<<day?沒有值
				else{
					//echo "看這舊的:".$row[$day1];
				$newlorryno=trim($row[$day1]).";".trim($_SESSION["lo"][$i]);//echo $newlorryno."UPDATE";<<day1有值
				//echo "新的:".$newlorryno;
				}
			}	
			
			$query="SELECT 		FLM_YEAR_MONTH,CTD_CUST_NO,PDD_PROD_NO,".$day1."
		FROM 		FILLPLAN_LORRY_MONTH3
		WHERE		 (FLM_YEAR_MONTH = '".substr($time3,0,4).substr($time3,5,2)."') AND (CTD_CUST_NO = '".$cust."') AND (PDD_PROD_NO = '".$prod."')AND(".$day1."='".$_SESSION["lo"][$i]."')";
		
		$result = mssql_query($query);
		$numrows = mssql_num_rows($result);
		
		if($numrows==1){}//全都一樣 不做事
	


		else{//只有day?不一樣更新lorry
			$query="UPDATE FILLPLAN_LORRY_MONTH3
							SET                   ".$day1." ='".$newlorryno."'
						WHERE          (FLM_YEAR_MONTH = '".substr($time3,0,4).substr($time3,5,2)."') AND (CTD_CUST_NO = '".$cust."') AND (PDD_PROD_NO = '".$prod."')";	
				$result = mssql_query($query);
			//echo $query;
			//echo "</br>";
			}
		}
		echo "</br>";
	}
	
}
	
?>
