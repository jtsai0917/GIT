<?php 
		session_start();
		$filetmp='./filetmp/'.date("Mdhis").'.txt';
		$_SESSION['filetmp']=$filetmp;
		$usi='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?url=input1';
		echo '<script>document.location.href="'.$usi.'";</script>';
		
?>
