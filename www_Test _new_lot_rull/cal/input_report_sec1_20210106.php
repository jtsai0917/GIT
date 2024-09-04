<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
$_SESSION['ok']=0;
$_SESSION['lot_no']=$_GET['lot_no'];
/////////////////////////取得分析項目表單////////////////////////////
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
$xx=1;
$columnX=0;
$_SESSION['redir']=$rev=$_SERVER['REQUEST_URI'];	
$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM,AnalyzeItem.ANI_NICKNAME, AnalyzeItem.ANI_FULLNAME
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.ELF_FORM = '".$_GET['efm']."') AND (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";
// echo $query;
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
//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	if($row['CTD_CUST_NO']<>''){$cust_no=$row['CTD_CUST_NO'];$cust_name=$row['CTD_CUST_NAME'];}
}
$query="SELECT  AnalyzeDesign.AND_APPLY_DATE
FROM             AnalyzeDesign
WHERE          (AnalyzeDesign.AND_LOT_NO = '".$_GET['lot_no']."')";
//echo $query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
//echo $query;
while ($row=mssql_fetch_array($result)){
	$dt=$row['AND_APPLY_DATE'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

<body>
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
  $pdd_short_name=$pdd->pdd_prod_short_name;
  $need_no=$pdd->needno;
  $_SESSION['needno']=$need_no;
  $_SESSION['cid']=trim($pdd->cid);
  if($pdd->pdd_type==''){$pdd->cid=='C00001';$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];
  if(substr($_GET['lot_no'],5,3)=='HIC'){$pdd->cid='C00000';$cust_name=get_cust_name($pdd->cid);$cust_no=$pdd->cid;}
  $_SESSION['cid']=$pdd->cid;
  if($_GET['ani_groupname']=='FPMS'){$_SESSION['select_cust']=$_SESSION['cid']=$pdd->cid='C00001';$cust_name='TYS Internal';}
  
  echo '客戶：'.get_cust_name($pdd->cid)."&nbsp;&nbsp;&nbsp;&nbsp;".$cust_no."</br>";
  echo "表單：".$table."</br>";
	echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
  $_SESSION['select_cust']=$cust_no;
  }
  
  if (trim($pdd->pdd_type)=='LY'){
	  if($pdd->cid=='C00001'){$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];}
	 if($_GET['ani_groupname']=='FPMS'){$_SESSION['select_cust']=$_SESSION['cid']=$pdd->cid='C00001';$cust_name='TYS Internal';}
  echo '客戶：'.get_cust_name($pdd->cid)."&nbsp;&nbsp;&nbsp;&nbsp;".$cust_no."</br>";
  echo "表單：".$table."</br>";
	echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
  $_SESSION['select_cust']=$cust_no;
  } 
  elseif((trim($pdd->pdd_type)=='BTL') or (trim($pdd->pdd_type)=='DM'))
  {	
  	if(substr($_GET['lot_no'],-1)=="_") // 內液處理
	{
		if($_SESSION['cid']=='C16043' or $_SESSION['cid']=='C20701' or $_SESSION['cid']=='C20702' or $_SESSION['cid']=='C20703' or $_SESSION['cid']=='C20704' or $_SESSION['cid']=='C20705' or $_SESSION['cid']=='C20707' or $_SESSION['cid']=='C20708' or $_SESSION['cid']=='C20709' or $_SESSION['cid']=='C20801' or $_SESSION['cid']=='C20901' or $_SESSION['cid']=='C20904' or $_SESSION['cid']=='C20942' or $_SESSION['cid']=='C20955' or $_SESSION['cid']=='C20956' or $_SESSION['cid']=='CXF001')
		{
			$lotid=substr($_GET['lot_no'],0,-1);
		//	echo "lot_no=".trim($_GET['lot_no'],-1);
		//	echo "<BR>";
			$query="SELECT   distinct     FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO, 
								  CUSTOMER_DATA.CTD_CUST_NAME
					FROM             FILLPLAN_DRUM_CUSTOMER INNER JOIN
								  CUSTOMER_DATA ON 
								  FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
					WHERE         (FDM_LOT_NO = '".$lotid."')";
		//	echo $query;
		//	echo "<BR>";
			$result=mssql_query($query);  
			$numRows = mssql_num_rows($result);
			if($numRows>1)
			{
				if($_SESSION['dm_cid']<>''){$_SESSION['select_cust']=$_SESSION['dm_cid'];}
				echo '選擇客戶:<select name="co0" id="co0">';
				echo '<option value="C00003">C00003--TYS DRUM 內液</option>';
				while($row=mssql_fetch_array($result))
				{
					echo '<option value="'.$row['CTD_CUST_NO'].'">'.$row['CTD_CUST_NO']."--".$row['CTD_CUST_NAME'].'</option>';
					$_SESSION['tmp1']=3;
				}
				echo '</select>';
				echo '<input type="submit" name="select_cust" id="select_cust" value="取得客規" /></br>';
				if($_SESSION['select_cust']=='C00000'){$_SESSION['select_cust']='C00003';}
				echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
				echo "表單：".$table."</br>";
				echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
			}
			elseif($numRows==1)
			{
				if($_SESSION['dm_cid']<>''){$_SESSION['select_cust']=$_SESSION['dm_cid'];}
				while($row=mssql_fetch_array($result))
				{
					$_SESSION['select_cust']=$row['CTD_CUST_NO'];
					$_SESSION['tmp1']=3;
				}
				if($_GET['ani_groupname']=='FPMS'){$_SESSION['select_cust']=$_SESSION['cid']=$pdd->cid='C00001';$cust_name='TYS Internal';}
				echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
				echo "表單：".$table."</br>";
				echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
			}
			elseif($numRows<1)
			{
				$_SESSION['select_cust']="C00003";
				echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
				echo "表單：".$table."</br>";
				echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
			}
		}//end if($_SESSION['cid']=='C16043')
		else
		{
			$_SESSION['select_cust']="C00003";
			echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
			echo "表單：".$table."</br>";
			echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
		}
	}
	else  // 充填口
	{
		if($_GET['pdd_prod_no']=='P001-200' or $_GET['pdd_prod_no']=='P002-190' or $_GET['pdd_prod_no']=='P006-330')
		{
			
				$_SESSION['select_cust']="C00003";
				echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
				echo "表單：".$table."</br>";
				echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
		}
		else
		{
			$query="SELECT   distinct     FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO, 
							  CUSTOMER_DATA.CTD_CUST_NAME
				FROM             FILLPLAN_DRUM_CUSTOMER INNER JOIN
							  CUSTOMER_DATA ON 
							  FILLPLAN_DRUM_CUSTOMER.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
				WHERE         (FDM_LOT_NO = '".$_GET['lot_no']."')";
	//	echo $query;
	//	echo "<BR>";
			$result=mssql_query($query);  
			$numRows = mssql_num_rows($result);
				if($_SESSION['dm_cid']<>''){$_SESSION['select_cust']=$_SESSION['dm_cid'];}
				if($numRows<1)
				{
					$_SESSION['select_cust']="C00001";
					if($_GET['ani_groupname']=='FPMS'){$_SESSION['select_cust']=$_SESSION['cid']=$pdd->cid='C00001';$cust_name='TYS Internal';}
					echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
					echo "表單：".$table."</br>";
					echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
				}
				if($numRows==1)
				{
					while($row=mssql_fetch_array($result))
					{
						$_SESSION['select_cust']=$row['CTD_CUST_NO'];
						if($_GET['ani_groupname']=='FPMS'){$_SESSION['select_cust']=$_SESSION['cid']=$pdd->cid='C00001';$cust_name='TYS Internal';}
					}
					if($_SESSION['select_cust']=='C00000'){$_SESSION['select_cust']='C00001';}
					echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
					echo "表單：".$table."</br>";
					echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
				}
				if($numRows>1)
				{
					echo '選擇客戶:<select name="co0" id="co0">';
					echo '<option value="C00001">C00001--TYS Internal</option>';
					while($row=mssql_fetch_array($result))
					{
						echo '<option value="'.$row['CTD_CUST_NO'].'">'.$row['CTD_CUST_NO']."--".$row['CTD_CUST_NAME'].'</option>';
						$_SESSION['tmp1']=3;
					}
					echo '</select>';
					echo '<input type="submit" name="select_cust" id="select_cust" value="取得客規" /></br>';
					if($_SESSION['select_cust']=='C00000'){$_SESSION['select_cust']='C00001';}
					echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
					echo "表單：".$table."</br>";
					echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
				}
		}
		
	}
  }
  ?>
  </td>
  
  <td>
<?php 
  	if($_GET['ani_groupname']=='FPMS'){$_SESSION['select_cust']=$_SESSION['cid']=$pdd->cid='C00001';$cust_name='TYS Internal';}
	$itemunit=showcust_spec($_SESSION['select_cust'],$_GET['pdd_prod_no'],$_GET['ani_groupname']) ; 
?>
    <p>自動引入上傳之檢驗報告內容，如果引入錯誤，請重新定義上傳欄位</p></td></tr></table>
  <table width="1200" border="1">
    <tr class="centet">
      <td width="200" bgcolor="#CCCCCC">日期: </td>
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
echo $sn;?>" size="4" readonly /></td></tr><tr>
      <td width="200" bgcolor="#CCCCCC">取樣瓶號碼：
<?php 

if(($_GET['ani_groupname']=='M13') or ($_GET['ani_groupname']=='M21') or substr($_SESSION['file'],-4,4)=='.txt' or substr($_SESSION['file'],-4,4)=='.TXT'){
	$smpno=new read_report;

	$smpno->group=$_GET['ani_groupname'];
	$smpno->url=$_SESSION['file'];
	echo "SSS";	$smpno->read();

}
else{
	$ss1=new read_report;
	$ss1->efm=$table;
	$ss1->item='Lot_No';
	$ss1->lot_no=$_GET['lot_no'];
	$ss1->url=$_SESSION['file'];
	$ss1->pid=$_GET['pdd_prod_no'];
	$ss1->group=$_GET['ani_groupname'];
	$ss1->read_SampleNo() ;
}

if(trim($_SESSION['SampleNo'])=='')	{$_SESSION['SampleNo']=$pdd_short_name;}
if(trim($_SESSION['SampleNoss'])=='')	{$_SESSION['SampleNoss']=$pdd_short_name;}
?>

      <input name="SampleNo" type="text" id="SampleNo" value="<?php if(!isset($_SESSION['SampleNo'])){echo $_SESSION['SampleNoss'];}else{echo $_SESSION['SampleNo'];}?>" size="10"  onchange="set_date_session(this.name,this.value)"/>
      選擇桶號：<select name="drumno2" id="drumno2" onChange="set_date_session(this.name,this.value)">
<?php
$sec=new sample;
if(substr($_GET['lot_no'],-1,1)=='_'){$o_lot=substr($_GET['lot_no'],0,-1);}else{$o_lot=$_GET['lot_no'];}
$sec->lot_no=$o_lot;
$sec->selections();
?>    
</select>

      <input type="submit" name="chdrumno" id="chdrumno" value="修改桶號" onClick="return confirm('確定修改?')"/>
      <td width="200" bgcolor="#CCCCCC">測試人員：
      <input name="Tester" type="text" id="Tester" value="<?php echo $_SESSION['uname']?>" size="10" readonly />
       
      <td width="200" bgcolor="#CCCCCC"> 前端處理人員:
      <input name="x5" type="submit" value="X"/>
      <input name="Operator" type="hidden" id="Operator" value="<?php echo $_GET['operator'];?>" size="10" readonly />
      <input name="op" type="text" value="<?php echo $_SESSION['userid'];?>" size="10" readonly />
      <input type="submit" name="an_first" id="button" value=" 工號  " onClick="window.open('./index.php?url=select_user', '_self');" />
      <input name="opname" type="text" value="<?php echo get_uname($_SESSION['userid']);?>" size="10" readonly />
      
    
    
  </table>
    
    
<table width="1200" border="1">
    <tr>
    <td width="30" bgcolor="#CCCCCC">項目</td>
    <td width="120" bgcolor="#CCCCCC">輸入值<span class="red">(不可為空白)</span></td>
    <td width="100" bgcolor="#CCCCCC">客規</td>
    <td width="50" bgcolor="#CCCCCC">DL</td>
    <td width="50" bgcolor="#CCCCCC">USL</td>
    <td width="50" bgcolor="#CCCCCC">LSL</td>
    <td width="100" bgcolor="#CCCCCC">客戶再分析標準/定量下限</td>
    </tr>    

<?php
$query="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$i=0;
while($row = mssql_fetch_array($result)){
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
	if($disc<>'X'){
	$ss=new read_report;
	$ss->efm=$table;
	$ss->item=$row['COLUMN_NAME'];
	$ss->url=$_SESSION['file'];
	$ss->pid=$_GET['pdd_prod_no'];
	$ss->group=$_GET['ani_groupname'];
	$ss->read();
	echo '<tr class="centet">';
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
		if(($_GET['ani_groupname'])<>'TN' or ($row['COLUMN_NAME']<>'Value1')){$_SESSION[$row['COLUMN_NAME']]=$ss->value ;	}
		if($row['COLUMN_NAME']=='Pd'){$_SESSION['Hx3']=$ss->value;}
		if($row['COLUMN_NAME']=='In'){$_SESSION['Ix3']=$ss->value;}
		if($row['COLUMN_NAME']=='Pt'){$_SESSION['Jx3']=$ss->value;}			
		
		if($_SESSION[$row['COLUMN_NAME']]=='' or ((substr($_SESSION[$row['COLUMN_NAME']],0,1)=='<') or (substr(trim($_SESSION[$row['COLUMN_NAME']]),0,1)=='>'))){echo ' style="background-color:white;"';}
		elseif($usl<>'' and $lsl<>'' and ($_SESSION[$row['COLUMN_NAME']]>$usl or $_SESSION[$row['COLUMN_NAME']]<$lsl)){echo ' style="background-color:red;"';$xx=0;;}
		elseif($stdu=='' and $usl<>'' and $_SESSION[$row['COLUMN_NAME']]<=$usl){echo ' style="background-color:white;"';}
	  	elseif(($_SESSION[$row['COLUMN_NAME']]<=$usl and $_SESSION[$row['COLUMN_NAME']]>$stdu) or ($_SESSION[$row['COLUMN_NAME']]>=$lsl and $_SESSION[$row['COLUMN_NAME']]<$stdl)) {echo ' style="background-color:yellow;"';$xx=0;}
	  	elseif((($_SESSION[$row['COLUMN_NAME']]>$usl) and ($_SESSION[$row['COLUMN_NAME']]<>'NULL') and ($spec<>NULL)) or (($_SESSION[$row['COLUMN_NAME']]<$lsl) and ($_SESSION[$row['COLUMN_NAME']]<>'NULL') and ($spec<>NULL))){echo ' style="background-color:red;"';$xx=0;}
	  	elseif($spec==NULL){echo ' style="background-color:white;"';}
	 	else{echo ' style="background-color:white;"';}
	 
	  if((($spec=='') or ($spec=='<')) and ($_SESSION[$row['COLUMN_NAME']]=='')){echo ' style="background-color:white;"';}
	  elseif((($_GET['ani_groupname']=='M13') or ($_GET['ani_groupname']=='M21')) and ($_SESSION[$row['COLUMN_NAME']]=='')){$value="0.000001";}
	  if((substr($_SESSION[$row['COLUMN_NAME']],0,1)=='<') or (substr(trim($_SESSION[$row['COLUMN_NAME']]),0,1)=='>')){$_SESSION[$row['COLUMN_NAME']]=0.000001;}
	  if(($_GET['ani_groupname'])=='TN' and ($row['COLUMN_NAME']=='Value1')){$value=$_SESSION['Value1'];}
	  else{
		  	$value=$_SESSION[$row['COLUMN_NAME']];
		  }
//	  echo ' value="'.$value.'"  onblur="'.'ShowName('."'".$row['COLUMN_NAME']."'".')"';
//		if((substr(trim($value),0,1)=='<') or (substr(trim($value),0,1)=='>')){$value=0.000001;}
	if($value=='#DIV/0!' and $_GET['pdd_prod_no']=='RR-001'){$value=0;}
	$aa[$columnX]=$row['COLUMN_NAME'];
	$columnX=$columnX+1;
 	echo ' value="'.$value.'" ';
	echo ' size="10"/>';
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
<?php }}} ?>
</table>

<table width="1200" border="1">
    <tr class="centet" >
      <td width="600" align="center"><font color="#FF0000">合否判定&nbsp;&nbsp;
      <?php if($xx==1){$x1="checked";echo "合";}else{$x1='unchecked';echo "否";} ?> </font>
      <input type="checkbox" name="Ok" id="Ok" <?php echo $x1;?> hidden>   &nbsp;&nbsp;&nbsp;&nbsp; 
        <input type="hidden" name="CHK1" id="CHK1" value="1" />
        <input type="hidden" name="CHK2" id="CHK2" value="1" />
        <input type="hidden" name="CHK3" id="CHK3" value="1" />
        <input type="hidden" name="CHK4" id="CHK4" value="1" />
        <input type="hidden" name="CHK5" id="CHK5" value="1" />
        <input type="hidden" name="CHK6" id="CHK6" value="1" />
        <input type="hidden" name="CHK7" id="CHK7" value="1" />
        <input type="hidden" name="CHK8" id="CHK8" value="1" />
        <input type="hidden" name="AnaManager" id="AnaManager" value="NULL" />
        <input type="hidden" name="TestDate" id="TestDate" value="<?php echo ddd($dt)." 00:00:00";?>" />
        <input type="hidden" name="AnalyzeTime" id="AnalyzeTime" value="<?php echo date("YmdHis") ?>" />
            <font color="#000000">     (ppm 及 ppb 的判定需有客規，若未設定客規請自行將數值除以1000.)</font>
      </td>
      <td width="400" align="center"><input type="submit" name="submit" id="submit" value=" 新  增 " onClick="return confirm('確定新增?')"/>
      <input type="hidden" name="MM_insert" value="form1">

      <input type="submit" name="cancel" id="cancel" value=" 取消  " onClick="window.open('<?php echo $_SESSION['redir']."&url=input_report_sec";?>', '_self');" />
      <input type="submit" name="check1" id="check1" value=" 檢 查 "/>
      <input type="button" name="define" id="define" value="定義EXCEL上傳欄位" onClick="window.open('../system/index.php?url=defineaniitem_&efm=<?php echo $_GET['efm'];?>&pid=<?php echo $_GET['pdd_prod_no'];?>','_self');" />
      <?php if ($_GET['ani_groupname']=="TM") {
		  echo '<input type="button" name="TM" id="TM" value="取得分析資料" onClick="window.open('."'/cal/ctm.php?pdd_chemical=".$_GET['pdd_chemical']."&ani_groupname=".$_GET['ani_groupname']."&lot_no=".$_GET['lot_no']."&cust_no=".$cust_no."&pid=".$_GET['pdd_prod_no']."', '_self');".'"'.' />';
	  }	  
	  ?>
      </td>
    </tr>
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
if (isset($_POST["submit_submit"])) 
{   
	//////////檢查樣品瓶號///////////
	if ($_POST['Ok']=="on"){$_POST['Ok']=1;}
	else {$_POST['Ok']=0;}
	//////////取得欄位名///////////
	
include ("../connections/conn.php");
$_SESSION['createtime']=date("YmdHis");


if($_POST['Ok']==0)
{
	$query="INSERT INTO dbo.analyze_fail
                          (lot_no, sample_no,PDD_PROD_NO,ani_group,ani_chemical,Tester,Analyzetime)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_GET['pdd_prod_no']."', '".$_GET['ani_groupname']."', '".$_GET['pdd_chemical']."','".$_SESSION['uid']."','".$_SESSION['createtime']."') ";
}
$query="INSERT INTO dbo.analyze_first
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['uptime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";
$result=mssql_query($query);
$query="INSERT INTO dbo.analyze_first1
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['uptime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";
$result=mssql_query($query);

$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$q1="INSERT INTO ".$table." (";
$q2=" VALUES     (";
while($row = mssql_fetch_array($result)){
	if(($row['COLUMN_NAME']=='B') and $_GET['pdd_chemical']=='H2O'){
		write_ttb(1,$_POST['SampleNo'],$_SESSION['BOK'],$_POST['Tester'],$_GET['lot_no'],"TLEQA9110101",$_POST['B'],$_POST['Operator'],$_SESSION['uptime']);
	}

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
		$_SESSION['ftb']=$from_lot->pdd_type;
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
	}
	}
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
        	$q2.="'".$_SESSION['uptime']."',";
			}
	else{
			$q1.="[".$row['COLUMN_NAME']."],";
    		$q2.="'".$_POST[$row['COLUMN_NAME']]."',";
	}
}
$q1=substr($q1,0,-1);
$q2=substr($q2,0,-1);
$qx=$q1.") 
".$q2.") ";
$query=$qx;
$_SESSION['qx']=$query;
$result = mssql_query($query);
if (!$result) {
    print("SQL statement failed with error:\n");
    print("   ".mssql_get_last_message()."\n");
  } 
jumpto($_SESSION['redir']."&url=input_report_sec");
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
	$cust_no=$_SESSION['select_cust']=$_POST['co0'];
	refresh();
}

if(isset($_POST['an_first'])){
$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result=mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0){
while($row=mssql_fetch_array($result)){
	$_SESSION[$row['COLUMN_NAME']."1"]=$_POST[$row['COLUMN_NAME']];
	jumpto("./index.php?url=select_user");
}
}
}

if(isset($_POST['cancel'])){
	keep_session();
	jumpto($_SESSION['redir']."&url=input_report_sec");
}

if(isset($_POST['button2']))
{
$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
$result=mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows>0)
	{
	while($row=mssql_fetch_array($result))
		{
			$_SESSION[$row['COLUMN_NAME']."1"]=$_POST[$row['COLUMN_NAME']];
			jumpto($rev);
		}
	}
}

if (isset($_POST["x5"])) 
{
	unset($_SESSION['userid']);
	unset($_SESSION['username']);
	refresh();
}

function write_ttb($sn,$smp_no,$ok,$uid,$lot,$table,$b,$op,$ani_time)
{
	$query="INSERT INTO ".$table."
                            (TestDate, CHK1, CHK2, LotNo, SerialNo, SampleNo, B, CHK3, CHK4, Ok, Tester, Operator, AnalyzeTime)
			VALUES          ('".date("Y-m-d")."',1,1,'".$lot."','".$sn."','".$smp_no."',".$b.",1,1,'".$ok."','".$uid."','".$op."','".$ani_time."')";
			$_SESSION['TMPQUERRY']=$query;
	$result=mssql_query($query);
}
if (isset($_POST["check1"])){
$num=count($aa);
for($k=0;$k<$num;$k++){
	$_SESSION[$aa[$k]]=$_POST[$aa[$k]];	
	echo $aa[$k].":".$_POST[$aa[$k]];	
	echo "<BR>";
}
 jumpto($_SESSION['lasturl1']."6");
}

if (isset($_POST["submit"])) {
	$num=count($aa);
for($k=0;$k<$num;$k++){
	$_SESSION[$aa[$k]]=$_POST[$aa[$k]];	
	echo $aa[$k].":".$_POST[$aa[$k]];	
	echo "<BR>";
	}
		$tmpurl="index.php?url=input_report_sec6&pdd_prod_no=".$_GET['pdd_prod_no']."&lot_no=".$_GET['lot_no']."&ani_groupname=".$_GET['ani_groupname']."&efm=".$_GET['efm']."&operator=".$_GET['operator']."&pdd_chemical=".$_GET['pdd_chemical']."&operator=".$_GET['operator'];
		jumpto($tmpurl);
//	 	jumpto($_SESSION['lasturl1']."6");
}

?>
