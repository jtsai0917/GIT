<?php
session_start();
$_SESSION['user']=$_GET['user'];
$_SESSION['name']=$_GET['name'];
header("Location: " . $_SESSION['lasturl'] );
?>
 