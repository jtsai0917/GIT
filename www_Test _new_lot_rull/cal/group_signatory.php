<?php
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include("../lib/user_right.php");

if(isset($_POST['sign_selected']))		{
	$cc=$_POST['chkbox'];
 	$n=count($cc);
 	$lotno_str='';
	for($x=0;$x<$n;$x++){
	//	echo "Lot NO: ".$cc[$x]."<BR>";
		$lotno_str  = $lotno_str.$cc[$x].",";
	}
	$lotno_str=substr($lotno_str,0,-1);
	$url='../cal/index.php?url=signatory&lotno='.$lotno_str;
//	echo '<a href="/cal/index.php?url=signatory&lotno='.$lotno_str.'" target="_new" title="">¶X®÷√±Æ÷</a>';
//	echo '<script>windows.open('.$url.');</script>';
	
	echo '<script>document.location.href="'.$url.'";</script>';
}
	
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
	//						echo $query."<BR>";
			$result=mssql_query($query);
			echo "Update ".$_POST['Lot_No'.$o]."<BR>";
			}
			else{
				echo "Passed ".$_POST['Lot_No'.$o]."<br>";
			}
			
	}

	echo "<script>window.close();</script>";
}

if(isset($_POST['urgent1'])){
	for($o=0;$o < $_POST['count'];$o++){
	//	echo $_POST['Lot_No'.$o].":".$_POST['chkbox'.$o]."<BR>";
		if($_POST['chkboxx'.$o]==''){
			$g=0;
		}
		if($_POST['chkboxx'.$o]=='on'){
			$g=1;
		}
		$query="select top(1) active from Analyze_pirun where Lot_No='".$_POST['Lot_No'.$o]."' and canceled=0";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		
		if($row[0]<>$g){
			$query="update Analyze_pirun set active=".$g.",canceled=1,last_modify_uid='".$_SESSION['uid']."',last_modify_datetime='".date("Y-m-d H:i:s")."' where Lot_No='".$_POST['Lot_No'.$o]."' and canceled=0";
			$result=mssql_query($query);
			
			$query="INSERT INTO Analyze_pirun (Lot_No, create_datetime, creator, canceled, active)
							VALUES          (N'".$_POST['Lot_No'.$o]."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102), N'".$_SESSION['uid']."', 0,".$g. ")";
	//						echo $query."<BR>";
			$result=mssql_query($query);
			echo "Update ".$_POST['Lot_No'.$o]."<BR>";
			}
			else{
				echo "Passed ".$_POST['Lot_No'.$o]."<br>";
			}
			
	}

	echo "<script>window.close();</script>";
}
?>