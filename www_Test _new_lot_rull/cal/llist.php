<?php
if(isset($_POST['urgent'])){
	for($o=0;$o < $_POST['count'];$o++){
	//	echo $_POST['Lot_No'.$o].":".$_POST['chkbox'.$o]."<BR>";
		if($_POST['chkbox'.$o]==''){
			$g=0;
		}
		if($_POST['chkbox'.$o]=='on'){
			$g=1;
		}
		$query="select top(1) active from Analyze_Urgent where Lot_No='".$_POST['Lot_No'.$o]."' and canceled=0";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		
		if($row[0]<>$g){
			$query="update Analyze_Urgent set active=".$g.",canceled=1,last_modify_uid='".$_SESSION['uid']."',last_modify_datetime='".date("Y-m-d H:i:s")."' where Lot_No='".$_POST['Lot_No'.$o]."' and canceled=0";
			$result=mssql_query($query);
			
			$query="INSERT INTO Analyze_Urgent (Lot_No, create_datetime, creator, canceled, active)
							VALUES          (N'".$_POST['Lot_No'.$o]."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102), N'".$_SESSION['uid']."', 0,".$g. ")";
			$result=mssql_query($query);
			echo "Update ".$_POST['Lot_No'.$o]."<BR>";
			}
			else{
				echo "Passed ".$_POST['Lot_No'.$o]."<br>";
			}
			/*
			$query="update Analyze_Urgent set active=".$g.",canceled=1,last_modify_uid='".$_SESSION['uid']."',last_modify_datetime='".date("Y-m-d H:i:s")."' where Lot_No='".$_POST['Lot_No'.$o]."' and canceled=0";
			$result=mssql_query($query);
			$query="INSERT INTO Analyze_Urgent (Lot_No, create_datetime, creator, canceled, active)
							VALUES          (N'".$_POST['Lot_No'.$o]."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102), N'".$_SESSION['uid'].'", 0,'.$g. ")";
			echo $query."<BR>";
			$result=mssql_query($query);
			*/
	}
}
if(isset($_POST['sign_selected'])){
		
	$cc=$_POST['chkbox'];
 	$n=count($cc);
 	$lotno_str='';
	for($x=0;$x<$n;$x++){
		echo "Lot NO: ".$cc[$x]."<BR>";
		$lotno_str  = $lotno_str.$cc[$x].",";
	}
	$lotno_str=substr($lotno_str,0,-1);
	$url='../cal/index.php?url=signatory&lotno='.$lotno_str;
	echo '<a href="/cal/index.php?url=signatory&lotno='.$lotno_str.'" target="_new" title="">¦X¨ÖÃ±®Ö</a>';
	echo '<script>windows.open('.$url.');</script>';
	
	echo '<script>document.location.href="'.$url.'";</script>';
}

function check_urgent($lotno){
	$query1="SELECT active FROM Analyze_Urgent where canceled=0 and Lot_No='".$lotno."'";
	$result1=mssql_query($query1);
	$row=mssql_fetch_row($result1);
	if($row[0]==1){
		return ' checked="checked" ';
	}
	else{
		return  ' ';
	}
}

function check_status($lotno){
	$query1="SELECT Signatory_Name, sign_items, Signatory_ID, LotNo, sign_datetime FROM Ani_Signatory where LotNo='".$lotno."' and cancel=0 order by sign_datetime desc";
	$result1=mssql_query($query1);
	if($row1=mssql_fetch_row($result1)){
		echo '<a href="/cal/index.php?url=signatory&lotno='.$lotno.'" target="_new" title="">'.$row1[0].'</a>';
	}
	else{
		echo '<a href="/cal/index.php?url=signatory&lotno='.$lotno.'" target="_new" title="">Ã±®Ö</a>';
	}
}
function fill_flow($lotno){
	$query="SELECT	Fill_Flow_Chart.flow FROM Fill_Flow_Chart WHERE (Lot_No = N'".$lotno."') order by date desc";
	$url="edit_flow.php?lot_no=".$lotno;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if(trim($row[0])==''){$a='½s¿è';}else{$a=$row[0];}
	$A='<a target="_blank" href="'.$url.'">'.$a.'</a>;'.$a;
	$B=explode(";",$A);
	return $B;	
}

?>