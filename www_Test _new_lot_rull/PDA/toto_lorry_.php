<?php   ///last edit by jtsai 2018-03-09 
session_start();
unset($_SESSION['urln1']);
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();

$query="select * FROM      FILLPLAN_OUT_DECIDE where FDM_LOT_NO='".$_GET['fod_lot']."'";
// echo $query;
$result=mssql_query($query);
$numrows=mssql_num_rows($result);
if($numrows==0){
//	my_msg($numrows."輸入錯誤","toto_lorry.php");	
}
$path_root=$_SERVER['HTTP_HOST'];
if(trim($_SESSION['stock_toto'])==''){$str1='autofocus="autofocus"';$str2='';$str3='';}
if(trim($_SESSION['fod_lot'])==''){$str1='';$str2='autofocus="autofocus"';$str3='';}
if(trim($_SESSION['stock_lot'])<>'' and trim($_SESSION['fod_lot'])<>''){$str1='';$str2='';$str3='autofocus="autofocus"';}
$page="";
if(isset($_POST['submit']))
{
	if($_POST['fod_lot']==''){
		jumpto("toto_lorry.php");	
	}
	if($_POST['c1']<>'on' or $_POST['c2']<>'on' or $_POST['c3']<>'on' or $_POST['c4']<>'on' or $_POST['c5']<>'on' or $_POST['c6']<>'on')
	{
		my_msg("未確認充填前準備","toto_lorry_.php?fod_lot=".$_POST['fod_lot']."&lorry_no=".$_POST['lorry_no']);	
	}
	else
	{
		$ar="toto_lorry__.php?fod_lot=".$_POST['fod_lot']."&lorry_no=".$_POST['lorry_no'];
		jumpto($ar);	
	}
}
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<style type="text/css">
body,td,th {
	font-size:20px;
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
<form name="form1" method="post" action="toto_lorry_.php">
    <input type="hidden" name="fod_lot" value="<?php echo $_GET['fod_lot'];?>" />
    <input type="hidden" name="stock_lot" value="<?php echo $_GET['lorry_no'];?>" />
    <font color="#FF00FF" >CAL 在庫 TOTO 轉 Lorry 移液作業</font>
  <BR>
  充填日期: <?php echo date("Y/m/d");?><BR />
  客戶名稱: <?php echo $custname;?><BR />
  移液量: <?php echo $mqty;?><BR />
  取樣瓶數: <?php echo $smpcnt;?><BR />
  LOT NO: <?php echo $_SESSION['fod_lot'];?><BR />
  充填前準備:<BR>
  <input type="checkbox" name="c1" checked="checked"  />充填閥開關確認<BR>
   <input type="checkbox" name="c2" checked="checked"  />是否藥品切換?<BR>
    <input type="checkbox" name="c3" checked="checked"  />Coupler 清潔 <BR>
     <input type="checkbox" name="c4" checked="checked"  />Coupler/O-Ring 狀態確認<BR>
  <input type="checkbox" name="c5" checked="checked"  />Coupler 接續<BR>
  <input type="checkbox" name="c6" checked="checked"  />容器洩壓(洩壓聲停一分鐘)<BR>
  充填量設定(L)<input type="text" name="c7" size="6" style="font-size:20px" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['c7'];?>"/><BR>
  
  <input type="hidden" name="fod_lot" value="<?php echo trim($_GET['fod_lot']);?>"  />
  <input type="hidden" name="lorry_no" value="<?php echo trim($_GET['lorry_no']);?>"  />
  
<input type="submit" name="submit" style="font-size:20px" value=" 送 出 " ></td></tr>
</form>
<a href="toto_lorry.php"><strong> 取消</strong></a>