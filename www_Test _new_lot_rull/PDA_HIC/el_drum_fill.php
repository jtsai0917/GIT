<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
remurl("last_pda");
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>EL藥品Drum充填</title>
<style type="text/css">
body,td,th {
	font-size: 30px;
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

<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?></p>
<p><a href="wash_drum_f1.php" title="wash_drum_f1" target="_self">洗桶作業</a></p>
<p><a href="el_drum_f1.php" title="DRUM 充填" target="_self">DRUM 充填</a></p>
<p><a href="samp_rcv_fill.php" title="取樣作業" target="_self">取樣作業</a></p>
 <p><strong><a href="fill.php">上一步</a></strong></p>
