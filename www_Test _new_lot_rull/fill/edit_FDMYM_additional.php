<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
remurl("fdmym");
$ymd=$_GET['ym'].$_GET['day'];
if(date("Ymd")<=$ymd)
{
	?>
	  <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
		<p>桶數：
	  <input type="text" name="fdm_day" id="fdm_day" value="<?php echo $day?>" > 
		  EX:(17)
		</p>
		<p> 
		  預先洗淨日期：
		  <label for="day"></label>
		  <input type="text" name="datepicker1" id="datepicker1" value="<?php $d=strtotime("-0 Days"); echo date("m/d/Y",$d); odo($wday)?>">
		</p>
		<p>
		<input type="submit" name="submit" id="submit" value=" 確 定 ">
		<input type="button" name="button" id="button" value=" 取 消 " onClick="window.close();">
	  </p>
	</form>
	</body>
	<?php
	$loginFormAction = $_SERVER['PHP_SELF'];
	if (isset($_POST['submit'])) 
	{
		if($_POST['fdm_day']<>'')
		{
			$pdd=new product;
			$pdd->pid=$_POST['pn'];
			$pdd->getone();
			$query="UPDATE          FILLPLAN_DRUM_MONTH
			SET                   FDM_CREATOR".$_POST['day']." ='".$_SESSION['MM_Username']."', FDM_WDAY".$_POST['day']." ='".dod($_POST['datepicker1'])."', FDM_DAY".$_POST['day']." ='".$_POST['fdm_day']."'
			WHERE          (FDM_YEAR_MONTH = '".$_POST['ym']."') AND (PDD_PROD_NO = '".$_POST['pn']."')";
			$result = mssql_query($query);
			$query="select * from dbo.FILLPLAN_OUT_DECIDE where (FOD_O_YEAR_MONTH='".$_POST['ym']."') and (FOD_O_DAY='".$_POST['day']."') and (PDD_PROD_NO='".$_POST['pn']."') ";
			$result=mssql_query($query);
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
		}
		if($_POST['fdm_day']=='')
		{
			$query="UPDATE        dbo.FILLPLAN_DRUM_MONTH
			SET                  FDM_DAY".$_POST['day']." = NULL  
			WHERE          (FDM_YEAR_MONTH = '".$_POST['ym']."') AND (PDD_PROD_NO = '".$_POST['pn']."')";
			$result = mssql_query($query);
			$query="delete from dbo.FILLPLAN_OUT_DECIDE where (FOD_O_YEAR_MONTH='".$_POST['ym']."') and (FOD_O_DAY='".$_POST['day']."') and (PDD_PROD_NO='".$_POST['pn']."') ";
			$result = mssql_query($query);
		}
			echo '<script type="text/javascript">';
			echo 'window.close()';
			echo '</script>';
//		jumpto("index.php?url=fill_monthly_m&ym=".$_POST['ym']);
	}
	}
else
{
/*
	echo '<SCRIPT Language=javascript>';
	echo "window.alert('已經超過出荷日期，不可變更')";
	echo "</SCRIPT>";
	echo '<script type="text/javascript">';
	echo 'window.close()';
	echo '</script>';
*/
}
?>