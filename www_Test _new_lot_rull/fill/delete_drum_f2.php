<?php
	session_start();
	include("../connections/conn.php");
	include ("../lib/fun.php");
	include ("../lib/jtsai.php");
					$up2="update DRUM_HISTORY_NORMAL 
					set DHN_DISCARD_DATE='".date("Ymd")."',DHN_CF_DISCARD_DATE='".date("Ymd")."'
					where DHN_DRUM_NO='".$_GET['id']."'";
					echo $_GET['id']."¤wµn°O¼o±ó<BR>";
					$resultup2 = mssql_query($up2);
?>