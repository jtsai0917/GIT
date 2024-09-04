<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form method="post" action="<?php echo $loginFormAction; ?>" method="post" >
 <input type="submit" name="sql" value="  新增下列項目  " />
 <input name="exit" type="submit" id="exit" value="  取  消  " />
 </form>
 
<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
	
echo '<table border="1" width="300">';
echo '<tr><td>項目</td><td>出貨日期</td><td>客戶</td><td>藥品</td><td>DRUM 數量</td></tr>';
	for($i=0;$i<$_SESSION["x"];$i++)
	{
		echo '<tr>';
		for($y=0;$y<$_SESSION["c"];$y++)
		{
			
			echo '<td>';
			echo  iconv("utf-8","big5",strtoupper($_SESSION["array1"][$i][$y]));
			echo '</td>';
			
		}
		echo '</tr>';
	}
	
	
	echo '</table></br>';
	
if(isset($_POST["sql"]))
{
	for($i=0;$i<$_SESSION["x"];$i++)
	{
		include_once("../connections/conn.php");
		$time1=strtotime($_SESSION["array1"][$i][1]);
				$time2=strtotime("+0 day",$time1);
				$time3=date("Y-m-d",$time2);
				$time4=date("Ym",$time2);
		$date1=trim($_SESSION["array1"][$i][1]);	
		$cust=trim($_SESSION["cn"][$i]);
		$prod=trim($_SESSION["pn"][$i]);
		$ym=substr($date1,0,4).substr($date1,5,2);
		//$lorry_no=trim($_SESSION["array1"][$i]);
		//$lorry_no=strtoupper($_SESSION["array1"][$i]);
		$day1="FDM_DAY".substr($time3,-2,2);
		$day2="FDM_WDAY".substr($time3,-2,2);
				
		$query="SELECT 		FDM_YEAR_MONTH,CTD_CUST_NO,PDD_PROD_NO,".$day1."
		FROM 		 FILLPLAN_DRUM_MONTH
		WHERE		 (FDM_YEAR_MONTH = '".substr($time3,0,4).substr($time3,5,2)."') AND (PDD_PROD_NO = '".$prod."', ".$_SESSION["array1"][$i][1].")";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		echo substr($time3,-2,2)."日，找到".$numRows."筆資料‧";
		echo "<BR>";
		if($numRows==0)//找不到資料時
		{
			$query1="INSERT INTO FILLPLAN_DRUM_MONTH  
                            (PDD_PROD_NO, CTD_CUST_NO, FDM_SPECIFIC, FDM_YEAR_MONTH,".$day1.",".$day2.")
				VALUES          ('".$prod."','','DM','".$ym."',".$_SESSION["array1"][$i][4].")";
			
			echo $query1;
			echo "<BR>";
			
//			$result1 = mssql_query($query1);
		
			echo "插入";
			echo "</br>";
		}
		else
		{//有找到資料
		
			$pdd=new product;
			$pdd->pid=$_POST['pn'];
			$pdd->getone();
			$query="UPDATE          FILLPLAN_DRUM_MONTH
			SET                   FDM_CREATOR".$substr($time3,-2,2)." ='".$_SESSION['MM_Username']."', FDM_WDAY".$substr($time3,-2,2)." ='".dod($_POST['datepicker1'])."', FDM_DAY".$substr($time3,-2,2)." ='".$_POST['fdm_day']."'
			WHERE          (FDM_YEAR_MONTH = '".$_POST['ym']."') AND (PDD_PROD_NO = '".$_POST['pn']."')";
			echo $query;
			echo "<BR>";
			$result = mssql_query($query);
			$query="select * from dbo.FILLPLAN_OUT_DECIDE where (FOD_O_YEAR_MONTH='".$_POST['ym']."') and (FOD_O_DAY='".$_POST['day']."') and (PDD_PROD_NO='".$_POST['pn']."') ";
//			$result=mssql_query($query);
			$numRows=mssql_num_rows($result);
			if($numRows==0)
			{
				$query="INSERT INTO dbo.FILLPLAN_OUT_DECIDE
								  (FOD_O_YEAR_MONTH, FOD_O_DAY, PDD_PROD_NO, FDM_CREATE_DATE, 
								  FDM_CREATOR, FDM_SPECIFIC, FDM_QTY_DRUM, FDM_QTY, FDM_QTY_UNIT, CTD_CUST_NO)
				select        '".$_POST['ym']."','".$_POST['day']."','".$_POST['pn']."','".date("Ymdhis")."','".$_SESSION['uid']."','DM','".$_POST['fdm_day']."', PDD_DRUM_KG*".$_POST['fdm_day'].",'".$pdd->pdd_unit."','' 
				from PRODUCT_DATA where PDD_PROD_NO= '".$_POST['pn']."'" ;
			$result = mssql_query($query); 
			}
			else
			{
			$query="UPDATE        dbo.FILLPLAN_OUT_DECIDE set FDM_QTY_DRUM='".$_POST['fdm_day']."' where (FOD_O_YEAR_MONTH='".$_POST['ym']."') and (FOD_O_DAY='".$_POST['day']."') and (PDD_PROD_NO='".$_POST['pn']."') ";
			$result=mssql_query($query);	
			}
		
		}//end 有找到資料

	}
	
}
if(isset($_POST["exit"]))
{
	echo '<script>document.location.href="'.$_SESSION['lasturl'].'";</script>';
}
	
?>
