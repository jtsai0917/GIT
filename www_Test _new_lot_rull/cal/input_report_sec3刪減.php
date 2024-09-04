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
$_SESSION['QQERY']=$query;
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
?>
<style type="text/css">
.red {
	color: #F00;
}
.blue {
	color: #00F;
}
#form1 table tr td p {
	color: #F00;
	font-size: 16px;
}
</style>

<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>輸入分析結果</title>
<style type="text/css">
.centet {
	text-align: center;
}
#form1 table tr td {
	text-align: center;
}
dd {
	text-align: left;
}
#form1 table tr td {
	text-align: left;
}
.a18 {
	font-size: 18px;
}
#form1 table tr td .red1 {
	color: #F00;
}
</style>





<form action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p>
    <input type="hidden" name="operator" id="operator" value="" />
  </p>
  <table width="1200" border="1"><tr><td width="400">
  <?php 
  $pdd=new get_from_lot_no;
  $pdd->lid=$_GET['lot_no'];
  $pdd->cid();
  $pdd->ani();
  $need_no=$pdd->needno;
  $_SESSION['needno']=199;
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
	echo '客戶：'.get_cust_name($pdd->cid)."&nbsp;&nbsp;&nbsp;&nbsp;".$cust_no."</br>";
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
  <p>請務必填寫正確的樣品瓶號碼</p></td></tr></table>
  <table width="1200" border="1">
    <tr>
    </tr>


</table>
  </br>  
<span class="a18">報告內容:</span>
    <?php
session_start();
include ("../connections/conn.php");
$query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result = mssql_query($query);
echo '<table width="1200" border="1"><tr>';
echo '<td width="30">列印</td>';
echo '<td width="30">編輯</td><td width="14">桶號</td>';
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
echo '<td>分析時間</td>';
echo '<td>前端處理人員</td></tr>';
$query="SELECT          ".substr($str,0,-2).",[SerialNo], [AnalyzeTime]
FROM              ".$table." 
WHERE          (LotNo  Like '".$_GET['lot_no']."%') order by SerialNo";
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
	echo '<tr>';
	echo '<td><a href="print_ani_result.php?table='.$table.'&AnalyzeTime='.$row['AnalyzeTime'].'&lot_no='.$row['LotNo'].'&ani_groupname='.$_GET['ani_groupname'].'&pdd_chemical='.$_GET['pdd_chemical'].'" target="new">列印</a></td>';
	if((($_SESSION['uname']==$row['Tester']) or ($auth->keyin_ok=='1')) and (($row['AnalyzeTime']+3000000)>date("YmdHis"))){echo '<td width="30"><a href="index.php?'.$urllast.'&smpno='.$row['SampleNo'].'&Ok='.$row['Ok'].'&ani_time='.$row['AnalyzeTime'].'&id='.$id.'&url=edit_report&lot_no='.$row['LotNo'].'" target="new">編輯</a></td>';}
	else{echo '<td></td>';}
	echo '<td>'.getdrumno($row['SampleNo'],$row['LotNo']).'</td>';
	for ($i=0;$i<$field;$i++)
		{	
			$column_name=mssql_field_name($result,$i);
			list ($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl)=get_test_spec($column_name,$_GET['pdd_prod_no'],$_SESSION['select_cust'],$_GET['ani_groupname']);
			if($column_name=='PP01'){$_SESSION['ss']=array($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl);}			
	  		if((($stdu<>'') and ($stdl<>'')) and (($row[$i]>$stdu) or ($row[$i]<$stdl))){echo '<td  style="background-color:#FF66CC;"';}
			elseif(($row[$i]>$stdu) and ($row[$i]<$usl) and $stdu<>'') {echo '<td  style="background-color:yellow;" ';}
			elseif(($row[$i]<$lsl) and ($row[$i]<>'NULL') and ($spec=='') and ($usl=='') and ($lsl<>'')){echo '<td  style="background-color:#FF66CC;"';}
	  		elseif(($row[$i]>$spec) and ($row[$i]<>'NULL') and ($spec<>NULL)){echo '<td  style="background-color:#FF66CC;"';}
			if(($row[$i]>$stdu) and ($row[$i]<$stdl) and ($spec<>NULL)){echo '<td  style="background-color:#FF66CC;"';}
	 		else{echo '<td style="background-color:#FFFFFF;"';}
			echo ' >'.$row[$i].'</td>';
		}
	$analyze_1st=new analyze_first;
	$analyze_1st->create_time=$row['AnalyzeTime'];
	$analyze_1st->lot_no=$row['LotNo'];
	$analyze_1st->sample_no=$row['SampleNo'];
	$analyze_1st->get();
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

if(isset($_POST['timereport']))
{
	auth('5-04',$_SESSION['aut']);

	
	$target=$_POST["selt"];
	$mys=implode(",",$target);
    $num=count($target);
	for ($x=0; $x<$num; $x++)
	{
	$find="select FILE_UPLOAD_TIME from FILE_REPORTS where ([index] = ".$target[$x].")";	
		$_SESSION['find']=$find;
		$resultf = mssql_query($find);
			$numRowsf = mssql_num_rows($resultf);			
			while($rowf = mssql_fetch_array($resultf))
			{
				$analyze=$rowf['FILE_UPLOAD_TIME'];
				
			}	
			
	$chkre="select *  from analyze_first where lot_no='".$_GET['lot_no']."' and  create_time='".$analyze."' ";
	$resultre = mssql_query($chkre);
			$numRowsre = mssql_num_rows($resultre);	
	$_SESSION['re0']==$numRowsre;
	if($numRowsre==0)
	{
	$query="INSERT INTO dbo.analyze_first
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
		VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$analyze."', 'Y', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";
		$_SESSION['query']=$query;
	$result=mssql_query($query);
	}
	}
}

if(isset($_POST["upload"]))
{  if($_POST['ABCD']=='Z')
	{
		echo "請選擇ABCD";
	}
	if($_POST['ABCD']!='Z')
	{
	$_GET['lot_no']=$_GET['lot_no'].$_POST['ABCD'];
	$_SESSION['getlot']=$_GET['lot_no'];
	//$upfile=substr($_GET['lot_no'],0,-1);
		$path="../reports/".$_GET['ani_groupname']."/";
		$filename=$_GET['lot_no']."_".date("YmdHis").$_FILES["file"]["name"];
		$fullpath="/reports/".$_GET['ani_groupname']."/".$filename;
		
		if (file_exists($path)){
		
			} 
		else {
			mkdir($path);
			}
		move_uploaded_file($_FILES["file"]["tmp_name"],$path.$filename);
		$_SESSION['uptime']=date("YmdHis");
		$query="INSERT INTO dbo.FILE_REPORTS
				(FILE_PATH, FILE_UPLOAD_TIME, ANI_GROUP, FILE_PID, FILE_UID, FILE_LOT_NO, FILE_CID, FILE_DISC, FILE_NAME, FORM_ID)
				VALUES          ('".$fullpath."', '".$_SESSION['uptime']."', '".$_GET['ani_groupname']."', '".$_GET['pdd_prod_no']."', '".$_SESSION['uid']."',
				 '".$_GET['lot_no']."', '".$cust_no."', '".$_POST['disc']."', '".$_FILES["file"]["name"]."', '".$_GET['efm']."')";
		$result = mssql_query($query);	
		$_SESSION['file']=$path.$filename;
		
			
	/*$_SESSION['createtime']=date("YmdHis");
	$query="INSERT INTO dbo.analyze_first
							  (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
	VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['createtime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";
	$result=mssql_query($query);*/
		
		scriptconfirm("是否上傳數據?","只上傳報告‧",$_SESSION['redir']."1");
	//	my_msg("是否自動上傳數據",$_SESSION['redir']."1");
	}
}

if(isset($_POST["delete"]))
{   


	$target=$_POST["selt"];
	$mys=implode(",",$target);
    $num=count($target);
	for ($x=0; $x<$num; $x++)
	{
		
		$find="select FILE_UPLOAD_TIME from FILE_REPORTS where ([index] = ".$target[$x].")";	
		$_SESSION['find']=$find;
		$resultf = mssql_query($find);
			$numRowsf = mssql_num_rows($resultf);			
			while($rowf = mssql_fetch_array($resultf))
			{
				$ind=$rowf['FILE_UPLOAD_TIME'];
				
			}	
			$querygg="delete from analyze_first where 
				(lot_no='".$_GET['lot_no']."') and (create_time='".$ind."')";
				$_SESSION['gg']=$querygg;
			$resultgg = mssql_query($querygg);
		$query="select * from dbo.FILE_REPORTS
				where ([index] = ".$target[$x].")";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			$file="..".$row['FILE_PATH'];
		}
		unlink($file);	
		$query="DELETE FROM dbo.FILE_REPORTS
				WHERE          ([index] = ".$target[$x].")";
					$result = mssql_query($query);
					$ulink=$_SERVER['REQUEST_URI'];
		
			
					
	/*echo '<script>document.location.href="'.$ulink.'";</script>';		*/
	}
	
	
}

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

if (isset($_POST["submit"])) 
{   session_start();
			//////////檢查樣品瓶號///////////
	if ($_POST['Ok']=="on"){$_POST['Ok']=1;}
	else {$_POST['Ok']=0;}
	//////////取得欄位名///////////
	
	include ("../connections/conn.php");
$_SESSION['createtime']=date("YmdHis");
$query="INSERT INTO dbo.analyze_first
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
VALUES         ('".$_POST['LotNo']."', '".$_POST['SampleNo']."', '".$_SESSION['createtime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";
$result=mssql_query($query);


/*
$query="UPDATE          QC_LotData
SET                   state = 'n'
WHERE          (Chemical = '".$_GET['pdd_chemical']."') AND (LotNo = '".$_GET['lot_no']."')";
$result=mssql_query($query);
*/

$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."') order by DATA_TYPE";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$q1="INSERT INTO ".$table." (";
$q2=" VALUES     (";
while($row = mssql_fetch_array($result)){
	if(($row['COLUMN_NAME']=='B') and $_GET['pdd_chemical']=='H2O'){
		write_ttb(1,$_POST['SampleNo'],$_POST['Ok'],$_POST['Tester'],$_POST['LotNo'],"TLEQA9110101",$_POST['B'],$_POST['Operator'],$_SESSION['createtime']);
		}
	if($row['COLUMN_NAME']=='TestDate'){
			$q1.="[".$row['COLUMN_NAME']."],";
            $q2.="'".date("Y-m-d")."',";
			}
	elseif($row['COLUMN_NAME']=='AnaManager'){
			;}
	elseif($row['DATA_TYPE']=='int') {
		if(trim($row['COLUMN_NAME'])=='SerialNo'){
			$q1.="[".$row['COLUMN_NAME']."],";
			$_SESSION['tble']=$table;
			$_SESSION['lot_no']=$_POST['LotNo'];
			$q2.=new_analyze_sn($table,$_POST['LotNo']).",";
		}else{
			$q1.="[".$row['COLUMN_NAME']."],";
            $q2.=$_POST[$row['COLUMN_NAME']].",";}
			}
	elseif($row['DATA_TYPE']=='float') {		
	$dift=new read_table;
	$dift->table=$table;
	$dift->LotNo=$_POST['LotNo'];
	$dift->row_name=$row['COLUMN_NAME'];
	$dift->read();
	$from_lot=new get_from_lot_no;
	$from_lot->lid=$_POST['LotNo'];
	$from_lot->ani();
	$from_lot->pdd_type;
	$_SESSION['ftb']=$from_lot->pdd_type;
	if(($_POST[$row['COLUMN_NAME']]>0.001) and ($_POST[$row['COLUMN_NAME']]<>'') and ($dift->value>0.001) and ($dift->value<>''))
	{
	if((($_POST[$row['COLUMN_NAME']]/($dift->value)>=10 or ($_POST[$row['COLUMN_NAME']]/($dift->value))<=0.1)) and ($from_lot->pdd_type=='LY'))
	{
		$diff=new difference_too_large;
		$diff->LotNo=$_POST['LotNo'];
		$diff->table=$table;
		$diff->uid=$_SESSION['uid'];
		$diff->create_time=date("YmdHi");
		$diff->item=$row['COLUMN_NAME'];
		$diff->ani_group=$_GET['ani_groupname'];
		$diff->write();		
	}}
			$q1.="[".$row['COLUMN_NAME']."],";
			if(($_POST[$row['COLUMN_NAME']]=='') or ($_POST[$row['COLUMN_NAME']]=='NULL'))
				{
					$q2.="NULL,";
				}
			else{
					$q2.=$_POST[$row['COLUMN_NAME']].",";
				}
			}
	elseif($row['DATA_TYPE']=='smalldatetime') {
			if ($_POST[$row['COLUMN_NAME']]==''){$_POST[$row['COLUMN_NAME']] = '';}
			if ($_POST[$row['COLUMN_NAME']]=='NULL'){$_POST[$row['COLUMN_NAME']] = '';}
			$q1.="[".$row['COLUMN_NAME']."],";
            $q2.="'".short_date($_POST[$row['COLUMN_NAME']])."',";
			}
	elseif($row['COLUMN_NAME']=='AnalyzeTime'){
			$q1.="[".$row['COLUMN_NAME']."],";
        	$q2.="'".$_SESSION['createtime']."',";
			}
	elseif(($row['COLUMN_NAME']=='B') or ($row['COLUMN_NAME']=='F') or ($row['COLUMN_NAME']=='Water')){
			if ($_POST[$row['COLUMN_NAME']]==''){$_POST[$row['COLUMN_NAME']] = NULL;}
			$q1.="[".$row['COLUMN_NAME']."],";
        	$q2.=$_POST[$row['COLUMN_NAME']].",";
			}
	else{
			$q1.="[".$row['COLUMN_NAME']."],";
    		$q2.="'".$_POST[$row['COLUMN_NAME']]."',";
	}
}
$q1=substr($q1,0,-1);
$q2=substr($q2,0,-1);
$qx=$q1.") 
".$q2.")";
$query=$qx;
$_SESSION['QX']=$query;
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } else {	//決定是否更改pre_spec狀態
			

/*
updateTM($table,$_GET['lot_no'],$cust_no,$_GET['ani_groupname'],$_GET['pdd_prod_no'],$_POST['SampleNo'],$_GET['pdd_chemical']);
$message = "新增了一筆資料";
echo "<script type='text/javascript'>alert('$message');</script>";
 echo '<script>document.location.href="'.$_SESSION['lasturl'].'";</script>';
*/
}   //////// END _ TM /////////

}	
//refresh();


function getdrumno($smpno,$lotno)
{
	$query="SELECT DISTINCT SMA_DRUMNO
FROM             dbo.Sample_All
WHERE         (SMA_ID = '".$smpno."') AND (SMA_LOT = '".$lotno."') AND (ISREWORK = 0)";
$result=mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
while($row=mssql_fetch_array($result)){
		return $row['SMA_DRUMNO'];
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

if(isset($_POST['chdrumno']))
{
	$query="UPDATE        dbo.Sample_All
SET                  SMA_DRUMNO = '".$_POST['drumno2']."'
WHERE         (SMA_ID = '".$_POST['SampleNo']."') AND (SMA_LOT = '".$_GET['lot_no']."')";
$result=mssql_query($query);
refresh();
}
if(isset($_POST['select_cust'])){
	$_SESSION['dm_cid']=$_POST['co0'];
	refresh();
}

if(isset($_POST['an_first'])){
$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result=mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
while($row=mssql_fetch_array($result)){
	$_SESSION[$row['COLUMN_NAME']]=$_POST[$row['COLUMN_NAME']];
	jumpto("./index.php?url=select_user");
}
}
}

if(isset($_POST['button2'])){
button2();
}

if (isset($_POST["leave"])) 
{
	  keep_session();
	  jumpto($_SESSION['lasturl']);
}

if (isset($_POST["x5"])) 
{
	unset($_SESSION['userid']);
	unset($_SESSION['username']);
	refresh();
}

function button2()
{
$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$_SESSION['table']."')";
$result=mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0)
	{
	while($row=mssql_fetch_array($result))
		{
		$_SESSION[$row['COLUMN_NAME']."1"]=$_POST[$row['COLUMN_NAME']];
		}
	}
}

function write_ttb($sn,$smp_no,$ok,$uid,$lot,$table,$b,$op,$ani_time)
{
	$query="INSERT INTO ".$table."
                            (TestDate, CHK1, CHK2, LotNo, SerialNo, SampleNo, B, CHK3, CHK4, Ok, Tester, Operator, AnalyzeTime)
			VALUES          ('".date("Y-m-d")."',1,1,'".$lot."','".$sn."','".$smp_no."',".$b.",1,1,'".$ok."','".$uid."','".$op."','".$ani_time."')";
			$_SESSION['TMPQUERRY']=$query;
	$result=mssql_query($query);
}
function auth1($aid,$emp_aut_group,$chk1)
{
		session_start();
	$chk1=0;
	if($emp_aut_group=='')
	{
		$query="SELECT          EMPLOYEE_AUTHORITY.*
				FROM              EMPLOYEE_AUTHORITY
				WHERE          (EMP_NO = '".$_SESSION['uid']."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
		{
			$emp_aut_group=$row['AUT_GROUP'];	
		}
	}
	
	$query="select * from CAPABILITY_DATA where (CBT_NO='".$aid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		$str=$row['CBT_KEYIN'];	
	}
	$aa=explode(';',$str);
	for($i=0;$i<count($aa);$i++){
		$aa[$i].=";";
		if(preg_match("/$aa[$i]/i", $emp_aut_group)){$chk1=1;}
	}
	if($chk1==1)
	{
		$_SESSION['ck']=1;
	}
}

?>
