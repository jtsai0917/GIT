<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	auth('9-01',$_SESSION['aut']);
	lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>產品基本資料</title>

</head>

<body>
查詢
<form id="form1" name="form1" method="post"  onsubmit="return checkform1(this);" action="<?php echo $loginFormAction; ?>">
  <table width="800" border="1">
    <tr>
      <td width="800">產品名稱：<span class="d1">
        <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if ($_GET['pid']){
			echo $_GET['pid'];
			$_SESSION['pid']=$_GET['pid'];
		}
		elseif($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no ', '_self');" />
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php 
		if ($_GET['pname']){
			echo $_GET['pname'];
			$_SESSION['pname']=$_GET['pname'];
		}
		elseif($_SESSION['pname']){
			echo $_SESSION['pname'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
        <input type="submit" name="load" id="load" value=" 新增/查詢 產品原料 " />
      </span></td>
    </tr>
  </table>
  <?php
$loginFormAction = $_SERVER['PHP_SELF'];
echo '<table width="800" border="1">';
echo '<tr><td>只 KEY 料號</td><td>目標料號：<input type="text" name="target_pid"></td><td>原料料號：<input type="text" name="ori_pid"></td><td>權重：<input type="text" name="weight"></td><td><input type="submit" name="submit" value=" 新增 資料"></td></tr>';
echo '</table>';
echo '<table width="800" border="1"><tr><td>料號</td><td>產品名稱</td><td>權重</td><td>原料料號</td><td>原料名稱</td></tr>';
$query="select * from PDD_MADE_FROM ";	
if($_SESSION['pid']<>''){$query.=" where target_pid='".$_SESSION['pid']."' ";}
$query.=" order by target_pid";
$result=mssql_query($query);
while($row=mssql_fetch_array($result))
{
	echo '<tr><td><a href="weight_setup.php?pid='.$row['target_pid'].'">'.$row['target_pid'].'</a></td><td>'.get_prod_name($row['target_pid']).'</td><td>'.$row['weight'].'</td><td>'.$row['ori_pid'].'</td><td>'.get_prod_name($row['ori_pid']).'</td></tr>';
}	


if(isset($_POST["submit"])){
	$query="select count(*) as aaa from PDD_MADE_FROM where target_pid='".$_POST['target_pid']."' and ori_pid='".$_POST['ori_pid']."' ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]==0){
		$query="INSERT INTO PDD_MADE_FROM (target_pid, ori_pid, weight, creator) VALUES  ('".$_POST['target_pid']."','".$_POST['ori_pid']."', ".$_POST['weight'].", '".$_SESSION['uid']."')";
//		echo $query."<BR>";
		$result=mssql_query($query);
		refresh();
	}
	else{
		my_msg("重複輸入");	
	}
}
?>
</form>
</body>
</html>