<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick(); 

echo '
Ｌｏｒｒｙ　充填使用次數</br>
日期區間：
<form action="" method="post">
        <input name="datepicker1" type="text" id="datepicker1" size="10" value="'.$_SESSION['datepicker1'].'"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="10" value="'.$_SESSION['datepicker2'].'"  onchange="set_date_session(this.name,this.value)">
		<input type="submit" name="submit" value="搜尋">
</form>
';

if(isset($_POST['submit'])){
	include_once("../connections/conn.php");
	$start= trim(dod($_SESSION['datepicker1']));
	$end= trim(dod($_SESSION['datepicker2']));
	$query="SELECT DISTINCT FDM_LY_NO, PDD_PROD_NO 
FROM              LORRY_FILL_CHECK INNER JOIN
                            FILLPLAN_OUT_DECIDE ON LORRY_FILL_CHECK.FDM_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO
WHERE          (LORRY_FILL_CHECK.LFC_W_LY_NO <> '') AND 
                            (FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY >= '".$start."') AND 
                            (FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY <= '".$end."') order by PDD_PROD_NO,FDM_LY_NO";
	$result=mssql_query($query);
	$i=1;
	echo '<table width="640" border="1">';
	echo '<tr><td>編號</td><td>Lorry 編號</td><td>'.$row['PDD_PROD_NO'].'</td><td>'.$_SESSION['datepicker1'].' 到 '.$_SESSION['datepicker2'].'使用次數</td></tr>';
	while($row=mssql_fetch_array($result)){
		echo '<tr><td>'.$i.'</td><td>'.$row['FDM_LY_NO'].'</td><td>'.$row['PDD_PROD_NO'].'</td><td>'.cnt($row['FDM_LY_NO'],$start,$end).'</td></tr>';
		$i++;
	}
	echo '</table>';
}


function cnt($lyno,$st,$ed){
	include_once("../connections/conn.php");
	$query="SELECT          COUNT(FILLPLAN_OUT_DECIDE.FDM_LY_NO) AS aa 
FROM              LORRY_FILL_CHECK INNER JOIN
                            FILLPLAN_OUT_DECIDE ON LORRY_FILL_CHECK.FDM_LOT_NO = FILLPLAN_OUT_DECIDE.FDM_LOT_NO
WHERE          (LORRY_FILL_CHECK.LFC_W_LY_NO <> '') AND 
                            (FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY >= '".$st."') AND 
                            (FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH + FILLPLAN_OUT_DECIDE.FOD_DAY <= '".$ed."') and FDM_LY_NO='".$lyno."'";	
//	echo $query."<BR>";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
	
}
?>