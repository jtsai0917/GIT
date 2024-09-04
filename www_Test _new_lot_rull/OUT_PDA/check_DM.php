<?php
session_start();  
include("../connections/conn.php");
include ("../lib/fun.php");
include ("../lib/jtsai.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
else{$return_page="index.php";}

	$ao=new get_from_lot_no;
	$ao->lid=$_SESSION['lid'];
	$ao->cid();
	if($ao->spc=='LY'){
			$type=$ao->lyno;
		}
	else{
			$type=$ao->showname;	
	}
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>悠技出貨檢查</title>
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

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<p><a href="fill.php"></a>
	<BR />作 業 者：
<?php 
	echo $_SESSION['uname'];
	echo '<table width="300" border="1"><tr align="center"><td width="100">批 號 </td><td width="200">'.$_SESSION['lid'].'</td></tr>' ;
	echo '<tr align="center"><td>檢查碼</td><td>'.$_SESSION['lorry_no1'].'</td></tr>' ;
	
	echo '<tr align="center"><td>包裝型態</td><td>'.$type.'</td></tr></table>' ;
	
	if($_SESSION['lorry_no1']==$type and $type<>''){
		echo '<BR><input type="submit" name="save" value=" 完 成 " >';
		
	}
	
	if($ao->numrows==0 and $_SESSION['lid']<>''){
		$_SESSION['lid']=$_SESSION['lorry_no1']=$_SESSION['lorry_no']='';
		my_msg('Lot NO 輸入錯誤!!');
	}
	if($_SESSION['lid']=='') // LOT NO
	{
		$_SESSION['lorry_no1']=$_SESSION['lorry_no']='';
		$fo1='autofocus="autofocus"';
		echo '<BR>Lot NO ：
    <input type="text"  autocomplete="off" name="lid" id="lid"  style="font-size:'.$_SESSION['font_size'].'px" size="12" value="'.$_SESSION['lid'].'"'.$fo1.'onchange="set_date_session(this.name,this.value)"/>';
	}
	else{
		$fo1='';
	}
	
	if($_SESSION['lid']<>'' and $_SESSION['lorry_no1']=='') //Lorry NO
	{
		$fo2='autofocus="autofocus"';
		echo '<BR />出貨檢查碼：
    <input type="password" autocomplete="off" name="lorry_no" id="lorry_no"  style="font-size:'.$_SESSION['font_size'].'px" size="12"  value="'.$_SESSION['lorry_no'].'"'.$fo2. ' onchange="set_date_session(this.name,this.value)"/>';
	$_SESSION['lorry_no1']=base64_decode(trim($_SESSION['lorry_no'])) ;
	}
	else{
		$fo2='';
	}
	
?>
</p>
<input type="submit" hidden="hidden" name="enter" value="ENTER"  />
</form>
<?php
if(isset($_POST['enter'])) {
	$_SESSION['lorry_no']=trim($_POST['lorry_no']);
	$_SESSION['lorry_no1']=base64_decode(trim($_SESSION['lorry_no'])) ;

	if($_SESSION['lorry_no']==$_SESSION['lorry_no1'])   ///  PASSED
	{
		$query="insert into ";	
		
		
	}
	
	
	refresh();
	
}
?>
<p><strong><a href="<?php echo $return_page;?>">主目錄</a></strong></p>