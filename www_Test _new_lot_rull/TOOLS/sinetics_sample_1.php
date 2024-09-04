<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
?>
<form action="" method="post" name="123">時間：
<input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php 	echo $_SESSION['datepicker1'];?>"  onchange="set_date_session(this.name,this.value)">~~
<input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php 	echo $_SESSION['datepicker2'];?>"  onchange="set_date_session(this.name,this.value)">
<input type="submit" name="start" id="start" value="start" />
</form>
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick(); 
if(isset($_POST['start'])){
	
echo '<table width="1200" border="1"><tr><td>AA</td></tr>';
$query="SELECT AnalyzeDesign.AND_LOT_NO, AnalyzeDesign.AND_ITEM, AnalyzeDesign.AND_GOODS, PRODUCT_DATA.PDD_CHEMICAL as ca FROM AnalyzeDesign INNER JOIN PRODUCT_DATA ON AnalyzeDesign.AND_GOODS = PRODUCT_DATA.PDD_PROD_NO WHERE (AND_APPLY_DATE >= '".dod($_POST['datepicker1'])."') and (AND_APPLY_DATE <= '".dod($_POST['datepicker2'])."')";
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	$aa=report($row['AND_ITEM'],$row['AND_LOT_NO'],$row['AND_GOODS'],$row['ca']);
	echo '<tr><td>'.$aa.'</td></tr>';
	
//	$a=substr(trim($row['SampleNo']),0,8);
//	$query1="UPDATE  QC_LotData SET SampleNo = '".$a."' WHERE   (ID = ".$row['ID'].")";
//	$result1=mssql_query($query1);
//	echo '<tr><td>'.$row['ID'].'</td><td>'.$row['LotNo'].'</td><td>'.$row['SampleNo'].'</td><td>'.$row['state'].'</td><td>'.$a.'</td><td>'.$query1.'</td></tr>';
}
echo '</table>';	

}


function report($str,$lot_no,$pdd_prod_no,$ca){
	$aa=explode(',',$str,-1);
	$query="SELECT DISTINCT AnalyzeItem.ANI_GROUPNAME AS ag, ELEMENT_FORM.PDD_CHEMICAL , ELEMENT_FORM.ELF_FORM FROM AnalyzeItem INNER JOIN ELEMENT_FORM ON AnalyzeItem.ANI_INDEX = ELEMENT_FORM.ELM_ID WHERE (ANI_INDEX <>'') AND (ELEMENT_FORM.PDD_CHEMICAL = '".$ca."') and (";
	for($i=0;$i<count($aa);$i++)
	{
		if ($i<(count($aa)-1)){
			$query.="(ANI_INDEX =".$aa[$i].") OR ";}
		else {$query.="(ANI_INDEX =".$aa[$i]."))";}	
	}
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$ef=trim($row['ELF_FORM']);
		$sp=re_sample($ef,$lot_no,$pdd_prod_no);
	}
//	return substr($san,0,-1);
//	return substr($ss,0,-1);
	return $sp;
//	echo '<br>';
//	return substr($ss,0,-1);
}

function re_sample($form,$lotno,$pddno){
	$query1="SELECT SampleNo FROM ".$form." WHERE (LotNo = '".$lotno."')";
	$result1=mssql_query($query1);
	$numrows=mssql_num_rows($result1);
	$row1=mssql_fetch_row($result1);
	if($numrows>0){
		$oo=add_sample($row1[0],$lotno,$pddno);	
		return $oo;
//		return '+'.$row1[0];
	}
	else{
		return "沒有憑號";
	}
}

function add_sample($smpid,$lotno,$pdd_no){
	$_SESSION['createtime']=date("YmdHis");
	//Sample是否建立過此LOT的此瓶號	
	$query_smp="select * from sample where SMP_ID='".$smpid."' and SMP_LOT='".$lotno."'";
	$result_smp=mssql_query($query_smp);
	$numrows_smp=mssql_num_rows($result_smp);
//	break;
	if($numrows_smp==0){ //Sample沒有建立過
		// Sample有沒有此瓶號
		$query_smp1="select * from sample where SMP_ID='".$smpid."'";
		$result_smp1=mssql_query($query_smp1);
		$numrows_smp1=mssql_num_rows($result_smp1);
		if($numrows_smp1==0){ //Sample沒有此瓶號
			if(substr($smpid,0,1)=='T'){$met="PFA";}
			else{$met="PE";}
			$query_creat_smp="INSERT INTO [dbo].[Sample] ([SMP_ID], [SMP_MAT], [SMP_MID_ID], [SMP_START], [SMP_TIMES], [SMP_SERVICE], [SMP_LOT], [SMP_DRUMNO],
			[SMP_USER], [SMP_OUT], [SMP_SMP], [SMP_ANA], [SMP_BACK], [SMP_SAVE], [SMP_SAVE_TIME], [SMP_FINISH], [SMP_JUNK], [SMP_JUNK_D]) VALUES 
			(N'".$smpid."', N'".$met."', N'".$pdd_no."', N'".date("Ymd")."', 1,1, '".$lotno."','".'1~8'."', 
			NULL, NULL, '".$_SESSION['uid']."', NULL, NULL, NULL, NULL, NULL, NULL, NULL)"; 
	//		$_SESSION['QQ1']=$query_creat_smp;
			$result_smp_creat=mssql_query($query_creat_smp);	
			$kk='1新增'.$smpid;						
		}
		else{//Sample已經有此瓶號
			$query_update_smp="UPDATE          dbo.Sample SET SMP_LOT = '".$lotno."', SMP_MID_ID =  N'".$pdd_no."', SMP_TIMES = SMP_TIMES + 1, SMP_SERVICE = 1, SMP_DRUMNO = '".'1~8'."', SMP_SAVE = '".date("Ymd")."' WHERE (SMP_ID = '".$smpid."')";
			$result_update_smp=mssql_query($query_update_smp);
			$kk='1更改'.$smpid;	
		}
	}
	
	
	//Sample All是否已有此LOT的此瓶號	
	$query_sma="select * from sample_all where SMA_ID='".$smpid."' and SMA_LOT='".$lotno."'";
	$result_sma=mssql_query($query_sma);
	$numrows_sma=mssql_num_rows($result_sma);
	if($numrows_sma==0){//沒有的狀況下
	// Sample_All有沒有此瓶號
		$query_sma="select * from sample_all where SMA_ID='".$smpid."'";
		$result_sma=mssql_query($query_sma);
		$numrows_sma=mssql_num_rows($result_sma);
		if($numrows_sma==0){
			$query_creat_sma="INSERT INTO dbo.Sample_All
								(SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT, SMA_USER, SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
								SMA_SAVE, SMA_SAVE_TIME, ISREWORK, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
								SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
								SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
								VALUES          ('".$smpid."',1,1,'".$lotno."',
								NULL,NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."',0,
								NULL,NULL,NULL,'".'1~8'."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
			$result_sma_creat=mssql_query($query_creat_sma);	
			$kk.='2新增'.$smpid;	
		}
		else{
			$query="select top(1) SMA_TIMES+1 from Sample_All where SMA_ID='".$smpid."' ORDER BY   SMA_TIMES DESC";
			$result=mssql_query($query);
			$row=mssql_fetch_row($result);
			$times=$row[0];
			$query_creat_sma="INSERT INTO dbo.Sample_All
								(SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT, SMA_USER, SMA_OUT, SMA_SMP, SMA_ANA, SMA_BACK, 
								SMA_SAVE, SMA_SAVE_TIME, ISREWORK, SMA_FINISH, SMA_JUNK, SMA_JUNK_D, SMA_DRUMNO, 
								SMA_SERIAL_NO, DHN_DRUM_NO, SMA_PREPSAMPLE_MAN, SMA_PREPSAMPLE_DATE, 
								SMA_PREPSAMPLE_BACK_DATE, SMA_PREPSAMPLE_BACK_MAN, SMA_RETURN, SMA_RETURN_MAN)
								VALUES          ('".$smpid."',".$times.",1,'".$lotno."',
								NULL,NULL,'".$_SESSION['uid']."',NULL,NULL,'".date("Ymd")."','".date("His")."',0,
								NULL,NULL,NULL,'".'1~8'."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
			$result_sma_creat=mssql_query($query_creat_sma);
			$kk.='3新增'.$smpid;	
		}
	}	
	return $lotno.":".$kk;
}
		   
?>