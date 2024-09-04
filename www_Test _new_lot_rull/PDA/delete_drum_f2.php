<?php
	session_start();
	include("../connections/conn.php");
	include ("../lib/fun.php");
	include ("../lib/jtsai.php");
					$up2="update DRUM_HISTORY_NORMAL 
					set DHN_DISCARD_DATE='".date("Ymd")."',DHN_CF_DISCARD_DATE='".date("Ymd")."',DHN_DISCARD_MAN='".$_SESSION['uid']."'
					where DHN_DRUM_NO='".$_GET['id']."'";
					echo $_GET['id']."已登記廢棄<BR>";
					$resultup2 = mssql_query($up2);
					my_msg($_GET['id']."  已經廢棄 ","wash_drum_f2.php");
?>