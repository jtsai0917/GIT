<?php 
include("../lib/fun.php");
session_start();
include("../connections/conn.php");
auth('9-01',$_SESSION['aut']);
lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>台帳版本控制</title>
</head>

<body>
<p>台帳版本設定 / 修改
</p>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">

  <table width="620" border="0">
    <tr>
      <td width="620">查詢 </td>
    </tr></table>
  <table width="620" border="1">
    <tr>
      <td>品名：<span class="d1">
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
      <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
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
      類別：
      <select name="type" id="type">
      	<option></option>
        <option>商品</option>
        <option>成品</option>
        <option>原料</option>
      </select>
      <input type="submit" name="search" id="search" value="  搜尋  " />
      </span></td>
    </tr>
  </table>
<?php

$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["search"])){
echo '<table width="480" border="1"><tr><td width="0">
修改文書編號之前，必須選取要修改的項目‧可一次勾選多項同時修改‧</br>
更新部分只限文書編號&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="submit" name="submit" id="submit" value="    更  新   "/>';
echo '</td></tr>
</table>
<table width="480" border="1">
  <tr>
    <td width="30" align=center>選取</td>
    <td width="200" align=center>藥品</td>
    <td width="200" align=center>文書編號</td>
    <td width="50" align=center>項目</td>
  </tr>';

$query="SELECT DISTINCT 
                            ACCOUNT_ISO_LIST.AIL_ISO_NO, PRODUCT_DATA.PDD_CHEMICAL, 
                            ACCOUNT_ISO_LIST.PDD_CLASS 
FROM              ACCOUNT_ISO_LIST INNER JOIN
                            PRODUCT_DATA ON ACCOUNT_ISO_LIST.PDD_CHEMICAL = PRODUCT_DATA.PDD_CHEMICAL
			WHERE (dbo.PRODUCT_DATA.PDD_PROD_NO<>'')";
if($_POST['pdd_chemical1']<>''){
	$query=$query." and (dbo.PRODUCT_DATA.PDD_PROD_NO = '".$_POST['pdd_chemical1']."')";
	}
if($_POST['type']<>''){
	$query=$query." AND (dbo.ACCOUNT_ISO_LIST.PDD_CLASS = '".$_POST['type']."')";
	}
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$i=0;
while($row = mssql_fetch_array($result))
{
	echo
	'<tr>
	<td align=center><input type="checkbox" name="selt['.$i.']"/></td>
	<input type="hidden" name="chemical['.$i.']" value="'.$row['PDD_CHEMICAL'].'"/>
	<td align=center>'.$row['PDD_CHEMICAL'].'</td>
	<td align=center><input type="text" name="isono['.$i.']" value="'.trim($row['AIL_ISO_NO']).'" /></td>
	<td align=center><input type="text" name="ptype['.$i.']" value="'.$row['PDD_CLASS'].'" </td>
  	</tr>';	
$i++;
}
$_SESSION['tmp']=$i;
echo '</table>';
}

echo '</br></form>';
if(isset($_POST["submit"])){	
	$checkbox=$_POST["selt"];
	$isono=$_POST["isono"];
	$ptype=$_POST["ptype"];
	$chemical=$_POST['chemical'];
	for($j=0;$j<$_SESSION['tmp'];$j++)
	{
		if($checkbox[$j]=='on')
		{
			$query="UPDATE          dbo.ACCOUNT_ISO_LIST
					SET                   AIL_ISO_NO ='".$isono[$j]."'
					WHERE          (PDD_CHEMICAL = '".$chemical[$j]."') and (PDD_CLASS = '".$ptype[$j]."')";
			$result = mssql_query($query);
			echo '更新 '.$ptype[$j].' 項目, 藥品為： '. $chemical[$j].' , 文書編號：'.$isono[$j].'</br>';
		}
	}
}

?> 