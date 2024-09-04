<?php
session_start();
$_SESSION['pid']=$_SESSION['prod_no']=$_SESSION['pdd_chemical1']=$_GET['pro_no'];
$_SESSION['pname']=$_SESSION['prod_name']=$_SESSION['pdd_chemical2']=$_GET['pro_name'];

header("Location: " . $_SESSION['lasturl'] );
?>
 