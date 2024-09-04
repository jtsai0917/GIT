<?php
session_start();
$_SESSION['no1']=$_GET['no1'];
$_SESSION['no2']=$_GET['no2'];
header("Location: " . $_SESSION['lasturl'] );
?>
 