<?php
session_start();
$_SESSION['date']="";
$_SESSION['time']="";
header("Location: " . $_SESSION['lasturl'] );
?>
 