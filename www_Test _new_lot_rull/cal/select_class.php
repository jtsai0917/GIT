<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>選擇分析目的</title>
</head>
<body>
<p>選擇分析目的:</p>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>
    <label>
      <input type="radio" name="class" value="1" id="class_0" />
      製品</label>
    <br />
    <label>
      <input type="radio" name="class" value="2" id="class_1" />
      解析</label>
    <br />
    <label>
      <input type="radio" name="class" value="3" id="class_2" />
      受入</label>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value=" 確定 " />
    <input type="submit" name="leave" id="leave" value=" 離開 " />
  </p>
</form>
</body>
</html>
<?php
	$loginFormAction = $_SERVER['PHP_SELF'];
	include("../lib/fun.php");
	session_start();
	if(isset($_POST['submit'])){
		if($_POST['class']==1){$_SESSION['class_name']="製品";$_SESSION['needno']=1;unset($_SESSION['cid']);unset($_SESSION['cname']);}
		if($_POST['class']==2){$_SESSION['class_name']="解析";$_SESSION['needno']=151;unset($_SESSION['cid']);unset($_SESSION['cname']);}
		if($_POST['class']==3){$_SESSION['class_name']="受入";$_SESSION['needno']=271;}
		jumpto($_SESSION['edit_anylize']);
	}
	if(isset($_POST['leave'])){
		jumpto($_SESSION['edit_anylize']);
	}
	
?>