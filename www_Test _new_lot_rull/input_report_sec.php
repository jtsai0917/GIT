<!DOCTYPE html>
<html>
<body>
<script>
function ShowName(column_id)
		{ 	
			var url="set_session.php?id="+column_id+"&value=";
			var valu=document.getElementById(column_id).value;
			window.open(url+valu,'setsession',config='height=0,width=0');
			window.location.reload();			
		}
function fresh()
	{
		document.getElementById("form1").submit();
		window.location.reload();
	}
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
$_SESSION['redir']=$rev=$_SERVER['REQUEST_URI'];	
$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM,AnalyzeItem.ANI_NICKNAME, AnalyzeItem.ANI_FULLNAME
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.ELF_FORM = '".$_GET['efm']."') AND (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";
//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
$_SESSION['table']=$table=$row['ELF_FORM'];
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
while($row = mssql_fetch_array($result)){
	if($row['CTD_CUST_NO']<>''){$cust_no=$row['CTD_CUST_NO'];$cust_name=$row['CTD_CUST_NAME'];}
	elseif(strpos($_GET['lot_no'],"HIC")>0){$cust_no='C00001';$cust_name='TYS Internal';
	}
	else{$cust_no='C00000';$cust_name='TYS';}
}
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
  $_SESSION['cid']=trim($pdd->cid);
  if($pdd->pdd_type==''){$pdd->cid=='C00001';$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];
  if(strpos($_GET['lot_no'],"HIC")>0){$pdd->cid='C00000';$cust_name=get_cust_name($pdd->cid);$cust_no=$pdd->cid;}
  $cust_no=$_SESSION['cid']=$pdd->cid;
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
  
  if (trim($pdd->pdd_type)=='LY'){
	  if($pdd->cid=='C00001'){$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];}
  echo '客戶：'.get_cust_name($pdd->cid)."&nbsp;&nbsp;&nbsp;&nbsp;".$cust_no."</br>";
  echo "表單：".$table."</br>";
	echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
  $_SESSION['select_cust']=$cust_no;
  } 
  elseif((trim($pdd->pdd_type)=='BTL') or (trim($pdd->pdd_type)=='DM')){
	$query="SELECT        FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO, 
                          CUSTOMER_DATA.CTD_CUST_NAME
FROM             FILLPLAN_DRUM_CUSTOMER INNER JOIN
                          CUSTOMER_DATA ON 
                          FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
WHERE         (FDM_LOT_NO = '".$_GET['lot_no']."')";
$result=mssql_query($query);  
$numRows = mssql_num_rows($result);
if($numRows>1){
	  echo '選擇客戶:<select name="co0" id="co0">';
while($row=mssql_fetch_array($result)){
		echo '<option value="'.$row['CTD_CUST_NO'].'">'.$row['CTD_CUST_NO']."--".$row['CTD_CUST_NAME'].'</option>';
		$cust_no=$_SESSION['select_cust']=$row['CTD_CUST_NO'];
		$_SESSION['tmp1']=3;
	}
	echo '</select>';
	echo '<input type="submit" name="select_cust" id="select_cust" value="取得客規" /></br>';
	echo '客戶：'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</br>";
	echo "表單：".$table."</br>";
	echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
  }
  elseif($numRows==1){
	  while($row=mssql_fetch_array($result)){
		  $_SESSION['tmp1']=4;
	  $cust_no=$_SESSION['select_cust']=$row['CTD_CUST_NO'];
			echo '客戶：'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</br>";
			echo "表單：".$table."</br>";
			echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
	  }}
	elseif($numRows<1){
			$_SESSION['tmp1']=5;
			$cust_no==$_SESSION['select_cust']=$pdd->cid;
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
    <tr class="centet">
      <td width="200" bgcolor="#CCCCCC">日期: <?php echo date("YmdHis");?></td>
      <td width="200" bgcolor="#CCCCCC">Lot NO：
      <input name="LotNo" type="text" id="LotNo" value="<?php echo $_GET['lot_no']?>" size="14" readonly /></td>
      <td width="200" bgcolor="#CCCCCC"> 序號：
      <input name="SerialNo" type="text" id="SerialNo" value="<?php 
	  $query="SELECT          ".$table.".*
FROM              ".$table." 
WHERE          (LotNo  = '".$_GET['lot_no']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$field= mssql_num_fields($result);
$sn=$numRows+1;
echo $sn;
	   ?>" size="4" readonly /></td></tr><tr>
      <td width="200" bgcolor="#CCCCCC">取樣瓶號碼：
      <input name="SampleNo" type="text" id="SampleNo" value="<?php echo $_SESSION['smpno'];?>" size="6" />
      <input type="submit" name="checksam" id="checksam" value="取得桶號" />
      <select name="drumno2" id="drumno2">
<?php
$gn=new get_from_lot_no;
$gn->lid=$_GET['lot_no'];
$gn->cid();
$sec=new sample;
$sec->lot_no=$_GET['lot_no'];
$sec->selections();
echo '</select>';
?>    
<input type="submit" name="chdrumno" id="chdrumno" value="修改桶號" onClick="return confirm('確定修改?')"/>
<td width="200" bgcolor="#CCCCCC">測試人員：
      <input name="Tester" type="text" id="Tester" value="<?php echo $_SESSION['uname']?>" size="10" readonly />      
      <td width="200" bgcolor="#CCCCCC"> 前端處理人員:
      <input name="x5" type="submit" value="X" />
      <input name="Operator" type="hidden" id="Operator" value="<?php echo $_GET['operator'];?>" size="10" readonly />
      <input name="op" type="text" value="<?php echo $_SESSION['userid'];?>" size="10"  />
      <input type="submit" name="an_first" id="button" value=" 工號  " onClick="window.open('./index.php?url=select_user', '_self');" />
      <input name="opname" type="text" value="<?php echo $_SESSION['username'];?>" size="10"  />

  </table>
	<table width="1200" border="1">
    <tr>
    <td width="60" bgcolor="#CCCCCC">項目</td>
    <td width="120" bgcolor="#CCCCCC">輸入值<span class="red1">(不可為空白)</span></td>
    <td width="100" bgcolor="#CCCCCC">客規</td>
    <td width="50" bgcolor="#CCCCCC">DL</td>
    <td width="50" bgcolor="#CCCCCC">USL</td>
	<td width="50" bgcolor="#CCCCCC">LSL</td>
    <td width="100" bgcolor="#CCCCCC">客戶再分析標準\定量下限</td>
    </tr> 
    <tr class="centet">
<?php
include("../connections/conn.php");
$query="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$i=0;
while($row = mssql_fetch_array($result))
{
if (($row['COLUMN_NAME']!='LotNo') && ($row['COLUMN_NAME']!='CHK5') && ($row['COLUMN_NAME']!='CHK6') && 
($row['COLUMN_NAME']!='CHK7') && ($row['COLUMN_NAME']!='CHK8') && ($row['COLUMN_NAME']!='CHK1') && ($row['COLUMN_NAME']!='CHK9') && ($row['COLUMN_NAME']!='CHK10') && ($row['COLUMN_NAME']!='CHK11') && ($row['COLUMN_NAME']!='CHK12') && ($row['COLUMN_NAME']!='CHK2') && ($row['COLUMN_NAME']!=
'CHK3') && ($row['COLUMN_NAME']!='CHK4') && ($row['COLUMN_NAME']!='Ok') && ($row['COLUMN_NAME']!='AnalyzeTime') && ($row['COLUMN_NAME']!='AnalyzeTime') && 
($row['COLUMN_NAME']!='AnaManager') && ($row['COLUMN_NAME']!='Tester') && ($row['COLUMN_NAME']!='Operator') && ($row['COLUMN_NAME']!='SampleNo') && 
($row['COLUMN_NAME']!='SerialNo') && ($row['COLUMN_NAME']!='TestDate'))
	{
		$clo=new ani_excel;
		$clo->table=$table;
		$clo->pid=$_GET['pdd_prod_no'];
		$clo->item=$row['COLUMN_NAME'];
		$clo->get();
		$disc=$_SESSION['disc'.$clo->item]=$clo->disc;
		if($clo->disc=='x'){$disc='X';}
	if($disc<>'X')
	{echo '<tr class="centet">';
		$i=$i+1;
		if($disc==''){$showitem=$row['COLUMN_NAME'];}
		elseif($disc=='NULL'){$showitem=$row['COLUMN_NAME'];}
		else {$showitem=$disc;}
		list ($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl)=get_test_spec($row['COLUMN_NAME'],$_GET['pdd_prod_no'],$_SESSION['select_cust'],$_GET['ani_groupname']);
		if($ps=='ppm'){$value=$row['COLUMN_NAME']/1000;}
		if($ps<>''){$_SESSION['ps']=$ps;}
		echo '<td><font color="#0000FF">'.$showitem.'</font>：</td><td>';
      	echo '<input name="'.$row['COLUMN_NAME'].'" id="'.$row['COLUMN_NAME'].'" type="text"  ';	  
	  	if($_SESSION[$row['COLUMN_NAME']."1"]<>''){$value=$_SESSION[$row['COLUMN_NAME']."1"];}
	  	if(($_SESSION[$row['COLUMN_NAME']."1"]>$stdu) and ($_SESSION[$row['COLUMN_NAME']."1"]<$spec)) {echo ' style="background-color:yellow;"';}
	  	elseif(($_SESSION[$row['COLUMN_NAME']."1"]>$spec) and ($_SESSION[$row['COLUMN_NAME']."1"]<>'NULL') and ($spec<>NULL)){echo ' style="background-color:red;"';}
	  	elseif($spec==NULL){echo ' style="background-color:white;"';}
	 	else{echo ' style="background-color:white;"';}
	 
	  if(($spec=='')||($spec=='<')){echo ' value="NULL" style="background-color:white;"';}
	  elseif((($_GET['ani_groupname']=='M13') or ($_GET['ani_groupname']=='M21')) and ($_SESSION[$row['COLUMN_NAME']."1"]=='')){$value="0.000001";}
//	  echo ' value="'.$value.'"  onblur="'.'ShowName('."'".$row['COLUMN_NAME']."'".')"';
 	echo ' value="'.$value.'"';
	echo ' size="6"/>';
	echo "(".$ps.")";
    echo '  </td>';
	   		$lsl=trim($lsl);
	   		if(($lsl==NULL) || ($lsl=='')){
				$spec=$spec.">";
				$showagain=$stdu.">\\".$dl;
			}
			else{
			$spec=$lsl."<".$usl;
			$showagain=$stdl."<".$stdu."\\".$dl;
			}
	 
	 ?>
       <td><?php echo $spec ;?></td><td><?php echo $dl;?></td><td><?php echo $usl;?></td><td><?php echo $lsl;?></td><td><?php echo $showagain;?></td></tr>
<?php 
	}}
} ?>

</table>
<table width="1200" border="1">
    <tr class="centet" >
      <td width="600" align="center"><font color="#FF0000">合否判定</font>
      <input type="checkbox" name="Ok" id="Ok" onBlur="fresh()" >   &nbsp;&nbsp;&nbsp;&nbsp; 
      <input type="hidden" name="CHK1" id="CHK1" value="1" />
      <input type="hidden" name="CHK2" id="CHK2" value="1" />
      <input type="hidden" name="CHK3" id="CHK3" value="1" />
      <input type="hidden" name="CHK4" id="CHK4" value="1" />
      <input type="hidden" name="CHK5" id="CHK5" value="1" />
      <input type="hidden" name="CHK6" id="CHK6" value="1" />
      <input type="hidden" name="CHK7" id="CHK7" value="1" />
      <input type="hidden" name="CHK8" id="CHK8" value="1" />
      <input type="hidden" name="AnaManager" id="AnaManager" value="NULL" />
      <input type="hidden" name="TestDate" id="TestDate" value="<?php echo $dt." 00:00:00";?>" />
      <input type="hidden" name="AnalyzeTime" id="AnalyzeTime" value="<?php echo $_SESSION['createtime']; ?>" /></td>
      <td width="600" align="center">
      <input type="submit" name="submit" id="submit" value=" 新  增 " onClick="return confirm('確定新增?')"/>
      <input type="hidden" name="MM_insert" value="form1">

      <input type="submit" name="leave" id="button" value=" 離開  " onClick="window.open('<?php echo $_SESSION['lasturl'];?>', '_self');" />
      <input type="submit" name="button2" id="button2" value=" 重  整 " onClick="window.open('<?php echo $rev;?>','_self');" />
      <input type="button" name="rework" id="rework" value=" 再分析作業 " onClick="window.open('./index.php?url=rework&lot_no=<?php echo $_GET['lot_no'];?>&ani_groupname=<?php echo $_GET['ani_groupname'];?>','_self');" />
      <input type="button" name="define" id="define" value="定義EXCEL上傳欄位" onClick="window.open('../system/index.php?url=defineaniitem_&efm=<?php echo $_GET['efm'];?>&pid=<?php echo $_GET['pdd_prod_no'];?>','_self');" /><?php if ($_GET['ani_groupname']=="TM") {
		  echo '<input type="button" name="TM" id="TM" value="取得TM分析資料" onClick="window.open('."'/cal/index.php?url=ctm&pdd_chemical=".$_GET['pdd_chemical']."&operator=".$_GET['operator']."&ani_groupname=".$_GET['ani_groupname']."&lot_no=".$_GET['lot_no']."&cust_no=".$cust_no."&pid=".$_GET['pdd_prod_no']."', '_self');".'"'.' />';
	  }?></td>
    </tr></table>
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
WHERE          (LotNo  = '".$_GET['lot_no']."') order by SerialNo";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$field= mssql_num_fields($result);
while ($row=mssql_fetch_array($result))
{
	echo '<tr>';
	echo '<td><a href="print_ani_result.php?AnalyzeTime='.$row['AnalyzeTime'].'&lot_no='.$_GET['lot_no'].'&ani_groupname='.$_GET['ani_groupname'].'&pdd_chemical='.$_GET['pdd_chemical'].'" target="new">列印</a></td>';
	if ($row['Tester']==$_SESSION['uname']){echo '<td width="30"><a href="index.php?'.$urllast.'&smpno='.$row['SampleNo'].'&Ok='.$row['Ok'].'&ani_time='.$row['AnalyzeTime'].'&id='.$id.'&url=edit_report" target="new">編輯</a></td>';}
	else{echo '<td></td>';}
	echo '<td>'.getdrumno($row['SampleNo'],$_GET['lot_no']).'</td>';
	for ($i=0;$i<$field;$i++)
		{	
			$column_name=mssql_field_name($result,$i);
			list ($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl)=get_test_spec($column_name,$_GET['pdd_prod_no'],$_SESSION['select_cust'],$_GET['ani_groupname']);
	  		if(($row[$i]>$stdu) and ($row[$i]<$spec)) {echo '<td  style="background-color:#66FFFF;" ';}
	  		elseif(($row[$i]>$spec) and ($row[$i]<>'NULL') and ($spec<>NULL)){echo '<td  style="background-color:#FF66CC;"';}
	 		else{echo '<td style="background-color:#FFFFFF;"';}
			echo ' >'.$row[$i].'</td>';
		}
	$analyze_1st=new analyze_first;
	$analyze_1st->lot_no=$_GET['lot_no'];
	$analyze_1st->sample_no=$row['SampleNo'];
	$analyze_1st->get();
	echo "<td>".get_uname($analyze_1st->first)."</td>";
	echo "</tr>";
}
?>
  </table> 
</br>
<span class="a18">檢驗報告非上傳人員無法刪除</span>
<table border="1" width="1200">
    <tr><td>檢驗報告上傳：
      <input type="file" name="file" id="file" />
      檔案說明：
<label for="disc"></label>
      <input name="disc" type="text" id="disc" size="30" />
      <input type="submit" name="upload" id="upload" value="送出" />   上傳檔名請不要用特殊字元 (EX: # @ ! % & $)
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
    </tr>
    </table>



<?php
include ("../connections/conn.php");
echo '<table width="1200" border="1"><tr>';
echo '<td>序號</td>';
echo '<td>檔名</td>';
echo '<td>上傳時間</td>';
echo '<td>分析群組</td>';
echo '<td>產品編號</td>';
echo '<td>客戶編號</td>';
echo '<td>上傳人員</td>';
echo '<td>Lot NO</td>';
echo '<td width="200">說明</td>';
echo '<td><input type="submit" name="delete" id="delete" value="刪除" /></td>';
echo '</tr>';
$query="SELECT          [index], [FILE_PATH], [FILE_UPLOAD_TIME], [ANI_GROUP], [FILE_PID], [FILE_CID], [FILE_UID], [FILE_LOT_NO], [FILE_DISC], 
                            [FILE_NAME]
							
FROM              FILE_REPORTS
WHERE          (FILE_LOT_NO = '".$_GET['lot_no']."') AND (ANI_GROUP = '".$_GET['ani_groupname']."')";
$result = mssql_query($query);
//echo $query;
while($row = mssql_fetch_array($result)){
	echo '<tr>';
	echo '<td>'.$row['index'].'</td>';
	echo'<td><a href="'.$row['FILE_PATH'].'">'.$row['FILE_NAME'].'</font> </a></td>';
	echo '<td>'.$row['FILE_UPLOAD_TIME'].'</td>';
	echo '<td>'.$row['ANI_GROUP'].'</td>';
	echo '<td>'.$row['FILE_PID'].'</td>';
	echo '<td>'.$row['FILE_CID'].'</td>';
	echo '<td>'.get_uname($row['FILE_UID']).'</td>';
	echo '<td>'.$row['FILE_LOT_NO'].'</td>';
	echo '<td>'.$row['FILE_DISC'].'</td>';
	echo '<td text-align="center">';
	if($_SESSION['uname']==get_uname($row['FILE_UID'])){
	echo '<input type="checkbox" name="selt[]" value="'.$row['index'].'" />';}
	echo '</td></tr>';
}
echo '</table></br>';
?>
</form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];

if(isset($_POST["upload"]))
{   
	$path="../reports/".$_GET['ani_groupname']."/";
	$filename=$_GET['lot_no']."_".date("YmdHis").$_FILES["file"]["name"];
	$fullpath="/reports/".$_GET['ani_groupname']."/".$filename;
	if (file_exists($path)){
	
		} 
	else {
		mkdir($path);
		}
	move_uploaded_file($_FILES["file"]["tmp_name"],$path.$filename);
	$query="INSERT INTO dbo.FILE_REPORTS
           	(FILE_PATH, FILE_UPLOAD_TIME, ANI_GROUP, FILE_PID, FILE_UID, FILE_LOT_NO, FILE_CID, FILE_DISC, FILE_NAME, FORM_ID)
			VALUES          ('".$fullpath."', '".date("YmdHis")."', '".$_GET['ani_groupname']."', '".$_GET['pdd_prod_no']."', '".$_SESSION['uid']."',
			 '".$_GET['lot_no']."', '".$cust_no."', '".$_POST['disc']."', '".$_FILES["file"]["name"]."', '".$_GET['efm']."')";
	$result = mssql_query($query);
	$_SESSION['file']=$path.$filename;
	scriptconfirm("是否上傳數據?","只上傳報告‧",$_SESSION['redir']."1");
//	my_msg("是否自動上傳數據",$_SESSION['redir']."1");
}

if(isset($_POST["delete"]))
{   
	$target=$_POST["selt"];
	$mys=implode(",",$target);
    $num=count($target);
	for ($x=0; $x<$num; $x++)
	{
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
	echo '<script>document.location.href="'.$ulink.'";</script>';		
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
                          (lot_no, sample_no, create_time, ps, create_user,first)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['createtime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."')";
$result=mssql_query($query);

$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."') order by DATA_TYPE";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$q1="INSERT INTO ".$table." (";
$q2=" VALUES     (";
while($row = mssql_fetch_array($result)){
	
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
			$_SESSION['lot_no']=$_GET['lot_no'];
			$q2.=new_analyze_sn($table,$_GET['lot_no']).",";
		}else{
			$q1.="[".$row['COLUMN_NAME']."],";
            $q2.=$_POST[$row['COLUMN_NAME']].",";}
			}
	elseif($row['DATA_TYPE']=='float') {
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
  } else {

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
	$cust_no=$_SESSION['select_cust']=$_POST['co0'];
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
?>
