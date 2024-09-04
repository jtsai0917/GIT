<?php
session_start();
array_splice($_SESSION['aa'],$_GET['id'],1);
echo "<script type='text/javascript'>
		window.close()
		</script>";
?>