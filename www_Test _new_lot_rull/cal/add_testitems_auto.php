<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>新增測試項目</title>
<style type="text/css">
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
</head>

<body>
<table border="1" width="720">
  
    <td><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
      項目簡稱：
      <label for="group_name"></label>
      <input name="group_name" type="text" id="group_name" size="10" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit" name="submit" id="submit" value="  查詢  " />
      <input type="submit" name="tm" id="tm" value="   TM  " />
      <input type="submit" name="m13" id="m13" value="   M13  " />
      <input type="submit" name="all" id="all" value="  全項  " />
      <input type="submit" name="normal" id="normal" value="  常規  " />
      <input type="submit" name="add1" id="add1" value="確定/離開"  />
	  <input type="submit" name="clear" id="clear" value="清除所有項目"  />
      <input type="submit" name="leave" id="leave" value="離開"  />
  </tr>
  
    </table>
  <table border="1" width="720">
  <tr>
  <td>  序號  </td>
  <td>代號  </td>
  <td>簡稱  </td>
  <td>分析項目  </td>
  <td>群組  </td>
  <td>選擇</td>
  </tr>    
  
  

<?php 
if($_SESSION['AND_GOODS']<>''){$_SESSION['prod_no']=$_SESSION['AND_GOODS'];}
if (isset($_GET['AND_GOODS'])){
	$_SESSION['prod_no']=$_GET['AND_GOODS'];
	}
if($_SESSION['AND_GOODS']<>''){$_SESSION['prod_no']=$_SESSION['AND_GOODS'];}
  $query="SELECT DISTINCT dbo.PRODUCT_DATA.PDD_PROD_NO, dbo.AnalyzeItem.*
FROM              dbo.ELEMENT_FORM INNER JOIN
                            dbo.PRODUCT_DATA ON dbo.ELEMENT_FORM.PDD_CHEMICAL = dbo.PRODUCT_DATA.PDD_CHEMICAL INNER JOIN
                            dbo.AnalyzeItem ON dbo.ELEMENT_FORM.ELM_ID = dbo.AnalyzeItem.ANI_INDEX
WHERE          (dbo.PRODUCT_DATA.PDD_PROD_NO = '".$_SESSION['prod_no']."')";

  if($_POST['group_name']<>""){
	$query=$query." and ([ANI_NICKNAME] LIKE '%".$_POST['group_name']."%')";}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
while ($row = mssql_fetch_array($result)){ ?>
<tr><td><?php echo $row['ANI_INDEX'] ?></td>
<td><?php echo $row['ANI_ID'] ?></td>
<td><?php echo $row['ANI_NICKNAME'] ?></td>
<td><?php echo $row['ANI_FULLNAME'] ?></td>
<td><?php echo $row['ANI_GROUPNAME'] ?></td>
<td><input type="checkbox" name="chkbox[]" id="1" value="<?php echo $row['ANI_INDEX'] ?>" <?php echo testnotonick($row['ANI_INDEX'],$_SESSION['prod_no'],$_SESSION['items'].",")?>/></td></tr>
<?php
}
?>
</table>
</form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
//////////////////////////////////////////////////////////
if(isset($_POST["normal"])){
	$_SESSION['items']="";
	$query="SELECT          dbo.RegularAnalyzeItem.*, dbo.AnalyzeItem.ANI_INDEX
	FROM              dbo.RegularAnalyzeItem INNER JOIN
                            dbo.AnalyzeItem ON dbo.RegularAnalyzeItem.ANI_ID = dbo.AnalyzeItem.ANI_ID
	WHERE          (dbo.RegularAnalyzeItem.PROD_NO = '".$_SESSION['prod_no']."')";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result)){
	$_SESSION['auto_testitems'].= $row['ANI_INDEX'].",";
	}

$url="index.php?url=add_testitems_auto";
jumpto($url);
}

if(isset($_POST["all"])){
		$_SESSION['auto_testitems']="";

	  $query="SELECT DISTINCT dbo.PRODUCT_DATA.PDD_PROD_NO, dbo.AnalyzeItem.*
		FROM              dbo.ELEMENT_FORM INNER JOIN
                            dbo.PRODUCT_DATA ON dbo.ELEMENT_FORM.PDD_CHEMICAL = dbo.PRODUCT_DATA.PDD_CHEMICAL INNER JOIN
                            dbo.AnalyzeItem ON dbo.ELEMENT_FORM.ELM_ID = dbo.AnalyzeItem.ANI_INDEX
		WHERE          (dbo.PRODUCT_DATA.PDD_PROD_NO = '".$_SESSION['prod_no']."')";
			$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row = mssql_fetch_array($result)){
		$_SESSION['auto_testitems'].= $row['ANI_INDEX'].",";
		}
$url="index.php?url=add_testitems_auto";
jumpto($url);
}

if(isset($_POST["tm"])){
		$_SESSION['auto_testitems']="";

	$query="SELECT DISTINCT 
                            dbo.PRODUCT_DATA.PDD_PROD_NO, dbo.AnalyzeItem.ANI_INDEX, dbo.AnalyzeItem.ANI_ID, 
                            dbo.AnalyzeItem.ANI_NICKNAME, dbo.AnalyzeItem.ANI_FULLNAME, dbo.AnalyzeItem.ANI_UNIT, 
                            dbo.AnalyzeItem.ANI_ANR_ID, dbo.AnalyzeItem.ANI_GROUPNAME, dbo.AnalyzeItem.ANI_DATAFIELD, 
                            dbo.AnalyzeItem.ANI_ORDER
FROM              dbo.ELEMENT_FORM INNER JOIN
                            dbo.PRODUCT_DATA ON dbo.ELEMENT_FORM.PDD_CHEMICAL = dbo.PRODUCT_DATA.PDD_CHEMICAL INNER JOIN
                            dbo.AnalyzeItem ON dbo.ELEMENT_FORM.ELM_ID = dbo.AnalyzeItem.ANI_INDEX
WHERE          (dbo.PRODUCT_DATA.PDD_PROD_NO = '".$_SESSION['prod_no']."') AND ((dbo.AnalyzeItem.ANI_GROUPNAME = 'M13') or (dbo.AnalyzeItem.ANI_GROUPNAME = 'M21') or (dbo.AnalyzeItem.ANI_GROUPNAME = 'TT') or (dbo.AnalyzeItem.ANI_GROUPNAME = 'TM'))";
			$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row = mssql_fetch_array($result)){
		$_SESSION['auto_testitems'].= $row['ANI_INDEX'].",";}
$url="index.php?url=add_testitems_auto";
jumpto($url);
}

if (isset($_POST["add1"])){
	$aa=$_POST['chkbox'];
    $_SESSION['items1']=implode(",", $aa);
$url=$_SESSION['lasturl'];
jumpto($url);	}

if (isset($_POST["clear"])){
    $_SESSION['items']="";
$url="index.php?url=add_testitems_auto";
jumpto($url);	}

if(isset($_POST["m13"])){
		$_SESSION['items']="";

	$query="SELECT DISTINCT 
                            dbo.PRODUCT_DATA.PDD_PROD_NO, dbo.AnalyzeItem.ANI_INDEX, dbo.AnalyzeItem.ANI_ID, 
                            dbo.AnalyzeItem.ANI_NICKNAME, dbo.AnalyzeItem.ANI_FULLNAME, dbo.AnalyzeItem.ANI_UNIT, 
                            dbo.AnalyzeItem.ANI_ANR_ID, dbo.AnalyzeItem.ANI_GROUPNAME, dbo.AnalyzeItem.ANI_DATAFIELD, 
                            dbo.AnalyzeItem.ANI_ORDER
FROM              dbo.ELEMENT_FORM INNER JOIN
                            dbo.PRODUCT_DATA ON dbo.ELEMENT_FORM.PDD_CHEMICAL = dbo.PRODUCT_DATA.PDD_CHEMICAL INNER JOIN
                            dbo.AnalyzeItem ON dbo.ELEMENT_FORM.ELM_ID = dbo.AnalyzeItem.ANI_INDEX
WHERE          (dbo.PRODUCT_DATA.PDD_PROD_NO = '".$_SESSION['prod_no']."') AND (dbo.AnalyzeItem.ANI_GROUPNAME = 'M13')";
//		echo $query;	
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row = mssql_fetch_array($result)){
		$_SESSION['items'].= $row['ANI_INDEX'].",";}
		echo $_SESSION['items'];
		$url="index.php?url=add_testitems_auto";
		jumpto($url);
}

if(isset($_POST['leave'])){
	jumpto($_SESSION['lasturl']);	
}
?>