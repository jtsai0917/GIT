<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
?>
<form name="form1" method="post" action="">
  日期: 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)">
        <input type="submit" name="ENT" value="ENTER">
</form>
<?php
if(isset($_POST['ENT'])){

$i=1;
	$query="SELECT FILLPLAN_OUT_DECIDE.PDD_PROD_NO, FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH, FILLPLAN_OUT_DECIDE.FOD_DAY, FILLPLAN_OUT_DECIDE.FDM_LOT_NO AS FOD_LOT, LORRY_FILL_CHECK.FDM_LOT_NO AS LFC_LOT FROM FILLPLAN_OUT_DECIDE LEFT OUTER JOIN LORRY_FILL_CHECK ON FILLPLAN_OUT_DECIDE.FDM_LOT_NO = LORRY_FILL_CHECK.FDM_LOT_NO  WHERE (FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY = '".dod($_SESSION['datepicker1'])."') AND (FILLPLAN_OUT_DECIDE.PDD_PROD_NO LIKE '%-000') ORDER BY   FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY";
//	echo $query;
	$result=mssql_query($query);
	while($row = mssql_fetch_array($result)){

		if(trim($row['FOD_LOT'])!=trim($row['LFC_LOT'])){
			echo $i.'：<a href="Lorry_check.php?lid='.trim($row['FOD_LOT']).'" target="new">'.trim($row['FOD_LOT']).'</a><BR>';
			$i++;
		}
		
	}
}
?>

