<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>無標題文件</title>
<style type="text/css">
body,td,th {
	font-size: 20px;
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
<p><a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="161" height="86" /></a>人員：<?php echo $_SESSION['uname']?></p>
<p><strong>回廠作業</strong></p>
<form id="form1" name="form1" method="post" action="">
  <label for="otd_no"></label>
  OTD NO: 
  <input type="text" name="otd_no" id="otd_no" width="100" height="20"/>
</form>
<p><strong>[儲存並離開]</strong></p>
<p><a href="index.php">[不儲存離開]</a></p>
<p><strong> <a href="<?php echo $_SESSION['index'];?>">上一步</a></strong></p>
</body>
</html>