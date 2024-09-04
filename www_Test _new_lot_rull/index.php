<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>登入畫面</title>
<style type="text/css">
#form1 {
	text-align: center;
}
</style>

</head>
<body>
<?php
//echo $_SERVER['HTTP_HOST'];
if(($_SERVER['SERVER_ADDR'])=='10.181.5.100'){
	$_SESSION['AX_DB']='此網段無法讀取AX資料庫';
	}
else{
	include_once("connections/ax_connections.php");
}
require("connections/conn.php");
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_POST['uid'])) {
  $loginUsername=$_POST['uid'];
  $password=$_POST['pwd'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "main.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
//當確定時	
//mysql_select_db($database_connection, $connection);
$query = "SELECT * FROM EMPLOYEE_DATA WHERE (EMP_NO = '".$_POST['uid']."') AND (EMP_PASSWORD = '".$_POST['pwd']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);

// $cur=odbc_exec($conn,$query); 
  $loginFoundUser = $numRows;
  if ($loginFoundUser) {
	 while($row = mssql_fetch_array($result))
	 {
	 $_SESSION['uname']=$row['EMP_NAME'];
	 $_SESSION['uid']=strtoupper($_POST['uid']);
	 $_SESSION['ugroup']=$row['DEP_NO'];
	 if($row['DEP_NO']=='YG'){
		 	$MM_redirectLoginSuccess = "/YG/index.php";
		 }
	 };
   // echo $MM_redirectLoginFailed;
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
  	echo "SSS";
    header("Location: ". $MM_redirectLoginFailed );
  }
}
mssql_close($dbhandle);
if(strpos($_SERVER['HTTP_HOST'],"intra"))
{
}
else{
$query = "SELECT [AUT_GROUP] FROM [dbo].[EMPLOYEE_AUTHORITY] WHERE [EMP_NO] = '".$_SESSION['uid']."'";
$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
$_SESSION['aut']=$row['AUT_GROUP'].";";	
}
if(($_SERVER['SERVER_ADDR'])=='195.7.2.100'){
		$ope='此網段無法讀取AX資料庫';
	}
	else{
		$ope=$_SESSION['AX_DB'];
	}

echo "Barcode資料庫: ".$_SESSION['dbhost']."&nbsp;&nbsp;&nbsp;(";
echo $_SESSION['dbname'].")</br>";
echo "AX 資料庫: ".$_SESSION['AX_DB']."&nbsp;&nbsp;&nbsp;(";
echo $ope.")</br>";
echo "Application service IP: ".$_SERVER['SERVER_ADDR']."</br>";
}
if(strpos($_SERVER['HTTP_HOST'],"intra"))///手機板使用
{
echo '<style type="text/css">
body,td,th {
	font-size:100px;
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
</style>';
echo '<form id="form1" name="form1" method="POST" action="'.$loginFormAction.'">
  <p><img src="pics/TYS.jpg"  width="497" height="396" alt="logo" /></p>
  <p>帳號:
    <input type="text"  style="font-size:45px;background:lightgreen;" name="uid" id="uid" />
  </p>
  <p id="form1">密碼: 
    <input type="password"  style="font-size:45px;background:lightgreen;" name="pwd" id="pwd" />
  </p>
    <input type="submit" name="submit" style="font-size:100px" id="submit" value="登入" />
    <input type="hidden" name="MM_insert" value="form1">

</form>
</body>
</html>';
}
else{
echo '<form id="form1" name="form1" method="POST" action="'.$loginFormAction.'">
  <p><img src="pics/TYS.jpg" width="297" height="196" alt="logo" /></p>
  <p>帳號:
    <input type="text" name="uid" id="uid" />
  </p>
  <p id="form1">密碼: 
    <input type="password" name="pwd" id="pwd" />
  </p>
    <input type="submit" name="submit" id="submit" value="登入" />
    <input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>';
}
?>
