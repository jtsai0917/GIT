<?php
session_start();
$_SESSION['y']=0;
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
?>
<form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
<?php
$i=1;
echo '</br><table border="0" width="800"><tr align="center" height="20" bgcolor="F0F000"><td border="0" width="600"> </td><td border="0" width="200"><input name="save" 
type="submit" value=" 確 定 " /><input type="submit" name="leave" id="leave" value="  離開  "></td></tr></table>';
echo '<table border="1" width="800"><tr align="center"><td>項次</td><td>分析群組</td><td>單元名稱</td><td>替代說明</td><td>資料型態</td><td>Excel 表格位置</td><td>建立日期</td><td>建立人員</td>';

$pdd_chemical=get_pdd_chemicla_from_pid($_GET['pid']);

$query="SELECT Rull_Reg FROM Analyze_Rulls where PDD_PROD_NO='".$_GET['pid']."' and CTD_CUST_NO like 'C00001'";
// echo $query."<BR>";
$result=mssql_query($query);
$numrows=mssql_num_rows($result);
$row=mssql_fetch_row($result);
if($numrows==0)
{
list_item("COA");
$query1="SELECT     distinct     AnalyzeItem.ANI_GROUPNAME
FROM              AnalyzeItem INNER JOIN
                            ELEMENT_FORM ON AnalyzeItem.ANI_INDEX = ELEMENT_FORM.ELM_ID
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$pdd_chemical."')";
$result1=mssql_query($query1);
while ($row = mssql_fetch_array($result1))
	{
		$efm=get_table($row['ANI_GROUPNAME'],$pdd_chemical);
		list_item($efm,$i,$row['ANI_GROUPNAME']);
	}
}
else{list_item("COA");$items_=$row[0];
//echo $items_;
$aa=explode(",",$items_);
$caa=count($aa);
for($n=0;$n<$caa;$n++){
		$sql1.=" (ANI_INDEX = ".$aa[$n].") OR";
	}
$query="SELECT DISTINCT ANI_GROUPNAME
FROM              AnalyzeItem
WHERE ". substr($sql1,0,-2);
$result1=mssql_query($query);
while ($row = mssql_fetch_array($result1))
	{
		$efm=get_table($row['ANI_GROUPNAME'],$pdd_chemical);
		list_item($efm,$i,$row['ANI_GROUPNAME']);
	}
}
echo '</table></br>';	
//echo $query."</br>";
?>
</form>

<?php
$editFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["save"]))
{
include("../connections/conn.php");
	$items = $_POST["item"];
	$efm = $_POST["efm"];
	$discs = $_POST["disc"];
	$excels = $_POST["excel"];
	$ani_group = $_POST["ani_group"];
	for($x=0;$x<($_SESSION['y']);$x++)
	{
		if($discs[$x]==''){$discs[$x]='NULL';}
		if($items[$x]==''){$items[$x]='NULL';}
		if($excels[$x]==''){$excels[$x]='NULL';}
		if($_GET['ani_group']=='COA')
		{
			$query="SELECT         item
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$efm[$x]."') AND (pid = '".$_GET['pid']."') AND (item = '".$items[$x]."')  AND (ani_group = 'COA')";	
			$result=mssql_query($query);
			$numrows=mssql_num_rows($result);
			if($numrows<1)
			{
			$queryn="INSERT INTO dbo.ani_excel_location
								  (item, disc, excel, efm, pid, create_datetime, create_user, ani_group)
					VALUES         ('".$items[$x]."','".$discs[$x]."','".$excels[$x]."','".$efm[$x]."','".$_GET['pid']."','".date("YmdHis")."','".$_SESSION['uid']."','".$_GET['ani_group']."')";
			$resultn=mssql_query($queryn);
			}
			else
			{
			include("../connections/conn.php");	
			$queryn1="UPDATE        dbo.ani_excel_location
					SET                  disc = '".$discs[$x]."', create_datetime = '".date("YmdHis")."', create_user = '".$_SESSION['uid']."', excel='".$excels[$x]."' 
					WHERE         (efm = '".$efm[$x]."') AND (pid = '".$_GET['pid']."') AND (item = '".$items[$x]."') AND (ani_group = 'COA')";	
				$resultn1=mssql_query($queryn1);
			}
		}
	}
refresh();
}

if(isset($_POST["leave"]))
{
	if($_GET['ani_group']){jumpto($_SESSION['lasturl']);}
	else{jumpto($_SESSION['lasturl1']);}
}

function list_item($efm,$i,$groupname){
	
if($efm<>'COA'){
$query="SELECT         *
FROM             INFORMATION_SCHEMA.COLUMNS
WHERE (TABLE_NAME ='".$efm."')";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	if (($row['COLUMN_NAME']=='TestDate') || ($row['COLUMN_NAME']=='CHK1') || ($row['COLUMN_NAME']=='CHK2') || ($row['COLUMN_NAME']=='CHK3') || 
	($row['COLUMN_NAME']=='CHK4') || ($row['COLUMN_NAME']=='CHK5') || ($row['COLUMN_NAME']=='CHK6') || ($row['COLUMN_NAME']=='CHK7') || ($row['COLUMN_NAME']=='CHK8') || 
	($row['COLUMN_NAME']=='SerialNo') || ($row['COLUMN_NAME']=='AnaManager') || ($row['COLUMN_NAME']=='CHK9') || ($row['COLUMN_NAME']=='CHK10') || ($row['COLUMN_NAME']=='CHK11') || ($row['COLUMN_NAME']=='CHK12')
	 || ($row['COLUMN_NAME']=='AnaManager') || ($row['COLUMN_NAME']=='LotNo') || ($row['COLUMN_NAME']=='SampleNo') || ($row['COLUMN_NAME']=='Tester') || ($row['COLUMN_NAME']=='Operator') || ($row['COLUMN_NAME']=='Ok') || ($row['COLUMN_NAME']=='AnalyzeTime')){;}
	else{
			$str=$str."[".$row['COLUMN_NAME']."], ";
			$aniexcel1=new ani_excel;
			$aniexcel1->table=$efm;
			$aniexcel1->item=$row['COLUMN_NAME'];
			$aniexcel1->pid=$_GET['pid'];
			$aniexcel1->get();
			echo '
			<tr align="center"><td>'.($_SESSION['y']+1).'</td><td>'.$groupname.'</td><td><input name="efm[]" type="hidden" value="'.$efm.'" />
			<input name="item[]" type="hidden" value="'.trim($row['COLUMN_NAME']).'" />'.trim($row['COLUMN_NAME']).'</td><td><input name="disc[]"
			type="text" size="30" value="'.trim($aniexcel1->disc).'" /></td><td>'.$row['DATA_TYPE'].'</td>
			<td><input name="excel[]" type="text" size="6" value="'.trim($aniexcel1->excel).'" /></td><td>'.trim($aniexcel1->create_datetime).
			'</td><td><input name="create_user[]" type="text" size="6" value="'.get_uname($aniexcel1->create_user).'" /></td></tr>';
			$i=$i+1;$_SESSION['y']=$_SESSION['y']+1;
		}
}} //end ifnot COA
if($efm=='COA'){
$query="SELECT          [index], item, disc, excel, efm, pid, create_datetime, create_user
FROM              ani_excel_location
WHERE          (item = 'Lot_no') AND (pid = '".$_GET['pid']."') and (ani_group='COA')";	
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	$aniexcel->excel=$row['excel'];
	$aniexcel->disc=$row['disc'];
	$aniexcel->create_datetime=date("YmdHis");
	$aniexcel->create_user=$row['create_user'];
}
echo '		<tr align="center"><td>'.($_SESSION['y']+1).'</td><td></td><td><input name="efm[]" type="hidden" value="COA" />
			<input name="item[]" type="hidden" value="Lot_No" />Lot No</td><td><input name="disc[]"
			type="text" size="30" value="'.trim($aniexcel->disc).'" /></td><td>TEXT</td>
			<td><input name="excel[]" type="text" size="6" value="'.trim($aniexcel->excel).'" /></td><td>'.trim($aniexcel->create_datetime).
			'</td><td><input name="create_user[]" type="text" size="6" value="'.get_uname($aniexcel->create_user).'" /></td></tr>';
$i=$i+1;$_SESSION['y']=$_SESSION['y']+1;
}
}

?>

