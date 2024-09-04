<?php
session_start();
$_SESSION['anyrull']="";
$_SESSION['anyname']="";
header("Location: " . $_SESSION['lasturl'] );
?>
