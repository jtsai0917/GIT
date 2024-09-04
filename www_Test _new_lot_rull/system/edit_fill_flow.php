<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	echo '<form name="form1" method="post" action="'.$loginFormAction.'">';
	echo '<font size="+2">設定充填流程相關項目</font><BR>';
	lasturl();
	datepick();
?>
<table width="550" border="1" bgcolor="#CCCCCC"><tr><td>
藥品：
<input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
<input name="pid" type="text" id="pid" size="16" value="<?php echo $_SESSION['pid']?>" readonly />
<input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
<input name="prod_name" type="text" id="prod_name" size="20" value="<?php echo $_SESSION['prod_name']?>" readonly />
</td></tr><tr><td>
設定項目: 
<select name="flow" onChange="set_date_session(this.name,this.value)">
<option value="series" <?php if($_SESSION['flow']=='系別'){echo 'selected="selected"';}?>>系別</option>
<option value="TANK" <?php if($_SESSION['flow']=='TANK'){echo 'selected="selected"';}?>>TANK</option>
<option value="Pump" <?php if($_SESSION['flow']=='Pump'){echo 'selected="selected"';}?>>PUMP</option>
<option value="Filter" <?php if($_SESSION['flow']=='Filter'){echo 'selected="selected"';}?>>Filter</option>
<option value="spot" <?php if($_SESSION['flow']=='spot'){echo 'selected="selected"';}?>>充填口</option>
</select>
<?php
	if($_SESSION['flow']=='TANK'){echo 'TANK 容量 ：<input type="text" name="capacity" size="10"> (M^3)';}
?> </td></tr></table>
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
	if($_SESSION['flow']=='series'){select_series();}
	if($_SESSION['flow']=='TANK'){select_Tank();}
	if($_SESSION['flow']=='Pump'){select_Pump();}
	if($_SESSION['flow']=='Filter'){select_Filter();}
	if($_SESSION['flow']=='spot'){select_Spot();}
?>
</table>
<?php
function select_series(){
	$query="SELECT * FROM FILL_Series where Prod_No='".$_SESSION['pid']."'";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		$url="delete_flow_series.php?index=".$row['index'].'&table=FILL_Series';
		echo '<tr><td>'.$row['index'].'</td><td>'.$row['Prod_No'].'</td><td>'.$row['series'].'</td><td>'.$row['discription'].'</td><td><a target="_blank" href="'.$url.'">刪除</a></td></tr>';
	}
}

function select_Tank($pid){
	$query="SELECT * FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$_SESSION['pid']."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		$url="delete_flow_series.php?index=".$row['TANK_NO'].'&table=TANK_DATA1';
		echo '<tr><td>'.$row['TANK_NO'].'</td><td>'.$row['PDD_PROD_NO'].'</td><td>'.$row['PROD_TANK'].'</td><td>'.$row['TANK_DESC'].'</td><td><a target="_blank" href="'.$url.'">刪除</a></td></tr>';
	}
}
function select_Pump(){
	$query="SELECT * FROM Fill_Pump where Prod_No='".$_SESSION['pid']."'";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		$url="delete_flow_series.php?index=".$row['index'].'&table=Fill_Pump';
		echo '<tr><td>'.$row['index'].'</td><td>'.$row['Prod_No'].'</td><td>'.$row['PumpNo'].'</td><td>'.$row['discription'].'</td><td><a target="_blank" href="'.$url.'">刪除</a></td></tr>';
	}
}

function select_Filter(){
	$query="SELECT * FROM Fill_Filter where Prod_No='".$_SESSION['pid']."'";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		$url="delete_flow_series.php?index=".$row['index'].'&table=Fill_Filter';
		echo '<tr><td>'.$row['index'].'</td><td>'.$row['Prod_No'].'</td><td>'.$row['filter'].'</td><td>'.$row['discription'].'</td><td><a target="_blank" href="'.$url.'">刪除</a></td></tr>';
	}
}

function select_Spot(){
	$query="SELECT * FROM Fill_Spot where Prod_No='".$_SESSION['pid']."'";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		$url="delete_flow_series.php?index=".$row['index'].'&table=Fill_Spot';
		echo '<tr><td>'.$row['index'].'</td><td>'.$row['Prod_No'].'</td><td>'.$row['SpotNo'].'</td><td>'.$row['discription'].'</td><td><a target="_blank" href="'.$url.'">刪除</a></td></tr>';
	}
}

if(isset($_POST['add'])){
	if($_POST['flow']=='series'){$query="INSERT INTO FILL_Series (series, discription, Prod_No) VALUES ('".$_POST['n1']."','".$_POST['d1']."','".$_POST['pid']."')";}
	if($_POST['flow']=='TANK'){$query=" INSERT INTO TANK_DATA1 (TANK_NO, PROD_TANK, TANK_DESC, PDD_PROD_NO, capacity) VALUES ((select max(TANK_NO)+1 from TANK_DATA1) ,'".$_POST['n1']."','".$_POST['d1']."','".$_POST['pid']."',".$_POST['capacity'].")";}	
	if($_POST['flow']=='Pump'){$query="INSERT INTO Fill_Pump (PumpNo, discription, Prod_No) VALUES ('".$_POST['n1']."','".$_POST['d1']."','".$_POST['pid']."')";}
	if($_POST['flow']=='Filter'){$query="INSERT INTO Fill_Filter (filter, discription, Prod_No) VALUES ('".$_POST['n1']."','".$_POST['d1']."','".$_POST['pid']."')";}
	if($_POST['flow']=='spot'){$query="INSERT INTO Fill_Spot (SpotNo, discription, Prod_No) VALUES ('".$_POST['n1']."','".$_POST['d1']."','".$_POST['pid']."')";}
	$_SESSION['TMP_ADD']=$query;
	$result=mssql_query($query);
	refresh();
}