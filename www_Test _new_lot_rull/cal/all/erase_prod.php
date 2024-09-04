<?php
session_start();
$_SESSION['prod_no1']=$_SESSION['prod_no']=$_SESSION['pid']=$_SESSION['pdd_chemical1']="";
$_SESSION['prod_name1']=$_SESSION['prod_name']=$_SESSION['pname']=$_SESSION['pdd_chemical2']="";
header("Location: " . $_SESSION['lasturl'] );
?>
 