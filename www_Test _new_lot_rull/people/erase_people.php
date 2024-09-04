<?php
session_start();
$_SESSION['user']="";
$_SESSION['name']="";
header("Location: " . $_SESSION['lasturl'] );
?>
 