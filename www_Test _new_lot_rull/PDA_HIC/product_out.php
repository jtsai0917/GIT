<?php
session_start();
$srvip=$_SERVER['SERVER_ADDR'];
if(substr($srvip,0,7)=='195.7.2.100')
{
	$_SESSION['index']='index.php';	
}
elseif(substr($srvip,0,7)=='143.2.11.48')
{
	$_SESSION['index']='index.php';	
}
elseif(substr($srvip,0,7)=='195.7.5.4')
{
	$_SESSION['index']='index1.php';	
}
else
{
	$_SESSION['index']='index.php';	
}
unset($_SESSION['steps']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript">
function textsizer(e){
var evtobj=window.event? event : e 
var unicode=evtobj.charCode? evtobj.charCode : evtobj.keyCode
var actualkey=String.fromCharCode(unicode)
if(actualkey=="1"){location.href="pda_drum.php";}
if(actualkey=="2"){location.href="pda_lorry.php";}
if(actualkey=="3"){location.href="<?php echo $_SESSION['index'];?>";}
}
document.onkeypress=textsizer
</script>

<title>無標題文件</title>
<style type="text/css">
body,td,th {
	font-size: 25px;
	font-weight: bold;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
</head>

<body>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<p><strong>出荷作業選單</strong></p>

<p><strong><a href="pda_drum.php">1. Drum出貨檢查作業</a></strong></p>
<p><strong><a href="pda_lorry.php">2. Lorry出貨檢查作業</a></strong></p>

<p><a href="<?php echo $_SESSION['index'];?>"><strong>3. 上一步</strong></a></p>
</body>
</html>