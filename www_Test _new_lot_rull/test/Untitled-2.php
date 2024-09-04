
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>無標題文件</title>
<?php
include("../lib/fun.php");
include("../connections/conn.php");
include("../checkuser.php");
include("../lib/jtsai.php");

session_start();
datepick();
?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
   <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td>
         <input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">
<?php 

if(isset($_POST['submit']))
{
$date1=dod($_SESSION['datepicker1']);
$date2=dod($_SESSION['datepicker2']);

//echo $date1,$date2;
$query="select DISTINCT		EWD.FDM_LOT_NO,FOD.PDD_PROD_NO,PD.PDD_PROD_NAME,FI.FID_FILL_BEGIN_DATE
from		EL_WASH_DRUM as EWD
inner join 		FILLPLAN_OUT_DECIDE as FOD
on		EWD.FDM_LOT_NO=FOD.FDM_LOT_NO
inner join		PRODUCT_DATA as PD
on		FOD.PDD_PROD_NO=PD.PDD_PROD_NO
inner join		FILL_INDICATE as FI
on		EWD.FDM_LOT_NO=FI.FDM_LOT_NO
where		FI.FID_FILL_BEGIN_DATE>='".$date1.".000000' and FI.FID_FILL_BEGIN_DATE<='".$date2.".999999'
";
$result = mssql_query($query);
$numrows = mssql_num_rows($result);

$aa=array();
	 echo '<table border="1" width="60%">';
		for($i=0;$i<$numrows;$i++)
		{
			
			
			
			while($row=mssql_fetch_array($result))
			{
				echo '<tr height="50">';
				
				
				echo  '<td>'.$row['FDM_LOT_NO'].'</td>';
				array_push($aa,$row['FDM_LOT_NO']);
				echo '<td>'.$row['PDD_PROD_NO'].'</td>';
				echo  '<td>'.$row['PDD_PROD_NAME'].'</td>';
				echo  '<td>'.substr($row['FID_FILL_BEGIN_DATE'],0,8).'</td>';
				echo '<td> <input type="button" name="rull" id="rull" value="列印" onClick="'."window.open('./testb.php?id=".$row['FDM_LOT_NO']." ', '_self');".'"/>';
				//echo '<td> <input type="submit" name="'.$i.'" id="rull" value="列印" onClick="'."window.open('./testb.php')".'"/>';
				//echo '<td>'.'<input name="op" type="submit"  id="op" value="列  印">'.'</td>';
				
				
				//$newlot=";".$row['FDM_LOT_NO'];
			
				
			}
			
			
		}
		//print_r($aa);
		
			
	
}

?>
</form>
<body>
</body>
</html>