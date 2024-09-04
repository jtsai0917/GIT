<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>SESSIONS</title>
<?php
session_start();
  foreach ($_SESSION as $key=>$val)
  echo $key."=>".$val."   "."</br>";
  print_r($_SESSION);