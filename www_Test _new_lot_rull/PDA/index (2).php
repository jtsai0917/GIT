<?php
session_start();
include("../lib/fun.php");
if($_SESSION['uid']==''){jumpto("login.php");}
if(!isset($_SESSION['font_size'])){$_SESSION['font_size']='20';}
$_SESSION['lid']='';
datepick();
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript">
function textsizer(e){
var evtobj=window.event? event : e 
var unicode=evtobj.charCode? evtobj.charCode : evtobj.keyCode
var actualkey=String.fromCharCode(unicode)
if(actualkey=="1"){location.href="fill.php";}
if(actualkey=="2"){location.href="product_out.php";}
if(actualkey=="3"){location.href="sample.php";}
if(actualkey=="4"){location.href="tank_batch.php";}
if(actualkey=="5"){location.href="logout.php";}
if(actualkey=="6"){location.href="lorry_check.php";}
}
document.onkeypress=textsizer
</script>
</head>

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
<p><img src="../pics/TYS.jpg" alt="" width="50" height="28" />人員：<font size="+3"> <?php echo $_SESSION['uname'];?></font> </p>
<p><a href="fill.php"><strong>1. 充填作業</strong></a></p>
<p><strong><a href="product_out.php">2. 出荷作業</a></strong></p>
<p><strong><a href="samp_rcv_fill.php">3. 樣品瓶作業</a></strong></p>
<p><strong><a href="tank_batch.php">4. 封槽作業</a></strong></p>
<p><strong><a href="logout.php">5. 登出</a></strong></p>
<p><strong><a href="Lorry_check.php">6. Lorry_補充填資料</a></strong></p>

<form id="form1" name="form1" method="post" action="">
  設定字型 &nbsp;&nbsp;
  <select name="font_size" id="font_size"  style="font-size:<?php echo $_SESSION['font_size'];?>px" onchange="set_date_session(this.name,this.value)">
    <option value="30" <?php if($_SESSION['font_size']=='30'){echo 'selected';}else{echo '';}?>>30</option>
    <option value="25" <?php if($_SESSION['font_size']=='25'){echo 'selected';}else{echo '';}?>>25</option>
    <option value="20" <?php if($_SESSION['font_size']=='20'){echo 'selected';}else{echo '';}?>>20</option>
    <option value="15" <?php if($_SESSION['font_size']=='15'){echo 'selected';}else{echo '';}?>>15</option>
    <option value="10" <?php if($_SESSION['font_size']=='10'){echo 'selected';}else{echo '';}?>>10</option>
  </select>
</form>

<?php
include("../lib/fun.php");
if($_SESSION['uid']==''){jumpto("login.php");}
$_SESSION['lid']='';
?>