<?php
session_start();
$_SESSION['comfirm']=$_GET['comfirm'];
my_msg("完成");
echo "<script type='text/javascript'>
window.close();
</script>";