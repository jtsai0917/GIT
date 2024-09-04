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
//這版2017/3/2只改  編輯時間+RR列印按鈕
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
session_start();
$_SESSION['lot_no']=$_GET['lot_no'];
$urllast=$_SESSION['urllast']=$_SERVER['QUERY_STRING'];
$xx=1;

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

if (isset($_POST["refresh"])){
$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."') order by DATA_TYPE";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){

			$_SESSION[$row['COLUMN_NAME']]=$_POST[$row['COLUMN_NAME']];
}
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
      <input name="SampleNo" type="text" id="SampleNo" value="<?php echo $_SESSION['SampleNo'];?>" size="6" onchange="set_date_session(this.name,this.value)"/>
      <input type="submit" name="checksam" id="checksam" value="取得桶號" />
      <select name="drumno2" id="drumno2">
<?php
$sec=new sample;
$sec->lot_no=$_GET['lot_no'];
$sec->selections();
?>    
</select>
<input type="submit" name="chdrumno" id="chdrumno" value="修改桶號" onClick="return confirm('確定修改?')"/>
<td width="200" bgcolor="#CCCCCC">測試人員：
      <input name="Tester" type="text" id="Tester" value="<?php echo $_SESSION['uname']?>" size="10" readonly />      
      <td width="200" bgcolor="#CCCCCC"> 前端處理人員:
      <input name="x5" type="submit" value="X" />
      <input name="Operator" type="hidden" id="Operator" value="<?php echo $_GET['operator'];?>" size="10" readonly />
      <input name="op" type="text" value="<?php echo $_SESSION['userid'];?>" size="10"  />
      <input type="submit" name="an_first" id="button" value=" 工號  " onClick="window.open('./index.php?url=select_user', '_self');" />
      <input name="opname" type="text" value="<?php echo get_uname($_SESSION['userid'])  ;?>" size="10"  />

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
if($_GET['ani_groupname']=='PMS')
{ $query="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS inner join AnalyzeItem on AnalyzeItem.ANI_ID = INFORMATION_SCHEMA.COLUMNS.COLUMN_NAME WHERE (TABLE_NAME ='".$table."') order by AnalyzeItem.ANI_ORDER";}

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
      	echo '<input name="'.$row['COLUMN_NAME'].'" id="'.$row['COLUMN_NAME'].'" type="text"';
		if(substr($clo->excel,0,1)=='=')
		{
			echo ' readonly="readonly"';
				
		}	  
	  	if($_SESSION[$row['COLUMN_NAME']]<>''){$value=$_SESSION[$row['COLUMN_NAME']];}
		if($_SESSION[$row['COLUMN_NAME']]==''){echo ' style="background-color:white;"';}
		elseif($stdu=='' and $usl<>'' and $_SESSION[$row['COLUMN_NAME']]<=$usl){echo ' style="background-color:white;"';}
	  	elseif(($_SESSION[$row['COLUMN_NAME']]<=$usl and $_SESSION[$row['COLUMN_NAME']]>$stdu) or ($_SESSION[$row['COLUMN_NAME']]>=$lsl and $_SESSION[$row['COLUMN_NAME']]<$stdl)) {echo ' style="background-color:yellow;"';$xx=0;$_SESSION['status']=1;}
	  	elseif((($_SESSION[$row['COLUMN_NAME']]>$usl) and ($_SESSION[$row['COLUMN_NAME']]<>'NULL') and ($spec<>NULL)) or (($_SESSION[$row['COLUMN_NAME']]<$lsl) and ($_SESSION[$row['COLUMN_NAME']]<>'NULL') and ($spec<>NULL))){echo ' style="background-color:red;"';$xx=0;}
	  	elseif($spec==NULL){echo ' style="background-color:white;"';}
	 	else{echo ' style="background-color:white;"';}
	 
	  if((($spec=='') or ($spec=='<')) and ($_SESSION[$row['COLUMN_NAME']]=='')){echo ' value="NULL" style="background-color:white;"';}
	  elseif((($_GET['ani_groupname']=='M13') or ($_GET['ani_groupname']=='M21')) and ($_SESSION[$row['COLUMN_NAME']]=='')){$value="0.000001";}
	  else{$value=$_SESSION[$row['COLUMN_NAME']];}
//	  echo ' value="'.$value.'"  onblur="'.'ShowName('."'".$row['COLUMN_NAME']."'".')"';
 	echo ' value="'.$value.'" ';
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
      <td width="600" align="center"><font color="#FF0000">合否判定 &nbsp;&nbsp;&nbsp;&nbsp;
      <?php if($xx==1){$x1="checked";echo "合";}else{$x1='unchecked';echo "否";} ?></font>
      <input type="checkbox" name="Ok" id="Ok" <?php echo $x1;?> hidden >   &nbsp;&nbsp;&nbsp;&nbsp; 
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
      <?php
	  	if(isset($_POST['refresh'])){echo '<input type="submit" name="submit" id="submit" value=" 新  增 "/> <input type="submit" name="refresh" id="refresh" value=" 檢 查 "/>';}
		else{echo '<input type="submit" name="refresh" id="refresh" value=" 檢 查 "/>';}
	  ?>
      <input type="hidden" name="MM_insert" value="form1">

      <input type="submit" name="leave" id="button" value=" 離  開  " onClick="window.open('<?php echo $_SESSION['lasturl'];?>', '_self');" />
      
      <input type="button" name="rework" id="rework" value=" 再分析作業 " onClick="window.open('./index.php?url=rework&lot_no=<?php echo $_GET['lot_no'];?>&ani_groupname=<?php echo $_GET['ani_groupname'];?>','_self');" />
      <input type="button" name="define" id="define" value="定義EXCEL上傳欄位" onClick="window.open('../system/index.php?url=defineaniitem_&efm=<?php echo $_GET['efm'];?>&pid=<?php echo $_GET['pdd_prod_no'];?>','_self');" />      <?php if ($_GET['ani_groupname']=="TM") {
		  echo '<input type="button" name="TM" id="TM" value="取得TM分析資料" onClick="window.open('."'/cal/index.php?url=ctm&pdd_chemical=".$_GET['pdd_chemical']."&operator=".$_GET['operator']."&ani_groupname=".$_GET['ani_groupname']."&lot_no=".$_GET['lot_no']."&cust_no=".$cust_no."&pid=".$_GET['pdd_prod_no']."', '_self');".'"'.' />';
	  }?></td>
    </tr></table>
    </br>  
<span class="a18">報告內容:</span>

<select name="work" onchange="this.submit()">
 <option value="0">請選擇</option>
  <option value="Al" <?php if($_POST['work']=='Al') echo 'selected';?>>Al</option>
<option value="Ca" <?php if($_POST['work']=='Ca') echo 'selected';?>>Ca</option>
<option value="Cr" <?php if($_POST['work']=='Cr') echo 'selected';?>>Cr</option>
<option value="Cu" <?php if($_POST['work']=='Cu') echo 'selected';?>>Cu</option>
<option value="Fe" <?php if($_POST['work']=='Fe') echo 'selected';?>>Fe</option>
<option value="K" <?php if($_POST['work']=='K') echo 'selected';?>>K</option>
<option value="Mg" <?php if($_POST['work']=='Mg') echo 'selected';?>>Mg</option>
<option value="Mn" <?php if($_POST['work']=='Mn') echo 'selected';?>>Mn</option>
<option value="Na" <?php if($_POST['work']=='Na') echo 'selected';?>>Na</option>
<option value="Ni" <?php if($_POST['work']=='Ni') echo 'selected';?>>Ni</option>
<option value="Pb" <?php if($_POST['work']=='Pb') echo 'selected';?>>Pb</option>
<option value="Zn" <?php if($_POST['work']=='Zn') echo 'selected';?>>Zn</option>
<option value="Co" <?php if($_POST['work']=='Co') echo 'selected';?>>Co</option>
</select>
 <input type="submit" name="printrr1" id="printrr1" value="列印">
 <input type="submit" name="printrr2" id="printrr2" value=" R&R總表列印(2個人)">
 <input type="submit" name="printrr" id="printrr" value=" R&R總表列印(3個人) TYS">
 <input type="submit" name="printrr0" id="printrr0" value=" R&R總表列印(3個人5次)">
 <input type="submit" name="printrr3" id="printrr3" value="TSMC R&R總表範本檔">
 
 <?php 
 if($_POST['work']=='Al'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Al.xls target="new">原始範本</a>';}
 if($_POST['work']=='Ca'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Ca.xls target="new">原始範本</a>';}
 if($_POST['work']=='Cr'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Cr.xls target="new">原始範本</a>';}
 if($_POST['work']=='Cu'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Cu.xls target="new">原始範本</a>';}
 if($_POST['work']=='Fe'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Fe.xls target="new">原始範本</a>';}
 if($_POST['work']=='K'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_K.xls target="new">原始範本</a>';}
 if($_POST['work']=='Mg'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Mg.xls target="new">原始範本</a>';}
 if($_POST['work']=='Mn'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Mn.xls target="new">原始範本</a>';}
 if($_POST['work']=='Na'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Na.xls target="new">原始範本</a>';}
 if($_POST['work']=='Ni'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Ni.xls target="new">原始範本</a>';}
 if($_POST['work']=='Pb'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Pb.xls target="new">原始範本</a>';}
 if($_POST['work']=='Zn'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Zn.xls target="new">原始範本</a>';}
 if($_POST['work']=='Co'){echo '<a href=/cal/RR/'.$_GET['pdd_chemical'].'_Co.xls target="new">原始範本</a>';}
 
  if(isset($_POST['printrr']))
{
	
	$lot1=substr($_GET['lot_no'],0,-1);
	$_SESSION['lot1']=$lot1;
///////橫的列印 藥品上到下

	if($_GET['ani_groupname']=='M13')
	{
		$i1=8;$i2=20;
		$eng0='B';
	

	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$reader = PHPExcel_IOFactory::createReader('Excel2007');// 讀取2007 excel 檔案
	$PHPExcel = $reader->load("../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/"."RR1"."/".$_GET['ani_groupname'].".xlsx");
//	echo "RR1:"."../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/"."RR1"."/".$_GET['ani_groupname'].".xlsx";
//		echo "../FormList/PrintAnalyze/RCIC/".$_GET['pid']."/".$_POST['group'].".xlsx<br>";
		$sheet = $PHPExcel->getSheet(1);
		$a1=array();
		$times=0;
		for ($row = $i1; $row <= $i2; $row++) 
		{
			$val = $sheet->getCellByColumnAndRow(1,$row)->getValue();
			if($val=='k'){$val='K';}
			
			$val = iconv("utf-8","big5",$val);
		//echo $val;
		
		
//			echo "<BR>";
			array_push($a1,$val);
		}	
		
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/"."RR1"."/".$_GET['ani_groupname'].".xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$lot1=substr($_GET['lot_no'],0,-1);
	$objPHPExcel->getActiveSheet()->setCellValue("B2",$_GET['pdd_chemical']);
	
	//$queryo="select distinct top 3 create_user from analyze_first where lot_no like '".$_SESSION['lot1']."%' and create_user!='' order by create_user ";
	$queryo="select distinct top 3 first,min(create_time) create_time from analyze_first where lot_no like '".$_SESSION['lot1']."%' group by first order by create_time";
	//echo $queryo;
		$resulto = mssql_query($queryo);
		while($rowo = mssql_fetch_array($resulto))
		{		
			if($times==0)
			{ 
				$name=get_uname($rowo['first']);$s=8;
				$objPHPExcel->getActiveSheet()->setCellValue('E3',iconv("big5","utf-8",$name));
			}
			if($times==1)
			{
				$name=get_uname($rowo['first']);$s=24;
				$objPHPExcel->getActiveSheet()->setCellValue('E4',iconv("big5","utf-8",$name));
			}
			if($times==2)
			{
				$name=get_uname($rowo['first']);$s=40;
				$objPHPExcel->getActiveSheet()->setCellValue('E5',iconv("big5","utf-8",$name));
			}
	/*		
	$query="select * from analyze_first as AF 
	left join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime 
	where lot_no like '".$_SESSION['lot1']."%' and create_user = '".$rowo['create_user']."' and AF.ani_group='M13' order by TL.lotno";
	*/
	//echo $query;
	$query="select distinct lot_no from analyze_first as AF 
	left join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime 
	where lot_no like '".$_SESSION['lot1']."%' and first = '".$rowo['first']."' and AF.ani_group='M13'";
	//echo $query.'<br>';
	$result = mssql_query($query);
	$ENG='C';$A='C';$B='M';$C='W';$n=0;
		while($row = mssql_fetch_array($result))
		{ $T=substr($row['create_time'],0,8);
			$objPHPExcel->getActiveSheet()->setCellValue('E2',date("Ymd"));
			//echo $ENG.$s;
				for($i=0;$i<count($a1);$i++)
				{
					$a2=array($A,$B,$C);
					$query1="select top 3 * from analyze_first as AF inner join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime where lot_no like '".$row['lot_no']."%' and first = '".$rowo['first']."' and AF.ani_group='M13' order by TL.lotno,create_time desc";
					$result1 = mssql_query($query1);
					while($row1 = mssql_fetch_array($result1))
					{
						if($times==0)
						{
							//echo get_uname($rowo['first']).','.$a1[$i].'='.$row1[$a1[$i]].','.$a2[$n].$s.'<br>';
							$objPHPExcel->getActiveSheet()->setCellValue($a2[$n].$s,$row1[$a1[$i]]);
						}
						if($times==1)
						{
							//echo get_uname($rowo['first']).','.$a1[$i].'='.$row1[$a1[$i]].','.$a2[$n].$s.'<br>';
							$objPHPExcel->getActiveSheet()->setCellValue($a2[$n].$s,$row1[$a1[$i]]);
							
						}
						if($times==2)
						{
							//echo get_uname($rowo['first']).','.$a1[$i].'='.$row1[$a1[$i]].','.$a2[$n].$s.'<br>';
							$objPHPExcel->getActiveSheet()->setCellValue($a2[$n].$s,$row1[$a1[$i]]);
							
						}
						
						$n++;
						if($n==3){$n=0;}	
					}	
					$s++;
				}
				$A++;$B++;$C++;	
				if($s==21 and $times==0){$s=8;}
				if($s==37 and $times==1){$s=24;}
				if($s==53 and $times==2){$s=40;} 	
			//for($r=0;$r<10;$r++){$eng++;}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
				
				
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		}
		
		$times++;
		}
		$objPHPExcel->setActiveSheetIndex(8);

		$ENG='D';$eng='C';$NUM='6';
		for($i=0;$i<30;$i++){
		if($NUM==6){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+8)));}
		if($NUM==7){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+8-1)));}
		if($NUM==8){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+8-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=9;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'6:'.$ENG.'8)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'6:'.$ENG.'8)-MIN('.$ENG.'6:'.$ENG.'8)'));
		if($NUM==10){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='11';
		for($i=0;$i<30;$i++){
		if($NUM==11){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+19)));}
		if($NUM==12){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+19-1)));}
		if($NUM==13){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+19-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=14;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'11:'.$ENG.'13)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'11:'.$ENG.'13)-MIN('.$ENG.'11:'.$ENG.'13)'));
		if($NUM==15){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='16';
		for($i=0;$i<30;$i++){
		if($NUM==16){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+30)));}
		if($NUM==17){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+30-1)));}
		if($NUM==18){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+30-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=19;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'16:'.$ENG.'18)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'16:'.$ENG.'18)-MIN('.$ENG.'16:'.$ENG.'18)'));
		if($NUM==20){$ENG++;}
		}
		
$objPHPExcel->setActiveSheetIndex(9);
		$ENG='D';$eng='C';$NUM='6';
		for($i=0;$i<30;$i++){
		if($NUM==6){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+9)));}
		if($NUM==7){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+9-1)));}
		if($NUM==8){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+9-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=9;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'6:'.$ENG.'8)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'6:'.$ENG.'8)-MIN('.$ENG.'6:'.$ENG.'8)'));
		if($NUM==10){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='11';
		for($i=0;$i<30;$i++){
		if($NUM==11){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+20)));}
		if($NUM==12){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+20-1)));}
		if($NUM==13){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+20-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=14;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'11:'.$ENG.'13)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'11:'.$ENG.'13)-MIN('.$ENG.'11:'.$ENG.'13)'));
		if($NUM==15){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='16';
		for($i=0;$i<30;$i++){
		if($NUM==16){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+31)));}
		if($NUM==17){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+31-1)));}
		if($NUM==18){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+31-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=19;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'16:'.$ENG.'18)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'16:'.$ENG.'18)-MIN('.$ENG.'16:'.$ENG.'18)'));
		if($NUM==20){$ENG++;}
		}
		
$objPHPExcel->setActiveSheetIndex(10);
		$ENG='D';$eng='C';$NUM='6';
		for($i=0;$i<30;$i++){
		if($NUM==6){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+10)));}
		if($NUM==7){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+10-1)));}
		if($NUM==8){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+10-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=9;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'6:'.$ENG.'8)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'6:'.$ENG.'8)-MIN('.$ENG.'6:'.$ENG.'8)'));
		if($NUM==10){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='11';
		for($i=0;$i<30;$i++){
		if($NUM==11){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+21)));}
		if($NUM==12){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+21-1)));}
		if($NUM==13){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+21-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=14;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'11:'.$ENG.'13)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'11:'.$ENG.'13)-MIN('.$ENG.'11:'.$ENG.'13)'));
		if($NUM==15){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='16';
		for($i=0;$i<30;$i++){
		if($NUM==16){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+32)));}
		if($NUM==17){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+32-1)));}
		if($NUM==18){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+32-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=19;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'16:'.$ENG.'18)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'16:'.$ENG.'18)-MIN('.$ENG.'16:'.$ENG.'18)'));
		if($NUM==20){$ENG++;}
		}
$objPHPExcel->setActiveSheetIndex(11);
		$ENG='D';$eng='C';$NUM='6';
		for($i=0;$i<30;$i++){
		if($NUM==6){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+11)));}
		if($NUM==7){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+11-1)));}
		if($NUM==8){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+11-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=9;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'6:'.$ENG.'8)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'6:'.$ENG.'8)-MIN('.$ENG.'6:'.$ENG.'8)'));
		if($NUM==10){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='11';
		for($i=0;$i<30;$i++){
		if($NUM==11){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+22)));}
		if($NUM==12){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+22-1)));}
		if($NUM==13){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+22-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=14;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'11:'.$ENG.'13)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'11:'.$ENG.'13)-MIN('.$ENG.'11:'.$ENG.'13)'));
		if($NUM==15){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='16';
		for($i=0;$i<30;$i++){
		if($NUM==16){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+33)));}
		if($NUM==17){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+33-1)));}
		if($NUM==18){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+33-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=19;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'16:'.$ENG.'18)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'16:'.$ENG.'18)-MIN('.$ENG.'16:'.$ENG.'18)'));
		if($NUM==20){$ENG++;}
		}
$objPHPExcel->setActiveSheetIndex(12);
		$ENG='D';$eng='C';$NUM='6';
		for($i=0;$i<30;$i++){
		if($NUM==6){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+12)));}
		if($NUM==7){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+12-1)));}
		if($NUM==8){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+12-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=9;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'6:'.$ENG.'8)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'6:'.$ENG.'8)-MIN('.$ENG.'6:'.$ENG.'8)'));
		if($NUM==10){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='11';
		for($i=0;$i<30;$i++){
		if($NUM==11){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+23)));}
		if($NUM==12){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+23-1)));}
		if($NUM==13){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+23-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=14;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'11:'.$ENG.'13)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'11:'.$ENG.'13)-MIN('.$ENG.'11:'.$ENG.'13)'));
		if($NUM==15){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='16';
		for($i=0;$i<30;$i++){
		if($NUM==16){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+34)));}
		if($NUM==17){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+34-1)));}
		if($NUM==18){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+34-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=19;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'16:'.$ENG.'18)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'16:'.$ENG.'18)-MIN('.$ENG.'16:'.$ENG.'18)'));
		if($NUM==20){$ENG++;}
		}
$objPHPExcel->setActiveSheetIndex(13);
		$ENG='D';$eng='C';$NUM='6';
		for($i=0;$i<30;$i++){
		if($NUM==6){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+13)));}
		if($NUM==7){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+13-1)));}
		if($NUM==8){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+13-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=9;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'6:'.$ENG.'8)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'6:'.$ENG.'8)-MIN('.$ENG.'6:'.$ENG.'8)'));
		if($NUM==10){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='11';
		for($i=0;$i<30;$i++){
		if($NUM==11){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+24)));}
		if($NUM==12){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+24-1)));}
		if($NUM==13){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+24-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=14;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'11:'.$ENG.'13)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'11:'.$ENG.'13)-MIN('.$ENG.'11:'.$ENG.'13)'));
		if($NUM==15){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='16';
		for($i=0;$i<30;$i++){
		if($NUM==16){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+35)));}
		if($NUM==17){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+35-1)));}
		if($NUM==18){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+35-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=19;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'16:'.$ENG.'18)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'16:'.$ENG.'18)-MIN('.$ENG.'16:'.$ENG.'18)'));
		if($NUM==20){$ENG++;}
		}
$objPHPExcel->setActiveSheetIndex(14);
		$ENG='D';$eng='C';$NUM='6';
		for($i=0;$i<30;$i++){
		if($NUM==6){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+14)));}
		if($NUM==7){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+14-1)));}
		if($NUM==8){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+14-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=9;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'6:'.$ENG.'8)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'6:'.$ENG.'8)-MIN('.$ENG.'6:'.$ENG.'8)'));
		if($NUM==10){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='11';
		for($i=0;$i<30;$i++){
		if($NUM==11){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+25)));}
		if($NUM==12){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+25-1)));}
		if($NUM==13){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+25-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=14;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'11:'.$ENG.'13)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'11:'.$ENG.'13)-MIN('.$ENG.'11:'.$ENG.'13)'));
		if($NUM==15){$ENG++;}
		}
		
		$ENG='D';$eng='C';$NUM='16';
		for($i=0;$i<30;$i++){
		if($NUM==16){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+36)));}
		if($NUM==17){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+36-1)));}
		if($NUM==18){
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8","='METAL RAW DATA'!".$eng.($NUM+36-2)));}
		$ENG++;$eng++;
		if($ENG=='N'){$ENG='D';$NUM++;}
		}
		for($i=0;$i<10;$i++){
		$NUM=19;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=AVERAGE('.$ENG.'16:'.$ENG.'18)'));
		$NUM++;
		$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,iconv("big5","utf-8",'=MAX('.$ENG.'16:'.$ENG.'18)-MIN('.$ENG.'16:'.$ENG.'18)'));
		if($NUM==20){$ENG++;}
		}
		
		$objPHPExcel->setActiveSheetIndex(15);
		$objPHPExcel->getActiveSheet()->setCellValue("D4",$T);
		$objPHPExcel->getActiveSheet()->setCellValue("D5",$_GET['pdd_chemical']."-".$_GET['ani_groupname']);
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("../tmp/tentimes.xlsx");
	echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp/tentimes.xlsx";</script>';
}////3個人的結尾按鈕
 if(isset($_POST['printrr0'])){
 		$objPHPExcel = new PHPExcel();	
		$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/"."RR1"."/M13_new.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1',iconv("big5","utf-8",$_GET['pdd_chemical']."-".$_GET['ani_groupname']));
		$objPHPExcel->getActiveSheet()->setCellValue('G1',date("Y-m-d"));
 	$queryo="select distinct top 3 first,min(create_time) create_time from analyze_first where lot_no like '".$_SESSION['lot1']."%' group by first order by create_time";
//	echo $queryo;
	$times=0;
	
		$resulto = mssql_query($queryo);
		
		while($rowo = mssql_fetch_array($resulto))
		{		
			if($times==0)
			{ 
				$u1=trim($rowo['first']);
				$name=get_uname($rowo['first']);$s=8;
				$objPHPExcel->getActiveSheet()->setCellValue('G2',iconv("big5","utf-8",$name));
			}
			if($times==1)
			{
				$u2=trim($rowo['first']);
				$name=get_uname($rowo['first']);$s=24;
				$objPHPExcel->getActiveSheet()->setCellValue('G3',iconv("big5","utf-8",$name));
			}
			if($times==2)
			{
				$u3=trim($rowo['first']);
				$name=get_uname($rowo['first']);$s=40;
				$objPHPExcel->getActiveSheet()->setCellValue('G4',iconv("big5","utf-8",$name));
			}
			$times++;
		}	

 		$lot1=substr($_GET['lot_no'],0,-1);
	$_SESSION['lot1']=$lot1;
	
	if($_GET['ani_groupname']=='M13')
	{
		$query="SELECT DISTINCT AF.lot_no as lotno,Na,Mg,Al,K,Ca,Cr,Mn,Fe,Ni,Co,Cu,Zn,Pb, AF.[first] as fst, AF.create_time   
		FROM              analyze_first AS AF LEFT OUTER JOIN ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime  
		where lot_no like '".$_SESSION['lot1']."%'  and AF.ani_group='M13' ORDER BY   AF.lot_no, AF.create_time";
//		echo "<BR>".$query."<BR>";
		echo "<BR>";
		$n1=7;
		$n2=24;
		$n3=41;
		$sn00=$sn01=$sn02=$sn03=$sn04=$sn10=$sn11=$sn12=$sn13=$sn14=$sn20=$sn21=$sn22=$sn23=$sn24=0;
		$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{ 
			$lotno=trim($row['lotno']);	
			$first=trim($row['fst']);
			$sn=substr($lotno,-1);			
//			echo "#".$first."#-U1#".$u1."#U2#".$u2."#U3#".$u3."#SN#".$sn."#<BR>";
			if($sn==0){					
				if($first==$u1){
					$location=($n1+$sn00);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn00++;
				}
				elseif($first==$u2){
					$location= ($n2+$sn10);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn10++;
				}
				elseif($first==$u3){
					$location= ($n3+$sn20);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn20++;
				}
			}
			if($sn==1){
				if($first==$u1){
					$location= ($n1+3+$sn01);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn01++;
				}
				elseif($first==$u2){
					$location= ($n2+3+$sn11);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn11++;
				}
				elseif($first==$u3){
					$location= ($n3+3+$sn21);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn21++;
				}
			}
			if($sn==2){
				if($first==$u1){
					$location= ($n1+6+$sn02);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn02++;
				}
				elseif($first==$u2){
					$location= ($n1+6+$sn12);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn12++;
				}
				elseif($first==$u3){
					$location= ($n1+6+$sn22);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn22++;
				}
			}
			if($sn==3){
				if($first==$u1){
					$location= ($n1+9+$sn03);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn03++;
				}
				elseif($first==$u2){
					$location= ($n1+9+$sn13);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn13++;
				}
				elseif($first==$u3){
					$location= ($n1+9+$sn23);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn23++;
				}
			}
			if($sn==4){
				if($first==$u1){
					$location= ($n1+12+$sn04);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn04++;
				}
				elseif($first==$u2){
					$location= ($n1+12+$sn14);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn14++;
				}
				elseif($first==$u3){
					$location= ($n1+12+$sn24);
					rnw($objPHPExcel,$location,$row['Na'],$row['Mg'],$row['Al'],$row['K'],$row['Ca'],$row['Cr'],$row['Mn'],$row['Fe'],$row['Ni'],$row['Co'],$row['Cu'],$row['Zn'],$row['Pb']);
					$sn24++;
				}
			}
		}
	}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("../tmp/tentimes.xlsx");
	echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp/tentimes.xlsx";</script>';
 } //END PRINTRR0
 
 function rnw($objPHPExcel,$location,$na,$mg,$al,$k,$ca,$cr,$mn,$fe,$ni,$co,$cu,$zn,$pb){
 //	echo $location."<BR>";
 		$objPHPExcel->getActiveSheet()->setCellValue('D'.$location,iconv("big5","utf-8",$na));
 		$objPHPExcel->getActiveSheet()->setCellValue('E'.$location,iconv("big5","utf-8",$mg));
 		$objPHPExcel->getActiveSheet()->setCellValue('F'.$location,iconv("big5","utf-8",$al));
 		$objPHPExcel->getActiveSheet()->setCellValue('G'.$location,iconv("big5","utf-8",$k));
 		$objPHPExcel->getActiveSheet()->setCellValue('H'.$location,iconv("big5","utf-8",$ca));
 		$objPHPExcel->getActiveSheet()->setCellValue('I'.$location,iconv("big5","utf-8",$cr));
 		$objPHPExcel->getActiveSheet()->setCellValue('J'.$location,iconv("big5","utf-8",$mn));
 		$objPHPExcel->getActiveSheet()->setCellValue('K'.$location,iconv("big5","utf-8",$fe));
 		$objPHPExcel->getActiveSheet()->setCellValue('L'.$location,iconv("big5","utf-8",$ni));
 		$objPHPExcel->getActiveSheet()->setCellValue('M'.$location,iconv("big5","utf-8",$co));
 		$objPHPExcel->getActiveSheet()->setCellValue('N'.$location,iconv("big5","utf-8",$cu));
 		$objPHPExcel->getActiveSheet()->setCellValue('O'.$location,iconv("big5","utf-8",$zn));
 		$objPHPExcel->getActiveSheet()->setCellValue('P'.$location,iconv("big5","utf-8",$pb));
 }
 if(isset($_POST['printrr3']))
 {
	$lot1=substr($_GET['lot_no'],0,-1);
	$_SESSION['lot1']=$lot1;
///////橫的列印 藥品上到下

	if($_GET['ani_groupname']=='M13')
	{
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RR/M13_new.xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$queryo="select distinct top 3 create_user from analyze_first where lot_no like '".$_SESSION['lot1']."%' and create_user!='' order by create_user ";
	//echo $queryo;
		$resulto = mssql_query($queryo);
		while($rowo = mssql_fetch_array($resulto))
		{		
			if($times==0)
			{ 
				$name=get_uname($rowo['create_user']);
			}
			if($times==1)
			{
				$name=get_uname($rowo['create_user']);
			}
			if($times==2)
			{
				$name=get_uname($rowo['create_user']);
			}
				
	$query="select * from analyze_first as AF 
	left join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime 
	where lot_no like '".$_SESSION['lot1']."%' and create_user = '".$rowo['create_user']."' and AF.ani_group='M13' order by TL.lotno";
	//echo $query;
	$result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{ 
			$T=substr($row['create_time'],0,8);	
		}
		$times++;
		}
	}
	
	
	$objPHPExcel->getActiveSheet()->setCellValue("D4",$T);
	$objPHPExcel->getActiveSheet()->setCellValue("D5",$_GET['pdd_chemical']."-".$_GET['ani_groupname']);
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("../tmp/tsmc GRR.xlsx");
	echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp/tsmc GRR.xlsx";</script>';
 }
 if(isset($_POST['printrr1']))
 {
	 /*以下是寫入RR NDC入資料庫*/
	 /*
	 $lot1=substr($_GET['lot_no'],0,-1);
	 $_SESSION['lot1']=$lot1;
	 $A=$_GET['pdd_chemical']."_".$_POST['work'];
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("../cal/RR/".$A.".xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$queryo="select distinct top 3 create_user from analyze_first where lot_no like '".$_SESSION['lot1']."%' and create_user!='' order by create_user desc";
	//echo $queryo;
		$resulto = mssql_query($queryo);
		$times=0;
		while($rowo = mssql_fetch_array($resulto))
		{		
				$ENG='B';
				if($times==0)
				{ 
					$name=$rowo['create_user'];$NUM=36;
					$objPHPExcel->getActiveSheet()->setCellValue('D33',iconv("big5","utf-8",$name));
				}
				if($times==1)
				{
					$name=$rowo['create_user'];$NUM=50;
					$objPHPExcel->getActiveSheet()->setCellValue('D47',iconv("big5","utf-8",$name));
				}
				if($times==2)
				{
					$name=$rowo['create_user'];$NUM=64;
					$objPHPExcel->getActiveSheet()->setCellValue('D61',iconv("big5","utf-8",$name));
				}
			
		$query="select ".$_POST['work']." from analyze_first as AF 
	left join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime 
	where lot_no like '".$_SESSION['lot1']."%' and create_user = '".$rowo['create_user']."' and AF.ani_group='M13' order by TL.lotno";
		$result = mssql_query($query);
		//echo $query;
		while($row = mssql_fetch_array($result))
		{	
			if($ENG=='E'){$ENG='B';$NUM++;}
			if($times==0){
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row[0]);
			}
			if($times==1){
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row[0]);
			}
			if($times==2){
			$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row[0]);
			}
			$ENG++;
			
		}
		$times++;
		}
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("../tmp/".$A.".xlsx");
	$reader = PHPExcel_IOFactory::createReader('Excel2007'); 
	$PHPExcel = $reader->load("../tmp/".$A.".xlsx");
	$sheet = $PHPExcel->getSheet(1);
	$val1 = $sheet->getCellByColumnAndRow(4,14)->getValue();
	echo $val1;
	$lot=$_GET['lot_no'];
	$saveuid=$_SESSION['uname'];
	*/
	
	/*以下是列印單一表單*/
	
	 $lot1=substr($_GET['lot_no'],0,-1);
	 $_SESSION['lot1']=$lot1;
	 $A=$_GET['pdd_chemical']."_".$_POST['work'];
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/"."RR1"."/".$_POST['work'].".xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$queryo="select distinct top 3 first  from analyze_first where lot_no like '".$_SESSION['lot1']."%' and create_user!='' order by first";
	//echo $queryo;
		$resulto = mssql_query($queryo);
		$times=0;
		$numRows=mssql_num_rows($resulto);
		while($rowo = mssql_fetch_array($resulto))
			{		
					$ENG='B';
					if($times==0)
					{ 
						$name=$rowo['first'];$NUM=36;
						$objPHPExcel->getActiveSheet()->setCellValue('D33',iconv("big5","utf-8",$name));
					}
					if($times==1)
					{
						$name=$rowo['first'];$NUM=50;
						$objPHPExcel->getActiveSheet()->setCellValue('D47',iconv("big5","utf-8",$name));
					}
					if($times==2)
					{
						$name=$rowo['first'];$NUM=64;
						$objPHPExcel->getActiveSheet()->setCellValue('D61',iconv("big5","utf-8",$name));
					}
				
			$query="select distinct lot_no from analyze_first as AF 
		left join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime 
		where lot_no like '".$_SESSION['lot1']."%' and first = '".$rowo['first']."' and AF.ani_group='M13'";
			$result = mssql_query($query);
			//echo $query;
			while($row = mssql_fetch_array($result))
			{	
				$query1="select top 3 ".$_POST['work']." from analyze_first as AF 
		inner join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime 
		where lot_no like '".$row['lot_no']."%' and first = '".$rowo['first']."' and AF.ani_group='M13' order by TL.lotno,first,create_time desc";
//		echo $query1.'<br>';
				$result1 = mssql_query($query1);
				while($row1 = mssql_fetch_array($result1))
				{	
					if($ENG=='E'){$ENG='B';$NUM++;}
					if($times==0){
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row1[0]);
					}
					if($times==1){
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row1[0]);
					}
					if($times==2){
					$objPHPExcel->getActiveSheet()->setCellValue($ENG.$NUM,$row1[0]);
					}
					$ENG++;
				}
				
			}
			$times++;
			}
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("../tmp/".$A.".xlsx");
	
	echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp/'.$A.'.xlsx";</script>';	
	
 }
 


if(isset($_POST['printrr2']))
{
	$lot1=substr($_GET['lot_no'],0,-1);
	$_SESSION['lot1']=$lot1;
///////橫的列印 藥品上到下

	if($_GET['ani_groupname']=='M13')
	{
		$i1=8;$i2=20;
		$eng0='B';
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$reader = PHPExcel_IOFactory::createReader('Excel2007');// 讀取2007 excel 檔案
	$PHPExcel = $reader->load("../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/".$_GET['ani_groupname'].".xlsx");
	
//		echo "../FormList/PrintAnalyze/RCIC/".$_GET['pid']."/".$_POST['group'].".xlsx<br>";
		$sheet = $PHPExcel->getSheet(1);
		$a1=array();
		$a2=array('C','M');
		$a3=array('W','AG');$times=0;
		for ($row = $i1; $row <= $i2; $row++) 
		{
			$val = $sheet->getCellByColumnAndRow(1,$row)->getValue();
			if($val=='k'){$val='K';}
			
			$val = iconv("utf-8","big5",$val);
		//echo $val;
		
		
//			echo "<BR>";
			array_push($a1,$val);
		}	
		
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/".$_GET['ani_groupname'].".xlsx");
	$objPHPExcel->setActiveSheetIndex(1);
	
	
	
	$lot1=substr($_GET['lot_no'],0,-1);
	//echo $lot1.'<br>';
	$objPHPExcel->getActiveSheet()->setCellValue("B2",$_GET['pdd_chemical']);
	$queryo="select distinct top 2 first,min(create_time) create_time from analyze_first where lot_no like '".$_SESSION['lot1']."%' group by first order by create_time";
	//echo $queryo;
		$resulto = mssql_query($queryo);
		while($rowo = mssql_fetch_array($resulto))
		{	
			if($times==0)
			{ 
				$name=get_uname($rowo['first']);
				$objPHPExcel->getActiveSheet()->setCellValue('E3',iconv("big5","utf-8",$name));
			}
			if($times==1)
			{
				$name=get_uname($rowo['first']);
				$objPHPExcel->getActiveSheet()->setCellValue('E4',iconv("big5","utf-8",$name));
			}

	$query="select distinct lot_no from analyze_first as AF 
	left join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime 
	where lot_no like '".$_SESSION['lot1']."%' and first = '".$rowo['first']."' and AF.ani_group='M13'";
	//echo $query;
	$n=0;
	$count=0;
	$result = mssql_query($query);
	
			while($row = mssql_fetch_array($result))
			{ 
				$query1="select top 2 * from analyze_first as AF 
	inner join ".$_GET['efm']." as TL on AF.create_time=TL.analyzetime 
	where lot_no like '".$row['lot_no']."%' and first = '".$rowo['first']."' and AF.ani_group='M13' order by TL.lotno,create_time desc";
				$T=substr($row1['create_time'],0,8);
				$objPHPExcel->getActiveSheet()->setCellValue('E2',date("Ymd"));
				$result1 = mssql_query($query1);
				while($row1 = mssql_fetch_array($result1))
				{ 
					for($i=0;$i<count($a1);$i++)
					{
						$s=$i+$i1;
						if($times==0)
						{
							//echo $a2[$n].$s.','.$a1[$i].':'.$row1[$a1[$i]].'<br>';
							$objPHPExcel->getActiveSheet()->setCellValue($a2[$n].$s,$row1[$a1[$i]]);
						}
						if($times==1)
						{
							//echo $a3[$n].$s.','.$a1[$i].':'.$row1[$a1[$i]].'<br>';
							$objPHPExcel->getActiveSheet()->setCellValue($a3[$n].$s,$row1[$a1[$i]]);
							
						}
					}
				
					if($times==0)
					{
						$a2[$n]++;
					}
					if($times==1)
					{
						$a3[$n]++;
					}	
			
			
				if($a3[$n]=='Z'){$count++;}
				if($a3[$n]=='Z' and $count==2){$a3[$n]='AA';}				
				$n++;
				if($n==2){$n=0;}
				}
			//for($r=0;$r<10;$r++){$eng++;}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
				
				
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
			}
		
			$times=1;
		
		}
		$objPHPExcel->setActiveSheetIndex(15);
		$objPHPExcel->getActiveSheet()->setCellValue("D4",$T);
		$objPHPExcel->getActiveSheet()->setCellValue("D5",$_GET['pdd_chemical']."-".$_GET['ani_groupname']);
}
if($_GET['ani_groupname']=='P' )//特別列印
{

	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/".$_GET['ani_groupname'].".xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('I2',date("Ymd"));
	$query="select distinct serialno from ".$_GET['efm']." where lotno like '".substr($_GET['lot_no'],0,-1)."%' ORDER BY SerialNo";
	$result=mssql_query($query);
	$aa=array();$x=0;
	while($row = mssql_fetch_array($result))
	{		
		$aa[$x]=$row['serialno'];
		$x++;
	}
	$o=5;
	for($k=0;$k<count($aa);$k++){
		$query="select PP02,PP05,Tester,SerialNo, convert(varchar, TestDate, 120) as dat1  from ".$_GET['efm']." where lotno like '".substr($_GET['lot_no'],0,-1)."%' and SerialNo=".$aa[$k]." order by lotno";
//		echo $query;
//		echo "<BR>";
		$result = mssql_query($query);
		$i=0;
		while($row = mssql_fetch_array($result))
		{	
			$tester=$row['Tester'];
			$testdate=$row['dat1'];
			$objPHPExcel->setActiveSheetIndex(0);
	//		$objPHPExcel->getActiveSheet()->setCellValue('B'.$o,iconv("big5","utf-8",$row['Tester']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3+$i, $o, $row['PP02']);		
			$objPHPExcel->setActiveSheetIndex(2);
	//		$objPHPExcel->getActiveSheet()->setCellValue('B'.$o,iconv("big5","utf-8",$row['Tester']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3+$i, $o, $row['PP05']);		
			$i++;	
		}
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$o,iconv("big5","utf-8",$tester));
		$objPHPExcel->getActiveSheet()->setCellValue('I2',iconv("big5","utf-8",substr($testdate,0,10)));
		$objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$o,iconv("big5","utf-8",$tester));
		$objPHPExcel->getActiveSheet()->setCellValue('I2',iconv("big5","utf-8",substr($testdate,0,10)));
		if($o==6){$o++;}	
		$o++;	
	}
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('K1',iconv("big5","utf-8",date("Y-m-d")));
	$objPHPExcel->setActiveSheetIndex(2);
	$objPHPExcel->getActiveSheet()->setCellValue('K1',iconv("big5","utf-8",date("Y-m-d")));
	$objPHPExcel->setActiveSheetIndex(4);
	$objPHPExcel->getActiveSheet()->setCellValue('D4',iconv("big5","utf-8",substr($testdate,0,10)));
}
		/*
		$objPHPExcel->setActiveSheetIndex(5);
		$objPHPExcel->getActiveSheet()->setCellValue("D4",$T);
		$objPHPExcel->getActiveSheet()->setCellValue("D5",$_GET['pdd_chemical']."-".$_GET['ani_groupname']);
////////////////////////////////////NH4
		*/
if($_GET['ani_groupname']=='A' )
{
	$objPHPExcel = new PHPExcel();	
	$objPHPExcel = PHPExcel_IOFactory::load("../FormList/PrintAnalyze/RR/".$_GET['pdd_chemical']."/".$_GET['ani_groupname'].".xlsx");
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('I2',date("Ymd"));
	$query="select distinct serialno from ".$_GET['efm']." where lotno like '".substr($_GET['lot_no'],0,-1)."%' ORDER BY SerialNo";
	$result=mssql_query($query);
	$aa=array();$x=0;
	while($row = mssql_fetch_array($result))
	{		
		$aa[$x]=$row['serialno'];
		$x++;
	}
	$o=5;
	for($k=0;$k<count($aa);$k++){
		$query="select Average,Tester,SerialNo, convert(varchar, TestDate, 120) as dat1  from ".$_GET['efm']." where lotno like '".substr($_GET['lot_no'],0,-1)."%' and SerialNo=".$aa[$k]." order by lotno";

		$result = mssql_query($query);
		$i=0;
		while($row = mssql_fetch_array($result))
		{	
			$tester=$row['Tester'];
			$testdate=$row['dat1'];
			$objPHPExcel->setActiveSheetIndex(0);
	//		$objPHPExcel->getActiveSheet()->setCellValue('B'.$o,iconv("big5","utf-8",$row['Tester']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3+$i, $o, $row['Average']);			
			$i++;	
		}
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$o,iconv("big5","utf-8",$tester));
		$objPHPExcel->getActiveSheet()->setCellValue('I2',iconv("big5","utf-8",substr($testdate,0,10)));
		if($o==6){$o++;}	
		$o++;	
	}
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('K1',iconv("big5","utf-8",date("Y-m-d")));
	$objPHPExcel->setActiveSheetIndex(2);
	$objPHPExcel->getActiveSheet()->setCellValue('D4',iconv("big5","utf-8",substr($testdate,0,10)));
}//////////////////////////////I_CL


		
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
		$objWriter->save("../tmp/tentimes.xlsx");
		echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp/tentimes.xlsx";</script>';			
}//按鈕結尾

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
	}
	}
echo '<td>序號</td>';
echo '<td>分析時間</td>';
echo '<td>前端處理人員</td></tr>';
$query="SELECT          ".substr($str,0,-2).",[SerialNo], [AnalyzeTime]
FROM              ".$table." 
WHERE          (LotNo  = '".$_GET['lot_no']."') order by SerialNo";
//echo $query;
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
	echo '<td><a href="print_ani_result.php?table='.$table.'&AnalyzeTime='.$row['AnalyzeTime'].'&lot_no='.$_GET['lot_no'].'&ani_groupname='.$_GET['ani_groupname'].'&pdd_chemical='.$_GET['pdd_chemical'].'" target="new">列印</a></td>';
	echo '<td width="30"><a href="index.php?'.$urllast.'&smpno='.$row['SampleNo'].'&Ok='.$row['Ok'].'&ani_time='.$row['AnalyzeTime'].'&id='.$id.'&url=edit_report&first='.$analyze_1st->first.'" target="new">編輯</a></td>';
	echo '<td>'.getdrumno($row['SampleNo'],$_GET['lot_no']).'</td>';
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
	echo "<td>".get_uname($analyze_1st->first)."</td>";
	echo "</tr>";
}
echo '
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
    </table>';

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
echo '<td><input type="submit" name="delete" id="delete" value="刪除" /><input type="submit" name="timereport" id="timereport" value="增加報告工時" /></td>';
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
	$_SESSION['savename']=get_uname($row['FILE_UID']);
		auth1('5-02',$_SESSION['aut']);//SESSION['ck']出處+
	
	if(($_SESSION['uname']==get_uname($row['FILE_UID'])) or $_SESSION['ck']==1)
	{		
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
if(isset($_POST['timereport']))
{
	auth('5-02',$_SESSION['aut']);

	
	$target=$_POST["selt"];
	$mys=implode(",",$target);
    $num=count($target);
	for ($x=0; $x<$num; $x++)
	{
	$find="select * from FILE_REPORTS where ([index] = ".$target[$x].")";	
		$_SESSION['find']=$find;
		$resultf = mssql_query($find);
			$numRowsf = mssql_num_rows($resultf);			
			while($rowf = mssql_fetch_array($resultf))
			{
				$analyze=$rowf['FILE_UPLOAD_TIME'];
				$uid=$rowf['FILE_UID'];
				
			}	
			
	$chkre="select *  from analyze_first1 where lot_no='".$_GET['lot_no']."' and  create_time='".$analyze."' ";
	$resultre = mssql_query($chkre);
			$numRowsre = mssql_num_rows($resultre);	
	$_SESSION['re0']==$numRowsre;
	if($numRowsre==0)
	{
	
	
	$query="INSERT INTO dbo.analyze_first1
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
		VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$analyze."', 'Y', '".$uid."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";
		$_SESSION['query']=$query;
	$result=mssql_query($query);
	}
	}
}

if(isset($_POST["upload"]))
{   
	$ext = end(explode('.', $_FILES["file"]["name"]));
	$path="../reports/".$_GET['ani_groupname']."/";
	$filename=$_GET['lot_no']."_".date("YmdHis").".".$ext;
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

if(isset($_POST["delete"]))
{   
	if($_SESSION['uname']==$_SESSION['savename'])
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
			$querygg="delete from analyze_first1 where 
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
	refresh();
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
$query="INSERT INTO dbo.analyze_first1
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['createtime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";
$result=mssql_query($query);
$query="INSERT INTO dbo.analyze_first
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['createtime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";
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
	if(($row['COLUMN_NAME']=='B') and $_GET['pdd_chemical']=='H2O' and $_GET['ani_groupname']<>'TT_B'){write_ttb(1,$_POST['SampleNo'],$_POST['Ok'],$_POST['Tester'],$_GET['lot_no'],"TLEQA9110101",$_POST['B'],$_POST['Operator'],$_SESSION['createtime']);}
	if($row['COLUMN_NAME']=='TestDate'){
			$q1.="[".$row['COLUMN_NAME']."],";
            $q2.="'".date("Y-m-d")."',";
			}
	elseif($row['DATA_TYPE']<>'float' and ($row['COLUMN_NAME']=='F' or $row['COLUMN_NAME']=='B' or $row['COLUMN_NAME']=='Water')){
			$q1.="[".$row['COLUMN_NAME']."],";
            $q2.='NULL'.",";
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
	$dift=new read_table;
	$dift->table=$table;
	$dift->LotNo=$_GET['lot_no'];
	$dift->row_name=$row['COLUMN_NAME'];
	$dift->read();
	$from_lot=new get_from_lot_no;
	$from_lot->lid=$_GET['lot_no'];
	$from_lot->ani();
	$from_lot->pdd_type;
	if(($_POST[$row['COLUMN_NAME']]>0.001) and ($_POST[$row['COLUMN_NAME']]<>'') and ($dift->value>0.001) and ($dift->value<>''))
	{
	if((($_POST[$row['COLUMN_NAME']]/($dift->value)>=10 or ($_POST[$row['COLUMN_NAME']]/($dift->value))<=0.1)) and ($from_lot->pdd_type=='LY'))
	{
		$diff=new difference_too_large;
		$diff->LotNo=$_GET['lot_no'];
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
echo "<BR>";
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
