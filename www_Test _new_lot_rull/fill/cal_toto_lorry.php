<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
?>
CAL TOTO轉LORRY移液作業檢查表

日期區間：<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
   <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td>
         <input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">
<?php 
if(isset($_POST['submit']))
{
	$date1=dod($_SESSION['datepicker1']);
		$date2=dod($_SESSION['datepicker2']);
		$query="select T3M.FDM_LOT_NO,FOD.PDD_PROD_NO,PD.PDD_PROD_NAME,FID.FID_FILL_BEGIN_DATE
		from IN2LORRY_CAL_TOTO_CHECK as T3M
		inner join FILLPLAN_OUT_DECIDE as FOD on T3M.FDM_LOT_NO = FOD.FDM_LOT_NO
		 INNER JOIN	PRODUCT_DATA as PD on FOD.PDD_PROD_NO = PD.PDD_PROD_NO
		 inner join FILL_INDICATE as FID on FOD.FDM_LOT_NO = FID.FDM_LOT_NO
		 where FID.FID_FILL_BEGIN_DATE>='".$date1."000000' and FID.FID_FILL_BEGIN_DATE<='".$date2."999999'
		 order by T3M.FDM_LOT_NO";
		 
//		 echo $query;
		 $result = mssql_query($query);
		$numrows = mssql_num_rows($result);
		 echo '<table border="1" width="60%">';
		for($i=0;$i<$numrows;$i++)
		{		
			while($row=mssql_fetch_array($result))
			{
				echo '<tr height="">';
				echo  '<td>'.$row['FDM_LOT_NO'].'</td>';
				
				echo '<td>'.$row['PDD_PROD_NO'].'</td>';
				echo  '<td>'.$row['PDD_PROD_NAME'].'</td>';
				echo  '<td>'.substr($row['FID_FILL_BEGIN_DATE'],0,8).'</td>';
				echo '<td> <input type="button" name="rull" id="rull" value="列印" onClick="'."window.open('./index.php?url=cal_toto_lorry_print&id=".$row['FDM_LOT_NO']." ', '_self');".'"/>';
				
			}
		
		}
	

		
		
		
}