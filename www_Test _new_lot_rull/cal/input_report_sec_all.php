<!DOCTYPE html>
<html>
<body>
<script type="text/javascript">
         <!--
            function getConfirmation(){
               var retVal = confirm("Do you want to continue ?");
               if( retVal == true ){
                  document.write ("User wants to continue!");
                  return true;
               }
               else{
                  document.write ("User does not want to continue!");
                  return false;
               }
            }
         //-->
      </script>

<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
$_SESSION['lot_no']=$_GET['lot_no'];
$urllast=$_SESSION['urllast']=$_SERVER['QUERY_STRING'];
$xx=1;
$_GET['lotdata']=base64_decode($_GET['lotdata']);
/////////////////////////取得分析項目表單////////////////////////////
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
remurl("input_report_sec");
datepick();
$_SESSION['cnt']=0;
unset($_SESSION['unit']);
$_SESSION['redir']=$rev=$_SERVER['REQUEST_URI'];	
lasturl1();
$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM,AnalyzeItem.ANI_NICKNAME, AnalyzeItem.ANI_FULLNAME
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.ELF_FORM = '".$_GET['efm']."') AND (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";
//
//echo $query;
//$_SESSION['QUERY']=$query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$table=$row['ELF_FORM'];
$ani_nick=$row['ANI_NICKNAME'];
$ani_full=$row['ANI_FULLNAME'];
}

$query="SELECT          FILLPLAN_OUT_DECIDE.FDM_LOT_NO, FILLPLAN_OUT_DECIDE.CTD_CUST_NO, 
                            CUSTOMER_DATA.CTD_CUST_NAME
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            CUSTOMER_DATA ON FILLPLAN_OUT_DECIDE.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE          (FILLPLAN_OUT_DECIDE.FDM_LOT_NO = '".$_GET['lot_no']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0)
{
	while($row = mssql_fetch_array($result))
	{
		if($row['CTD_CUST_NO']<>''){$cust_no=$row['CTD_CUST_NO'];$cust_name=$row['CTD_CUST_NAME'];}
		else{$cust_no='C00001';$cust_name='TYS Internal';}
	}
}
else{$cust_no='C00001';$cust_name='TYS Internal';}
$query="SELECT  AnalyzeDesign.AND_APPLY_DATE
FROM             AnalyzeDesign
WHERE          (AnalyzeDesign.AND_LOT_NO = '".$_GET['lot_no']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query;
while ($row=mssql_fetch_array($result)){
	$dt=date("Y-m-d");
}

if (isset($_POST["reload"])){
$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."') order by DATA_TYPE";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$_SESSION[$row['COLUMN_NAME']]=$_POST[$row['COLUMN_NAME']];
}
}

?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>輸入分析結果</title>
<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p>
    <input type="hidden" name="operator" id="operator" value="" />
  <a href="index.php?url=cal_prod_all">上一頁</a></p>
  <table width="1200" border="1"><tr><td width="400">
  <?php 
  $pdd=new get_from_lot_no;
  $pdd->lid=$_GET['lot_no'];
  $pdd->cid();
  $pdd->ani();
  $need_no=$pdd->needno;
  $_SESSION['needno']=$need_no;
  $cust_no=$_SESSION['cid']=trim($pdd->cid);
  if($pdd->pdd_type==''){$pdd->cid=='C00001';$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];
  if(strpos($_GET['lot_no'],"HIC")>0){$pdd->cid='C00000';$cust_name=get_cust_name($pdd->cid);$cust_no=$pdd->cid;}
  if ($cust_no==''){
	  $_SESSION['cid']=$_SESSION['select_cust']='C00001';
  		$_SESSION['tmp1']=1;
  }
  echo '客戶：'.get_cust_name($_SESSION['cid'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['cid']."</br>";
  echo "表單：".$table."</br>";
  $_SESSION['table']=$table;
	echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
  $_SESSION['select_cust']=$cust_no;
  
  }
  
  if (trim($pdd->pdd_type)=='LY')
  {
	if($pdd->cid=='C00001'){$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];}
	if($pdd->cid==''){$pdd->cid='C00001';}
	echo '客戶：'.get_cust_name($pdd->cid)."&nbsp;&nbsp;&nbsp;&nbsp;".$pdd->cid."</br>";
	echo "表單：".$table."</br>";
	echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
  	$_SESSION['select_cust']=$cust_no;
  } 
  elseif((trim($pdd->pdd_type)=='BTL') or (trim($pdd->pdd_type)=='DM'))
  {
	$query="SELECT   distinct     FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO, 
                          CUSTOMER_DATA.CTD_CUST_NAME
			FROM             FILLPLAN_DRUM_CUSTOMER INNER JOIN
                          CUSTOMER_DATA ON 
                          FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
			WHERE         (FDM_LOT_NO = '".$_GET['lot_no']."')";
	$result=mssql_query($query);  
	$numRows = mssql_num_rows($result);
	if($_SESSION['dm_cid']<>''){$_SESSION['select_cust']=$_SESSION['dm_cid'];}
	if($numRows>1)
	{
		echo '選擇客戶:<select name="co0" id="co0">';
		while($row=mssql_fetch_array($result))
		{
			echo '<option value="'.$row['CTD_CUST_NO'].'">'.$row['CTD_CUST_NO']."--".$row['CTD_CUST_NAME'].'</option>';
			$_SESSION['tmp1']=3;
		}
		echo '</select>';
		echo '<input type="submit" name="select_cust" id="select_cust" value="取得客規" /></br>';
		if($_SESSION['select_cust']=='C00000'){$_SESSION['select_cust']='C00001';}
		echo '客戶：'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</br>";
		echo "表單：".$table."</br>";
		echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
	}
	elseif($numRows==1)
	{
		$_SESSION['tmp1']=4;
		while($row=mssql_fetch_array($result))
		{
			$_SESSION['select_cust']=$row['CTD_CUST_NO'];
		}
		if($_SESSION['select_cust']=='C00000'){$_SESSION['select_cust']='C00001';}
			echo '客戶：'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</br>";
			echo "表單：".$table."</br>";
			echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
	}
	elseif($numRows<1)
	{
			$_SESSION['tmp1']=5;
			$_SESSION['cid']=$_SESSION['select_cust']='C00001';
			echo '客戶：'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</br>";
			echo "表單：".$table."</br>";
			echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
	}
  }
  ?>
  </td>
  <td><?php $itemunit=showcust_spec($_SESSION['select_cust'],$_GET['pdd_prod_no'],$_GET['ani_groupname']) ; ?>
  <p>&nbsp;</p></td></tr>
  </table>
  </br>  
<span class="a18">報告內容:</span>

    <?php
session_start();
include ("../connections/conn.php");
$query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result = mssql_query($query);
echo '<table width="1200" border="1"><tr bgcolor="#CCCCCC" >';
echo '<td width="30">列印</td>';
echo '<td width="30">桶號</td>';
while($row = mssql_fetch_array($result)){
	$i++;
	if (($row['COLUMN_NAME']=='TestDate') || ($row['COLUMN_NAME']=='CHK1') || ($row['COLUMN_NAME']=='CHK2') || ($row['COLUMN_NAME']=='CHK3') || ($row['COLUMN_NAME']=='AnalyzeTime') ||
	($row['COLUMN_NAME']=='CHK4') || ($row['COLUMN_NAME']=='CHK5') || ($row['COLUMN_NAME']=='CHK6') || ($row['COLUMN_NAME']=='CHK7') || ($row['COLUMN_NAME']=='CHK8') || 
	($row['COLUMN_NAME']=='SerialNo') || ($row['COLUMN_NAME']=='AnaManager') || ($row['COLUMN_NAME']=='CHK9') || ($row['COLUMN_NAME']=='CHK10') || ($row['COLUMN_NAME']=='CHK11')
	 || ($row['COLUMN_NAME']=='CHK12'))
	{
	}
	else{
		$clo=new ani_excel;
		$clo->table=$table;
		$clo->pid=$_GET['pdd_prod_no'];
		$clo->item=$row['COLUMN_NAME'];
		$clo->get();
		$id=$clo->id;
		$disc=$_SESSION['disc'.$clo->item]=$clo->disc;
		if($clo->disc=='x'){$disc='X';}
	if($disc<>'X'){
		
			$str=$str."[".$row['COLUMN_NAME']."], ";
			if(($clo->disc!='NULL') and ($clo->disc!='')){
			echo '<td>'.$clo->disc.'</td>';}
			else{echo '<td>'.$row['COLUMN_NAME'].'</td>';}
			}
		
}}
echo '<td>序號</td>';
echo '<td>檢驗日期</td>';
echo '<td>前端處理人員</td></tr>';
$query="SELECT          ".substr($str,0,-2).",[SerialNo],[TestDate],[AnalyzeTime]
FROM              ".$table." 
WHERE          (LotNo  = '".$_GET['lotdata']."') order by SerialNo";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$field= mssql_num_fields($result);
while ($row=mssql_fetch_array($result))
{
	$auth=new auth_user;
	$auth->cbt_no='5-03';
	$auth->uid=$_SESSION['uid'];
	$auth->user_auth_group();
	$auth->capability();
	$auth->keyin_isfit();
	
	$analyze_1st=new analyze_first;
	$analyze_1st->create_time=$row['AnalyzeTime'];
	$analyze_1st->lot_no=$row['LotNo'];
	$analyze_1st->sample_no=$row['SampleNo'];
	$analyze_1st->get();
	echo '<tr>';
	$newtimea=lotno_to_date($_GET['lot_no']).substr($row['AnalyzeTime'],8);
	echo '<td><a href="print_ani_result_all.php?table='.$table.'&AnalyzeTime='.$row['AnalyzeTime'].'&newtimea='.$newtimea.'&lot_no='.$_GET['lotdata'].'&ani_groupname='.$_GET['ani_groupname'].'&pdd_chemical='.$_GET['pdd_chemical'].'&lotdata='.$_GET['lot_no'].'" target="new">列印</a></td>';

	echo '<td>'.getdrumno($row['SampleNo'],$_GET['lotdata']).'</td>';
	for ($i=0;$i<$field-2;$i++)
		{	
			$column_name=mssql_field_name($result,$i);
			list ($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl)=get_test_spec($column_name,$_GET['pdd_prod_no'],$_SESSION['select_cust'],$_GET['ani_groupname']);
	  		if((($stdu<>'') and ($stdl<>'')) and (($row[$i]>$stdu) or ($row[$i]<$stdl))){echo '<td  style="background-color:#FF66CC;"';}
			elseif(($row[$i]==2 or $row[$i]==3) and $_GET['ani_groupname']=='INHP'){  ;}
			elseif(($row[$i]>$stdu) and ($row[$i]<$usl) and $stdu<>'') {echo '<td  style="background-color:yellow;" ';}
			elseif(($row[$i]<$lsl) and ($row[$i]<>'NULL') and ($spec=='') and ($usl=='') and ($lsl<>'')){echo '<td  style="background-color:#FF66CC;"';}
	  		elseif(($row[$i]>$spec) and ($row[$i]<>'NULL') and ($spec<>NULL)){echo '<td  style="background-color:#FF66CC;"';}
			if(($row[$i]>$stdu) and ($row[$i]<$stdl) and ($spec<>NULL)){echo '<td  style="background-color:#FF66CC;"';}
	 		else{echo '<td style="background-color:#FFFFFF;"';}
			if(trim($row[$i])==trim($_GET['lotdata'])){$row[$i]=$_GET['lot_no'];}

			echo ' >'.$row[$i].'</td>';
		}
	echo "<td>".std($newtimea)."</td>";
	echo "<td>".get_uname($analyze_1st->first)."</td>";
	echo "</tr>";
}
?>
  </table> 
</br>
</form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];



if (isset($_POST['checksam'])){
	$_SESSION['smpno']=$_POST['SampleNo'];
	$query="SELECT  distinct       SMA_DRUMNO
FROM             dbo.Sample_All
WHERE         (SMA_LOT = '".$_GET['lot_no']."') and (SMA_ID='".$_POST['SampleNo']."')";
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	$_SESSION['drumno']= $row['SMA_DRUMNO'];
}
refresh();
}

//refresh();


function getdrumno($smpno,$lotno)
{
	$query="SELECT DISTINCT SMA_DRUMNO
FROM             dbo.Sample_All
WHERE         (SMA_ID = '".$smpno."') AND (SMA_LOT = '".$lotno."')";
$result=mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
while($row=mssql_fetch_array($result)){
		if($row['ISREWORK']<>0){$t='(再分析)';}
		return $row['SMA_DRUMNO'].$t;
	}}
	else{ return getdrumst($smpno,$lotno);}
}

function getdrumst($smpno,$lotno){
	$query="SELECT DISTINCT SMA_DRUMNO
FROM             dbo.Sample_All
WHERE         (SMA_ID = '".$smpno."') AND (SMA_LOT = '".$lotno."') AND (ISREWORK IS NULL)";
$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		return "再分析：".$row['SMA_DRUMNO'];
	}
}
?>
