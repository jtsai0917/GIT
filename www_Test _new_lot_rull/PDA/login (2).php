<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");

$_client_ip=$_SERVER['REMOTE_ADDR'];
$_client_ip=substr($_client_ip,0,8);
?><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>TYS 化學藥品充填及管理系統</title>
<style type="text/css">
body,td,th {
	font-size: 30px;
}
</style>
</head>


<p><strong>  </strong></p>
<p><strong>PDA登入</strong></p>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>
    <label for="uid"></label>
    帳號：
    <font size="+2">
    <input type="text" name="uid" id="uid" style="font-size:30px" size="12" align="bottom" value="<?php echo $_SESSION['uid'];?>"></font>
  </p>
  <p>密碼：
    <label for="pwd"></label>
    <input name="pwd" type="password" id="pwd" style="font-size:30px" size="12" align="bottom" <?php if($_SESSION['uid']){echo 'autofocus';}?> >
  </p>
  <p>
    <label for="submit"></label>
    <input type="submit" name="submit" style="width:100px;height:40px;border:2px orange double;" value=" 登入 ">
  </p>
</form>
<p>&nbsp;</p>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_POST['submit'])) {
  $loginUsername=$_POST['uid'];
  $password=$_POST['pwd'];
  $MM_fldUserAuthorization = "";
  if($_client_ip=='195.7.6.' or $_client_ip=='195.7.5.'  or $_client_ip=='127.0.0.'){
	  $MM_redirectLoginSuccess = "index1.php";
	  $_SESSION['place']="HIC";
	  $_SESSION['index']="index1.php";
  }
  else{
	  $MM_redirectLoginSuccess = "index.php";
	  $_SESSION['place']="TYS";
	   $_SESSION['index']="index.php";
  }
  $MM_redirectLoginFailed = "login.php";
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
?>