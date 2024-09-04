<?php
session_start();
if (!$_SESSION['MM_Username']){
	header("Location:/index.php ");
}?>