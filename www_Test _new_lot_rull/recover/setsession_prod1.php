<?php
session_start();
$_SESSION['prod_no1']=$_GET['pro_no1'];
$_SESSION['prod_name1']=$_GET['pro_name1'];
header("Location: " . $_SESSION['lasturl'] );
?>