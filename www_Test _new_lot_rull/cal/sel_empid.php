<?php
	include("../lib/fun.php");
	$_SESSION['userid']=$_GET['id'];
	$_SESSION['username']=$_GET['uname'];
	$url=$_SESSION['redir'];
	echo $url;
	jumpto($url);
?>