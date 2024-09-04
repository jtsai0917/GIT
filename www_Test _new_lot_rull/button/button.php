<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>出勤打卡作業</title>
</head>

<body>
</body>
</html>
<BR />
<?php
echo '<form action="" method="post">&nbsp;&nbsp;&nbsp;&nbsp;<font size="+4"> 工號: '.$_SESSION['empno'].'</font>

<input type="hidden" name="empno" value="'.$_SESSION['empno'].'" /><BR>
<br />
&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="1" name="1" style="width:140px;height:140px;color:#F00; font-size:80px">
<input type="submit" value="2" name="2" style="width:140px;height:140px;color:#F00; font-size:80px" >
<input type="submit" value="3" name="3" style="width:140px;height:140px;color:#F00; font-size:80px" >
<input type="submit" value="確定" name="enter" style="width:140px;height:140px;color:#F00; font-size:40px">
<BR />
&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="4" name="4" style="width:140px;height:140px;color:#F00; font-size:80px" >
<input type="submit" value="5" name="5" style="width:140px;height:140px;color:#F00; font-size:80px" >
<input type="submit" value="6" name="6" style="width:140px;height:140px;color:#F00; font-size:80px">
<input type="submit" value="←" name="B" style="width:140px;height:140px;color:#F00; font-size:80px">
</br>
&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="7" name="7" style="width:140px;height:140px;color:#F00; font-size:80px">
<input type="submit" value="8" name="8" style="width:140px;height:140px;color:#F00; font-size:80px" >
<input type="submit" value="9" name="9" style="width:140px;height:140px;color:#F00; font-size:80px" >
<BR>
&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="E" name="E" style="width:140px;height:140px;color:#F00; font-size:80px" >
<input type="submit" value="0" name="0" style="width:140px;height:140px;color:#F00; font-size:80px">
<input type="submit" value="C" name="C" style="width:140px;height:140px;color:#F00; font-size:80px">


</form>
';

if(isset($_POST['1'])){
	$_SESSION['empno']=	$_SESSION['empno']."1";
//	refresh();
	echo "1";
}
if(isset($_POST['2'])){
	$_SESSION['empno']=	$_SESSION['empno']."2";
refresh();
}
if(isset($_POST['3'])){
	$_SESSION['empno']=	$_SESSION['empno']."3";
refresh();
}
if(isset($_POST['4'])){
	$_SESSION['empno']=	$_SESSION['empno']."4";
refresh();
}
if(isset($_POST['5'])){
	$_SESSION['empno']=	$_SESSION['empno']."5";
refresh();
}
if(isset($_POST['6'])){
	$_SESSION['empno']=	$_SESSION['empno']."6";
refresh();
}
if(isset($_POST['7'])){
	$_SESSION['empno']=	$_SESSION['empno']."7";
refresh();
}
if(isset($_POST['8'])){
	$_SESSION['empno']=	$_SESSION['empno']."8";
refresh();
}
if(isset($_POST['9'])){
	$_SESSION['empno']=	$_SESSION['empno']."9";
refresh();
}
if(isset($_POST['0'])){
	$_SESSION['empno']=	$_SESSION['empno']."0";
refresh();
}
if(isset($_POST['C'])){
	$_SESSION['empno']=	"";
refresh();
}
if(isset($_POST['E'])){
	$_SESSION['empno']=	$_SESSION['empno']."E";
refresh();
}
if(isset($_POST['R'])){
	$_SESSION['empno']=	$_SESSION['empno']."E";
refresh();
}

function refresh($refresht){
	$ulink=$_SERVER['REQUEST_URI'];
	echo '<script>document.location.href="'.$ulink.'";</script>';		
}