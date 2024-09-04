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
include_once("connections/ax_connections.php");
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
	 };
    
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
    header("Location: ". $MM_redirectLoginFailed );
  }
}
mssql_close($dbhandle);
$query = "SELECT [AUT_GROUP] FROM [dbo].[EMPLOYEE_AUTHORITY] WHERE [EMP_NO] = '".$_SESSION['uid']."'";
$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
$_SESSION['aut']=$row['AUT_GROUP'].";";	
}
echo "Barcode資料庫: ".$_SESSION['dbhost']."&nbsp;&nbsp;&nbsp;(";
echo $_SESSION['dbname'].")</br>";
echo "AX 資料庫: ".$_SESSION['AX_DB']."&nbsp;&nbsp;&nbsp;(";
echo $_SESSION['AX_DB'].")</br>";
echo "Application service IP: ".$_SERVER['SERVER_ADDR']."</br>";

?>
<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
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
</html>