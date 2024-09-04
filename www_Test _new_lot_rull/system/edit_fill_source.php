<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	echo '<form name="form1" method="post" action="'.$loginFormAction.'">';
	echo '<font size="+2">設定充填來源</font><BR>';
	lasturl();
	datepick();
?>
<table width="550" border="1" bgcolor="#CCCCCC"><tr><td>
藥品：
<input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
<input name="pid" type="text" id="pid" size="16" value="<?php echo $_SESSION['pid']?>" readonly />
<input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
<input name="prod_name" type="text" id="prod_name" size="20" value="<?php echo $_SESSION['prod_name']?>" readonly />
</td></tr></table>
<table width="550" border="1"><tr><td>
<form name="form1" method="post" action="">
名稱: 
<input type="text" name="n1" maxlength="20">
說明: 
<input type="text" name="d1" maxlength="40">
<input type="submit" name="add" value=" 新增 ">
</form>
</td></tr></table>
<BR>
<table width="800" border="1"><tr bgcolor="#CCCCCC"><td>Index</td><td>Prod_No</td><td>Item</td><td>Discription</td><td>Delete</td></tr>
<?php
	select_Tank();
?>
</table>
<?php

function select_Tank($pid){
	$query="SELECT * FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$_SESSION['pid']."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		$url="delete_flow_series.php?index=".$row['TANK_NO'].'&table=TANK_DATA1';
		echo '<tr><td>'.$row['TANK_NO'].'</td><td>'.$row['PDD_PROD_NO'].'</td><td>'.$row['PROD_TANK'].'</td><td>'.$row['TANK_DESC'].'</td><td><a target="_blank" href="'.$url.'">刪除</a></td></tr>';
	}
}

if(isset($_POST['add'])){

}