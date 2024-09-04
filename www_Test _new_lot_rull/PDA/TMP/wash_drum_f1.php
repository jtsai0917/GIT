<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
remurl("last_pda");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
</head>

<body>
<form id="form1" name="form1" method="post" action="el_drum_f2.php">
  <p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="161" height="86" /></a>人員：<?php echo $_SESSION['uname']?>  </p>
  <p>&nbsp;</p>
  <p><br />
      Lot No. ：
      <input type="text" name="lid" id="lid" autofocus  <?php echo ' value="'.$_SESSION['lid'].'"'; ?>  onKeyPress="return SubmitEnter(this,event)" onChange="set_date_session(this.name,this.value)"/>
      <input type="submit" name="enter" id="enter" value="送出" size="50"  />
      <input type="button" name="sample" id="enter" value="取樣" size="50" onClick="window.open('sample.php', '_self');" >
      <BR>
  </p>
    <p><strong><a href="fill.php">上一步</a></strong></p>
<br />
</form>
</body>
</html>
