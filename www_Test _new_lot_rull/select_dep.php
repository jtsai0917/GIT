<?php
	session_start();
	include("./lib/fun.php");
	$_SESSION['dep_id']=$_GET['id'];
	jumpto($_SESSION['lasturl']);
?>