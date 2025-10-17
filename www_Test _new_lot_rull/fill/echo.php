<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form name="post" action="<?php echo $loginFormAction; ?>" method="post" >
 <input type="submit" name="sql" value="  新增下列項目  " />
 <input name="exit" type="submit" id="exit" value="  取  消  " />
</form>
 
<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
session_start();
include("./connections/conn.php");
include("./lib/fun.php");
include("./lib/jtsai.php");
	
echo '<table border="1" width="300">';
echo '<tr><td>項目</td><td>出貨日期</td><td>客戶</td><td>藥品</td><td>LORRY NO</td></tr>';
	for($i=0;$i<$_SESSION["x"];$i++)
	{
		echo '<tr>';
		for($y=0;$y<$_SESSION["c"];$y++)
		{
			echo '<td>';
			echo  strtoupper($_SESSION["array1"][$i][$y]);
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
		//$lorry_no=trim($_SESSION["array1"][$i]);
		//$lorry_no=strtoupper($_SESSION["array1"][$i]);
		$day1="FLM_DAY".substr($time3,-2,2);
				
		$query="SELECT 		FLM_YEAR_MONTH,CTD_CUST_NO,PDD_PROD_NO,".$day1."
		FROM 		FILLPLAN_LORRY_MONTH
		WHERE		 (FLM_YEAR_MONTH = '".substr($time3,0,4).substr($time3,5,2)."') AND (CTD_CUST_NO = '".$cust."') AND (PDD_PROD_NO = '".$prod."')";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		
		$ch=" SELECT          CTP_SEL_LY_TOTO
					FROM              CUSTOMER_PRODUCTS
					where				(CTD_CUST_NO = '".$cust."') and (PDD_PROD_NO ='".$prod."' )";
					$chk = mssql_query($ch);
					while($row1 = mssql_fetch_array($chk))
					{
						$newchk=";".$row1["CTP_SEL_LY_TOTO"].";";
					}
		
		if($numRows==0)//找不到資料時
		{
			if(stripos($newchk,$_SESSION["lo"][$i])<>false){//比對LO是否存在資料庫 如果有
			$query1="INSERT INTO			 FILLPLAN_LORRY_MONTH(FLM_YEAR_MONTH,CTD_CUST_NO,PDD_PROD_NO,".$day1.") 
			VALUES ( '".$time4."', '".$_SESSION["cn"][$i]."', '".$_SESSION["pn"][$i]."','".$_SESSION["lo"][$i]."')";	
//			echo $query1;
			$result1 = mssql_query($query1);
			}
			else{echo "沒有此LORRY:".$_SESSION["lo"][$i];
			}
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
			if(strlen(trim($_SESSION["array1"][$i][4]))>6){
				$nnn= substr($_SESSION["array1"][$i][4],-1,1);			
			}
			else{
				$nnn='NULL';	
			}
			/*
			if(strlen($_SESSION["lo"][$i])>5){echo ">5:".$_SESSION["lo"][$i];echo "<BR>";}
			$_SESSION["lo"][$i]=substr(trim($_SESSION["lo"][$i]),0,6);
			$nnn= substr(trim($_SESSION["lo"][$i]),-1,1);	
			*/
			
			$query="insert into  dbo.FILLPLAN_OUT_DECIDE
			        (FOD_YEAR_MONTH, FOD_O_YEAR_MONTH, FOD_DAY, FOD_O_DAY, CTD_CUST_NO, PDD_PROD_NO, FDM_LY_NO, FDM_CREATE_DATE, FDM_CREATOR, FDM_SPECIFIC, FDM_QTY_UNIT, FDM_PURGE, LY_SN)
					VALUES          ('".$time4."','".$time4."','".substr($time3,-2,2)."','".substr($time3,-2,2)."','".$cust."','".$prod."','".$_SESSION["lo"][$i]."','".date("Ymdhis")."','".$_SESSION['uid']."','LY','KG','0', ".$nnn.")";	
//			echo $query."<BR>";		
			
			
			$result = mssql_query($query);	
	//		if($result){echo "insert ok";	}	
				
		$query="SELECT 		FLM_YEAR_MONTH,CTD_CUST_NO,PDD_PROD_NO,".$day1."
		FROM 		FILLPLAN_LORRY_MONTH
		WHERE		 (FLM_YEAR_MONTH = '".substr($time3,0,4).substr($time3,5,2)."') AND (CTD_CUST_NO = '".$cust."') AND (PDD_PROD_NO = '".$prod."')AND(".$day1."='".$_SESSION["lo"][$i]."')";
		
		$result = mssql_query($query);
		$numrows = mssql_num_rows($result);
		
		if($numrows==1){}//全都一樣 不做事
		else{//只有day?不一樣更新lorry
		if(stripos($newchk,$_SESSION["lo"][$i])<>false){
			
			$query="UPDATE FILLPLAN_LORRY_MONTH
							SET                   ".$day1." ='".$newlorryno."'
						WHERE          (FLM_YEAR_MONTH = '".substr($time3,0,4).substr($time3,5,2)."') AND (CTD_CUST_NO = '".$cust."') AND (PDD_PROD_NO = '".$prod."')";	
				$result = mssql_query($query);
				
			//echo "新的".$newlorryno."結束"; 
		}
		else{echo "沒有此LORRY:".$_SESSION["lo"][$i]."無法UPDATE";}
			echo "</br>";
			}
		}
		echo "</br>";
	}
//	echo '<script>document.location.href="'.$_SESSION['lasturl'].'";</script>';
	echo "完成";
}
if(isset($_POST["exit"]))
{
	echo '<script>document.location.href="'.$_SESSION['lasturl'].'";</script>';
}
	
?>
