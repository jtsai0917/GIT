<?php
session_start();
$_SESSION['AND_GOODS']=$_SESSION['pid']=$_SESSION['prod_no']=$_SESSION['pdd_chemical1']=$_GET['pro_no'];
$_SESSION['pname']=$_SESSION['prod_name']=$_SESSION['pdd_chemical2']=$_GET['pro_name'];
if(isset($_GET['return'])){echo $_GET['return'];$_SESSION['lasturl']='cal/index.php?url='.trim($_GET['return']);}

header("Location: " . $_SESSION['lasturl'] );
if($_SESSION['pass']==1){$_SESSION['pid_'.$_SESSION['num']]=$_SESSION['AND_GOODS'];}
?>
 