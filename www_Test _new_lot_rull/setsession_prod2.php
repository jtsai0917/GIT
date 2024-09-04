<?php
session_start();
$_SESSION['AND_GOODS']=$_GET['pro_no'];
//header("location:../test.php");
echo "setsession2";
header("Location: " . $_SESSION['lasturl'] );
?>
 