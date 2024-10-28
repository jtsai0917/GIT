<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");

echo '<meta http-equiv="Content-Type" content="text/html; charset=big5" /><meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />';

echo ' </br>

<form id="form1" name="form1" method="post" action="'.$loginFormAction.'">';
echo '
<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="161" height="86" /></a>人員'.$_SESSION['uname'].'</p>
Lot NO:&nbsp;&nbsp; <input type="text" name="lid" id="lid" width="50" height="20"  value="'.$_SESSION['lid'].'" onKeyPress="return SubmitEnter(this,event)" onchange="set_date_session(this.name,this.value)"/>';
$query="select count(*) from sample where SMP_LOT='".$_SESSION['lid']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$tlot=new get_from_lot_no;
	$tlot->lid=$_SESSION['lid'];
	$tlot->cid();
	$lst=$tlot->qty_drum;
	echo "<BR>";
	$mid=ceil($lst/2);
	if($lst%2==0){$mid=$mid+1;}
	echo '<BR>已掃描瓶數：'.$row[0]." 瓶";
	echo '<BR>';
	echo '取樣位置：<select name="selected_group" id="selected_group"onchange="set_date_session(this.name,this.value)"/>';
	if($tlot->spc=='LY'){
		echo '<option value="1~8">1~8</option>';
	}
	else{
		echo '<option value="0~0" ';
		if($_SESSION['selected_group']=='0~0'){echo ' selected="selected"';} 
		echo '>充填口</option>';
		echo '<option value="1~1" ';
		if($_SESSION['selected_group']=='1~1'){echo ' selected="selected"';} 
		echo '>前</option>';
		echo '<option value="'.$mid.'~'.$mid.'" ';
		if($_SESSION['selected_group']==$mid.'~'.$mid){echo ' selected="selected"';}  
		echo '>中</option>';
		echo '<option value="'.$lst."~".$lst.'" ';
		if($_SESSION['selected_group']==$lst.'~'.$lst){echo ' selected="selected"';} 
		echo '>後</option>';	
	}
	echo '</select><BR>';
		echo '樣品瓶號：<input type="text" name="smpid" id="smpid" width="30" height="20"  value="'.$_SESSION['smpid'].'" onKeyPress="return SubmitEnter(this,event)" onchange="set_date_session(this.name,this.value)"/>';
		echo '<input type="submit" name="add" id="add" value=" 新增 " />';

echo '
</form>';

if(isset($_POST['add'])) {
	$pdd=new get_from_lot_no;
	$pdd->lid=$_POST['lid'];
	$pdd->ani();
	$pdd->cid();
	$s3=$pdd->pdd_type;
	
	if($pdd->pdd_type<>'DM'){$_POST['sn']='1~8';}
	$_SESSION['lot_no']=$_POST['lid'];
	$query="SELECT          TOP (1) Sample_All.SMA_TIMES as T1, Sample.SMP_TIMES as T2
FROM              Sample_All INNER JOIN
                            Sample ON Sample_All.SMA_ID = Sample.SMP_ID
WHERE          (SMP_ID = '".$_POST['smpid']."')"; 
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
							VALUES          ('".$_POST['smpid']."',".$smp_times.",".$smp_service.",'".$_POST['lid']."',
							'".$pdd->cid."',NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."','".$isrework."',
							NULL,NULL,'".$_POST['selected_group']."','".$_POST['sn']."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
							
							
if($result = mssql_query($query)){echo "Sample_All added...<BR>";};
if (!$result) {
    echo "無法新增樣品瓶</br>";
  }
else{
	$query="UPDATE          dbo.Sample
			SET                   SMP_TIMES ='".$smp_times."', SMP_LOT ='".$_POST['lid']."', SMP_DRUMNO = '".$_POST['selected_group']."', SMP_USER =
			'".$pdd->cid."'	, SMP_SMP ='".$_SESSION['uid']."', SMP_SAVE ='".date("Ymd")."', SMP_SAVE_TIME ='".date("His")."' 
			WHERE          (SMP_ID = '".$_POST['smpid']."')";					
							
if($result = mssql_query($query)){echo "Sample updated...<BR>";};
	if (!$result) {
		echo "無法更新Sample資料表</br>";
	  }
	}
}
else
	{
		scriptconfirm("無此瓶號，建立新瓶號?","[".$_POST['sma_id']."] 無此樣品瓶號","insert_smp.php?smp_id=".$_POST['smpid']."&sn=".$_POST['sn']);
	}
refresh();
}
$i=1;
echo "已取樣品瓶瓶號";
echo "<BR>";
$query="select * from sample where SMP_LOT='".$_SESSION['lid']."'";
	$result=mssql_query($query);
	echo '<table border="1" width="300"><tr><td>瓶數</td><td>瓶號</td><td>取樣點</td></tr>';
	while($row=mssql_fetch_array($result))
	{
		echo '<tr>';
		echo "<td>第".$i."瓶：</td>";
		echo "<td>".$row['SMP_ID']."</td>";
		echo "<td>".$row['SMP_DRUMNO']."</td>";
		echo "</tr>";
		$i=$i+1;
	};

echo '<p><a href="'.$_SESSION['last_pda'].'"><strong>上一步</strong></a><strong></strong></p>';
