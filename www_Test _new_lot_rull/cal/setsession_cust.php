<?php
session_start();
if($_GET['serial_id']==''){
$_SESSION['cust_no']=$_SESSION['cid']=$_SESSION['CTD_CUST_NO']=$_GET['cust_no'];
$_SESSION['cust_name']=$_SESSION['cname']=$_SESSION['Memo']=$_GET['cust_name'];
}
else{$_SESSION[$_GET['serial_id']]=$_GET['cust_no'];}
//header("location:../test.php");
header("Location: " . $_SESSION['lasturl'] );
?>
 