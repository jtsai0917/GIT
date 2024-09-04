<?php
	session_start();
	include("../lib/fun.php");
	auth('9-01',$_SESSION['aut']);
	lasturl()
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>部門基本資料</title>
</head>

<body>
<table width="640" border="1">
	<tr align="center">
      <td align="center">製品桶槽</td>
    </tr>
</table>
<form id="form1" name="form1" method="post" action="">
  <table width="640" border="1">
    <tr align="center">
      <td align="center">化學藥品簡稱</td>
      <td>容量</td>
      <td>桶槽型式</td>
      <td>動作</td>
    </tr>
    <tr align="center">
      <td><input type="text" name="id" id="id" /></td>
      <td><input type="text" name="name" id="name" /></td>
      <td><input type="text" name="carry_type" id="carry_type" /></td>
      <td><input type="submit" name="add" id="add" value="新增 / 編輯" /></td>
    </tr>
<?php
session_start();
	include("../connections/conn.php");
	$query="SELECT         dbo.BIG_DRUM_VOLUME.*
FROM             dbo.BIG_DRUM_VOLUME";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
echo '</tr>
    <tr align="center">
      <td>'.$row['PDD_PROD_SHORT_NAME'].'</td>
      <td>'.$row['BDV_VOLUME'].'</td>
      <td>'.$row['CARRY_TYPE'].'</td>
      <td><input type="button" name="delete" id="delete" value="刪除" onClick="'."window.open('./delete_bdv.php?id=".$row['PDD_PROD_SHORT_NAME']." ', '_self');".'"/></td>
    </tr>';
}

?>
  </table></br>
</form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["add"])){
	//判別有無
	$query="SELECT  count(PDD_PROD_SHORT_NAME) as cnt FROM BIG_DRUM_VOLUME WHERE (PDD_PROD_SHORT_NAME = '".trim($_POST['id'])."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]==0){
		$query="INSERT INTO dbo.BIG_DRUM_VOLUME (PDD_PROD_SHORT_NAME, BDV_VOLUME, CARRY_TYPE)	VALUES ('".$_POST['id']."', ".$_POST['name'].",'".$_POST['carry_type']."')";
		$result = mssql_query($query);
	}
	elseif($row[0]>>0){
		$query="UPDATE  BIG_DRUM_VOLUME SET BDV_VOLUME = ".$_POST['name'].", CARRY_TYPE = ".$_POST['carry_type']." WHERE (PDD_PROD_SHORT_NAME = '".trim($_POST['id'])."')";
		$result = mssql_query($query);
	}
	refresh();
}
?>