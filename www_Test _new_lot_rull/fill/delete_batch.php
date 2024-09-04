<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form name="form1" method="post" action="">
  <input type="submit" name="ok" id="ok" value="確定刪除">
  <input type="submit" name="cancel" id="cancel" value="取消">
</form>
<?php

include("../connections/conn.php");
include("../lib/fun.php");

if(isset($_POST['ok'])){
	$query="update Tank_batch set active=0,memo='".$_SESSION['uid']."' where lot_no='".$_GET['lot_no']."' and active=1";
	$result=mssql_query($query);
	jumpto($_SESSION['lasturl']);
}
if(isset($_POST['cancel'])){
	jumpto($_SESSION['lasturl']);	
}
?>