<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
auth('5-01',$_SESSION['aut']);
remurl("input_report_sec");
datepick();
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
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
<?php 
$_SESSION['lot_no']=$_GET['lot_no'];
$urllast=$_SESSION['urllast']=$_SERVER['QUERY_STRING'];
$xx=1;
$_SESSION['uptime']='';

/////////////////////////取得分析項目表單////////////////////////////

$_SESSION['cnt']=0;
unset($_SESSION['unit']);
$_SESSION['redir']=$rev=$_SERVER['REQUEST_URI'];	
lasturl1();
$query="SELECT DISTINCT ELEMENT_FORM.ELF_FORM,AnalyzeItem.ANI_NICKNAME, AnalyzeItem.ANI_FULLNAME
FROM              ELEMENT_FORM INNER JOIN
                            AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$_GET['pdd_chemical']."') AND (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";
//

//$_SESSION['QUERY']=$query;
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	if($_GET['efm']==''){
	$table=$row['ELF_FORM'];
	}
	else{
		$table=$_GET['efm'];
		}
		
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
  $cust_no=trim($pdd->cid);
//  $cust_no=$_SESSION['cid']=trim($pdd->cid);
  if($pdd->pdd_type==''){$pdd->cid=='C00001';$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];
  if(strpos($_GET['lot_no'],"HIC")>0){$pdd->cid='C00000';$cust_name=get_cust_name($pdd->cid);$cust_no=$pdd->cid;}
  if ($cust_no==''){
	  $_SESSION['cid']=$_SESSION['select_cust']='C00001';
  		$_SESSION['tmp1']=1;
  }
  if($_GET['ani_groupname']=='FPMS'){$_SESSION['cid']=$cust_no='C00001';$cust_name='TYS Internal';}
  echo '客戶：'.get_cust_name($_SESSION['cid'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['cid']."</br>";
  echo "表單：".$table."</br>";
  $_SESSION['table']=$table;
	echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
  $_SESSION['select_cust']=$cust_no;
  
  }
  
  if (trim($pdd->pdd_type)=='LY' or trim($pdd->pdd_type)=='TANK' )
  {
//	echo "PDD_TYPE=".$pdd->pdd_type;
	if($pdd->cid=='C00001'){$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];}
	if($pdd->cid==''){$pdd->cid='C00001';}
	
	if($_GET['ani_groupname']=='FPMS'){$_SESSION['cid']=$pdd->cid='C00001';$cust_name='TYS Internal';}
	
	echo '客戶：';
	select_cust($_GET['pdd_prod_no'],$cust_no);
	echo "&nbsp;&nbsp;(第一個為原訂客戶)</br>";
	echo "表單：".$table."</br>";
	echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
  	$_SESSION['select_cust']=$_SESSION['cid'];
  } 
  elseif((trim($pdd->pdd_type)=='BTL') or (trim($pdd->pdd_type)=='DM'))
  {	
  	if(substr($_GET['lot_no'],-1)=="_") // 內液處理	
	{		
		$lotno=substr($_GET['lot_no'],0,-1);
		$query="UPDATE FILLPLAN_OUT_DECIDE SET PDD_PROD_NO ='".$_GET['pdd_prod_no']."' WHERE (FDM_LOT_NO = '".$lotno."')";
		$result=mssql_query($query);
	
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
			
				$_SESSION['select_cust']="C00001";
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
					echo '客戶：<font color="#FF0000"">'.get_cust_name($_SESSION['select_cust'])."&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['select_cust']."</font></br>";
					echo "表單：".$table."</br>";
					echo "品名： ".get_pdd_name($_GET['pdd_prod_no'])."     項目:".$_GET['ani_groupname'].'</br>';
				}
				if($numRows==1)
				{
					while($row=mssql_fetch_array($result))
					{
						$_SESSION['select_cust']=$row['CTD_CUST_NO'];
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
  		if($_GET['ani_groupname']=='FPMS'){$_SESSION['select_cust']=$pdd->cid='C00001';$cust_name='TYS Internal';}
 		$itemunit=showcust_spec($_SESSION['select_cust'],$_GET['pdd_prod_no'],$_GET['ani_groupname']) ; 
	?>
  <p>&nbsp;</p></td></tr></table>
  <table width="1200" border="1">
    <tr class="centet">
      <td width="500" bgcolor="#CCCCCC">日期: <?php echo date("YmdHis");?></td>
      <td width="300" bgcolor="#CCCCCC">Lot NO：
      <input name="LotNo" type="text" id="LotNo" value="<?php echo $_GET['lot_no']?>" size="14" readonly /></td>
      <td width="400" bgcolor="#CCCCCC"> 序號：
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
      <td width="200" bgcolor="#CCCCCC">取樣瓶號：
        <input name="SampleNo" type="text" id="SampleNo" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['SampleNo'];?>" size="6"/>
        <input type="checkbox" name="keep_sample" >
        (保留樣品)
        <label for="keep_sample"></label>
        桶號：
        <select name="drumno2" id="drumno2" onchange="set_date_session(this.name,this.value)">
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
		if($_SESSION['select_cust']==''){$_SESSION['select_cust']='C00001';}
		$i=$i+1;
		if($disc==''){$showitem=$row['COLUMN_NAME'];}
		elseif($disc=='NULL'){$showitem=$row['COLUMN_NAME'];}
		else {$showitem=$disc;}
		list ($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl)=get_test_spec($row['COLUMN_NAME'],$_GET['pdd_prod_no'],$_SESSION['select_cust'],$_GET['ani_groupname']);
		$_SESSION['USLP']=$usl;
		$_SESSION['LSLP']=$lsl;
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
		elseif(($_SESSION[$row['COLUMN_NAME']]==2 or $_SESSION[$row['COLUMN_NAME']]==3) and $_GET['ani_groupname']=='INHP'){  ;}
		elseif($usl<>'' and $lsl<>'' and ($_SESSION[$row['COLUMN_NAME']]>$usl or $_SESSION[$row['COLUMN_NAME']]<$lsl)){echo ' style="background-color:red;"';$xx=0;;}
		elseif($stdu=='' and $usl<>'' and $_SESSION[$row['COLUMN_NAME']]<=$usl){echo ' style="background-color:white;"';}
		elseif($usl<>'' and $lsl<>'' and ($_SESSION[$row['COLUMN_NAME']]>$usl or $_SESSION[$row['COLUMN_NAME']]<$lsl)){echo ' style="background-color:green;"';$xx=0;;}
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
      <td width="600" align="center">
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
      <input type="hidden" name="AnalyzeTime" id="AnalyzeTime" value="<?php echo $_SESSION['createtime']; ?>" />
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      <?php if($xx==1){$x1="checked";}else{$x1='unchecked';;} ?>
      <input type="checkbox" name="Ok" id="Ok" <?php echo $x1;?> hidden >   </td>
      <td width="600" align="center">
		<input type="submit" name="submit" id="submit" value=" 新  增 "/>
      <input type="hidden" name="MM_insert" value="form1">

      <input type="submit" name="leave" id="button" value=" 離  開  " onClick="window.open('<?php echo $_SESSION['lasturl'];?>', '_self');" />
      
      <input type="button" name="rework" id="rework" value=" 再分析作業 " onClick="window.open('./index.php?url=rework&lot_no=<?php echo $_GET['lot_no'];?>&ani_groupname=<?php echo $_GET['ani_groupname'];?>','_self');" />
      <input type="button" name="define" id="define" value="定義EXCEL上傳欄位" onClick="window.open('../system/index.php?url=defineaniitem_&efm=<?php echo $_GET['efm'];?>&pid=<?php echo $_GET['pdd_prod_no'];?>','_self');" />      <?php if ($_GET['ani_groupname']=="TM") {
		  echo '<input type="button" name="TM" id="TM" value="取得TM分析資料" onClick="window.open('."'/cal/index.php?url=ctm&pdd_chemical=".$_GET['pdd_chemical']."&operator=".$_GET['operator']."&ani_groupname=".$_GET['ani_groupname']."&lot_no=".$_GET['lot_no']."&cust_no=".$cust_no."&pid=".$_GET['pdd_prod_no']."', '_self');".'"'.' />';
	  }?>
      <input type="submit" name="reload" id="reload" value=" 頁面重整 "/></td>
    </tr></table>
    </br>  
<span class="a18">報告內容:</span>
    <?php
session_start();
// get ani_group spec

$query=" SELECT DISTINCT QC_Spec.ID, QC_Spec.SpecNo, QC_Spec.SpecVer, QC_Spec.IX, QC_Spec.ItemName, 
                            QC_Spec.ItemUnit, QC_Spec.USL, QC_Spec.LSL, QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, 
                             QC_Spec.ShowMode as SM, QC_Spec.state, QC_Spec.createuser, QC_Spec.createts, QC_Spec.Digit, 
                            QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, QC_Spec.STD_L, QC_Spec.showUSL, 
                            QC_CustProdSpec.CustNo, QC_CustProdSpec.ProdNo, QC_Spec.ItemName, 
                            QC_Item.ItemNickName, AnalyzeItem.ANI_DATAFIELD
FROM           QC_CustProdSpec INNER JOIN
                            QC_Spec ON QC_CustProdSpec.SpecVer = QC_Spec.SpecVer AND 
                            QC_CustProdSpec.SpecNo = QC_Spec.SpecNo INNER JOIN
                            QC_Item ON QC_Spec.ItemName = QC_Item.ItemName INNER JOIN
                            AnalyzeItem ON QC_Item.ItemNickName = AnalyzeItem.ANI_NICKNAME
WHERE       (QC_CustProdSpec.CustNo = '".$_SESSION['select_cust']."') AND (QC_CustProdSpec.ProdNo = '".$_GET['pdd_prod_no']."') AND 
                            (AnalyzeItem.ANI_GROUPNAME = '".$_GET['ani_groupname']."')";

$result=mssql_query($query);
$tt=0;
while($row=mssql_fetch_array($result)){
	$cspec[$row['ANI_DATAFIELD']]=$row;
//	echo $row['ANI_DATAFIELD'].":".$cspec[$row['ANI_DATAFIELD']]['ItemName']."<BR>";	
}
$_SESSION['SOSOSOSOSOSOSOSO']=$cspec;							

if($_GET['pdd_prod_no']=='GM-ICP75BLK' or $_GET['pdd_prod_no']=='GM-ICPUPWBLK'){echo '<input type="submit" name="slope" value="列印" ><input type="submit" name="slope_" value="產生 (RSQ/SLOPE)" >';}
include ("../connections/conn.php");
if($_GET['pdd_prod_no']=='GM-TOCM'){
	echo '<input type="submit" name="print_tocm" value=" TOCM 列印"	>';
}
$query="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
//echo $query;
$result = mssql_query($query);
echo '<table width="1200" border="1"><tr>';
echo '<td width="30">列印</td>';
echo '<td width="30">編輯</td><td width="14">桶號</td>';
while($row = mssql_fetch_array($result))
{
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
echo '<td>前端處理人員</td><td>取樣人員</td></tr>';
$query="SELECT          ".substr($str,0,-2).",[SerialNo], [AnalyzeTime]
FROM              ".$table." 
WHERE          (LotNo  = '".$_GET['lot_no']."') order by AnalyzeTime";
// echo $query;
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
	$query1="select * from PRODUCT_TYPE where PDD_PROD_NO='".$_GET['pdd_prod_no']."' ";
	$result1 = mssql_query($query1);
	while($row1 = mssql_fetch_array($result1))
	{ $GM=$row1['GM_TS'];}
		if(trim($GM)=='Y')
		{
			echo '<td><a href="print_ani_result1.php?table='.$table.'&AnalyzeTime='.$row['AnalyzeTime'].'&lot_no='.$_GET['lot_no'].'&ani_groupname='.$_GET['ani_groupname'].'&pdd_chemical='.$_GET['pdd_chemical'].'&pdd_prod_no='.$_GET['pdd_prod_no'].'" target="new">列印</a></td>';
		}
	else
	{
	echo '<td><a href="print_ani_result.php?table='.$table.'&AnalyzeTime='.$row['AnalyzeTime'].'&lot_no='.$_GET['lot_no'].'&ani_groupname='.$_GET['ani_groupname'].'&pdd_chemical='.$_GET['pdd_chemical'].'&pdd_prod_no='.$_GET['pdd_prod_no'].'" target="new">列印</a></td>';
	}
	if((($_SESSION['uname']==$row['Tester']) or ($auth->keyin_ok=='1')) and (($row['AnalyzeTime']+3000000)>date("YmdHis"))){echo '<td width="30"><a href="index.php?'.$urllast.'&smpno='.$row['SampleNo'].'&Ok='.$row['Ok'].'&ani_time='.$row['AnalyzeTime'].'&id='.$id.'&efm='.$table.'&url=edit_report&first='.$analyze_1st->first.'" target="new">編輯</a></td>';}
	else{echo '<td></td>';}
	echo '<td>'.getdrumno($row['SampleNo'],$_GET['lot_no']).'</td>'; 
	for ($i=0;$i<$field;$i++)
		{	
			$column_name=mssql_field_name($result,$i);
//	 		list ($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl)=get_test_spec($column_name,$_GET['pdd_prod_no'],$_SESSION['select_cust'],$_GET['ani_groupname']);
			
			$spec=$cspec[$column_name]['ANI_DATAFIELD'];
			$spec=$cspec[$column_name]['SpecNo'];
		 	$$dl=$cspec[$column_name]['DL'];
			$usl=$cspec[$column_name]['USL'];  //客規
	      	$stdu=$cspec[$column_name]['STD_U'];  //再分析標準
			$lsl=$cspec[$column_name]['LSL'];
		 	$ps=$cspec[$column_name]['ItemUnit'];
			$stdl=$cspec[$column_name]['STD_L'];
			$showmode=$cspec[$column_name]['SM'];		
			
	//		if($column_name=='PP01'){$_SESSION['ss']=array($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl);}		
			
			/*	
	  		if((($stdu<>'') and ($stdl<>'')) and (($row[$i]>$stdu) or ($row[$i]<$stdl))){echo '<td  style="background-color:#FF66CC;"';}
			elseif(($row[$i]==2 or $row[$i]==3) and $_GET['ani_groupname']=='INHP'){  ;}
			elseif($usl<>'' and $lsl<>'' and $row[$i]>$usl or $row[$i]<$lsl){echo '<td style="background-color:red;"';$xx=0;;}
			elseif($stdu=='' and $usl<>'' and $row[$i]<=$usl){echo ' <td style="background-color:white;"';}
			elseif(($row[$i]>$stdu) and ($row[$i]<=$usl) and $stdu<>'') {echo '<td  style="background-color:yellow;" ';}
			elseif(($row[$i]<$lsl) and ($row[$i]<>'NULL') and ($spec=='') and ($usl=='') and ($lsl<>'')){echo '<td  style="background-color:#FF66CC;"';}
	  		elseif(($row[$i]>$spec) and ($row[$i]<>'NULL') and ($spec<>NULL)){echo '<td  style="background-color:#FF66CC;"';}
			if(($row[$i]>$stdu) and ($row[$i]<$stdl) and ($spec<>NULL)){echo '<td  style="background-color:#FF66CC;"';}
	 		else{echo '<td style="background-color:#FFFFFF;"';}
			*/
			
			if($showmode=='<DL'or $showmode=='<USL'){
				if($usl<>'' and $row[$i]>$usl){echo '<td  style="background-color:#FF66CC"';}
				elseif($stdu<>'' and $row[$i]>$stdu){echo '<td  style="background-color:yellow;"';}
				else{echo '<td';}
			}
			elseif($showmode=='>LSL'){
				if($lsl<>'' and $row[$i]<$lsl){echo '<td  style="background-color:#FF66CC"';}
				elseif($stdl<>'' and $row[$i]<$stdl){echo '<td  style="background-color:yellow;"';}
				else{echo '<td';}
			}
			elseif($showmode=='VALUE'){
				
				if($row[$i]>$usl or $row[$i]<$lsl){echo '<td  style="background-color:#FF66CC"';}
				elseif(($row[$i]>$stdu and $row[$i]<$usl) or ($row[$i]<$stdl and $row[$i]>$lsl)){echo '<td  style="background-color:yellow;"';}
				else{echo '<td';}
			}
			else{echo '<td';}
			echo ' >'.$row[$i].'</td>';
			
		}
	echo "<td>".get_uname($analyze_1st->first)."</td>";
	echo "<td>".get_uname($analyze_1st->sampler)."</td>";
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
      <input type="checkbox" name="keep_sample2" >
(樣品不合格)
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
echo '<td>再確認/再取樣</td>';
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
	echo '<td>'.re_test(trim($row['FILE_LOT_NO']),trim($_GET['ani_groupname']),trim($row['FILE_UPLOAD_TIME'])).'</td>';
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
	if($_POST['keep_sample']=='on'){  //寫入保留樣品資料表
		$chkre="select *  from analyze_first1 where lot_no='".$_GET['lot_no']."' and  work_type=702 ";
		echo "<BR>".$chkre."<BR>";
		$resultre = mssql_query($chkre);
		$numRowsre = mssql_num_rows($resultre);	
		if($numRowsre==0)
		{
			$query="INSERT INTO dbo.analyze_first1
								  (lot_no, sample_no, create_time, create_user,work_type)
				VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".date("YmdHis")."','".$_SESSION['uid']."',702)";
//			echo "<BR>".$query."<BR>";
			$result=mssql_query($query);
		}
		
		$query="INSERT INTO Sample_Keeping (Lot_No, sample_no, keep_datetime, creator) VALUES  
		('".$_GET['lot_no']."','".$_POST['SampleNo']."','".date("YmdHis")."','".$_SESSION['uid']."')";
//		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
		$_SESSION['sample_no']='';
		$_SESSION['smp_lot']='';
	}
		if($_POST['keep_sample2']=='on'){  //寫入不合格樣品資料表
		$_POST['disc']="不合格樣品".$_POST['disc'];
		$query="SELECT  Sample_Failure.* FROM Sample_Failure WHERE (Lot_No = N'".$_GET['lot_no']."') AND (sample_no = N'".$_POST['SampleNo']."')";
		$result=mssql_query($query);
		$nums=mssql_num_rows($result);
		if($nums==0)
		{
			$query="INSERT INTO Sample_Failure (Lot_No, sample_no, upload_datetime, creator) VALUES  
			('".$_GET['lot_no']."','".$_POST['SampleNo']."','".date("YmdHis")."','".$_SESSION['uid']."')";
			$result=mssql_query($query);
		}
		else
		{
			my_msg("此瓶已記錄過");	
		}
		$_SESSION['sample_no']='';
		$_SESSION['smp_lot']='';
	}


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
	$fullpath1="/var/www/www_Test".$fullpath;
	chmod($fullpath1,777);
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
	if($_GET['ani_groupname']=='M13' or $_GET['ani_groupname']=='P' or $_GET['ani_groupname']=='A' or $_GET['ani_groupname']=='RAI')
	{
		
		scriptconfirm1("是否上傳數據?",$_SESSION['redir']."M13",$_SESSION['redir']."1");
	}
	else
	{
		scriptconfirm("是否上傳數據?","只上傳報告‧",$_SESSION['redir']."1");
	}
//	my_msg("是否自動上傳數據",$_SESSION['redir']."1");
}

if(isset($_POST["delete"]))
{   
	if($_SESSION['uname']==$_SESSION['savename'] or $_SESSION['uid']=='E903' or $_SESSION['uid']=='E319' or $_SESSION['uid']=='E1025')
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
		
			
					
	echo '<script>document.location.href="'.$ulink.'";</script>';		
	}
	
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

if(isset($_POST["submit"])) 
{  
	$_SESSION['keep_sample']=$_POST['keep_sample'];
	$query="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."') order by DATA_TYPE";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result)){
		$_SESSION[$row['COLUMN_NAME']]=$_POST[$row['COLUMN_NAME']];
	}
	jumpto($_SESSION['lasturl1']."6");
}	
//refresh();


function getdrumno($smpno,$lotno)
{
	$smpno=trim($smpno);
	$query="SELECT DISTINCT SMA_DRUMNO
FROM             dbo.Sample_All
WHERE         (SMA_ID = '".$smpno."') AND (SMA_LOT = '".$lotno."') AND (ISREWORK = 0)";
//echo $query."<BR>";
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
	$_SESSION['SampleNo']=$_POST['SampleNo'];
	$_SESSION['createtime']=date("YmdHis");
$query_smp="select * from sample where SMP_ID='".$_SESSION['SampleNo']."'";
// echo $query_smp."<Br>";
$result_smp=mssql_query($query_smp);
echo $numrows_smp=mssql_num_rows($result_smp)."<BR>";
if($numrows_smp==0){
	if(substr($_SESSION['SampleNo'],0,1)=='T'){$met="PFA";}
	else{$met="PE";}
	$query_creat_smp="INSERT INTO [dbo].[Sample] ([SMP_ID], [SMP_MAT], [SMP_MID_ID], [SMP_START], [SMP_TIMES], [SMP_SERVICE], [SMP_LOT], [SMP_DRUMNO], [SMP_USER], [SMP_OUT], [SMP_SMP], [SMP_ANA], [SMP_BACK], [SMP_SAVE], [SMP_SAVE_TIME], [SMP_FINISH], [SMP_JUNK], [SMP_JUNK_D]) VALUES (N'".$_SESSION['SampleNo']."', N'".$met."', N'".$_SESSION['pid']."', N'".date("Ymd")."', 1,1, '".$_GET['lot_no']."','".$_POST['drumno2']."', NULL, NULL, '".$_SESSION['uid']."', NULL, NULL, NULL, NULL, NULL, NULL, NULL)"; 
//	echo "Q1:".$query_creat_smp."<BR>";
	$result_smp_creat=mssql_query($query_creat_smp);							
}
$query_sma="select * from sample_all where SMA_ID='".$_SESSION['SampleNo']."'";
$result_sma=mssql_query($query_sma);
$numrows_sma=mssql_num_rows($result_sma);
if($numrows_sma==0){
	$query_creat_sma="INSERT INTO dbo.Sample_All
                            (SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT, SMA_USER, SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
                            SMA_SAVE, SMA_SAVE_TIME, ISREWORK, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
                            SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
                            SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
							VALUES          ('".$_SESSION['SampleNo']."',1,1,'".$_GET['lot_no']."',
							NULL,NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."',NULL,
							NULL,NULL,NULL,'".$_POST['drumno2']."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
//	echo "Q2:".$query_creat_sma."<BR>";
	$result_sma_creat=mssql_query($query_creat_sma);							
}


	$query="UPDATE        dbo.Sample_All
SET                  SMA_DRUMNO = '".$_POST['drumno2']."'
WHERE         (SMA_ID = N'".$_POST['SampleNo']."') AND (SMA_LOT = '".$_GET['lot_no']."')";
	$_SESSION['YSLSLT']= $query;
$result=mssql_query($query);
	$query="UPDATE        dbo.Sample
SET                  SMP_DRUMNO = '".$_POST['drumno2']."'
WHERE         (SMP_ID = '".$_POST['SampleNo']."') AND (SMP_LOT = '".$_GET['lot_no']."')";
	$_SESSION['YSLSLT']= $query;
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
function scriptconfirm1($str,$str1,$link)
{
	echo '
	<script>
if(confirm("'.$str.'"))
{
document.location.href="'.$link.'";
}
else
{
document.location.href="'.$str1.'";
}
</script> ';	
}

if(isset($_POST['print_tocm'])){
	$i=0;
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	$query="select top 2 * from ".$_GET['efm']." where  (LotNo = '".$_GET['lot_no']."') order by AnalyzeTime";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$aa[$i][0]=$row['AnalyzeTime'];	
		$aa[$i][1]=$row['SampleNo'];		
		$aa[$i][2]=$row['UPW60G'];		
		$aa[$i][3]=$row['H2SO4'];		
		$aa[$i][4]=$row['UPW_H2SO4'];			
		$aa[$i][5]=$row['TIMES'];		
		$aa[$i][6]=$row['UPW_1'];			
		$aa[$i][7]=$row['TEN_1'];			
		$aa[$i][8]=$row['UPW_2'];			
		$aa[$i][9]=$row['TEN_2'];			
		$aa[$i][10]=$row['UPW_3'];			
		$aa[$i][11]=$row['TEN_3'];			
		$aa[$i][12]=$row['UPW_4'];			
		$aa[$i][13]=$row['TEN_4'];			
		$aa[$i][14]=$row['AVG_UPW'];			
		$aa[$i][15]=$row['AVG_TEN'];			
		$aa[$i][16]=$row['TOC'];			
		$aa[$i][17]=$row['Tester'];		
		$i++;			
	}
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./form/TOCM.xlsx");
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4',iconv("big5","utf-8",std($aa[1][0])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6',iconv("big5","utf-8",$_GET['lot_no']));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D7',iconv("big5","utf-8",($aa[1][1])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E9',iconv("big5","utf-8",($aa[0][2])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E10',iconv("big5","utf-8",($aa[1][2])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H9',iconv("big5","utf-8",($aa[0][3])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H10',iconv("big5","utf-8",($aa[1][3])));

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K9',iconv("big5","utf-8",($aa[0][4])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K10',iconv("big5","utf-8",($aa[1][4])));
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N9',iconv("big5","utf-8",($aa[0][5])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N10',iconv("big5","utf-8",($aa[1][5])));
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D14',iconv("big5","utf-8",($aa[0][6])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D15',iconv("big5","utf-8",($aa[0][7])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E14',iconv("big5","utf-8",($aa[0][8])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E15',iconv("big5","utf-8",($aa[0][9])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F14',iconv("big5","utf-8",($aa[0][10])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F15',iconv("big5","utf-8",($aa[0][11])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G14',iconv("big5","utf-8",($aa[0][12])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G15',iconv("big5","utf-8",($aa[0][13])));
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D16',iconv("big5","utf-8",($aa[1][6])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D17',iconv("big5","utf-8",($aa[1][7])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E16',iconv("big5","utf-8",($aa[1][8])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E17',iconv("big5","utf-8",($aa[1][9])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F16',iconv("big5","utf-8",($aa[1][10])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F17',iconv("big5","utf-8",($aa[1][11])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G16',iconv("big5","utf-8",($aa[1][12])));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G17',iconv("big5","utf-8",($aa[1][13])));	
	
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
	$objWriter->save("../tmp/TOCM.xlsx");
	echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp/TOCM.xlsx";</script>';	
}

if(isset($_POST['slope']) or isset($_POST['slope_']))
{
	if($_GET['ani_groupname']=='M21' and $_GET['pdd_prod_no']=='GM-ICP75BLK'){$fname='SLOPE21.xlsx';}
	elseif($_GET['ani_groupname']=='TT_B'){$fname='SLOPE_B.xlsx';}
	elseif($_GET['ani_groupname']=='M21' and $_GET['pdd_prod_no']=='GM-ICPUPWBLK'){$fname='SLOPE21_1.xlsx';}
	require_once "../PHPEXCEL/Classes/PHPExcel.php";
	require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
	echo "./form/".$fname;
	$reader= PHPExcel_IOFactory::createReaderForFile("./form/".$fname);
	$reader->setReadDataOnly(true);
	$excel= $reader->load("./form/".$fname);	
	$sheet = $excel->getActiveSheet(0); //讀取第一個工作表(編號從 0 開始)
	$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
	$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
	$highestRows = $sheet->getHighestRow(); //最高行數。從1開始    
	$SheetName = $sheetNames[0];
	$excelData[$s]['SheetName'] = $SheetName;
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./form/".$fname);
	
	for($i=2;$i<$highestColumns;$i++)
	{
		$AA=trim($sheet->getCellByColumnAndRow($i,1)->getValue());
		$AA=substr($AA,-12);
		$AA=trim(substr($AA,0,5));
		$query="select [".$AA."] from ".$_GET['efm']." where LotNo='".$_GET['lot_no']."' and (SampleNo='BLK' or SampleNo='BK')";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,3,$row[0]);
		if($_GET['ani_groupname']=='TT_B'){$query="select [".$AA."] from ".$_GET['efm']." where LotNo='".$_GET['lot_no']."' and (SampleNo='0.2' or SampleNo='0.1')"; echo $query;}
		else{$query="select [".$AA."] from ".$_GET['efm']." where LotNo='".$_GET['lot_no']."' and SampleNo='0.1'";}
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,4,$row[0]);
		$query="select [".$AA."] from ".$_GET['efm']." where LotNo='".$_GET['lot_no']."' and SampleNo='0.5'";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,5,$row[0]);
	}
	
/*	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./form/".$fname);
	$query="select column_name from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = '".$_GET['efm']."' ";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result))
	{
		
	}
*/	
	
	$objPHPExcel->getActiveSheet()->getStyle( 'A1:u7')->getFill()->getStartColor()->setARGB('FFFFFFFF');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
 	$objWriter->save("../tmp/".$fname);	
	if(isset($_POST['slope'])){
		echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp/'.$fname.'";</script>';	
	}
}

if(isset($_POST['slope_']))
{

	//寫入RSQ,SLOPE
	
	$creat_day=date("Y-m-d");
	$creat_time=date("YmdHis");
	$lot_rsq=substr($_GET['lot_no'],0,-2)."RSQ";
	$lot_slop=substr($_GET['lot_no'],0,-2)."SLOP";

	$query="INSERT INTO ".$_GET['efm']."
                   (TestDate, CHK1, CHK2, LotNo, SerialNo, SampleNo, CHK3, CHK4, Ok, Tester, Operator, AnaManager, AnalyzeTime)
VALUES  ( CONVERT(DATETIME, '".$creat_day." 00:00:00', 102),1,1,'".$lot_rsq."',+1,'UPW',1,1,1,'".getusername($_SESSION['uid'])."','system','system','".$creat_time."')";
	$result=mssql_query($query);
	$query="INSERT INTO ".$_GET['efm']."
                   (TestDate, CHK1, CHK2, LotNo, SerialNo, SampleNo, CHK3, CHK4, Ok, Tester, Operator, AnaManager, AnalyzeTime)
VALUES  ( CONVERT(DATETIME, '".$creat_day." 00:00:00', 102),1,1,'".$lot_slop."',+1,'UPW',1,1,1,'".getusername($_SESSION['uid'])."','system','system','".$creat_time."')";
	$result=mssql_query($query);
	//echo $query;
	
	$query="update ".$_GET['efm']." set ";
	$query1="update ".$_GET['efm']." set ";
	
	$reader= PHPExcel_IOFactory::createReaderForFile("../tmp/".$fname);
	$reader->setReadDataOnly(true);
	$excel= $reader->load("../tmp/".$fname);	
	$sheet = $excel->getActiveSheet(0); //讀取第一個工作表(編號從 0 開始)
	$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
	$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
	$highestRows = $sheet->getHighestRow(); //最高行數。從1開始    
	$SheetName = $sheetNames[0];
	$excelData[$s]['SheetName'] = $SheetName;
	for($i=2;$i<$highestColumns;$i++)
	{
		$AA=trim($sheet->getCellByColumnAndRow($i,1)->getValue());
		$AA=substr($AA,-12);
		$AA=trim(substr($AA,0,5));
		$BB=trim($sheet->getCellByColumnAndRow($i,6)->getCalculatedValue());
		$CC=trim($sheet->getCellByColumnAndRow($i,7)->getCalculatedValue());
		$BB=round($BB);
		$CC=round($CC,5);
		//echo $AA.'SLOPE='.$BB.',RSQ='.$CC.'<BR>';
		$xx[$i][$AA]['SLOPE']=$BB;
		$_SESSION['SLOP'][$AA]=$BB;
		$xx[$i][$AA]['RSQ']=$CC;
		$_SESSION['RSQ'][$AA]=$CC;
		if($BB<>'#N/A'){$query.="[".$AA."]=".$BB.",";}
		else{$query.="[".$AA."]='',";}
		if($CC<>'#N/A'){$query1.="[".$AA."]=".$CC.",";}
		else{$query1.="[".$AA."]='',";}
	//	$query.="[".$AA."]=".$BB.",";
	//	$query1.="[".$AA."]=".$CC.",";
	}	
	for($i=0;$i<2;$i++)
	{
	$yy=0;
	if($i==0){$btl='SLOP';}else{$btl='RSQ';}
	$lot=substr($_GET['lot_no'],0,-2).$btl;
		$query3="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".$table."')";
		if($_GET['ani_groupname']=='PMS')
		{ $query3="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS inner join AnalyzeItem on AnalyzeItem.ANI_ID = INFORMATION_SCHEMA.COLUMNS.COLUMN_NAME WHERE (TABLE_NAME ='".$table."') order by AnalyzeItem.ANI_ORDER";}
		$result3 = mssql_query($query3);
		$numRows3 = mssql_num_rows($result3);
		while($row3 = mssql_fetch_array($result3))
		{
			$pdd_prod_no=substr($_GET['pdd_prod_no'],0,-3).$btl;
			list ($spec,$dl,$usl,$stdu,$ps,$stdl,$lsl)=get_test_spec($row3['COLUMN_NAME'],$pdd_prod_no,$_SESSION['select_cust'],$_GET['ani_groupname']);
			//echo $btl.','.$row3['COLUMN_NAME'].'='.$_SESSION[$btl][$row3['COLUMN_NAME']].',lsl='.$lsl.',usl='.$usl.',stdu='.$stdu.',stdl='.$stdl.'<br>';
			if(trim($_SESSION[$btl][$row3['COLUMN_NAME']])==''){continue;}
			if((trim($_SESSION[$btl][$row3['COLUMN_NAME']])==2 or trim($_SESSION[$btl][$row3['COLUMN_NAME']])==3) and $_GET['ani_groupname']=='INHP'){}
			elseif($usl<>'' and $lsl<>'' and (trim($_SESSION[$btl][$row3['COLUMN_NAME']])>$usl or trim($_SESSION[$btl][$row3['COLUMN_NAME']])<$lsl)){$yy=1;}
			elseif($stdu=='' and $usl<>'' and trim($_SESSION[$btl][$row3['COLUMN_NAME']])<=$usl){}
			elseif((trim($_SESSION[$btl][$row3['COLUMN_NAME']])<=$usl and trim($_SESSION[$btl][$row3['COLUMN_NAME']])>$stdu) or (trim($_SESSION[$btl][$row3['COLUMN_NAME']])>=$lsl and trim($_SESSION[$btl][$row3['COLUMN_NAME']])<$stdl)) {$yy=1;}
			elseif(((trim($_SESSION[$btl][$row3['COLUMN_NAME']])>$usl) and (trim($_SESSION[$btl][$row3['COLUMN_NAME']])<>'NULL') and ($spec<>NULL)) or ((trim($_SESSION[$btl][$row3['COLUMN_NAME']])<$lsl) and (trim($_SESSION[$btl][$row3['COLUMN_NAME']])<>'NULL') and ($spec<>NULL))){$yy=1;}
		}
		//echo $i.','.$yy.'<br>';
		if($yy==1)
		{
			$query3="update ".$_GET['efm']." set OK='0' where LotNo='".$lot."'";
			//echo $query3.'<br>';
		}
		else
		{
			$query3="update ".$_GET['efm']." set OK='1' where LotNo='".$lot."'";
		}
		//echo $query3.'<br>';
		$result3=mssql_query($query3);
	}
	unset($_SESSION['SLOP'],$_SESSION['RSQ']);
	$query=substr($query,0,-1);
	
	$query1=substr($query1,0,-1);
	
	$query.=" where LotNo='".$lot_slop."' and AnalyzeTime='".$creat_time."' ";
	
	$query1.=" where LotNo='".$lot_rsq."' and AnalyzeTime='".$creat_time."' ";
	//echo 'SLOP='.$query.'<br>'.'RSQ='.$query1;
	$result=mssql_query($query);
	$result1=mssql_query($query1);
	
}


function re_test($lotno,$anigroup,$anitime){
	$query="SELECT type FROM RE_TEST WHERE (LotNo = N'".$lotno."') AND (ani_group = N'".$anigroup."') AND (AnalyzeTime = N'".$anitime."')";	
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]==1){return '再確認';}
	elseif($row[0]==2){return '再取樣';}
	else{return '普通分析';}
}

class color_type{
	public  $get_test_spec;
	function check_spec(){
			
	}
}
?>

