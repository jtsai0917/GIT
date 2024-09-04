<?php
session_start();
$_SESSION['AND_GOODS']=$_SESSION['pro_no']=$_SESSION['prod_no']=$_SESSION['pid']=$_SESSION['pdd_chemical1']="";
$_SESSION['pro_name']=$_SESSION['prod_name']=$_SESSION['pname']=$_SESSION['pdd_chemical2']="";
header("Location: " . $_SESSION['lasturl'] );
?>
 