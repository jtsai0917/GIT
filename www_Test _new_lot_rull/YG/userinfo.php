<?php 
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="../css/main.css" rel="stylesheet" type="text/css">
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
<script src="./js/html5.js"></script>
<![endif]-->
<script src="../css/respond.min.js"></script>
<title>User information</title>
</head>

<body>
<p>&nbsp;</p>
<p>User ID：<?php echo $_SESSION['uid'];?></p>
<p>UserName：<?php echo $_SESSION['uname'];?></p>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <input type="submit" name="pwdchange" id="pwdchange" value="  Change your passwords  " />
<?php 
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["pwdchange"])){
	session_start();
	echo '</br></br></br>Previous Passwords：<input type="password" name="oldpwd" id="oldpwd" /></br>';
	echo 'New Passwords：<input type="password" name="newpwd" id="newpwd" />';
	echo '<input type="submit" name="submit" id="submit" value="  Done  " />';
}

if(isset($_POST["submit"])){
	$query="SELECT EMP_NO, EMP_NAME, EMP_PASSWORD FROM EMPLOYEE_DATA WHERE (EMP_NO = '".$_GET['uid']."') AND (EMP_PASSWORD = '".$_POST['oldpwd']."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows>0){
		$query="UPDATE          dbo.EMPLOYEE_DATA
				SET                   EMP_PASSWORD = '".$_POST['newpwd']."'
				WHERE          (dbo.EMPLOYEE_DATA.EMP_NO = '".$_GET['uid']."') AND (dbo.EMPLOYEE_DATA.EMP_PASSWORD = '".$_POST['oldpwd']."')";
	$result1 = mssql_query($query);
	$numRows1 = mssql_num_rows($result);
	if(!$result1){my_msg("Nothing has been done!!")
	;}
	else{my_msg("Passwords has been changed!!")
	;}
	}
	else{my_msg("Nothing has been done!!");}
}
?>
</form>
</body>
</html>