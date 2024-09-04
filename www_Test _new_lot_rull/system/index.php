<?php include("../checkuser.php");?>
<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" /><meta name="viewport" content="width=device-width, initial-scale=1">
<title>TYS 化學藥品充填及管理系統</title>
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
<script src="/css/respond.min.js">

            function getConfirmation()
			{
               var retVal = confirm("確定要刪除選取的檔案 ?");
               if( retVal == true ){
                  return true;
               }
               else{
                  return false;
               }
            }
			function ShowName()
		{
    		window.location.reload(forceGet);
			alert("Test");
		}
	


</script>

</head>
<body>

<div class="gridContainer clearfix">

  <div id="LayoutDiv1">
    <div id="banner">   
 <div id="logo"><img src="../pics/TYS.jpg" alt="logo" width="200" height="80"></div>
      <div id="logo_r"></div>
</div>
    <div id="menu"  <?php if($_SESSION['dbname']=="CHEMICAL_Test_build20161019"){echo 'style="background:#FF0"';} ?>>
      <div id="menuleft"><?php include("../css3menu/index.php")?></div>
      <div id="menuright"><?php include("../info.php"); ?></div>
    </div>
    <div id="contain">
    <?php 
	$url=$_GET['url'].".php";
	include($url); ?>
    </div>   

  </div>

</div>
</body>
</html>
 