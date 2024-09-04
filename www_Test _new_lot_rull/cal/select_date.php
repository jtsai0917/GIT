<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

</script>
<title>無標題文件</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <label for="datepicker4"></label>
  <input type="text" name="datepicker4" id="datepicker4" />
  <input type="submit" name="  submit" id="  submit" value="送出" />
</form>
</body>
</html>
<?php
session_start();
include("../lib/fun.php");
datepick();
$loginFormAction = $_SERVER['PHP_SELF'];

if($_POST['submit']){
	$_SESSION['datepicker4']=$_POST['datepicker4'];
	jumpto($_SESSION['add_analyze']);	
}	
?>