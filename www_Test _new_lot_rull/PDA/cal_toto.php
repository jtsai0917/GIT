<?php
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	lasturl();
	datepick();
?>

<body>
<font style="font-size:35px">
<p><a href="../PDA/toto_toto.php">1. CAL TOTO Тр TOTO </a></p>
<p><a href="../PDA/toto_lorry.php">2. CAL TOTO Тр LORRY </a></p>
</font>
</body>
</html>
