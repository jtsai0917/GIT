<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <input type="submit" name="sure" id="sure" value=" 確定刪除 ??">
  <input type="submit" name="not" id="not" value=" 取消刪除 ??">
</form>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$idx=$_GET['index'];
$lot_no=$_GET['lot_no'];
if(isset($_POST["sure"])){
	$query="DELETE FROM FILE_REPORTS
			WHERE          ([index] ='".$idx."')";
	echo $query;
	echo "</br>";
	$result = mssql_query($query);
	$query="DELETE FROM AnalyzeDesign where (AND_LOT_NO='".$lot_no."')";
	echo $query;
	echo "</br>";
	$result = mssql_query($query);
	echo "deleted already";
	echo "<script type='text/javascript'>
	window.open('".$_SESSION['lasturl']."','_self')
	</script>";
}
if(isset($_POST["not"])){
	echo "not deleted";
	echo "<script type='text/javascript'>
	window.open('".$_SESSION['lasturl']."','_self')
	</script>";
}
?>