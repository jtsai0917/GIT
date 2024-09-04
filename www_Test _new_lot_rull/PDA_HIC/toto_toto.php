<?php   ///last edit by jtsai 2018-03-09 
session_start();
unset($_SESSION['urln1']);
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
datepick();
$path_root=$_SERVER['HTTP_HOST'];
if(trim($_SESSION['stock_lot'])==''){$str1='autofocus="autofocus"';$str2='';$str3='';}
if(trim($_SESSION['fod_lot'])==''){$str1='';$str2='autofocus="autofocus"';$str3='';}
if(trim($_SESSION['stock_lot'])<>'' and trim($_SESSION['fod_lot'])<>''){$str1='';$str2='';$str3='autofocus="autofocus"';}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<style type="text/css">
body,td,th {
	font-size:<?php echo $_SESSION['font_size'];?>px;
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

<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
<form name="form1" method="post" action="">
<font color="#FF00FF" >CAL 在庫 TOTO 轉 出荷 TOTO 移液作業</font><BR><BR>
在庫用 LOT NO: <input type="text" name="stock_lot" size="14" style="font-size:20px" <?php echo $str1;?> id="stock_lot" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['stock_lot'];?>"><BR>
出荷用 LOT NO: <input type="text" name="fod_lot" size="14" style="font-size:20px" <?php echo $str2;?>  id="fod_lot" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['fod_lot'];?>"> 
<input type="submit" name="submit" value=" 送 出 " style="font-size:20px">
</form>
<a href="<?php echo $_SESSION['index'];?>"><strong> 取消</strong></a>

<?php
if(isset($_POST['submit'])){
	$url="toto_toto_.php?fod_lot=".strtoupper($_POST['fod_lot']).'&stock_lot='.strtoupper($_POST['stock_lot']);
	jumpto($url);	
}
?>