<?php
session_start();
$_SESSION['anyrull']=$_GET['anyrull'];
$_SESSION['anyname']=$_GET['anyname'];
//header("location:../test.php");
header("Location: " . $_SESSION['lasturl'] );
?>
 