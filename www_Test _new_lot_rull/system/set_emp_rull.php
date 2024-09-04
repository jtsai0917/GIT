<?php
	include("../connections/conn.php");
	include("../lib/fun.php");
	session_start();
?>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<table width="320" border="1">
<tr align="center">
<td width="40">O</td>
<td width="40">AUT_ID</td>
<td width="280">Åv­­  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="submit" id="submit" value="  Àx¦s  ">
<input type="submit" name="leave" id="leave" value="  Â÷¶}  " /></td>
</tr>
<?php
$query="SELECT          *
FROM              dbo.AUTHORITY_DATA";
$result = mssql_query($query);
while($row = mssql_fetch_array($result)){
	echo '<tr align="center"><td><input type="checkbox" name="chkbox[]" id="1" value="'.$row['AUT_NO'].'" '.rname($row['AUT_NO']).' /></td>';
	echo '<td>'.$row['AUT_NO'].'</td>';	
	echo '<td>'.$row['AUT_NAME'].'</td></tr>';	
}

?>
</table>
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
function rname($rid){
$query="select * from dbo.EMPLOYEE_AUTHORITY where dbo.EMPLOYEE_AUTHORITY.EMP_NO ='".$_GET['id']."'";
$result = mssql_query($query);
$numrows=mssql_num_rows($result);
if($numrows==0){
$query="INSERT INTO EMPLOYEE_AUTHORITY (EMP_NO) VALUES ('".$_GET['id']."')";
$result = mssql_query($query);
}
$query="SELECT          dbo.EMPLOYEE_DATA.EMP_NO, dbo.EMPLOYEE_AUTHORITY.AUT_GROUP
FROM              dbo.EMPLOYEE_AUTHORITY INNER JOIN
                            dbo.EMPLOYEE_DATA ON dbo.EMPLOYEE_AUTHORITY.EMP_NO = dbo.EMPLOYEE_DATA.EMP_NO
WHERE          (dbo.EMPLOYEE_DATA.EMP_NO = '".$_GET['id']."')";
$result = mssql_query($query);
$numrows=mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
	$rull=$row['AUT_GROUP'];
}
$aa=explode(';',$rull);
for($i=0;$i<count($aa);$i++)
{
  	if($aa[$i]==$rid){
		return ' checked="checked"';
	}
}
}
if (isset($_POST["submit"])){
	$aa=$_POST['chkbox'];
    $aut=implode(";", $aa);
	$query="UPDATE          dbo.EMPLOYEE_AUTHORITY
SET                   AUT_GROUP = '".$aut."'
WHERE          (EMP_NO = '".$_GET['id']."')";
sql_rec($_SERVER['QUERY_STRING'] ,$query);
echo $aut;
$result = mssql_query($query);
refresh();
}
if (isset($_POST["leave"])){
	jumpto($_SESSION['lasturl']);
}
?>
