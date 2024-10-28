<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
remurl("last_pda");
$_SESSION['dm_no']='';
$_SESSION['made_lot']='';
$_SESSION['lid']='';
if($_SESSION['uid']==''){jumpto("login.php");}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
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

<body>
<form id="form1" name="form1" method="post" action="el_drum_f2.php">
  <p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?></p>
      Lot NO：
      <input type="text"  autocomplete="off" style="font-size:25px" size="12" name="lid" id="lid" autofocus />
      <BR>
  </p>
    <p><strong><a href="fill.php">上一步</a></strong></p>
<br />
</form>
</body>
</html>
