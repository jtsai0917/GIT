<?php 
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
$jj=new get_from_lot_no;
$jj->lid=$_GET['lot_no'];
$jj->ani();
echo $pid=trim($jj->pid);
echo '<form name="form1" method="post" action="'.$loginFormAction.'">';
echo '<font size="+2">設定 '.$_GET['lot_no'].' 充填流程</font>';
if($_GET['id']==2){echo '<input type="submit" name="set_trans" value="設定相關參數">';}
echo '<BR><table width="1200" border="1" bgcolor="#CCCCCC"><tr><td width="150">系別</td><td width="150">TANK1</td><td width="150">Pump1</td><td width="150">TANK2</td><td width="150">Pump2</td><td width="150">Filter 1</td><td width="150">Filter 2</td><td width="150">Filter 3</td><td width="150">充填口</td></tr>';
echo '<tr><td>';
select_series($pid);
echo '</td><td>';
select_Tank(trim($jj->pid));
echo '</td><td>';
select_Pump($pid);
echo '</td><td>';
select_Tank1(trim($jj->pid));
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
	echo '充填流程：';
	echo $flow=substr($flow,0,-1);
	echo '<input type="hidden" name="flow" value="'.$flow.'" />';
	echo '<BR><input type="submit" name="save" value="    保存   " /><input type="submit" name="leave" value="    取消   " /><BR></form>';
}

if(isset($_POST['save'])){
/*
	$query="select count(*) FROM dbo.Fill_Flow_Chart where Lot_No='".$_GET['lot_no']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$n=$row[0];
*/
	$query="INSERT INTO dbo.Fill_Flow_Chart (Lot_No, flow, creator, date) VALUES ('".$_GET['lot_no']."','".$_POST['flow']."','".$_SESSION['uid']."','".date("YmdHis")."')";	
	$result=mssql_query($query);
	echo "完成";
	echo '<script type="text/javascript">window.close()</script>';
}
if(isset($_POST['leave'])){
	echo '<script type="text/javascript">window.close()</script>';
}
