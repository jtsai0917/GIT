<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$query="SELECT DISTINCT ANI_GROUPNAME
FROM              AnalyzeItem
WHERE          (ANI_GROUPNAME <> '')";
echo '<table width="240" border="1"><tr><td align="right"><input type="submit" name="add1" id="add1" value="確定/離開"  /></td></tr></table>';
echo '<table width="240" border="1">';
echo '  <tr><td>  群組  </td><td>選擇</td>
  </tr>  ';
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while ($row = mssql_fetch_array($result)){
	echo '<tr><td>'.$row['ANI_GROUPNAME'].'</td><td>';
	echo '<input type="checkbox" name="chkbox[]" id="1" value="'.$row['ANI_GROUPNAME'].'" /></td></tr>';
}
echo '</table></form>';
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_POST["add1"])){
	$aa=$_POST['chkbox'];
	$_SESSION['test_group']=implode(",", $aa);
	if($_SESSION['test_group']==''){$_SESSION['test_group']='0';}
$url=$_SESSION['lasturl'];
jumpto($url);
}
?>