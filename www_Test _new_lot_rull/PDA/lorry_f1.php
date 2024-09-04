<?php
session_start();  
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
if($_SESSION['place']=="HIC"){$return_page="index1.php";}
else{$return_page="index.php";}
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>Lorry 充填</title>
<style type="text/css">

body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
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
<form id="form1" name="form1" method="post" action="lorry_f2.php">
<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?></p>
    Lot No. ：
    <input type="text"  autocomplete="off" name="lid" id="lid"  style="font-size:<?php echo $_SESSION['font_size'];?>px" size="12" <?php echo ' value="'.$_SESSION['lid'].'"'; ?>  onKeyPress="return SubmitEnter(this,event)"/>
<?php
if($_SESSION['lid']==''){
	echo '<input type="submit" name="enter" id="enter" style="font-size:'.$_SESSION['font_size'].'px;" value="送出" />';
}
?>
</form>