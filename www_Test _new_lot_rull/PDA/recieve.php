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
<p>回收作業選單
</p>
<p><strong>1. TOTO桶回收作業</strong></p>
<p><strong>2. 回收桶再利用B(洗淨前工程)</strong></p>
<p><strong>3. 回收桶A (洗淨廢棄用)</strong></p>
<p><strong>4. 洗淨廢棄用出廠</strong></p>
<p><strong>5. 轉用</strong></p>
<p><strong>6. 棧板回收</strong></p>
<p><strong>7. 回收桶輸入作業</strong></p>
<p><strong><a href="index.php">8. </a></strong><a href="<?php echo $_SESSION['index'];?>"><strong> 上一步</strong></a><strong></strong></p>
</body>
</html>