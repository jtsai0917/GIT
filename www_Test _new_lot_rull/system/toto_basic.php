<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
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
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<table width="640" border="1">
	<tr align="center">
      <td align="center">TOTO桶槽 </td>
      <td align="center">
        桶號：
        <input name="id" type="text" id="id" size="12" />&nbsp;&nbsp;
        <input type="submit" name="search" id="search" value="   搜尋  " /></td>
    </tr>
</table>
  <table width="640" border="1">
    <tr align="center">
      <td align="center">統號</td>
      <td>啟用日期</td>
      <td>有效期限</td>
      <td>編輯</td>
    </tr>
    <tr align="center">
      <td><input type="text" name="no" id="no" /></td>
      <td><input type="text" name="start_date" id="start_date" /></td>
      <td><input type="text" name="end_date" id="end_date" /></td>
      <td><input type="submit" name="add" id="add" value="新增 / 編輯" /></td>
    </tr>
<?php
	$query="SELECT         dbo.DRUM_HISTORY_TOTO.*
FROM             dbo.DRUM_HISTORY_TOTO";
if($_SESSION['totoid']){$query=$query." where (HTM_DRUM_NO Like'%".$_SESSION['totoid']."%')";}

$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result)){
echo '</tr>
    <tr align="center">
      <td>'.$row['HTM_DRUM_NO'].'</td>
      <td>'.$row['HTM_CREATE_DATE'].'</td>
	  <td>'.$row['HTM_VALIDATE'].'</td>
      <td><input type="button" name="delete" id="delete" value="刪除" onClick="'."window.open('./delete_toto.php?id=".$row['HTM_DRUM_NO']." ', '_self');".'"/></td>
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
	$query="SELECT  count(HTM_DRUM_NO) as cnt FROM DRUM_HISTORY_TOTO WHERE (HTM_DRUM_NO = '".trim($_POST['no'])."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]==0){
		$query="INSERT INTO dbo.DRUM_HISTORY_TOTO (HTM_DRUM_NO, HTM_CREATE_DATE, HTM_VALIDATE) 	VALUES ('".$_POST['no']."', '".$_POST['start_date']."', '".$_POST['end_date']."')";
		$result = mssql_query($query);
	}
	elseif($row[0]>>0){
		$query="UPDATE  DRUM_HISTORY_TOTO SET HTM_DRUM_NO = '".$_POST['no']."', HTM_CREATE_DATE= '".$_POST['start_date']."', HTM_VALIDATE='".$_POST['end_date']."' WHERE (HTM_DRUM_NO = '".trim($_POST['no'])."')";
		$result = mssql_query($query);
	}
	refresh();
}
if(isset($_POST["search"])){
	$_SESSION['totoid']=$_POST['id'];
	refresh();
}
?>