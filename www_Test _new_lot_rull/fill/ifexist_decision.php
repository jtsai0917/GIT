<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
if(isset($_GET['lorry']))
{
	$query="select * from FILLPLAN_OUT_DECIDE as FOD where FOD.FOD_O_YEAR_MONTH = '".$_GET['ym']."' and FOD.FOD_O_DAY = '".$_GET['day']."' 
			and not FOD.FDM_MOD_DATE is null  and ( FDM_LY_NO = '".$_GET['lorry']."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows>0)
		{
		if($_SESSION['my_msg']==0){my_msg("已轉為充填計畫，不可變更",$_SESSION['flmym']);}
		}
	else{
//		echo "##".$_SESSION[$_GET['lorry']]."##";
		if($_GET['status']==0)
			{
				$_SESSION[$_GET['lorry']]=1;
			}	
		elseif($_GET['status']==1)
			{
				$_SESSION[$_GET['lorry']]=0;
			}
		}

	jumpto($_SESSION['flmym']);
}
;
?>