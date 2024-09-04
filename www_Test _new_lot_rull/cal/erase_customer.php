<?php
session_start();
$_SESSION['myallcust']=$_SESSION['cust_no']=$_SESSION['pdd_chemical3']="";
$_SESSION['CTD_CUST_NO']=$_SESSION['cust_name']=$_SESSION['pdd_chemical4']="";
header("Location: " . $_SESSION['lasturl'] );
?>
