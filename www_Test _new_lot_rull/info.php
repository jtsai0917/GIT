
<meta http-equiv="Content-Type" content="text/html; charset=big5" />

<?php
session_start();
include("connections/conn.php");
$query = "SELECT [AUT_GROUP] FROM [dbo].[EMPLOYEE_AUTHORITY] WHERE [EMP_NO] = '".$_SESSION['uid']."'";
$result = mssql_query($query);
while($row = mssql_fetch_array($result))
{
$_SESSION['aut']=$row['AUT_GROUP'].";";	
}
echo '<a  href=/Docs/index.html target="_blank"> 說明 </a>';
echo '<a  href=/session_dis.php> 登出 </a>';
echo '<a  href=/main.php?url=userinfo&uid='.$_SESSION['uid'].' \"target=\"_self\">'.$_SESSION['uname'].'</a>';
//echo $_SESSION['uname'];
?>