<?php
	include("../lib/fun.php");
	session_start();
	$url="index.php?url=".$_GET['ani_type']."&id=".$_GET['id']."&pid=".$_GET['pid'];;
	$_SESSION[$_GET['ani_type']]=$_GET['item'];
	jumpto($url);
?>