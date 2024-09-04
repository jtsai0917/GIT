<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
?>
<form name="form1" method="post" action="">

        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
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
		?>" readonly>
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no_1.php ', '_self');" >
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php echo get_prod_name($_SESSION['pid']);?>" readonly>
<?php
$pid=$_SESSION['pid'];
echo '<font size="+2">設定 '.$_GET['lot_no'].' 充填流程</font>';
if($_GET['id']==2){echo '<input type="submit" name="set_trans" value="設定相關參數">';}
echo '<BR><table width="1200" border="1" bgcolor="#CCCCCC"><tr><td width="150">系別</td><td width="150">TANK1</td><td width="150">Pump1</td><td width="150">TANK2</td><td width="150">Pump2</td><td width="150">Filter 1</td><td width="150">Filter 2</td><td width="150">Filter 3</td><td width="150">充填口</td></tr>';
echo '<tr><td>';
select_series($pid);
echo '</td><td>';
select_Tank(trim($pid));
echo '</td><td>';
select_Pump($pid);
echo '</td><td>';
select_Tank1(trim($pid));
echo '</td><td>';
select_Pump1($pid);
echo '</td><td>';
select_Filter1($pid);
echo '</td><td>';
select_Filter2($pid);
echo '</td><td>';
select_Filter3($pid);
echo '</td><td>';
select_Spot($pid);
echo '</td></tr></table>';
echo '<table width="1200" border="1" bgcolor="#CCCCCC"><tr><td>';
echo '<input type="submit" name="save1" value="    選擇完畢   " />';
echo '</td></tr></table>';




if(isset($_POST['save1'])){
	if($_POST['select_series']<>'')
	{$flow.=trim($_POST['select_series'])."/";}
	if($_POST['select_Tank']<>'')
	{$flow.=trim($_POST['select_Tank'])."/";}
	if($_POST['select_Pump']<>'')
	{$flow.=trim($_POST['select_Pump'])."/";}
	if($_POST['select_Tank1']<>'')
	{$flow.=trim($_POST['select_Tank1'])."/";}
	if($_POST['select_Pump1']<>'')
	{$flow.=trim($_POST['select_Pump1'])."/";}
	if($_POST['select_Filter1']<>'')
	{$flow.=trim($_POST['select_Filter1'])."/";}
	if($_POST['select_Filter2']<>'')
	{$flow.=trim($_POST['select_Filter2'])."/";}
	if($_POST['select_Filter3']<>'')
	{$flow.=trim($_POST['select_Filter3'])."/";}
	if($_POST['select_Spot']<>'')
	{$flow.=trim($_POST['select_Spot'])."/";}
	$flow=substr($flow,0,-1);

echo '<BR><form id="ll" method="post" action="">充填路徑<input type="text" name="flow" value="'.$flow.'" />頁面<input type="text" name="sheet"><input type="submit" name="enter" value=" 新增/修改 "><input type="submit" name="rr" value="refresh"></form>';}

if(isset($_POST['save'])){
/*
	$query="select count(*) FROM dbo.Fill_Flow_Chart where Lot_No='".$_GET['lot_no']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$n=$row[0];

	$query="INSERT INTO dbo.Fill_Flow_Chart (Lot_No, flow, creator, date) VALUES ('".$_GET['lot_no']."','".$_POST['flow']."','".$_SESSION['uid']."','".date("YmdHis")."')";	
	$result=mssql_query($query);
	echo "完成";
	echo '<script type="text/javascript">window.close()</script>';
	*/
}
if(isset($_POST['leave'])){
	echo '<script type="text/javascript">window.close()</script>';
}

echo '<table width="1240" border="1"><tr bgcolor="gray"><td>index</td><td>充填路徑</td><td>頁面</td><td width="10">X</td></tr>';
  $query="SELECT  [index], flow_txt, sheet_no FROM H2SO4_flow_chart_sheet";
  $result=mssql_query($query);
  while($row=mssql_fetch_array($result)){
	echo '<tr><td>'.$row['index'].'</td><td>'.$row['flow_txt'].'</td><td>'.$row['sheet_no'].'</td><td><a href="delete_flow_chart.php?id='.$row['index'].'" target="_blank">X</a></td></tr>';  
  }
echo '</table>';

if(isset($_POST['enter'])){
	$flow=trim($_POST['flow']);
	$query="select count(sheet_no) from H2SO4_flow_chart_sheet where flow_txt='".$flow."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]>>0){
		$query1="update H2SO4_flow_chart_sheet set sheet_no=".trim($_POST['sheet'])." where flow_txt='".$flow."'";	
		$result1=mssql_query($query1);
	}
	else{
		$query1="INSERT INTO H2SO4_flow_chart_sheet (flow_txt, sheet_no) VALUES  ('".$flow."',".trim($_POST['sheet']).")";	
		$result1=mssql_query($query1);
	}
}

if(isset($_POST['enter'])){
	refresh();	
}

function select_series($pid){
	echo '<select name="select_series" id="select_series" >';
	echo '<option value=""></option>';
	$query="SELECT series FROM FILL_Series WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['series'])==$_SESSION['select_series']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['series'].'" '.$select.'>'.$row['series'].'</option>';
	}
	echo '</select>';
}

function select_Tank($pid){
	echo '<select name="select_Tank" id="select_Tank">';
	echo '<option value=""></option>';
	$query="SELECT PROD_TANK FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PROD_TANK'])==$_SESSION['select_Tank']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PROD_TANK'].'" '.$select.'>'.$row['PROD_TANK'].'</option>';
	}
	echo '</select>';
}

function select_Tank1($pid){
	echo '<select name="select_Tank1" id="select_Tank1">';
	echo '<option value=""></option>';
	$query="SELECT PROD_TANK FROM TANK_DATA1 WHERE (PDD_PROD_NO = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PROD_TANK'])==$_SESSION['select_Tank1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PROD_TANK'].'" '.$select.'>'.$row['PROD_TANK'].'</option>';
	}
	echo '</select>';
}

function select_Pump($pid){
	echo '<select name="select_Pump" id="select_Pump">';
	echo '<option value=""></option>';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PumpNo'])==$_SESSION['select_Pump']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PumpNo'].'" '.$select.'>'.$row['PumpNo'].'</option>';
	}
	echo '</select>';
}

function select_Pump1($pid){
	echo '<select name="select_Pump1" id="select_Pump1">';
	echo '<option value=""></option>';
	$query="SELECT PumpNo FROM Fill_Pump WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['PumpNo'])==$_SESSION['select_Pump1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['PumpNo'].'" '.$select.'>'.$row['PumpNo'].'</option>';
	}
	echo '</select>';
}

function select_Filter1($pid){
	echo '<select name="select_Filter1" id="select_Filter1">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter1']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Filter2($pid){
	echo '<select name="select_Filter2" id="select_Filter2">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter2']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Filter3($pid){
	echo '<select name="select_Filter3" id="select_Filter3">';
	echo '<option value=""></option>';
	$query="SELECT filter FROM Fill_Filter WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['filter'])==$_SESSION['select_Filter3']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['filter'].'" '.$select.'>'.$row['filter'].'</option>';
	}
	echo '</select>';
}

function select_Spot($pid){
	echo '<select name="select_Spot" id="select_Spot">';
	echo '<option value=""></option>';
	$query="SELECT SpotNo FROM Fill_Spot WHERE (Prod_No = '".$pid."')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if(trim($row['SpotNo'])==$_SESSION['select_Spot']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['SpotNo'].'" '.$select.'>'.$row['SpotNo'].'</option>';
	}
	echo '</select>';
}
?>