<?php 
echo "1~12m3 充填作業檢查表簽核<BR>LotNo:<BR>";
session_start();
$aa=explode(",",$_GET['lotno']);
$num=count($aa);
for ($n=0;$n<$num;$n++){
	echo $aa[$n]."<BR>";
}
include("../lib/fun.php");
include("../lib/jtsai.php");
include('../connections/conn.php'); 

 $query="SELECT Signatory_Name, sign_items, Signatory_ID, LotNo, cancel, note, sign_datetime, disabled_datetime, disabled_uname, CONVERT(VARCHAR(50), [sign_datetime], 120) as aa \
 FROM Instruction_Signatory where LotNo='".$_GET['lotno']."' order by sign_datetime ";
	
	
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	
	if($numrows>0){
		$i=1;
		echo '歷史紀錄:<table width="1020" border="1" >';
		while ($row=mssql_fetch_array($result)){
			
			if($row['cancel']==1){
				$cancel='取消';
				$color='';
			}
			else{
				$cancel='有效';
				$color='bgcolor="yellow"';
			}
			echo '<tr '.$color.'><td>';
			echo $i.'&nbsp;&nbsp;'.$cancel.'&nbsp;&nbsp;&nbsp;簽核人 :'.$row['Signatory_Name'].'&nbsp;&nbsp;&nbsp;簽核時間 :'.$row['aa'].'&nbsp;&nbsp;&nbsp;簽核意見 :'.$row['note'].
			'&nbsp;&nbsp;&nbsp;取消人員 :'.$row['disabled_uname'].'&nbsp;&nbsp;&nbsp;取消時間 :'.$row['disabled_datetime'];
			echo '</td><tr>';
			$i++;
		}
		echo '</table>';
		echo '<form method="post" action="">
		<BR><BR><BR>簽核意見<BR>
		<textarea name="note" id="note" cols="65" rows="5" >已閱</textarea><BR>
		<input type="submit" name="sign" value="簽核">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="cancel" value="撤銷已同意之簽">&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="leave" value="關閉" type="submit">

		</form>';
		
	}
	else{
		echo '<form method="post" action="">
		<BR>簽核意見<BR>
		<textarea name="note" id="note" cols="65" rows="5" >已閱</textarea><BR>
		<input type="submit" name="sign" value="簽核">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="cancel" value="撤銷已同意之簽">&nbsp;&nbsp;&nbsp;&nbsp;<input name="leave" value="關閉" type="submit">
		</form>';
	}
if(isset($_POST['leave'])){
			$url='../fill/index.php?url=instruction';
	echo '<script>document.location.href="'.$url.'";</script>';
}
if(isset($_POST['sign'])){
	echo "Total:".$num."<BR>";
	if($num<1){
		$query="update Instruction_Signatory set cancel=1,disabled_uid='".$_SESSION['uid']."',disabled_uname='".$_SESSION['uname']."'  where LotNo='".$_GET['lotno']."' and cancel=0";

		$result=mssql_query($query);
		$query="INSERT INTO Instruction_Signatory (LotNo, Signatory_ID, Signatory_Name, note, cancel, LV,sign_datetime)
						VALUES          (N'".$_GET['lotno']."', N'".$_SESSION['uid']."', N'".$_SESSION['uname']."', N'".$_POST['note']."', 0, 0,'".date("Y-m-d H:i:s")."')";

		if($result1=mssql_query($query)){		my_msg($num);	}
	}
	elseif($num>=1){
		for($i=0;$i<$num;$i++){
			$query="update Instruction_Signatory set cancel=1 where LotNo='".$aa[$i]."' and cancel=0";
			$result=mssql_query($query);
			$query="INSERT INTO Instruction_Signatory (LotNo, Signatory_ID, Signatory_Name, note, cancel, LV,sign_datetime)
						VALUES          (N'".$aa[$i]."', N'".$_SESSION['uid']."', N'".$_SESSION['uname']."', N'".$_POST['note']."', 0, 0,'".date("Y-m-d H:i:s")."')";
			$result=mssql_query($query);		
		}
	}
	refresh();
}

if(isset($_POST['cancel'])){
	echo "Total:".$num."<BR>";
	if($num<1){
		$query="update Instruction_Signatory set cancel=1,active_flag=0,disabled_datetime='".date("Y-m-d H:i:s")."', disabled_uid='".$_SESSION['uid']."',disabled_uname='".$_SESSION['uname']."' where LotNo='".$_GET['lotno']."' and cancel=0";
		echo $query;
		$result=mssql_query($query);
	}
	elseif($num>=1){
		for($i=0;$i<$num;$i++){
			$query="update Instruction_Signatory set cancel=1,active_flag =0,disabled_datetime='".date("Y-m-d H:i:s")."',disabled_uid='".$_SESSION['uid']."',disabled_uname='".$_SESSION['uname']."' where LotNo='".$aa[$i]."' and cancel=0";
			echo $query;
			$result=mssql_query($query);
		}
	}
		refresh();
}


?>