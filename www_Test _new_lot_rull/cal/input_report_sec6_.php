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
$xx=1;

/////////////////////////取得分析項目表單////////////////////////////
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
$_SESSION['cnt']=0;
unset($_SESSION['unit']);	
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

if (isset($_POST["refresh"]) or isset($_POST["submit"])){
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
  <table width="1200" border="1"><tr><td width="1200" bgcolor="#999999" >
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
  $_SESSION['table']=$table;
  $_SESSION['select_cust']=$cust_no;
  }
  
  if (trim($pdd->pdd_type)=='LY')
  {
	if($pdd->cid=='C00001'){$cust_name=get_cust_name($_SESSION['cid']);$cust_no=$_SESSION['cid'];}
	if($pdd->cid==''){$pdd->cid='C00001';}
  	$_SESSION['select_cust']=$cust_no;
	$dm_no="1~8";
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
		while($row=mssql_fetch_array($result))
		{
			$_SESSION['tmp1']=3;
		}
		if($_SESSION['select_cust']=='C00000'){$_SESSION['select_cust']='C00001';}
	}
	elseif($numRows==1)
	{
		$_SESSION['tmp1']=4;
		while($row=mssql_fetch_array($result))
		{
			$_SESSION['select_cust']=$row['CTD_CUST_NO'];
		}
		if($_SESSION['select_cust']=='C00000'){$_SESSION['select_cust']='C00001';}
	}
	elseif($numRows<1)
	{
			$_SESSION['tmp1']=5;
			$_SESSION['cid']=$_SESSION['select_cust']='C00001';
	}
  }
  ?>
  <font color="green" size="+3"> 輸入確認</font>
   <font color="yellow" size="+0"> (此頁面只可修改樣品瓶號及前處理人員，不可修改檢驗結果)</font>
  </td></tr></table>
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
      <input type="submit" name="an_first" id="button" value=" 工號  " onClick="window.open('./index.php?url=select_user6', '_self');" />
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
      	echo '<input name="'.$row['COLUMN_NAME'].'" id="'.$row['COLUMN_NAME'].'" type="text" readonly="readonly"';	  
	  	if($_SESSION[$row['COLUMN_NAME']]<>''){$value=$_SESSION[$row['COLUMN_NAME']];}
		if($_SESSION[$row['COLUMN_NAME']]==''){echo ' style="background-color:white;"';}
		elseif(($_SESSION[$row['COLUMN_NAME']]==2 or $_SESSION[$row['COLUMN_NAME']]==3) and $_GET['ani_groupname']=='INHP'){  ;}
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
      <input type="hidden" name="AnalyzeTime" id="AnalyzeTime" value="<?php echo $_SESSION['createtime']; ?>" />      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font color="#FF0000">合否判定</font> 
      <?php if($xx==1){$x1="checked";echo '<font color="blue" size="+2">合</font>';}else{$x1='unchecked';echo '<font color="red" size="+2">否</font>';} ?>
      <input type="checkbox" name="Ok" id="Ok" <?php echo $x1;?> hidden >   </td>
      <td width="600" align="center">
      <input type="submit" name="submit" id="submit" value=" 確定 / 儲存"/>
      <input type="hidden" name="MM_insert" value="form1">

      <input type="submit" name="leave" id="button" value=" 放棄 / 離開  " onClick="window.open('<?php echo $_SESSION['lasturl1'];?>', '_self');" />      <?php if ($_GET['ani_groupname']=="TM") {
		  echo '<input type="button" name="TM" id="TM" value="取得TM分析資料" onClick="window.open('."'/cal/index.php?url=ctm&pdd_chemical=".$_GET['pdd_chemical']."&operator=".$_GET['operator']."&ani_groupname=".$_GET['ani_groupname']."&lot_no=".$_GET['lot_no']."&cust_no=".$cust_no."&pid=".$_GET['pdd_prod_no']."', '_self');".'"'.' />';
	  }?></td>
    </tr></table>
    </br>  
  </table> 

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
{   
	session_start();
			//////////檢查樣品瓶號///////////
	if ($_POST['Ok']=="on"){$_POST['Ok']=1;}
	else {$_POST['Ok']=0;}
	//////////取得欄位名///////////
	
	include ("../connections/conn.php");
	if($need_no<=150){
		$smno=trim($_SESSION['SampleNo']);
		if($smno<>'S9' and $smno<>'S6' and $smno<>'W2' and $smno<>'O1')
		{
			$query="SELECT          PDD_PROD_SHORT_NAME
			FROM              PRODUCT_DATA where PDD_PROD_NO='".$_GET['pdd_prod_no']."'";	
			$result=mssql_query($query);
			$row=mssql_fetch_row($result);
			if(substr($_SESSION['SampleNo'],1,2)<>trim($row[0])){my_msg("瓶號不符合規則");}
		}
	}


$_SESSION['createtime']=date("YmdHis");
$query_smp="select * from sample where SMP_ID='".$_SESSION['SampleNo']."'";
$result_smp=mssql_query($query_smp);
$numrows_smp=mssql_num_rows($result_smp);
if($numrows_smp==0){
	if(substr($_SESSION['SampleNo'],0,1)=='T'){$met="PFA";}
	else{$met="PE";}
	$query_creat_smp="INSERT INTO [dbo].[Sample] ([SMP_ID], [SMP_MAT], [SMP_MID_ID], [SMP_START], [SMP_TIMES], [SMP_SERVICE], [SMP_LOT], [SMP_DRUMNO], [SMP_USER], [SMP_OUT], [SMP_SMP], [SMP_ANA], [SMP_BACK], [SMP_SAVE], [SMP_SAVE_TIME], [SMP_FINISH], [SMP_JUNK], [SMP_JUNK_D]) VALUES (N'".$_SESSION['SampleNo']."', N'".$met."', N'".$_SESSION['pid']."', N'".date("Ymd")."', 1,1, '".$_GET['lot_no']."', NULL, NULL, NULL, '".$_SESSION['uid']."', NULL, NULL, NULL, NULL, NULL, NULL, NULL)"; 
	echo "Q1:".$query_creat_smp."<BR>";
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
							NULL,NULL,NULL,'".$dm_no."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
	echo "Q2:".$query_creat_sma."<BR>";
	$result_sma_creat=mssql_query($query_creat_sma);							
}

$query="INSERT INTO dbo.analyze_first1
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['createtime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";

$result=mssql_query($query);
$query="INSERT INTO dbo.analyze_first
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['createtime']."', '".$_POST['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."',".$_SESSION['needno'].")";

$result=mssql_query($query);

if($_POST['Ok']==0)
{
	$query="INSERT INTO dbo.analyze_fail
                          (lot_no, sample_no,PDD_PROD_NO,ani_group,ani_chemical,Tester,Analyzetime)
VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_GET['pdd_prod_no']."', '".$_GET['ani_groupname']."', '".$_GET['pdd_chemical']."','".$_SESSION['uid']."','".$_SESSION['createtime']."') ";

$result=mssql_query($query);
}

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
jumpto($_SESSION['lasturl1']);
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
	jumpto("./index.php?url=select_user6");
}
}
}

if(isset($_POST['button2'])){
button2();
}

if (isset($_POST["leave"])) 
{
	keep_session();
	jumpto($_SESSION['lasturl1']);
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
