<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
$_SESSION['smp_rcv']=$_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>接收樣品瓶</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">

    <p>
    <label for="lot_no"></label>
  Lot NO : &nbsp;&nbsp;&nbsp;
  <input name="lot_no" type="text" id="lot_no" onchange="set_date_session(this.name,this.value)" value="<?php 
  session_start();
  if(isset($_GET['lot_no'])){$_SESSION['lot_no']=$_GET['lot_no'];}
	echo $_SESSION['lot_no'];	
  ?>
  " size="15" 
  />
  <input type="submit" name="getlotinfo" id="getlotinfo" value="取得相關資料" />

  ( 設定瓶號之前，請先取得相關的瓶號訊息)</p>
  <p>樣品瓶號：
  <input name="sma_id" type="text" id="sma_id" size="15" />  
  再分析
  <input type="checkbox" name="rework" id="rework" />
  <label for="rework"></label>
   &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;保留樣品<input type="checkbox" name="sample_keep" /></p>
    <p>    取樣批號：
   
  
<?php
if(substr($_SESSION['lot_no'],-1,1)=='_'){
			$gflot=substr($_SESSION['lot_no'],0,-1);
		}
		else{
			$gflot=$_SESSION['lot_no'];
		}
echo '<select name="sn" id="sn">';
  	$query="SELECT          FILLPLAN_OUT_DECIDE.FDM_QTY_DRUM, PRODUCT_DATA.PDD_CHEMICAL
FROM              FILLPLAN_OUT_DECIDE INNER JOIN
                            PRODUCT_DATA ON FILLPLAN_OUT_DECIDE.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO 
		WHERE          (FDM_LOT_NO = '".$gflot."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($result)
{
while($row = mssql_fetch_array($result))
	{
			$pdd_chemical=$row['PDD_CHEMICAL'];
		echo   $num=$row['FDM_QTY_DRUM'];
	}
if(($_SESSION['spc']=='LY') or ($_SESSION['spc']=='')){
	echo '<option value="1~8">1~8</option>';
	}
	elseif((($_SESSION['spc']=='DM') or ($_SESSION['spc']=='BTL')) and ($pdd_chemical=='IPA')){
		echo "IPA";
		echo '<option value="0~0">充填口</option>';
	$x=floor($num/8);
	
	for($i=0;$i<=$x;$i++){
	$num1=($i*8+1)."~".(($i+1)*8);
	echo '<option value="'.$num1.'">'.$num1.'</option>';
	}
	}
	elseif((($_SESSION['spc']=='DM') or ($_SESSION['spc']=='BTL')) and ($pdd_chemical<>'IPA')){
		echo "!=IPA";
	$x=floor($num/2);
	echo '<option value="0~0">0~0</option>';
	echo '<option value="1~1">1~1</option>';
	echo '<option value="'.$x.'~'.$x.'">'.$x.'~'.$x.'</option>';
	echo '<option value="'.$num.'~'.$num.'">'.$num.'~'.$num.'</option>';  
	}
}
echo ' </p></select>';

?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="submit" id="submit" value="新增" />
(將瓶號新增至此 Lot No. 的檢驗)
<input type="submit" name="return" id="return" value="離開" />
</form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["submit"]))
{
	echo "REWORK:".$_POST['rework']."<BR>";
	$lotno=strtoupper(trim($_POST['lot_no']));
	$sma_id=strtoupper(trim($_POST['sma_id']));
	$sma_short=substr($sma_id,1,2);
	// check if fit rule
	$pdd=new get_from_lot_no;
	$pdd->lid=$lotno;
	$pdd->ani();
	$pdd_short_name= strtoupper($pdd->pdd_prod_short_name);
	$odi=preg_match("/".$pdd_short_name."/i", $sma_id);
	
//	if($sma_short==$pdd_short_name){
	if($odi==1){
		if($_POST['sample_keep']=='on'){  // 紀錄保留樣品瓶
			$query="INSERT INTO Sample_Keeping (Lot_No, sample_no, keep_datetime, creator) VALUES  
			('".$lotno."','".$sma_id."','".date("YmdHis")."','".$_SESSION['uid']."')";
			$result=mssql_query($query);
		}
		
		$_SESSION['drum_sn']=$_POST['sn'];
		if($_POST['rework']=='on'){$isrework='0';}
		else{$isrework='';}
		$_SESSION['lot_no']=$lotno;
		$query="SELECT          TOP (1) Sample_All.SMA_TIMES as T1, Sample.SMP_TIMES as T2
		FROM              Sample_All INNER JOIN
									Sample ON Sample_All.SMA_ID = Sample.SMP_ID
		WHERE          (SMP_ID = '".$sma_id."')"; 
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
									VALUES          ('".$sma_id."',".$smp_times.",".$smp_service.",'".$lotno."',
									'".$_SESSION['s1']."',NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."','".$isrework."',
									NULL,NULL,NULL,'".$_POST['sn']."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
									
//		echo $query."<BR>";
		$result = mssql_query($query);
		if (!$result) {
			echo "無法新增樣品瓶</br>";
		  }
		else{
			$query="UPDATE          dbo.Sample
					SET                   SMP_TIMES ='".$smp_times."', SMP_LOT ='".$lotno."', SMP_DRUMNO = '".$_POST['sn']."', SMP_USER ='".$_SESSION['s1']."'
					, SMP_SMP ='".$_SESSION['uid']."', SMP_SAVE ='".date("Ymd")."', SMP_SAVE_TIME ='".date("His")."' 
					WHERE          (SMP_ID = '".$sma_id."')";
			$result = mssql_query($query);
			if (!$result) {
				echo "無法更新Sample資料表</br>";
			  }
			}
		}
		else
			{
				scriptconfirm("無此瓶號，建立新瓶號?","[".$sma_id."] 無此樣品瓶號","insert_smp.php?smp_id=".$sma_id."&sn=".$_POST['sn']."&rework=".$isrework);
			}
	}
	else
	{
		my_msg($sma_id." 樣品瓶號不符合規則");	
	}
}
if(isset($_POST["return"])){
	jumpto($_SESSION['lasturl']);
}

if(isset($_POST["getlotinfo"])){
	$pdd=new get_from_lot_no;
	$pdd->lid=$_POST['lot_no'];
	$pdd->ani();
	$pdd->cid();
	$s3=$pdd->pdd_type;
	$_SESSION['s1']=$s1=$pdd->cid;
	$_SESSION['s2']=$s2=$pdd->pid;
	$_SESSION['spc']=$s3;
	$_SESSION['lot_no']=trim($_POST['lot_no']);
	refresh();
}
$query="SELECT    distinct      dbo.Sample_All.* ,dbo.Sample.SMP_MID_ID
FROM             dbo.Sample_All INNER JOIN
                          dbo.Sample ON dbo.Sample_All.SMA_ID = dbo.Sample.SMP_ID 
WHERE          (SMA_LOT = '".$_SESSION['lot_no']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
echo '<table width="600" border="1">';
echo '<tr><td>Customer：('.$_SESSION['s1'].")  ".get_cust_name($_SESSION['s1'])."</td><td>Product_ID：".$_SESSION['s2'].'</td></tr>';
echo '<tr><td>Product_Type：'.$_SESSION['spc'].'</td><td>Product_Name：'.get_prod_name($_SESSION['s2']).'</td></tr></table>';
echo " 樣品瓶號：</br>";
echo '<table width="600" border="1">';
echo '<tr><td width="40" align="center">瓶號</td><td width="40" align="center">桶號</td><td width="40" align="center">取樣人員</td></tr>';
while($row = mssql_fetch_array($result))
	{   
		if($row['ISREWORK']=='0'){ $is_rework='   (再分析瓶)';}
		else{ $is_rework='';}
		echo '<tr><td align="center">'.$row['SMA_ID'].$is_rework.'</td><td align="center">'.$row['SMA_DRUMNO'].'</td><td align="center">'.getusername($row['SMA_SMP']).'</td></tr>';
	}
echo '</table></br>';
?>