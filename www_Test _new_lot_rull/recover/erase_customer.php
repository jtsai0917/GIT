<?php
session_start();
$_SESSION['cid']=$_SESSION['cust_no']=$_SESSION['pdd_chemical3']="";
$_SESSION['cname']=$_SESSION['cust_name']=$_SESSION['pdd_chemical4']="";
header("Location: " . $_SESSION['lasturl'] );
?>
 