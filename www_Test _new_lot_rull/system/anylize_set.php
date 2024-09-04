<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Anylize Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="/css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="/css/main.css" rel="stylesheet" type="text/css">
<style type="text/css">
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
<!-- 
若要深入了解檔案頂端 html 標籤周圍的條件式註解:
paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/

如果您使用自訂的 Modernizr 組建 (http://www.modernizr.com/)，請執行下列動作:
* 在這裡將連結插入您的 js
* 將下列連結移至 html5shiv
* 將「no-js」類別新增至頂端的 html 標籤
* 如果您在 Modernizr 組建中包含 MQ Polyfill，也可以將連結移至 respond.min.js 
-->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="/css/respond.min.js"></script>
</head>
<body>
<table width="300" border="0">
  <tr>
    <td><input type="button" name="lorry" id="lorry" value="   Lorry   " onclick="window.open('index.php?url=anylize_set&url1=lorry_set ', '_self');" /></td>
    <td><input type="button" name="drum" id="drum" value="   Drum/BTL   " onclick="window.open('index.php?url=anylize_set&url1=drum_set', '_self');" /></td>
  </tr>
</table>
<div class="gridContainer clearfix">
    <div id="contain">
    <?php 
	$url1=$_GET['url1'].".php";
	include($url1); ?>
    </div>   
</div>

</body>
</html>