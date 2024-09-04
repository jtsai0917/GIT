<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");

if($_SESSION['input3']!='Y')
{
$query="INSERT INTO dbo.analyze_first1
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
		VALUES         ('".$_GET['lot_no']."', '".$_POST['SampleNo']."', '".$_SESSION['uptime']."', '".$_GET['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."','".$_SESSION['needno']."')";
		$_SESSION['query']=$query;
	$result=mssql_query($query);
}
if($_SESSION['input3']=='Y')
{
	$_SESSION['input3']='';
	$query="INSERT INTO dbo.analyze_first1
                          (lot_no, sample_no, create_time, ps, create_user,first,ani_group,chemical,need_no)
		VALUES         ('".$_SESSION['getlot']."', '".$_POST['SampleNo']."', '".$_SESSION['uptime']."', '".$_GET['ani_groupname']."', '".$_SESSION['uid']."','".$_POST['op']."','".$_GET['ani_groupname']."','".$_GET['pdd_chemical']."','".$_SESSION['needno']."')";
		$_SESSION['query']=$query;
	$result=mssql_query($query);
	
}
jumpto($_SESSION['lasturl1']);
?>