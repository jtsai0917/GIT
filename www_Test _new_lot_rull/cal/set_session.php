<?php
session_start();
$_SESSION[$_GET['id']."1"]=$_GET['value'];
sleep(1);
?>
<script>window.opener=null; window.close();</script>