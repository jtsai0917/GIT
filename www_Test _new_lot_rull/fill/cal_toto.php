<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
?>
CAL 在庫TOTO轉出荷TOTO移液作業檢查表</br>
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
		$query="select 	ICT.FDM_LOT_NO,FI.PDD_PROD_NO,PD.PDD_PROD_NAME,FI.FID_FILL_BEGIN_DATE
				FroM		IN2OUT_CAL_TOTO_CHECK AS ICT
				INNER JOIN	FILL_INDICATE AS FI
				ON		ICT.FDM_LOT_NO=FI.FDM_LOT_NO
				INNER JOIN	PRODUCT_DATA as PD
				on		FI.PDD_PROD_NO=PD.PDD_PROD_NO
				where FI.FID_FILL_BEGIN_DATE>='".$date1."000000' and FI.FID_FILL_BEGIN_DATE<='".$date2."999999'";
//		echo "<BR>"."<BR>".$query."<BR>";
		$result = mssql_query($query);
		$numrows = mssql_num_rows($result);
		$_SESSION['sss']=$query;
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
				echo '<td> <input type="button" name="rull" id="rull" value="列印" onClick="'."window.open('./index.php?url=cal_toto_print&id=".$row['FDM_LOT_NO']." ', '_self');".'"/>';
				
			}
		
		}
	
}

?>
</form>
