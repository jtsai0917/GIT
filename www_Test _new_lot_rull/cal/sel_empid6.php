<?php
	include("../lib/fun.php");
	$_SESSION['userid']=$_GET['id'];
	$_SESSION['username']=$_GET['uname'];
	$url=$_SESSION['redir'];
	if(substr($url,-1)<>'c'){$url=substr($url,0,-1);}
	jumpto($url."6");
?>