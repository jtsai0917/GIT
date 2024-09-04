<?php
session_start();
include("/connections/conn.php");
include("/lib/fun.php");
include("/lib/jtsai.php");
$editFormAction = $_SERVER['PHP_SELF'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
}
$_SESSION['which']='all';
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
<form id="form1" name="form1" method="post" action="">
  <p>查詢</p>
  <p>產品編號 : 
    <label for="pdd_pro_no"></label>
    <input type="text" name="pdd_pro_no" id="pdd_pro_no" />
   產品名稱 : 
   <label for="pdd_prod_name"></label>
   <input type="text" name="pdd_prod_name" id="pdd_prod_name" />
   <input type="submit" name="submin" id="submin" value="送出" /> <br />

   <input name="submit1" type="submit" id="submit1" value=" 列出製品 ">
      <input name="submit2" type="submit" id="submit2" value=" 列出解析 ">

   <input name="submit3" type="submit" id="submit3" value=" 列出原料 ">
<input name="submit4" type="submit" id="submit4" value=" 全部顯示 ">
         <input name="submit5" type="submit" id="submit5" value=" EL分析課內 ">
         <input name="submit6" type="submit" id="submit6" value=" EL ">
         <input name="submit7" type="submit" id="submit7" value=" DP ">

  

  </p>
   <input type="hidden" name="MM_insert" value="form1">

</form>
<table width="450" border="1">
  <tr>
    <td width="99" class="center">產品編號</td>
    <td width="185" class="center">產品名稱</td>
    <td width="49" class="center">選擇</td>    
  </tr> 
  <?PHP 
   if(isset($_POST['submit1']))
   { $_SESSION['which']='0';
	   $query="select * from PRODUCT_TYPE where TYPE_PROD='Y' ";
	   $result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
	echo "<td>".get_prod_name($row['PDD_PROD_NO'])."</td>";
	echo '<td><a href="setsession_prod.php?pro_no='.$row['PDD_PROD_NO'].'&pro_name='.get_prod_name($row['PDD_PROD_NO']).'">選取</a></td>';
	echo "</tr>";
		}

   }  
   if(isset($_POST['submit2']))
   {
	   $_SESSION['which']='0';
	   $query="select * from PRODUCT_TYPE where TYPE_ANALY='Y' ";
	   $result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
	echo "<td>".get_prod_name($row['PDD_PROD_NO'])."</td>";
	echo '<td><a href="setsession_prod.php?pro_no='.$row['PDD_PROD_NO'].'&pro_name='.get_prod_name($row['PDD_PROD_NO']).'">選取</a></td>';
	echo "</tr>";
		}

   }  
   if(isset($_POST['submit3']))
   { $_SESSION['which']='0';
	   $query="select * from PRODUCT_TYPE where TYPE_RAW='Y' ";
	   $result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
	echo "<td>".get_prod_name($row['PDD_PROD_NO'])."</td>";
	echo '<td><a href="setsession_prod.php?pro_no='.$row['PDD_PROD_NO'].'&pro_name='.get_prod_name($row['PDD_PROD_NO']).'">選取</a></td>';
	echo "</tr>";
		}

   }  
     if(isset($_POST['submit5']))
   { $_SESSION['which']='0';
	   $query="select * from PRODUCT_TYPE where department='EL分析課內' ";

	   $result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
	echo "<td>".get_prod_name($row['PDD_PROD_NO'])."</td>";
	echo '<td><a href="setsession_prod.php?pro_no='.$row['PDD_PROD_NO'].'&pro_name='.get_prod_name($row['PDD_PROD_NO']).'">選取</a></td>';
	echo "</tr>";
		}
	}
	if(isset($_POST['submit6']))
   { $_SESSION['which']='0';
	   $query="select * from PRODUCT_TYPE where department='EL' ";
	   $result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
	echo "<td>".get_prod_name($row['PDD_PROD_NO'])."</td>";
	echo '<td><a href="setsession_prod.php?pro_no='.$row['PDD_PROD_NO'].'&pro_name='.get_prod_name($row['PDD_PROD_NO']).'">選取</a></td>';
	echo "</tr>";
		}
	}	
	if(isset($_POST['submit7']))
   { $_SESSION['which']='0';
	   $query="select * from PRODUCT_TYPE where department='DP' ";
	   $result = mssql_query($query);
		while($row = mssql_fetch_array($result))
		{
			echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
	echo "<td>".get_prod_name($row['PDD_PROD_NO'])."</td>";
	echo '<td><a href="setsession_prod.php?pro_no='.$row['PDD_PROD_NO'].'&pro_name='.get_prod_name($row['PDD_PROD_NO']).'">選取</a></td>';
	echo "</tr>";
		}
	}
	
   ?>
  <?php
$editFormAction = $_SERVER['PHP_SELF'];

if(isset($_POST['submit4'])){$_SESSION['which']='all';}
	if($_SESSION['which']=='all')
	{
	include_once("connections/conn.php");
	$query="SELECT [PDD_PROD_NO],[PDD_PROD_NAME],[PDD_SHOW] FROM [dbo].[PRODUCT_DATA] WHERE ([PDD_TYPE]<>'')";
	if($_GET['pdd_class']<>""){
	$query=$query." AND ([PDD_CLASS] LIKE '%".$_GET['pdd_class']."%')";}
	if($_POST['pdd_pro_no']<>""){
	$query=$query." AND ([PDD_PROD_NO] LIKE '%".$_POST['pdd_pro_no']."%')";}
	if($_POST['pdd_prod_name']<>""){
	$query=$query." AND ([PDD_PROD_NAME] LIKE '%".$_POST['pdd_prod_name']."%')";}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while($row = mssql_fetch_array($result))
{

	echo "<tr>";
    echo "<td>".$row['PDD_PROD_NO']."</td>";
	echo "<td>".$row['PDD_PROD_NAME']."</td>";
	echo '<td><a href="setsession_prod2.php?pro_no='.$row['PDD_PROD_NO'].'&pro_name='.$row['PDD_PROD_NAME'].'">選取</a></td>';
	echo "</tr>";

}
}

function get_prod_name($pid){
	include("./connections/conn.php");
$query1="SELECT          PDD_PROD_NO, PDD_PROD_NAME  
FROM              dbo.PRODUCT_DATA
WHERE (PDD_PROD_NO='".trim($pid)."')";	
	$result1= mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);

	while($row1 = mssql_fetch_array($result1)){
		$pname=$row1['PDD_PROD_NAME'];
	}
	return $pname;
}
?>
</table>
<p>&nbsp;</p>
</body>
</html>
