<?php
session_start();
$_SESSION['pid3']=$_SESSION['pdd_chemical1']=$_GET['pro_no'];
//header("location:../test.php");
header("Location: " . $_SESSION['lasturl'] );
?>
 