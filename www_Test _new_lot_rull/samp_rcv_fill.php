<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
$_SESSION['smp_rcv']=$_SERVER['REQUEST_URI'];
if($_SESSION['place']=="HIC"){$return_page="index1.php";}
else{$return_page="index.php";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<style type="text/css">
body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>

</head>

<body>
<?php 
	if(trim($_SESSION['lot_no'])==''){$auto_lotno='autofocus'; $auto_sma_id='';}
	if(trim($_SESSION['lot_no'])<>''){$auto_lotno=''; $auto_sma_id='autofocus';}
	
?>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  Lot NO : 
  <input name="lot_no" type="text" id="lot_no" style="font-size:<?php echo $_SESSION['font_size'];?>px" size="12" <?php echo $auto_lotno;?> onchange="set_date_session(this.name,this.value)" value="<?php
  	echo $_SESSION['lot_no'];	
  ?>"/>
<?php
$_SESSION['lot_no']=trim($_SESSION['lot_no']);
$_SESSION['sma_id']=trim($_SESSION['sma_id']);
if(substr($_SESSION['lot_no'],-1,1)=='_'){
			$gflot=substr($_SESSION['lot_no'],0,-1);
		}
		else{
			$gflot=$_SESSION['lot_no'];
		}
$query="select count(*) from sample where SMP_LOT='".$_SESSION['lot_no']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$tlot=new get_from_lot_no;
	$tlot->lid=$_SESSION['lot_no'];
	$tlot->cid();
	$lst=$tlot->qty_drum;
	$mid=ceil($lst/2);
	if($lst%2<>0){$mid=$mid+1;}
	echo '<BR>已掃描瓶數：'.$row[0]." 瓶";
	echo '<BR>';
	echo '取樣位置：<select name="sn" style="font-size:'.$_SESSION['font_size'].'px" id="selected_group" />';
	if($tlot->spc=='LY'){
		echo '<option value="1~8">1~8</option>';
	}
	else{
		echo '<option value="0~0" ';
		echo '>充填口</option>';
		echo '<option value="1~1" ';
		echo '>前</option>';
		echo '<option value="'.$mid.'~'.$mid.'" ';
		echo '>中</option>';
		echo '<option value="'.$lst."~".$lst.'" ';
		echo '>後</option>';	
	}
	echo '</select><BR>';
?>樣品瓶號：
  <input name="sma_id" type="text" id="sma_id" <?php echo $auto_sma_id;?> style="font-size:<?php echo $_SESSION['font_size'];?>px" size="6" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<BR />
<input type="submit" name="submit" id="submit" value="新增" style="width:100px;height:<?php echo ($_SESSION['font_size']+10);?>px;border:2px orange double;" />
</form>
</body>
</html>
<?php
echo '<p><a href="'.$return_page.'"><strong>上一步</strong></a><strong></strong></p></font>';
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["submit"]) and ($_POST['sma_id']<>'') and $_POST['lot_no']<>'')
{
	if($_POST['sample_keep']=='on'){  // 紀錄保留樣品瓶
		$query="INSERT INTO Sample_Keeping (Lot_No, sample_no, keep_datetime, creator) VALUES  
		('".$_SESSION['lot_no']."','".$_POST['sma_id']."','".date("YmdHis")."','".$_SESSION['uid']."')";
		$result=mssql_query($query);
	}
	
	$_SESSION['drum_sn']=$_POST['sn'];
	if($_POST['rework']=='on'){$isrework='0';}
	else{$isrework='';}
	$_SESSION['lot_no']=$_POST['lot_no'];
	
	// 判斷此 LOT 是否已經有瓶號
	$query1="SELECT  COUNT(*) AS cnt FROM Sample_All WHERE (SMA_ID = '".$_POST['sma_id']."') AND (SMA_LOT = '".$_POST['lot_no']."')";
	$result1=mssql_query($query1);
	$row1=mssql_fetch_row($result1);
	$a=$row1[0];
	if($a==0)
	{
		$query="SELECT          TOP (1) Sample_All.SMA_TIMES as T1, Sample.SMP_TIMES as T2
	FROM              Sample_All INNER JOIN
								Sample ON Sample_All.SMA_ID = Sample.SMP_ID
	WHERE          (SMP_ID = '".$_POST['sma_id']."')"; 
		$query.=" ORDER BY   Sample_All.SMA_TIMES DESC, Sample.SMP_TIMES DESC";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		if($numRows>0)
		{
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
										VALUES          ('".$_POST['sma_id']."',".$smp_times.",".$smp_service.",'".$_POST['lot_no']."',
										'".$_SESSION['s1']."',NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."','".$isrework."',
										NULL,NULL,NULL,'".$_POST['sn']."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
			$result = mssql_query($query);
			if (!$result) {
				echo "無法新增樣品瓶</br>";
			  }
			else{
				$query="UPDATE          dbo.Sample
						SET                   SMP_TIMES ='".$smp_times."', SMP_LOT ='".$_POST['lot_no']."', SMP_DRUMNO = '".$_POST['sn']."', SMP_USER ='".$_SESSION['s1']."'
						, SMP_SMP ='".$_SESSION['uid']."', SMP_SAVE ='".date("Ymd")."', SMP_SAVE_TIME ='".date("His")."' 
						WHERE          (SMP_ID = '".$_POST['sma_id']."')";
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