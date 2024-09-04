<?php
session_start();
$_SESSION['lot_no']=$_SESSION['lid']="";
header("Location: " . $_SESSION['lasturl'] );
?>
 