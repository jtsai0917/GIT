<?php
	session_start();
	include("../lib/fun.php");
	auth('9-01',$_SESSION['aut']);
	lasturl();
	datepick();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>清除資料</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  刪除&nbsp;&nbsp;&nbsp;
  <input type="text" name="datepicker1" id="datepicker1" size="10" />&nbsp;&nbsp;&nbsp;
  之前的分析資料&nbsp;&nbsp;&nbsp;
  <input type="submit" name="submit" id="submit" value=" 確 定 " />
</form>
</body>
</html>
<?php
	if(isset($_POST['submit'])){
		$d1=date("Ymd");
		echo "今天日期：".date("m/d/Y");

//		echo $_SERVER['REQUEST_URI'];
		_confirm("確定要刪除  ".$_POST['datepicker1']." 前的資料",$_SERVER['REQUEST_URI'],'./index.php?url=delete_page&date='.dod($_POST['datepicker1'])."235959");

	}
?>



