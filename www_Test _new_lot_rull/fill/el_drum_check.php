
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
?>
EL DRUM 充填 CHECKLIST </br>日期區間：<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
   <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td>
         <input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">
<?php 
if(isset($_POST['submit']))
{
$date1=dod($_SESSION['datepicker1']);
$date2=dod($_SESSION['datepicker2']);

$query="SELECT DISTINCT 
                            FI.FDM_LOT_NO, FOD.PDD_PROD_NO, PD.PDD_PROD_NAME, FI.FID_FILL_BEGIN_DATE, PD.PDD_TYPE
FROM              FILL_INDICATE AS FI INNER JOIN
                            PRODUCT_DATA AS PD INNER JOIN
                            FILLPLAN_OUT_DECIDE AS FOD ON PD.PDD_PROD_NO = FOD.PDD_PROD_NO ON 
                            FI.FDM_LOT_NO = FOD.FDM_LOT_NO LEFT OUTER JOIN
                            EL_WASH_DRUM AS EWD ON FI.FDM_LOT_NO = EWD.FDM_LOT_NO 
where		PD.PDD_TYPE='DM' and FI.FID_FILL_BEGIN_DATE>='".$date1."000000' and FI.FID_FILL_BEGIN_DATE<='".$date2."999999'
";
// echo "<BR>".$query."<BR>";
$result = mssql_query($query);
$numrows = mssql_num_rows($result);
$aa=array();
	 echo '<table border="1" width="60%">';
		for($i=0;$i<$numrows;$i++)
		{		
			while($row=mssql_fetch_array($result))
			{
				echo '<tr height="">';
				echo  '<td>'.$row['FDM_LOT_NO'].'</td>';
				array_push($aa,$row['FDM_LOT_NO']);
				echo '<td>'.$row['PDD_PROD_NO'].'</td>';
				echo  '<td>'.$row['PDD_PROD_NAME'].'</td>';
				echo  '<td>'.substr($row['FID_FILL_BEGIN_DATE'],0,8).'</td>';
				echo '<td> <input type="button" name="rull" id="rull" value="列印" onClick="'."window.open('./index.php?url=print_drum&id=".$row['FDM_LOT_NO']." ', '_self');".'"/>';
				
			}
		
		}
		//print_r($aa);
}

?>
</form>
<body>
</body>
</html>