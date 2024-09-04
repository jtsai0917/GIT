<?php
session_start();
if($_GET['serial_id']==''){
$_SESSION['cust_no']=$_SESSION['cid']=$_SESSION['pdd_chemical3']=$_GET['cust_no'];
$_SESSION['cust_name']=$_SESSION['cname']=$_SESSION['pdd_chemical4']=$_GET['cust_name'];
}
else{$_SESSION[$_GET['serial_id']]=$_GET['cust_no'];}
//header("location:../test.php");
	if($_SESSION['edit_fill_out']<>'')
	{
	header("Location: " . $_SESSION['edit_fill_out'] );
	}
	else{
	header("Location: " . $_SESSION['lasturl'] );
	}
if($_SESSION['pass']==1){$_SESSION['cust_no2_'.$_SESSION['num']]=$_SESSION['cust_no'];}
?>
 