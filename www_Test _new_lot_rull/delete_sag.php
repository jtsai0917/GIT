<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
$query="DELETE FROM SIGN_AGREE_ITEM where (SAG_NO='".$_GET['no']."')";
$result = mssql_query($query);
$query="DELETE FROM SIGN_AGREE where (SAG_NO='".$_GET['no']."')";
$result = mssql_query($query);
$query="update OUT_PLAN set SAG_NO='' where (SAG_NO='".$_GET['no']."')";
$result = mssql_query($query);
jumpto($_SESSION['lasturl']);
?>