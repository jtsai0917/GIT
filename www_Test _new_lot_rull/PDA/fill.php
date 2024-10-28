<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
if($_SESSION['uid']==''){jumpto("login.php");}
$_SESSION['lid']='';
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

?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript">
function textsizer(e){
var evtobj=window.event? event : e 
var unicode=evtobj.charCode? evtobj.charCode : evtobj.keyCode
var actualkey=String.fromCharCode(unicode)
if(actualkey=="7"){location.href="lorry_fill.php";}
if(actualkey=="2"){location.href="el_drum_fill.php";}
if(actualkey=="3"){location.href="el_drum_fill.php";}
if(actualkey=="4"){location.href="pda_20L_f1.php";}
if(actualkey=="5"){location.href="pda_20L_FILL.php";}
if(actualkey=="6"){location.href="index.php";}
if(actualkey=="1"){location.href="lorry_fill_hic.php";}
}
document.onkeypress=textsizer
</script>

<style type="text/css">
body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
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
<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?></p>
<p><strong><a href="lorry_fill.php">1. 新版 LORRY 充填作業</a></strong></p>
<p><strong><a href="http://<?php echo $server_ip = $_SERVER['SERVER_ADDR'];?>:88/PDA/lorry_fill.aspx?uid=<?php echo $_SESSION['uid'];?>">1. 新版 LORRY 充填作業</a></strong></p>
<p><strong><a href="el_drum_fill.php">2. EL藥品Drum充填/洗淨</p>
<p><strong><a href="el_drum_fill.php">3. IPA Drum充填</p>
<p><strong><a href="pda_20L_FILL.php">4. 20L PE充填/洗淨</p>
<p><strong><a href="cal_toto.php">5. CAL TOTO 充填</p>

<p><strong><a href="<?php echo $_SESSION['index'];?>">6. 上一步</a></strong></p>

</body>
</html>