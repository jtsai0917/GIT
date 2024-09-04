<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../lib/fun.php");
include("../connections/conn.php");
$query="select ori_pid,weight from PDD_MADE_FROM where target_pid='".$_GET['pid']."'";
$result=mssql_query($query);
$row=mssql_fetch_row($result);
$v1=$row[0];
$v2=$row[1];
echo 
'
<font size="+4">
<form name="form1" method="post" action="">
修改設定值<BR>
  來源料號：
  <input type="text" style="font-size:20" size="8" name="pid" id="pid" value="'.$v1.'"><BR>
  權重：
  <input type="text" style="font-size:20" size="4" name="weight" id="weight" value="'.$v2.'">
  <BR>
  <input type="submit" style="font-size:20"  name="submit" value=" 確定/修改 ">
  <input type="submit" style="font-size:20" name="leave" value=" 離開 ">
  <BR><BR>
  <input type="submit" style="font-size:20"  name="kill" value=" 刪除資料 ">
</form>
</font>
';

if(isset($_POST['submit'])){
	$query="update PDD_MADE_FROM set ori_pid='".$_POST['pid']."',weight=".$_POST['weight']." where target_pid='".$_GET['pid']."'";
	$result=mssql_query($query);
}

if(isset($_POST['leave'])){
	jumpto("index.php?url=prod_from");
}

if(isset($_POST['kill'])){
	$query="delete from PDD_MADE_FROM where ori_pid='".$_POST['pid']."' and weight=".$_POST['weight']." and target_pid='".$_GET['pid']."'";
	$result=mssql_query($query);
}

?>
