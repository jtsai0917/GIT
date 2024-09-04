<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
?>
選擇人員(僅列出分析課)
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <table width="800" border="0">
    <tr>
      <td align="right" width="600">姓名 
        <label for="name"></label>
      <input type="text" name="name" id="name"></td>
      <td align="center" width="200"><input type="submit" name="search" id="search" value="  搜尋  "> <input type="submit" name="leave" id="leave" value="  離開  "></td>
    </tr>
  </table>
  <table width="800" border="1">
    <tr align="center">
      <td>工號</td><td>人員</td><td>選擇</td>
    </tr>
<?php
	$query="SELECT         dbo.EMPLOYEE_DATA.*
FROM             dbo.EMPLOYEE_DATA WHERE         (DEP_NO = 'AN')";
if($_SESSION['an_name']<>''){$query=$query." and (EMP_NAME Like '%".$_SESSION['an_name']."%')";}
$result=mssql_query($query);
while($row=mssql_fetch_array($result)){
	echo '<tr><td align="center">'.$row['EMP_NO'].'</td><td align="center">'.$row['EMP_NAME'].'</td><td align="center"><input type="button" name="choose" value="  V  " 
	onClick="window.open('."'/cal/index.php?url=sel_empid6&id=".$row['EMP_NO']."&uname=".$row['EMP_NAME']."', '_self');".'"'.' />';
}
?>
 </table>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];

if(isset($_POST["search"]))
{
	$_SESSION['an_name']=$_POST['name'];
}

if(isset($_POST["leave"]))
{
	jumpto($_SESSION['redir']);
}
?>
