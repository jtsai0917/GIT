<?php
session_start();
include("/connections/conn.php");
include("/lib/fun.php");
include("/lib/jtsai.php");
$editFormAction = $_SERVER['PHP_SELF'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>prod info</title>
<style type="text/css">
.center {
	text-align: center;
}
</style></head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>查詢</p>
  <p>產品編號 : 
    <label for="pdd_pro_no"></label>
    <input type="text" name="pdd_pro_no" id="pdd_pro_no" />
   產品名稱 : 
   <label for="pdd_prod_name"></label>
   <input type="text" name="pdd_prod_name" id="pdd_prod_name" />
   <input type="submit" name="submit" id="submit" value="送出" />
   <input type="submit" name="submit2" id="submit2" value=" 選擇完成 " />
  
   
  </p>
   <input type="hidden" name="MM_insert" value="form1">
<table width="400" border="1">
  <tr>
    <td width="100" class="center">產品編號</td>
    <td width="200" class="center">產品名稱</td>
    <td width="100" class="center"><input type="submit" name="all" id="all" value="全選" /><input type="submit" name="none" id="none" value="取消" /></td>  
  </tr>
  <?php
$editFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST['all'])){ $selct=1;$ck= ' checked';}
if(isset($_POST['none'])){ $selct=0;$ck= '';}

if(isset($_POST['submit2'])){
	$fill = $_POST["fill"];
	$myallprod = implode(";",$fill);	
	$_SESSION['myallprod']=$myallprod;
	echo $myallprod;
	echo '<script>document.location.href="'.$_SESSION['lasturl1'].'";</script>';
}

	include_once("connections/conn.php");
	if(isset($_POST['submit'])){$_SESSION['pid']=$_POST['pdd_pro_no'];$_SESSION['pname']=$_POST['pdd_prod_name'];}
	$query="SELECT [PDD_PROD_NO],[PDD_PROD_NAME],[PDD_SHOW] FROM [dbo].[PRODUCT_DATA] WHERE ([PDD_TYPE]<>'')";
	if($_GET['pdd_class']<>""){
	$query=$query." AND ([PDD_CLASS] LIKE '%".$_GET['pdd_class']."%')";}
	if($_SESSION['pid']<>""){
	$query=$query." AND ([PDD_PROD_NO] LIKE '%".$_SESSION['pid']."%')";}
	if($_SESSION['pname']<>""){
	$query=$query." AND ([PDD_PROD_NAME] LIKE '%".$_SESSION['pname']."%')";}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{	
	if(trim($row['PDD_SHOW'])=='')
	{
	echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
	echo "<td>".$row['PDD_PROD_NAME']."</td>";
	echo '<td align="center"><input type="checkbox" name="fill[]" id="fill" value="'.$row['PDD_PROD_NO'].'"'.$ck.'></td>';
	echo "</tr>";
	}
}



?>
</table>
<table width="400" border="1">
  <tr>
    <td align="center"><input type="submit" name="submit2" id="submit2" value=" 選擇完成 " /></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
