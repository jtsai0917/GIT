<?php
session_start();
$_SESSION['prod_no']=$_GET['pro_no'];
$_SESSION['prod_name']=$_GET['pro_name'];

header("Location: " . $_SESSION['lasturl'] );
?>
 