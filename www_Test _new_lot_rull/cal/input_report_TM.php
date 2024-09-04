<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$query="SELECT          FILLPLAN_OUT_DECIDE.FDM_LOT_NO, FILLPLAN_OUT_DECIDE.CTD_CUST_NO, 
                            CUSTOMER_DATA.CTD_CUST_NAME
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            CUSTOMER_DATA ON FILLPLAN_OUT_DECIDE.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE          (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".$_GET['lot_no']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
echo "<BR>";
echo "Lot No :".$_GET['lot_no'];
if($numRows>0)
{
	while($row = mssql_fetch_array($result))
	{
		if($row['CTD_CUST_NO']<>''){$cust_no=$row['CTD_CUST_NO'];$cust_name=$row['CTD_CUST_NAME'];}
		else{$cust_no='C00001';$cust_name='TYS Internal';}
	}
}
else{$cust_no='C00001';$cust_name='TYS Internal';}

$query="SELECT DISTINCT 
                   PRODUCT_DATA.PDD_PROD_NO, AnalyzeItem.ANI_INDEX, AnalyzeItem.ANI_ID, AnalyzeItem.ANI_NICKNAME, 
                   AnalyzeItem.ANI_FULLNAME, AnalyzeItem.ANI_UNIT, AnalyzeItem.ANI_ANR_ID, AnalyzeItem.ANI_GROUPNAME, 
                   AnalyzeItem.ANI_DATAFIELD AS DF1, AnalyzeItem.ANI_ORDER, ELEMENT_FORM.ELF_FORM AS FM1 
FROM      ELEMENT_FORM INNER JOIN
                   PRODUCT_DATA ON ELEMENT_FORM.PDD_CHEMICAL = PRODUCT_DATA.PDD_CHEMICAL INNER JOIN
                   AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX 
WHERE   PRODUCT_DATA.PDD_PROD_NO = '".$_GET['pdd_prod_no']."' AND (AnalyzeItem.ANI_NICKNAME = 'Ag' OR AnalyzeItem.ANI_NICKNAME = 'Al' OR AnalyzeItem.ANI_NICKNAME = 'As' OR AnalyzeItem.ANI_NICKNAME = 'Au' OR AnalyzeItem.ANI_NICKNAME = 'Ba' OR AnalyzeItem.ANI_NICKNAME = 'Be' OR AnalyzeItem.ANI_NICKNAME = 'Bi' OR AnalyzeItem.ANI_NICKNAME = 'Ca' OR AnalyzeItem.ANI_NICKNAME = 'Cd' OR AnalyzeItem.ANI_NICKNAME = 'Co' OR AnalyzeItem.ANI_NICKNAME = 'Cr' OR AnalyzeItem.ANI_NICKNAME = 'Cu' OR AnalyzeItem.ANI_NICKNAME = 'Fe' OR AnalyzeItem.ANI_NICKNAME = 'Ga' OR AnalyzeItem.ANI_NICKNAME = 'Ge' OR AnalyzeItem.ANI_NICKNAME = 'K' OR AnalyzeItem.ANI_NICKNAME = 'Li' OR AnalyzeItem.ANI_NICKNAME = 'Mg' OR AnalyzeItem.ANI_NICKNAME = 'Mn' OR AnalyzeItem.ANI_NICKNAME = 'Mo' OR AnalyzeItem.ANI_NICKNAME = 'Na' OR AnalyzeItem.ANI_NICKNAME = 'Nb' OR AnalyzeItem.ANI_NICKNAME = 'Ni' OR AnalyzeItem.ANI_NICKNAME = 'Pb' OR AnalyzeItem.ANI_NICKNAME = 'Sb' OR AnalyzeItem.ANI_NICKNAME = 'Sn' OR AnalyzeItem.ANI_NICKNAME = 'Sr' OR AnalyzeItem.ANI_NICKNAME = 'Ta' OR AnalyzeItem.ANI_NICKNAME = 'Ti' OR AnalyzeItem.ANI_NICKNAME = 'Tl' OR AnalyzeItem.ANI_NICKNAME = 'Zn' OR AnalyzeItem.ANI_NICKNAME = 'Zr')";  //在這裡可以決定輸入TotalMetal 的項目
//echo $query;
echo '<form name="form1" method="post" action="">';
echo '</br><table border="1">TM items 客戶 : ('.$cust_no.')'.$cust_name;
echo '<tr  bgcolor="#CCCCCC"><td></td>';
$aa=array();$x=0;
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	echo '<td width="60" align="center">'.$row['ANI_NICKNAME'].'</td>'  ;  //項目
	$aa[$x][0]=$row['ANI_NICKNAME'];
	$aa[$x][1]=$row['FM1'];
	$aa[$x][2]=$row['DF1'];
	$x++;
}
echo '</tr><tr><td bgcolor="#CCCCCC">Values</td>';
for($i=0;$i<$x;$i++){
	echo '<td width="60" align="center">'.xvalue($aa[$i][0],$aa[$i][1],$aa[$i][2]).'</td>'  ;  //值
} 
echo '</tr>';
echo '</tr><tr><td bgcolor="#CCCCCC">USL</td>';

for($i=0;$i<$x;$i++){
	echo '<td width="60" align="center">'.get_spc($cust_no,"USL",$aa[$i][0]).'</td>'  ;  //DL
} 
echo '</tr>';

echo '</tr><tr><td bgcolor="#CCCCCC">DL</td>';
for($i=0;$i<$x;$i++){
	echo '<td width="60" align="center">'.get_spc($cust_no,"DL",$aa[$i][0]).'</td>'  ;  //DL
} 
echo '</tr>';

echo '</tr><tr><td bgcolor="#CCCCCC">STD_U</td>';
for($i=0;$i<$x;$i++){
	echo '<td width="60" align="center">'.get_spc($cust_no,"STD_U",$aa[$i][0]).'</td>'  ;  //DL
} 
echo '</tr>';

echo '</tr><tr><td bgcolor="#CCCCCC">UNIT</td>';
for($i=0;$i<$x;$i++){
	echo '<td width="60" align="center">'.get_spc($cust_no,"ANI_UNIT",$aa[$i][0]).'</td>'  ;  //DL
} 
echo '</tr>';

echo '</tr><tr><td bgcolor="#CCCCCC">取值</td>';
$total=0;
for($i=0;$i<$x;$i++){
	if(xvalue($aa[$i][0],$aa[$i][1],$aa[$i][2])<=get_spc($cust_no,"DL",$aa[$i][0]))
	{
		$value=get_spc($cust_no,"DL",$aa[$i][0]);
	}
	elseif(xvalue($aa[$i][0],$aa[$i][1],$aa[$i][2])>get_spc($cust_no,"DL",$aa[$i][0]))
	{
		$value=xvalue($aa[$i][0],$aa[$i][1],$aa[$i][2]);
	}
	echo '<td width="60" align="center">'.round($value,3).'</td>'  ;  //DL
	$total=$total+round($value,3);
} 
echo '</tr>';
echo '</table><BR>'  ;

$usl=get_spc($cust_no,"USL","TM");
$dl=get_spc($cust_no,"DL","TM");
$stdu=get_spc($cust_no,"STD_U","TM");
if($total<=$stdu){$status=0;}
if($total>$stdu and $total<$usl and $usl<>''){$status=1; $str='bgcolor="#CCFF66"';}
if($total>$usl and $usl<>''){$status=2; $str='bgcolor="#FF3333"';}
echo 'TotalMetal 值/規格';
echo '<table border="1">';
echo '<tr bgcolor="#CCCCCC"><td width="100">Total Metal </td><td width="50">USL</td><td width="50">DL</td><td width="50">STD_U</td></tr>';
echo '<tr><td '.$str.'>'.$total.'</td><td>'.$usl.'</td><td>'.$dl.'</td><td>'.$stdu.'</td></tr>';
echo '</table>';
?>
和否判定
<input type="checkbox" name="Ok" id="Ok" <?php if($status==0){echo "checked";}?>/>
</br>
樣品瓶號
  <input type="text" name="smp_name" id="smp_name">
<input type="submit" name="creat" id="submit" value="   新 增   ">
<input type="button" name="button" id="button" value="   離 開   " onClick="window.open('<?php echo $quit=$_SERVER['REQUEST_URI']."&url=input_report_sec";?>', '_self');" />
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["creat"]))
{ 
	$datetime=date("YmdHis");
	if($_POST['Ok']=='on'){
		$_POST['Ok']=1;	
		$ok=1;
		$query="INSERT INTO ani_result_group (lot_no, ani_group, result, creator, createtime) VALUES (N'".trim($_GET['lot_no'])."', N'TM', '".$ok."', N'".$_SESSION['uid']."', N'".$datetime."')";
        $result= mssql_query($query);
	}
	else{
		$_POST['Ok']=0;	
		$ok=0;
		$query="INSERT INTO ani_result_group (lot_no, ani_group, result, creator, createtime) VALUES (N'".trim($_GET['lot_no'])."', N'TM', '".$ok."', N'".$_SESSION['uid']."', N'".$datetime."')";
        $result= mssql_query($query);
	}
	$query="SELECT  ELEMENT_FORM.ELF_FORM
FROM      ELEMENT_FORM INNER JOIN
                   AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE   (AnalyzeItem.ANI_NICKNAME = 'TM') AND (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$table=$row[0];
	
	$query="SELECT  AnalyzeDesign.AND_PERSON, EMPLOYEE_DATA.EMP_NAME
FROM      AnalyzeDesign INNER JOIN
                   EMPLOYEE_DATA ON AnalyzeDesign.AND_PERSON = EMPLOYEE_DATA.EMP_NO
WHERE   (AnalyzeDesign.AND_LOT_NO = '".$_GET['lot_no']."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$creator=$row[1];
	
	$query="select count(*) as cnt from ".$table. " where LotNo='".$_GET['lot_no']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$mxsn=$row[0]+1;
	
	$query="INSERT INTO ".$table."
                   (TestDate, CHK1, CHK2, LotNo, SerialNo, SampleNo, TM, Ok, Tester, Operator, AnaManager, AnalyzeTime)
			VALUES  ('".date("Y-m-d")."',1,1,'".$_GET['lot_no']."',".$mxsn.",'".$_POST['smp_name']."',".$total.",".$_POST['Ok'].",'".get_uname($_SESSION['uid'])."','".$creator."',NULL,'".date("YmdHis")."')";
	$result1=mssql_query($query);
	
	// Add Sample
		$_SESSION['lot_no']=$_GET['lot_no'];
		$query="SELECT          TOP (1) Sample_All.SMA_TIMES as T1, Sample.SMP_TIMES as T2
	FROM              Sample_All INNER JOIN
								Sample ON Sample_All.SMA_ID = Sample.SMP_ID
	WHERE          (SMP_ID = '".$_POST['smp_name']."')"; 
	$query.=" ORDER BY   Sample_All.SMA_TIMES DESC, Sample.SMP_TIMES DESC";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows>0){
	while($row = mssql_fetch_array($result))
		{   
			
			$smp_times=max($row['T1'],$row['T2'])+1;
			$smp_service=$row['SMP_SERVICE'];
			if($smp_service==''){$smp_service=1;}
		}
	$query="INSERT INTO dbo.Sample_All
								(SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT, SMA_USER, SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
								SMA_SAVE, SMA_SAVE_TIME, ISREWORK, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
								SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
								SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
								VALUES          ('".$_POST['smp_name']."',".$smp_times.",".$smp_service.",'".$_GET['lot_no']."',
								'".$_SESSION['s1']."',NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."','".$isrework."',
								NULL,NULL,NULL,'".'1~8'."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";

	$result = mssql_query($query);
	if (!$result) {
		echo "無法新增樣品瓶</br>";
	  }
	else{
		$query="UPDATE          dbo.Sample
				SET                   SMP_TIMES ='".$smp_times."', SMP_LOT ='".$_GET['lot_no']."', SMP_DRUMNO = '1~8', SMP_USER ='".$_SESSION['s1']."'
				, SMP_SMP ='".$_SESSION['uid']."', SMP_SAVE ='".date("Ymd")."', SMP_SAVE_TIME ='".date("His")."' 
				WHERE          (SMP_ID = '".$_POST['smp_name']."')";

		$result = mssql_query($query);
		if (!$result) {
			echo "無法更新Sample資料表</br>";
		  }
		}
	}
	else
		{
			scriptconfirm("無此瓶號，建立新瓶號?","[".$_POST['sma_id']."] 無此樣品瓶號","insert_smp.php?smp_id=".$_POST['sma_id']."&sn=".$_POST['sn']);
		}
	
	jumpto( $quit);
}


function xvalue($nick,$form,$field){
	$query="select ".$field." from ".$form." where LotNo='".$_GET['lot_no']."' and Ok=1 order by AnalyzeTime desc";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

function get_spc($cust_no,$field,$item){
	$query="SELECT DISTINCT ".$field."
					FROM      QC_CustProdSpec INNER JOIN
                   QC_Spec ON QC_CustProdSpec.SpecVer = QC_Spec.SpecVer AND 
                   QC_CustProdSpec.SpecNo = QC_Spec.SpecNo INNER JOIN
                   QC_Item ON QC_Spec.ItemName = QC_Item.ItemName INNER JOIN
                   AnalyzeItem ON QC_Item.ItemNickName = AnalyzeItem.ANI_NICKNAME
	WHERE          (QC_CustProdSpec.CustNo = '".$cust_no."') AND (QC_CustProdSpec.ProdNo = '".$_GET['pdd_prod_no']."') AND (AnalyzeItem.ANI_NICKNAME='".$item."')";
	$result = mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}

?>
